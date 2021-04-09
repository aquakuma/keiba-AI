<?php
include('./db_connect.php');
$mode = $_POST['mode'];


// データベース接続

try{



    //DB操作用オブジェクトの作成

    $pdo = db_connect();
    //PDOの設定変更（エラー黙殺→例外発生）
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,          //3
        PDO::ERRMODE_EXCEPTION);    //2


    if ($mode == "init"){
        init($pdo);
    }

    if ($mode == "predict"){
        $date = $_POST['date'];
        $racecourse_input = $_POST['racecourse'];
        predict($pdo,$date,$racecourse_input);
    }

    if ($mode == "racecourse"){
        $date = $_POST['date'];
        racecourse($pdo,$date);
    }

    if ($mode == "predict_show"){
        predict_show($pdo);
    }
    
    if ($mode == "graphic_data"){
        graphic($pdo);
    }

    if ($mode == "race_num"){
        race_num($pdo);
    }

    if ($mode == "race_recovery"){
        race_recovery($pdo);
    }

    if ($mode == "limen_win_rate"){
        limen_win_rate($pdo);
    }

    if ($mode == "limen_race_num"){
        limen_race_num($pdo);
    }

    if ($mode == "limen_recovery"){
        limen_recovery($pdo);
    }
}

catch (PDOException $e) {
    print('接続失敗:' . $e->getMessage());
    die();
}

//1年分レース
function init($pdo){
    $sql = "select DISTINCT  DATE_FORMAT(time,'%m/%d') as date,
    (case WEEKDAY(time)
    when 0 then '月'
    when 1 then '火'
    when 2 then '水'
    when 3 then '木'
    when 4 then '金'
    when 5 then '土'
    when 6 then '日'
    else 'x' end)
    as week
    from predict
    where time > (now() - interval 1 year + interval 1 day + interval 1 day)
    order by time desc;";
    $dbh = $pdo->query($sql);

    $index = 0;

    while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
        //インスタンスのみ→PDO::FETCH_NUM
        //連想配列のみ→PDO::FETCH_ASSOC
        //両方→PDO::FETCH_BOTH（メモリの無駄）
        //print_r($record);
        $ouput[$index]['date']= $record["date"];
        $ouput[$index]['week']= $record["week"];

        $index++;
        
    }
    
    //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($ouput,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}


//選択した場合　当日レース
function predict($pdo,$date,$racecourse_input){
    $sql = "select race_id,racecourse,race_title,round,DATE_FORMAT(time, '%H:%i') as start,total_horse,pre_num,reliability,cre_dif,limen,pre_rank,odds,first,win from predict where racecourse = '$racecourse_input' and DATE_FORMAT(time, '%m/%d') = '$date' and time > (now() - interval 1 year + interval 1 day + interval 1 day) and time < now()";
    $dbh = $pdo->query($sql);

    $index = 0;

    while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
        //インスタンスのみ→PDO::FETCH_NUM
        //連想配列のみ→PDO::FETCH_ASSOC
        //両方→PDO::FETCH_BOTH（メモリの無駄）
        //print_r($record);
        $ouput[$index]['race_id']= $record["race_id"];
        $ouput[$index]['racecourse']= $record["racecourse"];
        $ouput[$index]['race_title']= $record["race_title"];
        $ouput[$index]['round']= $record["round"];
        $ouput[$index]['start']= $record["start"];
        $ouput[$index]['total_horse']= $record["total_horse"];
        $ouput[$index]['pre_num']= $record["pre_num"];
        $ouput[$index]['reliability']= $record["reliability"];
        $ouput[$index]['cre_dif']= $record["cre_dif"];
        $ouput[$index]['limen']= $record["limen"];
        $ouput[$index]['pre_rank']= $record["pre_rank"];
        $ouput[$index]['odds']= $record["odds"];
        $ouput[$index]['first']= $record["first"];
        $ouput[$index]['win']= $record["win"];
        $index++;
    }

    //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($ouput,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

//当日にレースあった競馬場
function racecourse($pdo,$date){
    $sql = "select DISTINCT racecourse from predict where DATE_FORMAT(time, '%m/%d') = '$date' and time > (now() - interval 1 year + interval 1 day + interval 1 day )";
    $dbh = $pdo->query($sql);

    $index = 0;

    while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
        //インスタンスのみ→PDO::FETCH_NUM
        //連想配列のみ→PDO::FETCH_ASSOC
        //両方→PDO::FETCH_BOTH（メモリの無駄）
        //print_r($record);
        $racecourse[$index]= $record["racecourse"];
        $index++;
    }

    //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($racecourse,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

//予測レース表示
function predict_show($pdo){
    $sql = "select race_id,racecourse,race_title,round,DATE_FORMAT(time, '%H:%i') as start,total_horse,pre_num,reliability,cre_dif,limen,odds from predict where time > now() ORDER BY time";
    //$sql = "select race_id,racecourse,race_title,round,DATE_FORMAT(time, '%H:%i') as start,total_horse,pre_num,reliability,cre_dif,limen,odds from predict LIMIT 4";
    $dbh = $pdo->query($sql);

    $index = 0;

    while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
        //インスタンスのみ→PDO::FETCH_NUM
        //連想配列のみ→PDO::FETCH_ASSOC
        //両方→PDO::FETCH_BOTH（メモリの無駄）
        //print_r($record);
        $ouput[$index]['race_id']= $record["race_id"];
        $ouput[$index]['racecourse']= $record["racecourse"];
        $ouput[$index]['race_title']= $record["race_title"];
        $ouput[$index]['round']= $record["round"];
        $ouput[$index]['start']= $record["start"];
        $ouput[$index]['total_horse']= $record["total_horse"];
        $ouput[$index]['pre_num']= $record["pre_num"];
        $ouput[$index]['reliability']= $record["reliability"];
        $ouput[$index]['cre_dif']= $record["cre_dif"];
        $ouput[$index]['limen']= $record["limen"];
        $ouput[$index]['odds']= $record["odds"];

        $index++;
    }

    //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($ouput,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

//勝率グラフ
function graphic($pdo){
    $sql = "select distinct racecourse from predict;";
    $dbh = $pdo->query($sql);

    $index = 0;

    while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
        //インスタンスのみ→PDO::FETCH_NUM
        //連想配列のみ→PDO::FETCH_ASSOC
        //両方→PDO::FETCH_BOTH（メモリの無駄）
        //print_r($record);
        $racecourse[$index]= $record["racecourse"];
        $index++;
    }
    foreach($racecourse as $place){
        $sql = "select count(*) as allrace from predict where racecourse = '$place' and limen >=0.8 and time > (now() - interval 1 year + interval 1 day + interval 1 day);";
        $dbh = $pdo->query($sql);
        while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
            //インスタンスのみ→PDO::FETCH_NUM
            //連想配列のみ→PDO::FETCH_ASSOC
            //両方→PDO::FETCH_BOTH（メモリの無駄）
            //print_r($record);
            $all= $record["allrace"];
        }

        $sql = "select count(*) as win from predict where racecourse = '$place' and win = 1 and limen >=0.8 and time > (now() - interval 1 year + interval 1 day + interval 1 day);";
        $dbh = $pdo->query($sql);
        while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
            //インスタンスのみ→PDO::FETCH_NUM
            //連想配列のみ→PDO::FETCH_ASSOC
            //両方→PDO::FETCH_BOTH（メモリの無駄）
            //print_r($record);
            $win= $record["win"];
        }
        $win_rate[$place] = $win/$all;
    }


    //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($win_rate,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

//レース数グラフ
function race_num($pdo){
    $sql = "select distinct racecourse from predict;";
    $dbh = $pdo->query($sql);

    $index = 0;

    while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
        //インスタンスのみ→PDO::FETCH_NUM
        //連想配列のみ→PDO::FETCH_ASSOC
        //両方→PDO::FETCH_BOTH（メモリの無駄）
        //print_r($record);
        $racecourse[$index]= $record["racecourse"];
        $index++;
    }
    foreach($racecourse as $place){
        $sql = "select count(*) as allrace from predict where racecourse = '$place' and limen >=0.8 and time > (now() - interval 1 year + interval 1 day);";
        $dbh = $pdo->query($sql);
        while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
            //インスタンスのみ→PDO::FETCH_NUM
            //連想配列のみ→PDO::FETCH_ASSOC
            //両方→PDO::FETCH_BOTH（メモリの無駄）
            //print_r($record);
            $all= $record["allrace"];
        }

        $race_num[$place] = $all;
    }


    //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($race_num,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

//レース回収率グラフ
function race_recovery($pdo){
    $sql = "select distinct racecourse from predict;";
    $dbh = $pdo->query($sql);

    $index = 0;

    while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
        //インスタンスのみ→PDO::FETCH_NUM
        //連想配列のみ→PDO::FETCH_ASSOC
        //両方→PDO::FETCH_BOTH（メモリの無駄）
        //print_r($record);
        $racecourse[$index]= $record["racecourse"];
        $index++;
    }
    foreach($racecourse as $place){
        $sql = "select odds,win from predict where racecourse = '$place' and limen >=0.8 and time > (now() - interval 1 year + interval 1 day);";
        $dbh = $pdo->query($sql);
        $prize = 0;
        $bat = 0;
        while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
            //インスタンスのみ→PDO::FETCH_NUM
            //連想配列のみ→PDO::FETCH_ASSOC
            //両方→PDO::FETCH_BOTH（メモリの無駄）
            //print_r($record);
            $odds= $record["odds"];
            $win= $record["win"];

            $bat +=100;
            $prize +=$odds * $win * 100;


        }

        $race_recovery[$place] = round($prize/$bat,5);
    }


    //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($race_recovery,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}


//閾値勝率グラフ
function limen_win_rate($pdo){


    $lime_dif = [];
    $dif_value = 0.5;
    $limen_max = 5;
    for($dif = 0.8; $dif <=$limen_max ;$dif +=$dif_value){
        array_push($lime_dif,$dif);
    }
    $index = 0;
    for($i = 0 ;$i< count($lime_dif);$i++){
        if($i != count($lime_dif)-1){
            $limen_a = $lime_dif[$i];
            $limen_b = $lime_dif[$i+1];
            $sql = "select count(*) as all_race from predict where limen >=$limen_a and limen < $limen_b and time > (now() - interval 1 year + interval 1 day);";
            $dbh = $pdo->query($sql);

            $all = 0;
            while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
                //インスタンスのみ→PDO::FETCH_NUM
                //連想配列のみ→PDO::FETCH_ASSOC
                //両方→PDO::FETCH_BOTH（メモリの無駄）
                //print_r($record);
                $all= $record["all_race"];
            }
            $sql = "select count(*) as win from predict where limen >=$limen_a and limen < $limen_b and win = 1 and time > (now() - interval 1 year + interval 1 day);";
            $dbh = $pdo->query($sql);

            $win = 0;
            while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
                //インスタンスのみ→PDO::FETCH_NUM
                //連想配列のみ→PDO::FETCH_ASSOC
                //両方→PDO::FETCH_BOTH（メモリの無駄）
                //print_r($record);
                $win= $record["win"];
            }
            $limen_win_rate[$limen_a."-".$limen_b] = round($win/$all,5);

        }
        else{
            $limen_a = $lime_dif[$i];

            $sql = "select count(*) as all_race from predict where limen >=$limen_a and time > (now() - interval 1 year + interval 1 day);";
            $dbh = $pdo->query($sql);

            $all = 0;
            while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
                //インスタンスのみ→PDO::FETCH_NUM
                //連想配列のみ→PDO::FETCH_ASSOC
                //両方→PDO::FETCH_BOTH（メモリの無駄）
                //print_r($record);
                $all= $record["all_race"];
            }
            $sql = "select count(*) as win from predict where limen >=$limen_a and win = 1 and time > (now() - interval 1 year + interval 1 day);";
            $dbh = $pdo->query($sql);

            $win = 0;
            while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
                //インスタンスのみ→PDO::FETCH_NUM
                //連想配列のみ→PDO::FETCH_ASSOC
                //両方→PDO::FETCH_BOTH（メモリの無駄）
                //print_r($record);
                $win= $record["win"];
            }
            $limen_win_rate[$limen_a."以上"] = round($win/$all,5);

        }

    }




    //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($limen_win_rate,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}


//閾値レース数グラフ
function limen_race_num($pdo){


    $lime_dif = [];
    $dif_value = 0.5;
    $limen_max = 5;
    for($dif = 0.8; $dif <=$limen_max ;$dif +=$dif_value){
        array_push($lime_dif,$dif);
    }
    $index = 0;
    for($i = 0 ;$i< count($lime_dif);$i++){
        if($i != count($lime_dif)-1){
            $limen_a = $lime_dif[$i];
            $limen_b = $lime_dif[$i+1];
            $sql = "select count(*) as all_race from predict where limen >=$limen_a and limen < $limen_b and time > (now() - interval 1 year + interval 1 day);";
            $dbh = $pdo->query($sql);

            $all = 0;
            while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
                //インスタンスのみ→PDO::FETCH_NUM
                //連想配列のみ→PDO::FETCH_ASSOC
                //両方→PDO::FETCH_BOTH（メモリの無駄）
                //print_r($record);
                $all= $record["all_race"];
            }

            $limen_win_rate[$limen_a."-".$limen_b] = $all;

        }
        else{
            $limen_a = $lime_dif[$i];

            $sql = "select count(*) as all_race from predict where limen >=$limen_a and time > (now() - interval 1 year + interval 1 day);";
            $dbh = $pdo->query($sql);

            $all = 0;
            while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
                //インスタンスのみ→PDO::FETCH_NUM
                //連想配列のみ→PDO::FETCH_ASSOC
                //両方→PDO::FETCH_BOTH（メモリの無駄）
                //print_r($record);
                $all= $record["all_race"];
            }
            $sql = "select count(*) as win from predict where limen >=$limen_a and win = 1 and time > (now() - interval 1 year + interval 1 day);";
            $dbh = $pdo->query($sql);

            $limen_win_rate[$limen_a."以上"] = $all;

        }

    }




    //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($limen_win_rate,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}



//閾値回収率グラフ
function limen_recovery($pdo){


    $lime_dif = [];
    $dif_value = 0.5;
    $limen_max = 5;
    for($dif = 0.8; $dif <=$limen_max ;$dif +=$dif_value){
        array_push($lime_dif,$dif);
    }
    $index = 0;
    for($i = 0 ;$i< count($lime_dif);$i++){
        if($i != count($lime_dif)-1){
            $limen_a = $lime_dif[$i];
            $limen_b = $lime_dif[$i+1];
            $sql = "select odds,win from predict where limen >=$limen_a and limen < $limen_b and time > (now() - interval 1 year + interval 1 day);";
            $dbh = $pdo->query($sql);

            $prize = 0;
            $bat = 0;
            while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
                //インスタンスのみ→PDO::FETCH_NUM
                //連想配列のみ→PDO::FETCH_ASSOC
                //両方→PDO::FETCH_BOTH（メモリの無駄）
                //print_r($record);
                $odds= $record["odds"];
                $win= $record["win"];
    
                $bat +=100;
                $prize +=$odds * $win * 100;
            }
            $limen_recovery[$limen_a."-".$limen_b] = round($prize/$bat,5);

        }
        else{
            $limen_a = $lime_dif[$i];
            $sql = "select odds,win from predict where limen >=$limen_a and time > (now() - interval 1 year + interval 1 day)";
            $dbh = $pdo->query($sql);

            $prize = 0;
            $bat = 0;
            while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
                //インスタンスのみ→PDO::FETCH_NUM
                //連想配列のみ→PDO::FETCH_ASSOC
                //両方→PDO::FETCH_BOTH（メモリの無駄）
                //print_r($record);
                $odds= $record["odds"];
                $win= $record["win"];
    
                $bat +=100;
                $prize +=$odds * $win * 100;
            }
            $limen_recovery[$limen_a."以上"] = round($prize/$bat,5);
        }

    }




    //jsonとして出力
    header('Content-type: application/json');
    echo json_encode($limen_recovery,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}