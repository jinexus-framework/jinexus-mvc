<?php
namespace JiNexus\Mvc\Application\Factory;

use JiNexus\Config\Config\Factory\ConfigFactory;
use JiNexus\Http\Http\Factory\HttpFactory;
use JiNexus\ModuleManager\Exception;
use JiNexus\ModuleManager\ModuleManager\Factory\ModuleManagerFactory;
use JiNexus\Mvc\Application\Application;
use JiNexus\Mvc\Factory\AbstractFactory;
use JiNexus\Route\Route\Factory\RouteFactory;

use Whoops;

/**
 * Class ApplicationFactory
 * @package JiNexus\Mvc\Application\Factory
 */
class ApplicationFactory extends AbstractFactory
{
    /**
     * @param array $appConfig
     * @return Application
     */
    public static function build($appConfig = [])
    {
        $whoopsRun = new Whoops\Run();
        $whoopsHandler = new Whoops\Handler\PrettyPageHandler();
        $whoopsRun->pushHandler($whoopsHandler);
        $whoopsRun->register();

        $moduleManager = ModuleManagerFactory::build();
        $moduleManager->setModules($appConfig['modules']);

        $mergeConfig = [];
        foreach ($moduleManager->getModules() as $key => $module) {
            try {
                $moduleConfig = $moduleManager->getConfig($module);
            } catch (Exception $e) {
                throw new \RuntimeException($e->getMessage());
            }

            $mergeConfig = array_merge_recursive($mergeConfig, $moduleConfig);
        }

        $config = ConfigFactory::build();
        $config->setConfig($mergeConfig);

        $route = RouteFactory::build();
        $route->setRoutes($config->get('routes'));
        $route->getRedirect()->setRoutes($route->getRoutes());

        $http = HttpFactory::build();

        return new Application($config, $http, $moduleManager, $route);
    }
}
