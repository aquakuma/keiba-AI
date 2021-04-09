<?php
    $handle    = fopen('php://input','r');
    //$jsonInput = fgets($handle);
    $jsonInput = json_decode(fgets($handle),JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    //$ouput['debug'] = 'successful'.$jsonInput['push'];

    $$mode = "";
    if(isset($jsonInput['mode'])){
        $mode = $jsonInput['mode'];
    }
    

    require_once('../db/keiba.php');

    $pdo = new PDO(DSN, DB_USER, DB_PASSWORD);
    //PDOの設定変更（エラー黙殺→例外発生）
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,          //3
        PDO::ERRMODE_EXCEPTION);    //2


    if(empty($mode)){
        header("Location: index.html");
        exit;
    }

    if($mode == "date"){
        init($pdo);
    }
    if($mode == "racecourse"){
        racecourse($pdo,$jsonInput['date']);
    }
    if($mode == "race"){
        race($pdo,$jsonInput['date'],$jsonInput['course']);
    }
    if($mode == "predict"){
        predict($pdo);
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
        where time > (now() - interval 1 year + interval 1 day)
        AND first IS NOT NULL
        order by time desc;";
        $dbh = $pdo->query($sql);

        $index = 0;

        while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
            //インスタンスのみ→PDO::FETCH_NUM
            //連想配列のみ→PDO::FETCH_ASSOC
            //両方→PDO::FETCH_BOTH（メモリの無駄）
            //print_r($record);
            $ouput[$index]['date']= $record["date"]." (".$record["week"].")";


            $index++;
        }

        //jsonとして出力
        header('Content-type: application/json');
        echo json_encode($ouput,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
    
    //当日にレースあった競馬場
    function racecourse($pdo,$date){
        $date = substr($date, 0, 5);
        $sql = "select DISTINCT racecourse from predict where DATE_FORMAT(time, '%m/%d') = '$date' and time > (now() - interval 1 year + interval 1 day) AND first IS NOT NULL";
        $dbh = $pdo->query($sql);

        $index = 0;

        while($record = $dbh->fetch(PDO::FETCH_ASSOC)){
            //インスタンスのみ→PDO::FETCH_NUM
            //連想配列のみ→PDO::FETCH_ASSOC
            //両方→PDO::FETCH_BOTH（メモリの無駄）
            //print_r($record);
            $ouput[$index]['racecourse']= $record["racecourse"];
            $index++;
        }

        //jsonとして出力
        header('Content-type: application/json');
        echo json_encode($ouput,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    //選択したレース
    function race($pdo,$date,$racecourse){
        $date = substr($date, 0, 5);
        $sql = "select race_id,racecourse,race_title,round,DATE_FORMAT(time, '%H:%i') as start,total_horse,pre_num,reliability,cre_dif,limen,pre_rank,odds,first,win from predict where racecourse = '$racecourse' and DATE_FORMAT(time, '%m/%d') = '$date' and time > (now() - interval 1 year + interval 1 day) and time < now() ";
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
            $ouput[$index]['reliability']= round($record["reliability"],4);
            $ouput[$index]['cre_dif']= round($record["cre_dif"],4);
            $ouput[$index]['limen']= round($record["limen"],4);
            $ouput[$index]['odds']= $record["odds"];

            if(empty($record["pre_rank"])){
                $ouput[$index]['pre_rank'] = " ";
            }
            else{
                $ouput[$index]['pre_rank'] = $record["pre_rank"];
            }
            if($record["pre_rank"] == "0"){
                $ouput[$index]['pre_rank'] = "中止";
            }

            if(empty($record["first"])){
                $ouput[$index]['first'] = " ";
            }
            else{
                $ouput[$index]['first']= $record["first"];
            }

            if(empty($record["win"])){
                $ouput[$index]['win'] = 0;
            }
            else{
                $ouput[$index]['win']= $record["win"];
            }


            //枠番計算
            $frame_number_pre = 0;
            $frame_number_first = 0;
            if($record["total_horse"]<= 8){
                $frame_number_pre = $record["pre_num"];
                $frame_number_first = $record["first"];
            }

            if($record["total_horse"] > 9 && $record["total_horse"]<= 16){
                if($record["pre_num"] <= 16 - $record["total_horse"]){
                    $frame_number_pre = $record["pre_num"];
                }
                else{
                    $frame_number_pre = 16 - $record["total_horse"] + ceil((($record["pre_num"] + $record["total_horse"] - 16) /($record["total_horse"] * 2 - 16)) * ($record["total_horse"] - 8));
                }
                if($record["first"] <= 16 - $record["total_horse"]){
                    $frame_number_first = $record["first"];
                }
                else{
                    $frame_number_first = 16 - $record["total_horse"] + ceil((($record["first"] + $record["total_horse"] - 16) /($record["total_horse"] * 2 - 16)) * ($record["total_horse"] - 8));
                }
            }

            if($record["total_horse"] > 16){
                if($record["total_horse"] == 17){
                    if($record["pre_num"] == 17){
                        $frame_number_pre = 8;
                    }
                    else{
                        $frame_number_pre = ceil($record["pre_num"]/2);
                    }
                    if($record["first"] == 17){
                        $frame_number_first = 8;
                    }
                    else{
                        $frame_number_first = ceil($record["first"]/2);
                    }
                }
                if($record["total_horse"] == 18){
                    if($record["pre_num"] == 18){
                        $frame_number_pre = 8;
                    }
                    else if($record["pre_num"] == 17){
                        $frame_number_pre = 8;
                    }
                    else if($record["pre_num"] == 16){
                        $frame_number_pre = 8;
                    }
                    else if($record["pre_num"] == 15){
                        $frame_number_pre = 7;
                    }
                    else{
                        $frame_number_pre = ceil($record["pre_num"]/2);
                    }

                    if($record["first"] == 18){
                        $frame_number_first = 8;
                    }
                    else if($record["first"] == 17){
                        $frame_number_first = 8;
                    }
                    else if($record["first"] == 16){
                        $frame_number_first = 8;
                    }
                    else if($record["first"] == 15){
                        $frame_number_first = 7;
                    }
                    else{
                        $frame_number_first = ceil($record["first"]/2);
                    }
                }

            }

            if($frame_number_first<1 || $frame_number_first >18){
                $frame_number_first = 1;
            }

            $ouput[$index]['frame_pre']= $frame_number_pre;
            $ouput[$index]['frame_first']= $frame_number_first;

            $index++;
        }

        //jsonとして出力
        header('Content-type: application/json');
        echo json_encode($ouput,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
    

    //予測レース表示
    function predict($pdo){
        $sql = "select race_id,racecourse,race_title,first,round,DATE_FORMAT(time, '%H:%i') as start,total_horse,pre_num,reliability,cre_dif,limen,odds from predict where time > now() ORDER BY time";
        //$sql = "select race_id,racecourse,race_title,first,round,DATE_FORMAT(time, '%H:%i') as start,total_horse,pre_num,reliability,cre_dif,limen,odds from predict limit 16";

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
            $ouput[$index]['reliability']= round($record["reliability"],4);
            $ouput[$index]['cre_dif']= round($record["cre_dif"],4);
            $ouput[$index]['limen']= round($record["limen"],4);
            $ouput[$index]['odds']= $record["odds"];
            $ouput[$index]['first']= $record["first"];

            


            //枠番計算
            $frame_number_pre = 0;
            $frame_number_first = 0;
            if($record["total_horse"]<= 8){
                $frame_number_pre = $record["pre_num"];
                $frame_number_first = $record["first"];
            }

            if($record["total_horse"] > 9 && $record["total_horse"]<= 16){
                if($record["pre_num"] <= 16 - $record["total_horse"]){
                    $frame_number_pre = $record["pre_num"];
                }
                else{
                    $frame_number_pre = 16 - $record["total_horse"] + ceil((($record["pre_num"] + $record["total_horse"] - 16) /($record["total_horse"] * 2 - 16)) * ($record["total_horse"] - 8));
                }
                if($record["first"] <= 16 - $record["total_horse"]){
                    $frame_number_first = $record["first"];
                }
                else{
                    $frame_number_first = 16 - $record["total_horse"] + ceil((($record["first"] + $record["total_horse"] - 16) /($record["total_horse"] * 2 - 16)) * ($record["total_horse"] - 8));
                }
            }

            if($record["total_horse"] > 16){
                if($record["total_horse"] == 17){
                    if($record["pre_num"] == 17){
                        $frame_number_pre = 8;
                    }
                    else{
                        $frame_number_pre = ceil($record["pre_num"]/2);
                    }
                    if($record["first"] == 17){
                        $frame_number_first = 8;
                    }
                    else{
                        $frame_number_first = ceil($record["first"]/2);
                    }
                }
                if($record["total_horse"] == 18){
                    if($record["pre_num"] == 18){
                        $frame_number_pre = 8;
                    }
                    else if($record["pre_num"] == 17){
                        $frame_number_pre = 8;
                    }
                    else if($record["pre_num"] == 16){
                        $frame_number_pre = 8;
                    }
                    else if($record["pre_num"] == 15){
                        $frame_number_pre = 7;
                    }
                    else{
                        $frame_number_pre = ceil($record["pre_num"]/2);
                    }

                    if($record["first"] == 18){
                        $frame_number_first = 8;
                    }
                    else if($record["first"] == 17){
                        $frame_number_first = 8;
                    }
                    else if($record["first"] == 16){
                        $frame_number_first = 8;
                    }
                    else if($record["first"] == 15){
                        $frame_number_first = 7;
                    }
                    else{
                        $frame_number_first = ceil($record["first"]/2);
                    }
                }

            }


            $ouput[$index]['frame_pre']= $frame_number_pre;
            $ouput[$index]['frame_first']= $frame_number_first;

            $index++;
        }

        //jsonとして出力
        header('Content-type: application/json');
        echo json_encode($ouput,JSON_UNESCAPED_UNICODE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
?>