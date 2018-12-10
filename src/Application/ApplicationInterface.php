<?php
namespace JiNexus\Mvc\Application;

use JiNexus\Config\Config\ConfigInterface;
use JiNexus\Http\Http\HttpInterface;
use JiNexus\ModuleManager\ModuleManager\ModuleManagerInterface;
use JiNexus\Mvc\Base\BaseInterface;
use JiNexus\Mvc\Exception;
use JiNexus\Mvc\View\View;
use JiNexus\Mvc\View\ViewInterface;
use JiNexus\Route\Route\RouteInterface;

/**
 * Interface ApplicationInterface
 * @package JiNexus\Mvc\Application
 */
interface ApplicationInterface extends BaseInterface
{
    /**
     * ApplicationInterface constructor
     *
     * @param ConfigInterface $config
     * @param HttpInterface $http
     * @param ModuleManagerInterface $moduleManager
     * @param RouteInterface $route
     */
    public function __construct(ConfigInterface $config, HttpInterface $http, ModuleManagerInterface $moduleManager, RouteInterface $route);

    /**
     * Dispatch Action
     *
     * @param $controller
     */
    public function dispatchAction($controller);

    /**
     * Dispatch Controller
     *
     * @return \JiNexus\Mvc\Controller\ControllerInterface
     */
    public function dispatchController();

    /**
     * Dispatch View
     *
     * @return View
     */
    public function dispatchView();

    /**
     * Convert errors to \ErrorException instances
     * @param int $number
     * @param string $string
     * @param string $file
     * @param int $line
     * @throws \ErrorException
     */
    public static function error($number, $string, $file, $line);

    /**
     * Get Action Name
     *
     * @return string
     */
    public function getActionName();

    /**
     * @return ConfigInterface
     */
    public function getConfig();

    /**
     * Get Controller Name
     *
     * @return string
     */
    public function getControllerName();

    /**
     * @return HttpInterface
     */
    public function getHttp();

    /**
     * Get Match Route
     *
     * @return array
     */
    public function getMatchRoute();

    /**
     * @return ModuleManagerInterface
     */
    public function getModuleManager();

    /**
     * Get Module Name
     *
     * @return string
     */
    public function getModuleName();

    /**
     * @return string
     */
    public function getNamespace();

    /**
     * @return RouteInterface
     */
    public function getRoute();

    /**
     * @return ViewInterface
     */
    public function getView();

    /**
     * Render View
     */
    public function renderView();

    /**
     * Run Application
     */
    public function run();
}
