<?php

namespace liw\app\controllers;

use liw\core\base\BaseController;
use liw\app\model\DB;
use liw\Engine\Logger;

class MainController extends BaseController
{

    /**
     * indexAction
     */

    public function indexAction()
    {
    }

    /**
     * loginAction - проверка совпадения логина, паролей, а так же активации по email
     */

    public function loginAction()
    {
        /**
         * Проверка ввода логина и пароля
         */

        $validate = true;
        if (isset($_POST['submit'])) {
            if (empty($_POST['login'])) {
                echo '<div style="text-align: center;"><span style="color:red;"><br>Введите логин!</br></span></div>';
                $validate = false;
            } elseif (empty($_POST['password'])) {
                echo '<div style="text-align: center;"><span style="color:red;"><br>Введите пароль!</br></span></div>';
                $validate = false;
            }
        }

        /**
         * Если проверка пройдена, вроверяем данные
         */

        if ($validate) {
            if (isset($_POST['submit'])) {
                $a = new DB();
                $a->connectDB();

                $login = $_POST['login'];
                $password = md5($_POST['password']);
                $username = $login;
                $result = $a->activeCheck($username);

                if ($result === 0) {
                    $row = $a->passCheck($username);
                    if ($password != $row['password']) {
                        echo '<div style="text-align: center;"><span style="color:red;"><br>Неверный пароль!</br></span></div>';
                    }
                    else {
                        echo '<div style="text-align: center;"><span style="color:red;"><br>Пожалуйста, активируйте Ваш аккаунт!</br></span></div>';
                    }
                } elseif ($result > 0) {
                    $row = $a->passCheck($username);
                    if ($password == $row['password']) {
                        $_SESSION['password'] = $password;
                        $_SESSION['login'] = $login;
                        header("Location: profile");
                        exit();
                    }
                    else {
                        echo '<div style="text-align: center;"><span style="color:red;"><br>Неверный пароль!</br></span></div>';
                    }
                }
                else {
                    echo '<div style="text-align: center;"><span style="color:red;"><br>Логин <b>' . $_POST['login'] . '</b> не найден!</br></span></div>';
                }
                $a->close();
            }
        }


    }

    /**
     * tempkeyAction - получение tempkey, проверка и активация его в базе данных
     */

    public function tempkeyAction()
    {
        $info = '';
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);

        if (!empty($_GET['temp_key']) && isset($_GET['temp_key'])) {
            $a = new DB();
            $tmp = $a->tempCheck();
            if ($tmp > 0) {
                $temp = $a->tempDis();
                if ( $temp == 1) {
                   $a->tempUpdate();
                }
                $info = "<div style='text-align: center;\>'<span>Ваш аккаунт активирован! <a href='/app/view/login'><button>Перейти к авторизации</button></a></div><span>";
                require_once WWW . '/Engine/Logger.php';
                $a->close();
                if (!empty($logerwork)) {
                    $log = $a->tempDis();
                    if ($log > 0) {
                        $logs = $a->getRes();
                        $row = mysqli_fetch_assoc($logs);
                        $username = $row['login'];
                        $text = " Пользователь $username подтвердил свою учётную запись.";
                        $b = new Logger();
                        $b->logFileName();
                        $b->logWrite($text);
                    }

                } else {
                    $info = "<div style=\"text-align: center;\"><span>Ваш аккаунт уже активирован</span></div>";
                }
            }
        } else {
            $info = "<div style=\"text-align: center;\"><span>Неверный код активации</span></div>";
        }
        echo $info;
    }

    /**
     * profileAction - изменение логина, пароля, ФИО авторизированого пользователя
     */

    public function profileAction()
    {
        if (!isset($_SESSION["login"])) {
            header("location:/view/login");
        }

        $validate = true;

        /**
         *
         * Редактирование логина
         *
         */

        if (isset($_POST['submit'])) {
            $new_login = $_POST['login'];
            if (empty($new_login)) {
                echo '<div style="text-align: center;"><span style="color:red;">Вы не ввели логин!</span></div>';
                $validate = false;
            }

            if ($validate) {
                $a = new DB();
                require_once WWW . '/Engine/Logger.php';
                if (!empty($logerwork)) {
                    $log = "SELECT login FROM users WHERE `login`='" . $_SESSION['login'] . "'";
                    $result = mysqli_query($a->connectDB(), $log);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $username = $row['login'];
                        $text = " Пользователь $username изменил свой логин на $new_login.";
                        $b = new Logger();
                        $b->logFileName();
                        $b->logWrite($text);
                    }
                }
                    $username = $new_login;
                    $result = $a->loginCheck($username);
                    if ($result > 0) {
                        echo '<div style="text-align: center;"><span style="color:red;">Пользователь с таким логином уже существует! 
                        Пожалуйста, введите другой логин!</span></div>';
                    }
                    else {
                        $sql = "UPDATE `users` SET login = ? WHERE login = '" . $_SESSION['login'] . "'";
                        $a->query($sql, $username);
                        echo '<div style="text-align: center;"><span style="color:red;">Ваш логин изменён! Пожалуйста, нажмите кнопку "Выйти", и войдите в ваш профиль заново, используя новый логин.</span></div>';
                    }
                $a->close();
                }
            }


        /**
         *
         * Редактирование пароля
         *
         */

        if (isset($_POST['submit2'])) {
            $new_pass = $_POST['password'];
            if (empty($new_pass)) {
                echo '<div style="text-align: center;"><span style="color:red;">Вы не ввели пароль!</span></div>';
                $validate = false;
            }
            if ($validate) {
                $mdpass = md5($new_pass);
                $a = new DB();
                require_once WWW . '/Engine/Logger.php';
                if (!empty($logerwork)) {
                    $login = $_SESSION['login'];
                    $text = " Пользователь $login изменил свой пароль.";
                    $b = new Logger();
                    $b->logFileName();
                    $b->logWrite($text);
                }
                $sql = "UPDATE `users` SET password = ? WHERE login = '" . $_SESSION['login'] . "'";
                $passwd = $mdpass;
                $a->query($sql, $passwd);
                echo "<div style=\"text-align: center;\"><span>Пароль успешно изменён!</div></span>";
                $a->close();
            }
        }

        /**
         *
         *
         * Редактирование Ф.И.О
         *
         *
         */

        if (isset($_POST['submit3'])) {
            $new_fio = $_POST['fio'];
            if (empty($new_fio)) {
                echo '<div style="text-align: center;"><span style="color:red;">Вы не ввели Ф.И.О!</span></div>';
                $validate = false;
            }
            if ($validate) {
                $login = $_SESSION['login'];
                $a = new DB();
                require_once WWW . '/Engine/Logger.php';
                $sql = "UPDATE `users` SET fio = ? WHERE login = '" . $_SESSION['login'] . "'";
                $fullname = $new_fio;
                $a->query($sql, $fullname);
                $a->close();
                    echo "<div style=\"text-align: center;\"><span>Ф.И.О успешно изменено!</div></span>";
                }
            }
        }


    /**
     * logoutAction - выход из профиля
     */

    public
    function logoutAction()
    {
        unset($_SESSION['login']);
        session_destroy();
    }
}
