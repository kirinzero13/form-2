<?php

/**
 * Вывод ошибок
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$path = trim($_SERVER['REQUEST_URI'], '/');

use liw\core\Router;

/**
 * Константы с путями к корневой папке, к контроллерам, и к вьяхам
 */

define('WWW', __DIR__);
define('APP', __DIR__ . '/app/controllers');
define('WORK', 'liw\app\controllers\\');
define('VIEWS', __DIR__ . '/app/view/');
define('LAYOUT', 'default');

session_start();

require_once 'debug.php';

require WWW . '/vendor/autoload.php';

/**
 * В зависимости от URL адреса, запускает определенныё контроллер и метод
 */

Router::add('view/registration', ['controller' => 'Registration', 'action' => 'registr']);
Router::add('view/login', ['controller' => 'Main', 'action' => 'login']);
Router::add('view/profile', ['controller' => 'Main', 'action' => 'profile']);
Router::add('logout', ['controller' => 'Main', 'action' => 'logout']);
Router::add('index', ['controller' => 'Main', 'action' => 'index']);
Router::add('view/tempkey', ['controller' => 'Main', 'action' => 'tempkey']);
Router::add('', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
Router::dispatch($path);
