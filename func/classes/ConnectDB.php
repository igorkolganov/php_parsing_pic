<?php

class ConnectDB{
    private $host = '127.0.0.1';
    private $user = 'mysql';
    private $password = 'mysql';
    private $nameDB = 'test_base';
    private $link;
    private $sql;

    protected function connect(){
        $this->link = mysqli_connect($this->host, $this->user, $this->password, $this->nameDB);
        if (mysqli_connect_errno()) {
            echo 'Error connection to DB (' . mysqli_connect_errno() . ')' . mysqli_connect_error();
            exit();
        }
        mysqli_set_charset($this->link, 'utf8');
    }

    protected function insertData($editData, $editDate, $dataBody, $photoData){
        $this->connect();
        $this->sql = "INSERT INTO news_parse (title, dates, body, picture) VALUES ('$editData', '$editDate', '$dataBody', '$photoData')";
        mysqli_query($this->link, $this->sql);
    }

    protected function getData($a){
        $id = $a ? $a : 142;
        $this->connect();

        if ($a)
            $this->sql = "SELECT * FROM news_parse WHERE id='$id'";
        else
            $this->sql = "SELECT * FROM news_parse";

        $result = mysqli_query($this->link, $this->sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
$connect = new ConnectDB();