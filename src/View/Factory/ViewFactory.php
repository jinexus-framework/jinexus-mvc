<?php
namespace JiNexus\Mvc\View\Factory;

use JiNexus\Mvc\Application\ApplicationInterface;
use JiNexus\Mvc\Factory\AbstractFactory;
use JiNexus\Mvc\View\View;

/**
 * Class ViewFactory
 * @package JiNexus\Mvc\View\Factory
 */
class ViewFactory extends AbstractFactory
{
    /**
     * @param ApplicationInterface $app
     * @return View
     */
    public static function build(ApplicationInterface $app)
    {
        return new View($app);
    }
}
