<?php

namespace liw\app\model;


class DB {

    protected $pdo;
    public $connect;
    public $params;
    public $result;
    public $stmt;

    public function connectDB() {
        require WWW . '/Config/db_setting.php';
        $connect = mysqli_connect($params["db_host"],$params["db_user"],$params["db_pass"],$params["db_name"]);
        mysqli_set_charset( $connect, 'utf8');
        $this->connect = $connect;
        return $this->connect;
    }

    public function close()
    {
        mysqli_close($this->connect);
    }

    public function execute($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * @param $sql string запрос
     * Подготовка sql и возвращение данных
     * @return array
     */

    public function query($sql, $params)
    {
        $stmt = $this->connectDB()->prepare($sql);
        $stmt->bind_param("s", $params);
        $res = $stmt->execute();
        if ($res !== false) {
            $result = mysqli_stmt_get_result($stmt);
            return $result;
        }
        return [];
    }

    public function makeQuery($sql, $param = []){
        $stmt = $this->connectDB()->prepare($sql);
        $stmt->bind_param($param);
        $res = $stmt->execute();
        if ($res !== false) {
            $result = mysqli_stmt_get_result($stmt);
            $this->result = $result;
            $rowNum = $result->num_rows;
            return $rowNum;
        }
        return [];
    }

    public function insertUsersReg($param =[]) {
        $stmt = $this->connect->prepare("INSERT INTO `users` (login, password, email, fio, temp_key ) VALUES (?, ?, ?, ?, ?)");
        $tmp = array();
        foreach($param as $key => $value) $tmp[$key] = &$param[$key];
        call_user_func_array(array($stmt, 'bind_param'), $tmp);
        $res = $stmt->execute();
        return $res;
    }

   public function loginCheck($username) {
       $stmt = $this->connect->prepare("SELECT * FROM `users` WHERE login = ?");
       $stmt->bind_param("s", $username);
       $res = $stmt->execute();
       if ($res !== false) {
           $result = mysqli_stmt_get_result($stmt);
           $this->result = $result;
           $rowNum = $result->num_rows;
           return $rowNum;
       }
       return [];
   }

   public function activeCheck($username){
       $stmt = $this->connect->prepare("SELECT * FROM users WHERE login = ? AND active= 1");
       $stmt->bind_param("s", $username);
       $res = $stmt->execute();
       if ($res !== false) {
           $result = mysqli_stmt_get_result($stmt);
           $this->result = $result;
           $rowNum = $result->num_rows;
           return $rowNum;
       }
       return [];
   }

   public function passCheck($username){
       $stmt = $this->connect->prepare("SELECT password FROM users WHERE login = ?");
       $stmt->bind_param("s", $username);
       $res = $stmt->execute();
       if ($res === true) {
           $result = mysqli_stmt_get_result($stmt);
           $assoc = mysqli_fetch_assoc($result);
           return $assoc;
       }
       return [];
   }

   public function tempCheck() {
       $temp_key = mysqli_real_escape_string($this->connectDB(), ($_GET['temp_key']));
       $stmt = $this->connect->prepare("SELECT id FROM users WHERE temp_key = '$temp_key'");
       $res = $stmt->execute();
       if ($res !== false) {
           $result = mysqli_stmt_get_result($stmt);
           $this->result = $result;
           $rowNum = $result->num_rows;
           return $rowNum;
       }
       return [];
   }

   public function tempDis() {
       $temp_key = mysqli_real_escape_string($this->connectDB(), ($_GET['temp_key']));
       $stmt = $this->connect->prepare("SELECT id FROM users WHERE temp_key ='$temp_key' AND active = '0'");
       $res = $stmt->execute();
       if ($res !== false) {
           $result = mysqli_stmt_get_result($stmt);
           $this->result = $result;
           $rowNum = $result->num_rows;
           return $rowNum;
       }
       return [];
   }

   public function tempUpdate() {
       $temp_key = mysqli_real_escape_string($this->connectDB(), ($_GET['temp_key']));
       $stmt = $this->connect->prepare("UPDATE users SET active='1' WHERE temp_key='$temp_key'");
       $res = $stmt->execute();
       if ($res !== false) {
           $result = mysqli_stmt_get_result($stmt);
           $rowNum = $result->num_rows;
           return $rowNum;
       }
       return [];
   }

   public function getRes(){
       $temp_key = mysqli_real_escape_string($this->connectDB(), ($_GET['temp_key']));
       $stmt = $this->connect->prepare("SELECT id FROM users WHERE temp_key = '$temp_key'");
       $res = $stmt->execute();
       if ($res !== false) {
           $result = mysqli_stmt_get_result($stmt);
           return $result;
       }
       return [];
   }
}