<?php

namespace liw\Engine;

include WWW . '/Config/db_setting.php';

if ($logerwork) {

    class Logger
    {

        public $logfile;
        public $text;

        /**
         * Создание файла для логирования
         */
        public function logFileName()
        {
            $logfile = WWW . '/Logs/log_' . date('d-M-Y');
            $this->logfile = $logfile;
            return $this->logfile;
        }

        /**
         * Запись в файл логирования события
         * @param $text
         * @return string
         */
        public function logWrite($text)
        {
            $date = date('H:i:s');
            $read = file_put_contents($this->logfile, $date . $text . "\n", FILE_APPEND);
            return $read;
        }

    }

}