<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>競馬予想</title>

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

    <script src="./js/graphic.js"></script>
    <link rel="stylesheet" href="./css/bootstrap-reboot.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include('header.html')?>

    <div class = "main_content">
        <h1 style="text-align: center;">直近1年間レース　(閾値0.8以上のみ)</h1>
        <div class="title-bar">
            <h2>競馬場勝率</h2>
        </div>
        <canvas id="chart_win_rate"></canvas>

        <div class="title-bar">
            <h2>競馬場レース数</h2>
        </div>
        <canvas id="chart_race_num"></canvas>

        <div class="title-bar">
            <h2>競馬場回収率</h2>
        </div>
        <canvas id="chart_race_recovery"></canvas>


        <div class="title-bar">
            <h2>閾値勝率</h2>
        </div>
        <canvas id="chart_limen_win_rate"></canvas>

        <div class="title-bar">
            <h2>閾値レース数</h2>
        </div>
        <canvas id="chart_limen_race_num"></canvas>

        <div class="title-bar">
            <h2>閾値回収率</h2>
        </div>
        <canvas id="chart_limen_recovery"></canvas>

        

    </div>
</body>
</html>
