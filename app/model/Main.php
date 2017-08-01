<?php

namespace liw\app\model;

class Main
{

    public function sendMail($email, $temp_key) {
        $base_url = '192.168.7.243/';
        $to = $email;
        $subject = "Проверка Вашего email!";
        $message = 'Привет! Чтобы активировать Ваш аккаунт - 
                    Вам необходимо вставить ссылку в адресную строку : ' . $base_url . 'view/tempkey?temp_key=' . $temp_key . '';
        mail($to, $subject, $message);
    }

}