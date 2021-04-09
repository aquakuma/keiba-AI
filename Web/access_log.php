<?php
    date_default_timezone_set('Asia/Tokyo');
    $uri = $_SERVER["REQUEST_URI"]; // アクセスしたページのURI
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))// IPアドレス取得
    {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR']; 
    }
    else{
        $ipaddress = $_SERVER['REMOTE_ADDR']; 
    }

    ///////////////////IP国///////////////////

    require './vendor/autoload.php';

    use GeoIp2\Database\Reader;

    // ダウンロードしたバイナリを指定
    $reader = new Reader('./GeoLite2-City/GeoLite2-City.mmdb');


    $country = "";
    $city = "";
    try{
        // チェックしたいIPアドレスを指定
        $place = $reader->city($ipaddress);

        $country = $place->country->names['ja'];

        $city = $place->city->names['ja'];

    }catch(Exception $e){
        $country = "";
        $city = "";
    }


    //////////////////////////////////////

    if($ipaddress != "10.1.26.115" && $ipaddress != "10.0.0.233"){
        $pdo = db_connect();

        //SQL文作成
        $sql = "INSERT INTO access_log (ip,access_date,address) VALUES(:ip,:access_date,:address)";
    
        //プリペアードステートメントの設定と取得
        $prestmt = $pdo->prepare($sql);
    
        //値の設定
        $prestmt->bindValue(':ip', $ipaddress);
        $date = date('Y-m-d H:i:s');
        $prestmt->bindValue(':access_date', $date, PDO::PARAM_STR);
        $prestmt->bindValue(':address', $country.$city);
        //SQL実行 
        $prestmt->execute();
    }



?>