<?php
    function db_connect(){
        //include('../db/keiba.php');
        $pdo = new PDO("mysql:host=localhost;dbname=keiba;charset=utf8mb4", "root", "Pigburger_17");
        //let logo = getElementById('id');
    
        //PDOの設定変更
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        $pdo->setAttribute(
            PDO::ATTR_EMULATE_PREPARES,
            false
        );
        return $pdo;
    }


?>