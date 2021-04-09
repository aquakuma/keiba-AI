<?php

include('./db_connect.php');

function get_json( $type = null,$place){

    $appid = "b5459d99eb1e2dca609054ff7ec496ae";
    
    $racecourse = [];
    $racecourse = [
        '札幌'=>[43.07640544703823, 141.32394378498122],
        '函館'=>[41.78243538688644, 140.77488854633023],
        '福島'=>[37.76546565669543, 140.48014298484995],
        '新潟'=>[37.94754256607698, 139.18699165601805],
        '東京'=>[35.6626158986008, 139.48577619643837],
        '中山'=>[35.725496918632935, 139.9631534001491],
        '中京'=>[35.066927633098516, 136.99201440198917],
        '京都'=>[34.90700024961088, 135.72717395409438],
        '阪神'=>[34.78042267028736, 135.3630143289646],
        '小倉'=>[33.84288750136832, 130.87284802708987]
    ];
  
    $url = "http://api.openweathermap.org/data/2.5/weather?lat=".$racecourse[$place][0]."&lon=".$racecourse[$place][1] . "&units=metric&APPID=" . $appid;

    $json = file_get_contents( $url );
    //$json = mb_convert_encoding( $json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN' );
    $json_decode = json_decode( $json );
  

    //id取得
    $id = $json_decode->weather[0]->id;

    //現在の天気
    if( $type  === "weather" ):
      //$out = $json_decode->weather[0]->main;
      $out = _ryusWheatherDescription($id);
  
    //現在の天気アイコン
    elseif( $type === "icon" ):
      //$out = "<img src='https://openweathermap.org/img/wn/" . $json_decode->weather[0]->icon . "@2x.png'>";
      $out = "https://openweathermap.org/img/wn/" . $json_decode->weather[0]->icon . "@2x.png";
  
    //現在の気温
    elseif( $type  === "temp" ):
      $out = $json_decode->main->temp;
  
    //現在の気温ID
    elseif( $type  === "id" ):
        $out = $json_decode->weather[0]->id;

    //パラメータがないときは配列を出力
    else:
      $out = $json_decode;
  
    endif;
  
    return $out;
}


function _ryusWheatherDescription($id){
    $description = array();
    $description[200] = '小雨と雷雨';
    $description[201] = '雨と雷雨';
    $description[202] = '大雨と雷雨';
    $description[210] = '光雷雨';
    $description[211] = '雷雨';
    $description[212] = '重い雷雨';
    $description[221] = 'ぼろぼろの雷雨';
    $description[230] = '小雨と雷雨';
    $description[231] = '霧雨と雷雨';
    $description[232] = '重い霧雨と雷雨';
    $description[300] = '光強度霧雨';
    $description[301] = '霧雨';
    $description[302] = '重い強度霧雨';
    $description[310] = '光強度霧雨の雨';
    $description[311] = '霧雨の雨';
    $description[312] = '重い強度霧雨の雨';
    $description[313] = 'にわかの雨と霧雨';
    $description[314] = '重いにわかの雨と霧雨';
    $description[321] = 'にわか霧雨';
    $description[500] = '小雨';
    $description[501] = '適度な雨';
    $description[502] = '重い強度の雨';
    $description[503] = '非常に激しい雨';
    $description[504] = '極端な雨';
    $description[511] = '雨氷';
    $description[520] = '光強度のにわかの雨';
    $description[521] = 'にわかの雨';
    $description[522] = '重い強度にわかの雨';
    $description[531] = '不規則なにわかの雨';
    $description[600] = '小雪';
    $description[601] = '雪';
    $description[602] = '大雪';
    $description[611] = 'みぞれ';
    $description[612] = 'にわかみぞれ';
    $description[615] = '光雨と雪';
    $description[616] = '雨や雪';
    $description[620] = '光のにわか雪';
    $description[621] = 'にわか雪';
    $description[622] = '重いにわか雪';
    $description[701] = '靄';
    $description[711] = '煙';
    $description[721] = 'ヘイズ';
    $description[731] = '砂、ほこり旋回する';
    $description[741] = '霧';
    $description[751] = '砂';
    $description[761] = 'ほこり';
    $description[762] = '火山灰';
    $description[771] = 'スコール';
    $description[781] = '竜巻';
    $description[800] = '晴天';
    $description[801] = '薄い雲';
    $description[802] = '曇';
    $description[803] = '曇りがち';
    $description[804] = '厚い雲';
    if (array_key_exists($id, $description) === false) {
        return $id;
    }
    return $description[$id];
}

function select_racecourse(){
    $pdo = db_connect();

    //SQL文作成
    $sql = "SELECT DATE_FORMAT(time,'%Y-%m-%d') AS date FROM predict ORDER BY time DESC LIMIT 1";
    //プリペアードステートメントの設定と取得
    $prestmt = $pdo->prepare($sql);
    //SQL実行 
    $prestmt->execute();
    //抽出結果取得
    $date = $prestmt->fetch(PDO::FETCH_ASSOC);

    $date = $date['date'];
    $d = $date;
    //SQL文作成
    $sql = "SELECT DISTINCT racecourse FROM predict WHERE DATE_FORMAT(time,'%Y-%m-%d') = '$date';";
    //プリペアードステートメントの設定と取得
    $prestmt = $pdo->prepare($sql);
    //SQL実行 
    $prestmt->execute();
    //抽出結果取得
    $racecourse = $prestmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($racecourse as $key => $row){
        //echo "<br>key = ".$key."<br>value = ".$row['racecourse'];
        $racecourse[$key]['icon'] = get_json('icon',$row['racecourse']);
        $racecourse[$key]['weather'] = get_json('weather',$row['racecourse']);
        $racecourse[$key]['temp'] = get_json('temp',$row['racecourse']);
    }

    return $racecourse;
}
$racecourse = select_racecourse();
$racecourse_json = json_encode($racecourse);
/*
echo('<pre>');
var_dump(select_racecourse());
echo('</pre>');
*/
?>

<script>

    function init(){
        var racecourse = JSON.parse('<?php echo $racecourse_json ?>');


        var table = document.getElementById('weather');
        while (table.querySelector('tr')) {
            table.querySelector('tr').remove();
        }

        for(var row in racecourse){
            var tr = document.createElement('tr');

            var td = document.createElement('td');
            td.textContent = racecourse[row]['racecourse'] + "競馬場";
            tr.appendChild(td);

            var td = document.createElement('td');
            td.textContent = racecourse[row]['temp']+"℃";
            tr.appendChild(td);

            table.appendChild(tr);

            var tr = document.createElement('tr');

            var img = document.createElement('img');
            var td = document.createElement('td');
            img.setAttribute('src', racecourse[row]['icon']); 
            td.appendChild(img);
            tr.appendChild(td);




            var td = document.createElement('td');
            td.textContent = racecourse[row]['weather'];
            tr.appendChild(td);

            table.appendChild(tr);
        }

    }
    
    window.addEventListener('load', init);
</script>


