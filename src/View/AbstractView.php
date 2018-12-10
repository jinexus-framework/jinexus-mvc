<?php
namespace JiNexus\Mvc\View;

use JiNexus\Config\Config\Config;
use JiNexus\Config\Config\ConfigInterface;
use JiNexus\Mvc\Application\ApplicationInterface;
use JiNexus\Mvc\Base\AbstractBase;
use JiNexus\Mvc\Exception;

/**
 * Class AbstractView
 * @package JiNexus\Mvc\View
 */
abstract class AbstractView extends AbstractBase implements ViewInterface
{
    /**
     * @var ApplicationInterface
     */
    protected $app;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * List of Routes
     *
     * @var array
     */
    protected $routes = [];

    /**
     * View variables
     *
     * @var array
     */
    protected $variables = [];

    /**
     * AbstractView constructor
     *
     * @param ApplicationInterface $app
     */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;

        if ($this->app->getConfig() instanceof Config) {
            $this->config = $this->app->getConfig();

            if ($this->config->has('routes')) {
                $this->routes = $this->config->get('routes');
            }
        }
    }

    /**
     * Magic method to get a view variable, forwards to $this->get()
     *
     * @param string $variable
     * @return mixed
     */
    public function __get($variable)
    {
        return $this->get($variable);
    }

    /**
     * Magic method to set a view variable, forwards to $this->set()
     *
     * @param string $variable
     * @param null $value
     * @return $this
     */
    public function __set($variable, $value = null)
    {
        $this->set($variable, $value);

        return $this;
    }

    /**
     * Base Path
     *
     * @param string $file
     * @return string
     */
    public function basePath($file = '')
    {
        $basePath = dirname($_SERVER['PHP_SELF']);
        if ($basePath != '/') {
            $basePath .= '/';
        }

        if (! empty($file)) {
            $basePath.= ltrim($file, '/');
        }

        return $basePath;
    }

    /**
     * Base URL
     *
     * @param string $uri
     * @return string
     */
    public function baseUrl($uri = '')
    {
        return sprintf(
            "%s://%s%s%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            ($_SERVER['SERVER_PORT'] == 80) ?: ':' . $_SERVER['SERVER_PORT'],
            $_SERVER['REQUEST_URI'],
            ltrim($uri, '/')
        );
    }

    /**
     * Render content
     *
     * @throws Exception
     */
    public function content()
    {
        if ($this->app->getMatchRoute()) {
            $actionName = strtolower(preg_replace('/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', '-', $this->app->getActionName()));

            $fileArray = [
                strtolower($this->app->getModuleName()),
                str_replace('controller', '', strtolower($this->app->getControllerName())),
                $actionName . '.phtml'
            ];

            $file = implode('/', $fileArray);
        } else {
            $viewManager = $this->config->get('view_manager');
            $file = $viewManager['template_map']['error/404'];
        }

        $this->render($file);
    }

    /**
     * Recursively make a value safe for HTML
     *
     * @param mixed $data
     * @return mixed
     */
    public function htmlEncode($data)
    {
        switch (gettype($data)) {
            case 'array':
                foreach ($data as $key => $value) {
                    $data[$key] = $this->htmlEncode($value);
                }

                break;
            case 'object':
                $data = clone $data;

                foreach ($data as $key => $value) {
                    $data->$key = $this->htmlEncode($value);
                }

                break;
            case 'string':
            default:
                $data = htmlentities($data, ENT_QUOTES, 'UTF-8');

                break;
        }

        return $data;
    }

    /**
     * Recursively decode an HTML encoded value
     *
     * @param mixed $data
     * @return mixed
     */
    public function htmlDecode($data)
    {
        switch (gettype($data)) {
            case 'array':
                foreach ($data as $key => $value) {
                    $data[$key] = $this->htmlDecode($value);
                }

                break;
            case 'object':
                $data = clone $data;

                foreach ($data as $key => $value) {
                    $data->$key = $this->htmlDecode($value);
                }

                break;
            case 'string':
            default:
                $data = html_entity_decode($data, ENT_QUOTES, 'UTF-8');

                break;
        }

        return $data;
    }

    /**
     * Get a view variable
     *
     * @param string $variable
     * @param bool $htmlEncode
     * @return mixed|null
     */
    public function get($variable, $htmlEncode = true)
    {
        $value = null;

        if ( isset($this->variables[$variable]) ) {
            $value = $this->variables[$variable][$htmlEncode ? 'safe' : 'unsafe'];
        }

        return $value;
    }

    /**
     * Get all variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set a view variable
     *
     * @param string $variable
     * @param mixed $value
     * @return \JiNexus\Mvc\View\ViewInterface
     */
    public function set($variable, $value = null)
    {
        $this->variables[$variable] = array(
            'safe' => $this->htmlEncode($value),
            'unsafe' => $value
        );

        return $this;
    }

    /**
     * Set all variables
     *
     * @param array $variables
     */
    public function setVariables($variables = [])
    {
        foreach ($variables as $variable => $value) {
            $this->set($variable, $value);
        }
    }

    /**
     * Render a file
     *
     * @param string $file
     * @throws Exception
     */
    public function render($file = '')
    {
        $viewManager = $this->config->get('view_manager');

        if ($viewManager['template_path_stack']) {
            $file = $viewManager['template_path_stack'] . '/' . $file;
        }

        $fileInfo = pathinfo($file);
        if (! isset($fileInfo['extension']) || $fileInfo['extension'] != 'phtml') {
            $file = $file . '.phtml';
        }

        if (is_file($file)) {
            if (! headers_sent()) {
                header('X-Generator: JiNexus Framework');
            }

            ob_start();
            include $file;
            ob_end_flush();
        } else {
            throw new Exception('View not found');
        }
    }

    /**
     * Get the URL from routes using a routeName
     *
     * @param $routeName
     * @return string
     * @throws \JiNexus\Route\Exception
     */
    public function url($routeName)
    {
        return $this->app->getRoute()->getRouteUri($routeName, $this->routes);
    }
}
