<?php
namespace JiNexus\Mvc\Application;

use JiNexus\Config\Config\ConfigInterface;
use JiNexus\Http\Http\HttpInterface;
use JiNexus\ModuleManager\ModuleManager\ModuleManagerInterface;
use JiNexus\Mvc\Base\AbstractBase;
use JiNexus\Mvc\Controller\Factory\ControllerFactory;
use JiNexus\Mvc\Exception;
use JiNexus\Mvc\Model\ViewModel;
use JiNexus\Mvc\View\Factory\ViewFactory;
use JiNexus\Mvc\View\View;
use JiNexus\Mvc\View\ViewInterface;
use JiNexus\Route\Route\RouteInterface;

/**
 * Class AbstractApplication
 * @package JiNexus\Mvc\Application
 */
abstract class AbstractApplication extends AbstractBase implements ApplicationInterface
{
    /**
     * Action Name
     *
     * @var
     */
    protected $actionName;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * Controller Name
     *
     * @var string
     */
    protected $controllerName;

    /**
     * @var HttpInterface
     */
    protected $http;

    /**
     * @var array
     */
    protected $matchRoute;

    /**
     * @var ModuleManagerInterface
     */
    protected $moduleManager;

    /**
     * Module Name
     *
     * @var string
     */
    protected $moduleName;

    /**
     * Namespace
     *
     * @var string
     */
    protected $namespace;

    /**
     * @var RouteInterface
     */
    protected $route;

    /**
     * @var ViewInterface
     */
    protected $view;

    /**
     * AbstractApplication constructor
     *
     * @param ConfigInterface $config
     * @param HttpInterface $http
     * @param ModuleManagerInterface $moduleManager
     * @param RouteInterface $route
     */
    public function __construct(ConfigInterface $config, HttpInterface $http, ModuleManagerInterface $moduleManager, RouteInterface $route)
    {
        $this->moduleManager = $moduleManager;
        $this->config = $config;
        $this->route = $route;
        $this->http = $http;

        $this->matchRoute = $this->route->getMatchRoute($this->route->getRoutes());

        if ($this->matchRoute) {
            $this->namespace = $this->matchRoute[key($this->matchRoute)]['controller'];

            $controllerExplode = explode('\\', $this->namespace);
            $moduleName = reset($controllerExplode);
            $controllerName = end($controllerExplode);
            $actionName = $this->matchRoute[key($this->matchRoute)]['action'];

            $this->moduleName = $moduleName;
            $this->controllerName = $controllerName;
            $this->actionName = $actionName;
        }
    }

    /**
     * Dispatch Action
     *
     * @param $controller
     */
    public function dispatchAction($controller)
    {
        $action = $this->matchRoute[key($this->matchRoute)]['action'] . 'Action';

        $viewModel = $controller->$action();

        if ($viewModel instanceof ViewModel) {
            $this->view->setVariables($viewModel->getVariables());
        }
    }

    /**
     * Dispatch Controller
     *
     * @return \JiNexus\Mvc\Controller\ControllerInterface
     */
    public function dispatchController()
    {
        // Instantiate the Controller
        return ControllerFactory::build($this);
    }

    /**
     * Dispatch View
     *
     * @return View
     */
    public function dispatchView()
    {
        return ViewFactory::build($this);
    }

    /**
     * Convert errors to \ErrorException instances
     * @param int $number
     * @param string $string
     * @param string $file
     * @param int $line
     * @throws \ErrorException
     */
    public static function error($number, $string, $file, $line)
    {
        throw new \ErrorException($string, 0, $number, $file, $line);
    }

    /**
     * Get Action Name
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @return ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get Controller Name
     *
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @return HttpInterface
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * Get Match Route
     *
     * @return array
     */
    public function getMatchRoute()
    {
        return $this->matchRoute;
    }

    /**
     * @return ModuleManagerInterface
     */
    public function getModuleManager()
    {
        return $this->moduleManager;
    }

    /**
     * Get Module Name
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return RouteInterface
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return ViewInterface
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Render View
     */
    public function renderView()
    {
        $viewManager = $this->config->get('view_manager');

        try {
            $this->view->render($viewManager['template_map']['layout/layout']);
        } catch (Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * Run Application
     */
    public function run()
    {
        $this->view = $this->dispatchView();

        if (! $this->matchRoute) {
            header('HTTP/1.0 404 Not Found');
            header('Status: 404 Not Found');
            $this->renderView();
            exit();
        }

        $controller = $this->dispatchController();
        $this->dispatchAction($controller);
        $this->renderView();
    }
}
