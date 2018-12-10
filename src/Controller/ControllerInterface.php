<?php
namespace JiNexus\Mvc\Controller;

use JiNexus\Http\Http\HttpInterface;
use JiNexus\Mvc\Application\ApplicationInterface;
use JiNexus\Mvc\Base\BaseInterface;
use JiNexus\Mvc\View\ViewInterface;

/**
 * Interface ControllerInterface
 * @package JiNexus\Mvc\Controller
 */
interface ControllerInterface extends BaseInterface
{
    /**
     * ControllerInterface constructor
     *
     * @param ApplicationInterface $app
     */
    public function __construct(ApplicationInterface $app);
}
