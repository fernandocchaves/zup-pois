<?php
namespace ZupPois\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

abstract class BaseController implements ControllerProviderInterface
{

    abstract public function mount($controllers);

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $this->mount($controllers);
        return $controllers;
    }
}