
//Chart.js 外部から読み込み　使用
// GETリクエストでjavascriptファイルを読み込みコールバック関数で実行
$.getScript("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js", function(){
    // 読み込まれたjavscriptをいろいろ使う
  });
  
  // あるいは
  $.ajax({
    url: "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js",
    type: "GET",
    dataType: "script"
  }).done(function(data) {
  // 文字列をjavascriptとして実行。
    eval(data);
    // 読み込まれたjavscriptをいろいろ使う
      
    //DBからデータ取り出し
    win_rate();
    race_num();
    race_recovery();
    limen_recovery();
    limen_race_num();
    limen_win_rate();

});


//競馬場勝率
function win_rate() {
    $.ajax({
        url:'./connectDB.php', //送信先
        type:'POST', //送信方法
        datatype: 'json', //受け取りデータの種類
        data:{
            'mode': "graphic_data"
        }
    })
    // Ajax通信が成功した時
    .done( function(data) {
        
        //グラフ作成
        let chartArea = document.getElementById("chart_win_rate");
        //データを用意する

        let draw = { 
            labels: [],
            datasets: [{
                label:"勝率",
                data: []

            }]
        }

        var tmp = [];
        var i = 0;
        for (const [key, value] of Object.entries(data)) {
            draw['labels'].push(key);
            draw.datasets[0].data.push(value);



        }

        //表示処理
        var myChart = new Chart(chartArea, {
            type: 'bar',
            data: draw

        });
    })
    // Ajax通信が失敗した時
    .fail( function(data) {
        console.log('通信失敗');
        console.log(data);
    })
}

//競馬場レース数
function race_num() {
    $.ajax({
        url:'./connectDB.php', //送信先
        type:'POST', //送信方法
        datatype: 'json', //受け取りデータの種類
        data:{
            'mode': "race_num"
        }
    })
    // Ajax通信が成功した時
    .done( function(data) {
        
        //グラフ作成
        var chartArea = document.getElementById("chart_race_num");
        //データを用意する

        var draw = { 
            labels: [],
            datasets: [{
                label:"レース数",
                data: []
            }]
        }

        var tmp = [];
        for (const [key, value] of Object.entries(data)) {
            draw['labels'].push(key);
            draw.datasets[0].data.push(value);

        }

        //表示処理
        var myChart = new Chart(chartArea, {
            type: 'bar',
            data: draw
        });
    })
    // Ajax通信が失敗した時
    .fail( function(data) {
        console.log('通信失敗');
        console.log(data);
    })
}

//競馬場回収率
function race_recovery() {
    $.ajax({
        url:'./connectDB.php', //送信先
        type:'POST', //送信方法
        datatype: 'json', //受け取りデータの種類
        data:{
            'mode': "race_recovery"
        }
    })
    // Ajax通信が成功した時
    .done( function(data) {
        
        //グラフ作成
        var chartArea = document.getElementById("chart_race_recovery");
        //データを用意する

        var draw = { 
            labels: [],
            datasets: [{
                label:"回収率",
                data: [],
                backgroundColor: [] // 配列にしておく必要がある
            }]
        }

        var tmp = [];
        for (const [key, value] of Object.entries(data)) {
            draw['labels'].push(key);
            draw.datasets[0].data.push(value);

            //console.log(key);
            //console.log(value);
            //console.log(data);
            if (value >= 1) {
                draw.datasets[0].backgroundColor.push('#3F88C5')   // 値が正の場合は青
            } 
            else {
                draw.datasets[0].backgroundColor.push('#FF5E5B')   // 値が負の場合は赤
            }

        }

        //表示処理
        var myChart = new Chart(chartArea, {
            type: 'bar',
            data: draw
        });
    })
    // Ajax通信が失敗した時
    .fail( function(data) {
        console.log('通信失敗');
        console.log(data);
    })
}

//閾値勝率
function limen_win_rate() {
    $.ajax({
        url:'./connectDB.php', //送信先
        type:'POST', //送信方法
        datatype: 'json', //受け取りデータの種類
        data:{
            'mode': "limen_win_rate"
        }
    })
    // Ajax通信が成功した時
    .done( function(data) {
        
        //グラフ作成
        var chartArea = document.getElementById("chart_limen_win_rate");
        //データを用意する

        var draw = { 
            labels: [],
            datasets: [{
                label:"勝率",
                data: []
            }]
        }

        var tmp = [];
        for (const [key, value] of Object.entries(data)) {
            draw['labels'].push(key);
            draw.datasets[0].data.push(value);

            //console.log(key);
            //console.log(value);
            //console.log(data);
            //console.log('IN');

        }

        //表示処理
        var myChart = new Chart(chartArea, {
            type: 'bar',
            data: draw
        });
    })
    // Ajax通信が失敗した時
    .fail( function(data) {
        console.log('通信失敗');
        console.log(data);
    })
}


//閾値レース数
function limen_race_num() {
    $.ajax({
        url:'./connectDB.php', //送信先
        type:'POST', //送信方法
        datatype: 'json', //受け取りデータの種類
        data:{
            'mode': "limen_race_num"
        }
    })
    // Ajax通信が成功した時
    .done( function(data) {
        
        //グラフ作成
        var chartArea = document.getElementById("chart_limen_race_num");
        //データを用意する

        var draw = { 
            labels: [],
            datasets: [{
                label:"レース数",
                data: []
            }]
        }

        var tmp = [];
        for (const [key, value] of Object.entries(data)) {
            draw['labels'].push(key);
            draw.datasets[0].data.push(value);

            //console.log(key);
            //console.log(value);
            //console.log(data);
            //console.log('IN');

        }

        //表示処理
        var myChart = new Chart(chartArea, {
            type: 'bar',
            data: draw
        });
    })
    // Ajax通信が失敗した時
    .fail( function(data) {
        console.log('通信失敗');
        console.log(data);
    })
}




//閾値回収率
function limen_recovery() {
    $.ajax({
        url:'./connectDB.php', //送信先
        type:'POST', //送信方法
        datatype: 'json', //受け取りデータの種類
        data:{
            'mode': "limen_recovery"
        }
    })
    // Ajax通信が成功した時
    .done( function(data) {
        
        //グラフ作成
        var chartArea = document.getElementById("chart_limen_recovery");
        //データを用意する

        var draw = { 
            labels: [],
            datasets: [{
                label:"回収率",
                data: [],
                backgroundColor:[]
            }]
        }

        var tmp = [];
        for (const [key, value] of Object.entries(data)) {
            draw['labels'].push(key);
            draw.datasets[0].data.push(value);

            //console.log(key);
            //console.log(value);
            //console.log(data);
            if (value >= 1) {
                draw.datasets[0].backgroundColor.push('#3F88C5')   // 値が正の場合は青
            } 
            else {
                draw.datasets[0].backgroundColor.push('#FF5E5B')   // 値が負の場合は赤
            }

        }

        //表示処理
        var myChart = new Chart(chartArea, {
            type: 'bar',
            data: draw
        });
    })
    // Ajax通信が失敗した時
    .fail( function(data) {
        console.log('通信失敗');
        console.log(data);
    })
}