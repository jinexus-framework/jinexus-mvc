<?php
namespace JiNexus\Mvc\Controller;

use JiNexus\Http\Http\HttpInterface;
use JiNexus\Http\Request\RequestInterface;
use JiNexus\Mvc\Application\ApplicationInterface;
use JiNexus\Mvc\Base\AbstractBase;
use JiNexus\Mvc\View\ViewInterface;

/**
 * Class AbstractController
 * @package JiNexus\Mvc\Controller
 */
abstract class AbstractController extends AbstractBase implements ControllerInterface
{
    /**
     * View instance
     *
     * @var ViewInterface
     */
    protected $view;

    /**
     * Http instance
     *
     * @var HttpInterface
     */
    protected $http;

    /**
     * Request instance
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var \JiNexus\Route\Redirect\RedirectInterface
     */
    protected $redirect;

    /**
     * AbstractController constructor
     *
     * @param ApplicationInterface $app
     * @throws \ReflectionException
     */
    public function __construct(ApplicationInterface $app)
    {
        $this->view = $app->getView();
        $this->http = $app->getHttp();
        $this->request = $app->getHttp()->getRequest();
        $this->redirect = $app->getRoute()->getRedirect();

        // TODO: For future usage
        $reflection = new \ReflectionClass($this);
    }
}
