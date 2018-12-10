<?php
namespace JiNexus\Mvc\View;

use JiNexus\Mvc\Application\ApplicationInterface;
use JiNexus\Mvc\Base\BaseInterface;
use JiNexus\Mvc\Exception;

/**
 * Interface ViewInterface
 * @package JiNexus\Mvc\View
 */
interface ViewInterface extends BaseInterface
{
    /**
     * ViewInterface constructor
     *
     * @param ApplicationInterface $app
     */
    public function __construct(ApplicationInterface $app);

    /**
     * Magic method to get a view variable, forwards to $this->get()
     *
     * @param string $variable
     * @return mixed
     */
    public function __get($variable);

    /**
     * Magic method to set a view variable, forwards to $this->set()
     *
     * @param string $variable
     * @param null $value
     * @return $this
     */
    public function __set($variable, $value = null);

    /**
     * Base Path
     *
     * @param string $file
     * @return string
     */
    public function basePath($file = '');

    /**
     * Base URL
     *
     * @param string $uri
     * @return string
     */
    public function baseUrl($uri = '');

    /**
     * Render content
     *
     * @throws Exception
     */
    public function content();

    /**
     * Recursively make a value safe for HTML
     *
     * @param mixed $data
     * @return mixed
     */
    public function htmlEncode($data);

    /**
     * Recursively decode an HTML encoded value
     *
     * @param mixed $data
     * @return mixed
     */
    public function htmlDecode($data);

    /**
     * Get a view variable
     *
     * @param string $variable
     * @param bool $htmlEncode
     * @return mixed|null
     */
    public function get($variable, $htmlEncode = true);

    /**
     * Get all variables
     *
     * @return array
     */
    public function getVariables();

    /**
     * Set a view variable
     *
     * @param string $variable
     * @param mixed $value
     * @return \JiNexus\Mvc\View\ViewInterface
     */
    public function set($variable, $value = null);

    /**
     * Set all variables
     *
     * @param array $variables
     */
    public function setVariables($variables = []);

    /**
     * Render a file
     *
     * @param string $file
     * @throws Exception
     */
    public function render($file = '');

    /**
     * Url
     *
     * @param $routeName
     * @return string
     * @throws \JiNexus\Route\Exception
     */
    public function url($routeName);
}
