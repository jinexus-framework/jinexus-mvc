<?php
namespace JiNexus\Mvc\Controller\Factory;

use JiNexus\Http\Http\HttpInterface;
use JiNexus\Mvc\Application\ApplicationInterface;
use JiNexus\Mvc\Controller\ControllerInterface;
use JiNexus\Mvc\Factory\AbstractFactory;
use JiNexus\Mvc\View\ViewInterface;

/**
 * Class ControllerFactory
 * @package JiNexus\Mvc\Controller\Factory
 */
class ControllerFactory extends AbstractFactory
{
    /**
     * @param ApplicationInterface $app
     * @return ControllerInterface
     */
    public static function build(ApplicationInterface $app)
    {
        $fileArray = [
            'module',
            $app->getModuleName(),
            'src',
            'Controller',
            $app->getControllerName() . '.php'
        ];

        $file = implode('/', $fileArray);

        if (! is_file($file)) {
            if (! headers_sent()) {
                header('HTTP/1.1 404 Not Found');
                header('Status: 404 Not Found');
            }
        }

        $namespace = $app->getNamespace();

        return new $namespace($app);
    }
}
