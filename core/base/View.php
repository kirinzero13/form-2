<?php
/**
 * Created by PhpStorm.
 * User: super
 * Date: 12.07.17
 * Time: 9:40
 */

namespace liw\core\base;


class View
{
    public $route = [];

    public $view;

    public $layout;

    public function __construct($route, $layout = '', $view = '')
    {
        $this->route = $route;
        $this->layout = $layout ?: LAYOUT;
        $this->view = $view;
    }

    public function render ()
    {
        $file_view = "app/view/{$this->route['controller']}/{$this->view}.php";
        if (is_file($file_view)) {
            require $file_view;
        }

        else {
            echo "$file_view не найден!";
        debug($file_view);
        }
    }


}