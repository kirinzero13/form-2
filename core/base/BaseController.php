<?php

namespace liw\core\base;


abstract class BaseController
{
    public $route = [];

    public $view;

    public $layout;

    /**
     * Controller constructor.
     * Подключение view страниц
     * @param $route
     */

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = $route['action'];
    }

    public function getView()
    {
        $vObj = new View($this->route, $this->layout, $this->view);
        $vObj->render();
    }


}