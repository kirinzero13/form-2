<?php

namespace liw\core;


class Router {


    /**
     * таблица маршрутов
     * @var array
     */
    public static $routes = [];

    /**
     * текущий маршрут
     * @var array
     */
    public static $route = [];

    /**
     * добавляет маршрут в таблицу маршрутов
     * @param $reg
     * @param array $route маршрут (контроллер, экшн, параметр)
     */
    public static function add($reg, $route = [])
    {
        self::$routes[$reg] = $route;
    }

    /**
     * возвращает таблицу паршрутов
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * возращает текущий маршрут
     * @return array
     */
    public static function getRoute()
    {
        return self::$route;
    }

    /**
     * Поиск URL в таблице маршрутов
     * @param $url - входящий URL адрес
     * @return bool
     */
    public static function getUrl($url)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#", $url, $matches)) {
                foreach ($matches as $key => $value) {
                    if(is_string($key)) {
                        $route[$key] = $value;
                    }
                }
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    /**
     * Перенаправляет входящий URL адрес по корректному маршруту
     * @param string $url входящий URL
     *
     */
    public static function dispatch($url) {
        if(self::getUrl($url)) {
            $controller = WORK . self::$route['controller'] . 'Controller';
            if (class_exists($controller)) {
                $cObj = new $controller(self::$route);
                $action = self::$route['action'] . 'Action';
                if (method_exists($cObj, $action)) {
                    $cObj->$action();
                    $cObj->getView();
                        }
                else {
                    echo "Метод $controller::$action не найден!";
                }
            }
            else {
                echo "Контроллер $controller не найден.";
            }
        }
        else {
            echo "Error 404. Страница $url не найдена!";
        }
    }

}