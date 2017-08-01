<?php
/**
 * Created by PhpStorm.
 * User: super
 * Date: 12.07.17
 * Time: 9:22
 */

namespace liw\app\controllers;
use liw\core\base\BaseController;
use liw\app\model\DB;
use liw\Engine\Logger;
use liw\app\model\Main;

class RegistrationController extends BaseController
{

    public function registrAction()
    {
        $validate = true;
        if (isset($_POST['submit'])) {

            if (!preg_match("#^[aA-zZ0-9\-_]+$#", $_POST['login'])) {
                echo '<div style="text-align: center;"><span style="color:red;"> Есть недопустимые символы в логине!</span></div>';
                $validate = false;
            }
            elseif (!preg_match("#^[aA-zZ0-9\-_]+$#", $_POST['password'])) {
                echo '<div style="text-align: center;"><span style="color:red;"> Есть недопустимые символы в пароле!</span></div>';
                $validate = false;
            }

            if (empty($_POST['login'])) {
                echo '<div style="text-align: center;"><span style="color:red;">Введите логин!</span></div>'; // Выводим сообщение об ошибке
                $validate = false;
            } elseif (empty($_POST['password'])) {
                echo '<div style="text-align: center;"><span style="color:red;">Введите пароль!</span></div>'; // Выводим сообщение об ошибке
                $validate = false;
            } elseif (empty($_POST['email'])) {
                echo '<div style="text-align: center;"><span style="color:red;">Введите почту!</span></div>'; // Выводим сообщение об ошибке
                $validate = false;
            } elseif (empty($_POST['fio'])) {
                echo '<div style="text-align: center;"><span style="color:red;">Введите Ф.И.О!</span></div>'; // Выводим сообщение об ошибке
                $validate = false;
            }
        }

        if ($validate) {
           $a = new DB();
           if (isset($_POST['submit'])) {
               $login = $_POST['login'];
               $password = md5($_POST['password']);
               $email = $_POST['email'];
               $fio = $_POST['fio'];
               $temp_key = md5($login . time());
               $a->connectDB();
               $username = $login;
               $result = $a->loginCheck($username);

               if ($result > 0) {
                   echo '<div style="text-align: center;"><span style="color:red;">Пользователь с таким логином уже существует! Пожалуйста, введите другой логин!</span></div>';
                   $a->close();
               }

               else {
                   $param = array("sssss", &$login, &$password, &$email, &$fio, &$temp_key);
                   $a->insertUsersReg($param);
                   $mail = new Main();
                   $mail->sendMail($email, $temp_key);
                   echo '<div style="text-align: center;"><span><br>Регистрация прошла успешно! Мы отправили письмо для активации на Ваш email, пожалуйста, активируйте его!</br></span></div>';
                   $a->close();

                   require_once WWW . '/Engine/Logger.php';
                   if (!empty($logerwork)) {
                       $text = " Был зарегестрирован пользователь - $login.";
                       $b = new Logger();
                       $b->logFileName();
                       $b->logWrite($text);
                   }
               }
           }
        }
    }
}
