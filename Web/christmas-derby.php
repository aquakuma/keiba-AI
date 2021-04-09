<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>クリスマスダービー</title>
    <link rel="stylesheet" href="./css/game.css">
    <link rel="icon" href="./img/icon.png" type="image/vnd.microsoft.icon"/>
	<script>

        var bg_time;
        var tree_shiny;
        var goalin;

        let no1 = new Object();
        no1.name = 'ディープインパクト';
        no1.num = 1;
        no1.imgw = 'img/santa1_w.png';
        no1.imgr = 'img/santa1_r.png';
        no1.zindex = '10';
        no1.btm = '95px'
        let no2 = new Object();
        no2.name = 'アーモンドアイ';
        no2.num = 2;
        no2.imgw = 'img/santa2_w.png';
        no2.imgr = 'img/santa2_r.png';
        no2.zindex = '11';
        no2.btm = '80px'
        let no3 = new Object();
        no3.name = 'サイレンスズカ';
        no3.num = 3;
        no3.imgw = 'img/santa3_w.png';
        no3.imgr = 'img/santa3_r.png';
        no3.zindex = '12';
        no3.btm = '75px'
        let no4 = new Object();
        no4.name = 'スペシャルウィーク';
        no4.num = 4;
        no4.imgw = 'img/santa4_w.png';
        no4.imgr = 'img/santa4_r.png';
        no4.zindex = '13';
        no4.btm = '60px'
        let no5 = new Object();
        no5.name = 'オグリキャップ';
        no5.num = 5;
        no5.imgw = 'img/santa5_w.png';
        no5.imgr = 'img/santa5_r.png';
        no5.zindex = '14';
        no5.btm = '45px'
        let no6 = new Object();
        no6.name = 'トウカイテイオー';
        no6.num = 6;
        no6.imgw = 'img/santa6_w.png';
        no6.imgr = 'img/santa6_r.png';
        no6.zindex = '15';
        no6.btm = '30px'
        let no7 = new Object();
        no7.name = 'メジロマックイーン';
        no7.num = 7;
        no7.imgw = 'img/santa7_w.png';
        no7.imgr = 'img/santa7_r.png';
        no7.zindex = '16';
        no7.btm = '15px'
        let no8 = new Object();
        no8.name = 'ハルウララ';
        no8.num = 8;
        no8.imgw = 'img/santa8_w.png';
        no8.imgr = 'img/santa8_r.png';
        no8.zindex = '17';
        no8.btm = '0px'


        let bat = new Object();
        bat.all_bat = 0;
        bat.all_prize = 0;
        bat.tansyo_money = 0;
        bat.fukusyo_money = 0;
        bat.sanrentan_money = 0;
        bat.prize = 0;
        bat.recovery = 0;

        let rider = [no1,no2,no3,no4,no5,no6,no7,no8];

        var first = 0;
        var second = 0;
        var third = 0;
        var first_odds = 0;
        var second_odds = 0;
        var third_odds = 0;

        rider.forEach(function(santa){
            //オッズ生成
            if(santa.num != 8){
                santa.odds = (Math.floor( Math.random() * 150 ))/10 +1;
            }
            else{
                santa.odds = (Math.floor( Math.random() * 150 ))/10 +101;
            }
        });



        function start(){
            var time_x = 0;
            var time_per = 0;
            var rank = 1;
            var bg = document.getElementById( "bg" );
            bg.style.objectPosition = `0%`; 
            no1.img = document.getElementById( "no1" );
            no2.img = document.getElementById( "no2" );
            no3.img = document.getElementById( "no3" );
            no4.img = document.getElementById( "no4" );
            no5.img = document.getElementById( "no5" );
            no6.img = document.getElementById( "no6" );
            no7.img = document.getElementById( "no7" );
            no8.img = document.getElementById( "no8" );
            var tree = document.getElementById( "tree0" );
            var goal_flag = 0;

            //レース開始　馬券受付終了
            var buy_table = document.getElementsByName( "buy_table");
            buy_table.forEach(function(item){
                item.style.display = 'none';
            });
            
            //賭け金フラグ初期化
            bat.tansyo_flag=0;
            bat.fukusyo_flag = 0;
            bat.prize = 0;
            


            var all_bat = document.getElementById( "all_bat");
            bat.all_bat += Number(bat.tansyo_money) + Number(bat.fukusyo_money) + Number(bat.sanrentan_money);
            all_bat.textContent = '本日賭け金:'+ bat.all_bat;


            var all_bat = document.getElementById( "race_bat");
            all_bat.textContent = 'レース賭け金:'+ Number(bat.tansyo_money + bat.fukusyo_money + bat.sanrentan_money);

            var race_prize = document.getElementById( "race_prize");
            race_prize.textContent = 'レース払戻金:'+ Number(bat.prize);

            bat.recovery = bat.all_prize/bat.all_bat;
            var recovery = document.getElementById( "recovery");
            recovery.textContent = `回収率:${Math.round(bat.recovery*100)}%`;

            console.log('bat:'+bat.tansyo);
            rider.forEach(function(santa){
                santa.rank = 0;
            });
            //console.log(no1.img);
            //no1.img.style.left = `200px`;


                bg_time = setInterval(function(){
                //背景流し
                bg.style.objectPosition = `${time_per}%`;
                time_per +=0.1;
                time_x++;



                if(time_x %5 == 0 && time_per <=90){

                    //垂直移動 571 701
                    rider.forEach(function(santa){
                        var random = Math.floor( Math.random() * 20 ) - 10;
                        santa.bottom = parseInt(window.getComputedStyle(santa.img, null).getPropertyValue('bottom')) + random;
                        //console.log(no1.img.style.bottom);
                        
                        if(santa.bottom >130){
                            santa.bottom = 130;
                        }
                        if(santa.bottom <0){
                            santa.bottom= 0;
                        }

                        santa.img.style.zIndex = `${200 - santa.bottom}`;
                        santa.img.style.bottom = `${santa.bottom}px`;

                        
                    });

                    //console.log(Number(no1_bottom));
                    //////////


                    //水平移動 224 1024
                    rider.forEach(function(santa){
                        var random = Math.floor( Math.random() * 20 ) - 10;
                        if(santa.num != 8){
                            santa.left = parseInt(window.getComputedStyle(santa.img, null).getPropertyValue('left')) + random + (15 - santa.odds)*0.3;
                        }
                        else{
                            santa.left = parseInt(window.getComputedStyle(santa.img, null).getPropertyValue('left')) + random;
                        }
                        //console.log(window.getComputedStyle(santa.img, null).getPropertyValue('left'));
                        
                        if(santa.left >800){
                            santa.left = 800;
                        }
                        if(santa.left <0){
                            santa.left= 0;
                        }

                        santa.mile += santa.left;
                        santa.img.style.left = `${santa.left}px`;
                    });


                    //console.log(no1_left);
                    //////////
                }

                
                
                //ゴール処理
                if(time_per > 100 && goal_flag == 0){
                    goal_flag = 1;
                    rider.forEach(function(santa){
                        santa.goalin = setInterval(function(){                        
                            //ライダー
                            santa.left = parseInt(window.getComputedStyle(santa.img, null).getPropertyValue('left')) + 5;
                            if(santa.left >800){
                                santa.img.style.display = 'none';
                                santa.rank = rank;
                                rank++;
                                console.log(santa.num+":"+santa.rank);

                                //結果表示
                                var table = document.getElementById( "result" );
                                var row = document.createElement("tr");
                                var t_rank = document.createElement("td");
                                t_rank.textContent = santa.rank;
                                row.appendChild(t_rank);
                                var t_num = document.createElement("td");
                                t_num.textContent = santa.num;
                                row.appendChild(t_num);
                                var t_name = document.createElement("td");
                                t_name.textContent = santa.name;
                                row.appendChild(t_name);
                                var t_odds = document.createElement("td");
                                t_odds.textContent = santa.odds;
                                row.appendChild(t_odds);

                                table.appendChild(row);

                                ///////////
                                clearInterval(santa.goalin);
                                if(rank == 9){
                                    result();
                                }

                                /*
                                //払戻し処理

                                //単勝
                                if(rank == 2){
                                    if(Number(bat.tansyo)　== Number(santa.num)){
                                        bat.prize = santa.odds*100;
                                    }
                                    else{
                                        bat.prize = -100;
                                    }
                                }
                                */
                            }

                            santa.img.style.left = `${santa.left}px`;
                            if(tree_cnt %3 == 0){

                                //走る演出
                                var img_name = santa.img.getAttribute('src');
                                if(img_name == santa.imgw){
                                    santa.img.src = santa.imgr;                                   
                                }
                                else{
                                    santa.img.src = santa.imgw;                                  
                                }
                                /////////
                            }                       
                        },20);
                    });

                    //console.log('in');
                }

                if(time_per >90 && time_per <100){

                    //ツリー処理
                    tree.style.display = 'block';
                    var tree_left = parseInt(window.getComputedStyle(tree, null).getPropertyValue('left')) - 10;
                    if(tree_left < 0 ){
                        tree_left = 0;
                    }
                    tree.style.left = `${tree_left}px`;

                }

                /////////////
                
                if(tree_cnt %3 == 0){
                    //走る演出

                    rider.forEach(function(santa){
                        var img_name = santa.img.getAttribute('src');
                        if(img_name == santa.imgw){
                            santa.img.src = santa.imgr;
                                    
                        }
                        else{
                            santa.img.src = santa.imgw;                           
                        }
                    });

                    /////////
                }


                if(time_per >100){
                    clearInterval(bg_time);
                }
            }, 20);
            var tree_cnt = 0;
            tree_shiny = setInterval(function(){
                //ツリー処理
                tree_cnt++;
                if(tree_cnt % 24 < 6){
                    tree.src = 'img/tree1.png';
                }
                else if(tree_cnt % 24 < 12 && tree_cnt % 24 >= 6){
                    tree.src = 'img/tree4.png';
                }
                else if(tree_cnt % 24 < 18 && tree_cnt % 24 >= 12){
                    tree.src = 'img/tree3.png';
                }
                else{
                    tree.src = 'img/tree2.png';
                }
            },20)

        }

        function stop(bg_time,tree_shiny){
            clearInterval(bg_time);
            clearInterval(tree_shiny);

            
            var bg = document.getElementById( "bg" );
            bg.style.objectPosition = `0%`;

            var tree = document.getElementById( "tree0" );
            tree.style.display = 'none';
            tree.style.left = '950px';

            no1.img = document.getElementById( "no1" );
            no2.img = document.getElementById( "no2" );
            no3.img = document.getElementById( "no3" );
            no4.img = document.getElementById( "no4" );
            no5.img = document.getElementById( "no5" );
            no6.img = document.getElementById( "no6" );
            no7.img = document.getElementById( "no7" );
            no8.img = document.getElementById( "no8" );


            //馬券受付
            var buy_table = document.getElementsByName( "buy_table");
            buy_table.forEach(function(item){
                item.style.display = 'block';
            });      
            
            var table = document.getElementById('syutuba');
            while (table.querySelector('tr')) {
                table.querySelector('tr').remove();
            }
            var table = document.getElementById('tansyo');
            while (table.querySelector('tr')) {
                table.querySelector('tr').remove();
            }

            var table = document.getElementById('fukusyo');
            while (table.querySelector('tr')) {
                table.querySelector('tr').remove();
            }

            var table = document.getElementById('sanrentan');
            while (table.querySelector('tr')) {
                table.querySelector('tr').remove();
            }

            rider.forEach(function(santa){
                clearInterval(santa.goalin);
                santa.img.style.display = 'block';
                santa.img.style.left = `0px`;
                santa.img.style.bottom = santa.btm;
                santa.img.style.zIndex = santa.zindex;
                //オッズ生成
                if(santa.num != 8){
                    santa.odds = (Math.floor( Math.random() * 150 ))/10 +1;
                }
                else{
                    santa.odds = (Math.floor( Math.random() * 150 ))/10 +101;
                }

                
                //出馬表
                var syutuba_t = document.getElementById( "syutuba");
                var row = document.createElement("tr");
                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);
                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);
                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                
                row.appendChild(t_odds);

                syutuba_t.appendChild(row);

                //単勝
                var syutuba_t = document.getElementById( "tansyo");
                var row = document.createElement("tr");

                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);

                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);

                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                row.appendChild(t_odds);

                var tansyo_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'tansyo';
                radio_btn.value = santa.num;

                tansyo_td.appendChild(radio_btn);
                row.appendChild(tansyo_td);

                syutuba_t.appendChild(row);

                //複勝
                var syutuba_t = document.getElementById( "fukusyo");
                var row = document.createElement("tr");

                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);

                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);

                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                row.appendChild(t_odds);

                var tansyo_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'fukusyo';
                radio_btn.value = santa.num;

                tansyo_td.appendChild(radio_btn);
                row.appendChild(tansyo_td);

                syutuba_t.appendChild(row);


                //三連単
                var syutuba_t = document.getElementById( "sanrentan");
                var row = document.createElement("tr");

                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);

                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);

                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                row.appendChild(t_odds);

                var first_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'first';
                radio_btn.value = santa.num;
                first_td.appendChild(radio_btn);
                row.appendChild(first_td);

                var second_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'second';
                radio_btn.value = santa.num;
                second_td.appendChild(radio_btn);
                row.appendChild(second_td);

                var third_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'third';
                radio_btn.value = santa.num;
                third_td.appendChild(radio_btn);
                row.appendChild(third_td);

                syutuba_t.appendChild(row);
                
            });

            var table = document.getElementById('result');
        
            while (table.querySelector('tr')) {
                table.querySelector('tr').remove();
            }

        }

        function new_race(bg_time,tree_shiny){
            clearInterval(bg_time);
            clearInterval(tree_shiny);
            rider.forEach(function(santa){
                santa.img.style.display = 'block';
                santa.img.style.left = `0px`;
                santa.img.style.bottom = santa.btm;
                santa.img.style.zIndex = santa.zindex;
            });

            
            var bg = document.getElementById( "bg" );
            bg.style.objectPosition = `0%`;

            var tree = document.getElementById( "tree0" );
            tree.style.display = 'none';
            tree.style.left = '950px';

            no1.img = document.getElementById( "no1" );
            no2.img = document.getElementById( "no2" );
            no3.img = document.getElementById( "no3" );
            no4.img = document.getElementById( "no4" );
            no5.img = document.getElementById( "no5" );
            no6.img = document.getElementById( "no6" );
            no7.img = document.getElementById( "no7" );
            no8.img = document.getElementById( "no8" );

            

            var table = document.getElementById('result');
        
            while (table.querySelector('tr')) {
                table.querySelector('tr').remove();
            }
        }

        function result(){
            var btn = document.getElementById( "btn1" );
            btn.textContent = '次のレース';

            //馬券受付
            var buy_table = document.getElementsByName( "buy_table");
            buy_table.forEach(function(item){
                item.style.display = 'block';
            });


            var table = document.getElementById('syutuba');
            while (table.querySelector('tr')) {
                table.querySelector('tr').remove();
            }
            var table = document.getElementById('tansyo');
            while (table.querySelector('tr')) {
                table.querySelector('tr').remove();
            }

            var table = document.getElementById('fukusyo');
            while (table.querySelector('tr')) {
                table.querySelector('tr').remove();
            }

            var table = document.getElementById('sanrentan');
            while (table.querySelector('tr')) {
                table.querySelector('tr').remove();
            }

            console.log('debug:'+bat.prize);
            rider.forEach(function(santa){


                //払戻し処理

                //単勝
                if(bat.tansyo_flag != 1){
                    if(santa.rank==1 && santa.num == bat.tansyo){
                        bat.prize += santa.odds*bat.tansyo_money;
                        bat.tansyo_flag = 1;
                    }
                    else{
                        bat.prize += 0;
                    }
                }

                //複勝
                if(bat.fukusyo_flag != 1){
                    if((santa.rank==1 || santa.rank==2 || santa.rank==3) && santa.num == bat.fukusyo){
                        tmp_odds = Math.round(santa.odds*0.7 * 10) / 10;
                        if(tmp_odds < 1){
                            tmp_odds = 1;
                        }
                        bat.prize += tmp_odds*bat.fukusyo_money;
                        bat.fukusyo_flag = 1;
                        console.log('fuku:'+tmp_odds);
                    }
                    else{
                        bat.prize += 0;
                    }
                }


                //三連単
                if(santa.rank == 1){
                    first = santa.num;
                    first_odds = santa.odds;
                }
                if(santa.rank == 2){
                    second = santa.num;
                    second_odds = santa.odds;
                }
                if(santa.rank == 3){
                    third = santa.num;
                    third_odds = santa.odds;
                }





                clearInterval(santa.goalin);

                //オッズ生成
                if(santa.num != 8){
                    santa.odds = (Math.floor( Math.random() * 150 ))/10 +1;
                }
                else{
                    santa.odds = (Math.floor( Math.random() * 150 ))/10 +101;
                }

                
                //出馬表
                var syutuba_t = document.getElementById( "syutuba");
                var row = document.createElement("tr");
                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);
                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);
                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                
                row.appendChild(t_odds);

                syutuba_t.appendChild(row);

                //単勝
                var syutuba_t = document.getElementById( "tansyo");
                var row = document.createElement("tr");

                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);

                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);

                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                row.appendChild(t_odds);

                var tansyo_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'tansyo';
                radio_btn.value = santa.num;

                tansyo_td.appendChild(radio_btn);
                row.appendChild(tansyo_td);

                syutuba_t.appendChild(row);

                //複勝
                var syutuba_t = document.getElementById( "fukusyo");
                var row = document.createElement("tr");

                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);

                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);

                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                row.appendChild(t_odds);

                var tansyo_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'fukusyo';
                radio_btn.value = santa.num;

                tansyo_td.appendChild(radio_btn);
                row.appendChild(tansyo_td);

                syutuba_t.appendChild(row);


                //三連単
                var syutuba_t = document.getElementById( "sanrentan");
                var row = document.createElement("tr");

                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);

                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);

                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                row.appendChild(t_odds);

                var first_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'first';
                radio_btn.value = santa.num;
                first_td.appendChild(radio_btn);
                row.appendChild(first_td);

                var second_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'second';
                radio_btn.value = santa.num;
                second_td.appendChild(radio_btn);
                row.appendChild(second_td);

                var third_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'third';
                radio_btn.value = santa.num;
                third_td.appendChild(radio_btn);
                row.appendChild(third_td);

                syutuba_t.appendChild(row);

                
            });
            console.log(bat.prize);
            console.log('flag:'+bat.tansyo_flag);

            //三連単
            if(bat.first == first){
                if(bat.second == second){
                    if(bat.third == third){
                        bat.prize += Math.floor(first_odds*second_odds*third_odds*bat.sanrentan_money);
                    }
                }
            }

            console.log('first:'+first+'  second:'+second+'  third:'+third);

            //払戻し
            var all_prize = document.getElementById( "all_prize");
            bat.all_prize += Number(bat.prize);
            all_prize.textContent = '本日払戻金:'+ bat.all_prize;

            var race_prize = document.getElementById( "race_prize");
            race_prize.textContent = 'レース払戻金:'+ Number(bat.prize);

            bat.recovery = bat.all_prize/bat.all_bat;
            var recovery = document.getElementById( "recovery");
            recovery.textContent = `回収率:${Math.round(bat.recovery*100)}%`;

            //賭け金初期化
            bat.tansyo_money = 0;
            bat.fukusyo_money = 0;
        }

        function btn(){
            var btn = document.getElementById( "btn1" );
            console.log(btn.textContent);
            if(btn.textContent == 'レース開始'){
                btn.textContent = 'レース中止';
                start();
                return;
            }
            if(btn.textContent == 'レース中止'){
                stop(bg_time,tree_shiny);
                btn.textContent = 'レース開始';
                return;
            }

            if(btn.textContent == '次のレース'){
                new_race(bg_time,tree_shiny);
                start();
                btn.textContent = 'レース中止';
                return;
            }
        }


        //単勝
        function bat_tansyo(){

            
            bat.tansyo = document.getElementById('tansyo_form').tansyo.value;
            bat.tansyo_money = document.getElementById('tansyo_form').bat_money.value *100;
            var tansyo_num = document.getElementById( "tansyo_bat");
            tansyo_num.textContent = '枠番：' + bat.tansyo;
            var tansyo_money = document.getElementById( "tansyo_bat_money");
            tansyo_money.textContent = '賭け金：' + bat.tansyo_money;

        }
        //複勝
        function bat_fukusyo(){
            
            bat.fukusyo = document.getElementById('fukusyo_form').fukusyo.value;
            bat.fukusyo_money = document.getElementById('fukusyo_form').bat_money.value *100;
            var fukusyo_num = document.getElementById( "fukusyo_bat");
            fukusyo_num.textContent = '枠番：' + bat.fukusyo;
            var fukusyo_money = document.getElementById( "fukusyo_bat_money");
            fukusyo_money.textContent = '賭け金：' + bat.fukusyo_money;
        }
        //三連単
        function bat_sanrentan(){
            
            bat.first = document.getElementById('sanrentan_form').first.value;
            bat.second = document.getElementById('sanrentan_form').second.value;
            bat.third = document.getElementById('sanrentan_form').third.value;
            bat.sanrentan_money = document.getElementById('sanrentan_form').bat_money.value *100;
            var sanrentan_num = document.getElementById( "sanrentan_bat");
            sanrentan_num.textContent = `枠番：${bat.first}->${bat.second}->${bat.third}`;
            var sanrentan_money = document.getElementById( "sanrentan_bat_money");
            sanrentan_money.textContent = '賭け金：' + bat.sanrentan_money;
        }



        function reset_tansyo(){
            bat.tansyo = 0;
            bat.tansyo_money = 0;

            var tansyo_num = document.getElementById( "tansyo_bat");
            tansyo_num.textContent = '枠番：' + bat.tansyo;
            var tansyo_money = document.getElementById( "tansyo_bat_money");
            tansyo_money.textContent = '賭け金：' + bat.tansyo_money;
        }
        function reset_fukusyo(){
            bat.fukusyo = 0;
            bat.fukusyo_money = 0;

            var fukusyo_num = document.getElementById( "fukusyo_bat");
            fukusyo_num.textContent = '枠番：' + bat.fukusyo;
            var fukusyo_money = document.getElementById( "fukusyo_bat_money");
            fukusyo_money.textContent = '賭け金：' + bat.fukusyo_money;
        }
        function reset_sanrentan(){
            bat.sanrentan = 0;
            bat.sanrentan_money = 0;

            var sanrentan_num = document.getElementById( "sanrentan_bat");
            sanrentan_num.textContent = '枠番：' + bat.sanrentan;
            var sanrentan_money = document.getElementById( "sanrentan_bat_money");
            sanrentan_money.textContent = '賭け金：' + bat.sanrentan_money;
        }


        function init(){
            
            rider.forEach(function(santa){
                //出馬表
                var syutuba_t = document.getElementById( "syutuba");
                var row = document.createElement("tr");
                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);
                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);
                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                
                row.appendChild(t_odds);

                syutuba_t.appendChild(row);

                //単勝
                var syutuba_t = document.getElementById( "tansyo");
                var row = document.createElement("tr");

                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);

                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);

                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                row.appendChild(t_odds);

                var tansyo_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'tansyo';
                radio_btn.value = santa.num;

                tansyo_td.appendChild(radio_btn);
                row.appendChild(tansyo_td);

                syutuba_t.appendChild(row);
                
                //複勝
                var syutuba_t = document.getElementById( "fukusyo");
                var row = document.createElement("tr");

                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);

                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);

                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                row.appendChild(t_odds);

                var tansyo_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'fukusyo';
                radio_btn.value = santa.num;

                tansyo_td.appendChild(radio_btn);
                row.appendChild(tansyo_td);

                syutuba_t.appendChild(row);

                //三連単
                var syutuba_t = document.getElementById( "sanrentan");
                var row = document.createElement("tr");

                var t_num = document.createElement("td");
                t_num.textContent = santa.num;
                row.appendChild(t_num);

                var t_name = document.createElement("td");
                t_name.textContent = santa.name;
                row.appendChild(t_name);

                var t_odds = document.createElement("td");
                t_odds.textContent = santa.odds;
                row.appendChild(t_odds);

                var first_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'first';
                radio_btn.value = santa.num;
                first_td.appendChild(radio_btn);
                row.appendChild(first_td);

                var second_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'second';
                radio_btn.value = santa.num;
                second_td.appendChild(radio_btn);
                row.appendChild(second_td);

                var third_td = document.createElement("td");
                var radio_btn = document.createElement("input");
                radio_btn.type ='radio';
                radio_btn.name = 'third';
                radio_btn.value = santa.num;
                third_td.appendChild(radio_btn);
                row.appendChild(third_td);

                syutuba_t.appendChild(row);
            });



            var recovery = document.getElementById( "recovery");
            recovery.textContent = `回収率:${Math.round(bat.recovery*100)}%`;

            var all_bat = document.getElementById( "all_bat");
            all_bat.textContent = '本日賭け金:0';

            var all_prize = document.getElementById( "all_prize");
            all_prize.textContent = '本日払戻金:0';

            var all_bat = document.getElementById( "race_bat");
            all_bat.textContent = 'レース賭け金:0';

            var all_prize = document.getElementById( "race_prize");
            all_prize.textContent = 'レース払戻金:0';
        }

        window.addEventListener('load', init);
    </script>
    
    <link rel="stylesheet" href="./css/bootstrap-reboot.css">
</head>

<body>



<h1 style="margin: 30px auto; width: 400px; text-align: center;">クリスマスダービー</h1>
    

    <div class="syotuba">

        <div>
            <h2>次レース出馬表</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>枠番</th>
                        <th>トナカイ名</th>
                        <th>オッズ</th>
                    </tr>
                </thead>
                <tbody id ='syutuba'>

                </tbody>
            </table>
            </div>
        <div>
            <h2>レース結果</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>着順</th>
                        <th>枠番</th>
                        <th>トナカイ名</th>
                        <th>オッズ</th>
                    </tr>
                </thead>
                <tbody id = 'result'>

                </tbody>
            </table>
        </div>

        <div class="score">
            <h2 id="recovery"></h2>
            <h2 id="all_bat"></h2>
            <h2 id="all_prize"></h2>
            <h2 id="race_bat"></h2>
            <h2 id="race_prize"></h2>
        </div>

        <div class = 'bat_table'>
            <h2>払戻金計算</h2>
            <h3>単勝オッズ:単勝オッズ</h3>
            <h3>複勝オッズ:単勝オッズ x 0.7</h3>
            <h3>三連単オッズ:一着オッズ x 二着オッズ x 三着オッズ</h3>
        </div>
    </div>
    <div class="main">
        
        <div class="box">
            <img src="img/bg1.jpg" id="bg">
            <img src="img/santa1_w.png" alt="" class="santa" id="no1">
            <img src="img/santa2_w.png" alt="" class="santa" id="no2">
            <img src="img/santa3_w.png" alt="" class="santa" id="no3">
            <img src="img/santa4_w.png" alt="" class="santa" id="no4">
            <img src="img/santa5_w.png" alt="" class="santa" id="no5">
            <img src="img/santa6_w.png" alt="" class="santa" id="no6">
            <img src="img/santa7_w.png" alt="" class="santa" id="no7">
            <img src="img/santa8_w.png" alt="" class="santa" id="no8">
            
            <img src="img/tree0.png" alt="" class="tree" id="tree0">
        </div>

        <div style="margin: 0 auto; width: 300px; text-align: center; padding-bottom: 50px;">
            <button onclick = 'btn();' id ='btn1' >レース開始</button>
        </div>
    </div>






    

    <div class="bat_bar">

        <!--単勝-->
        <div class = 'bat_table'>
            <h2>単勝</h2>
            <h3 id='tansyo_bat'></h3>
            <h3 id='tansyo_bat_money'></h3>
            <div name = 'buy_table'>
                <form action="#" id = 'tansyo_form'>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>枠番</th>
                                <th>トナカイ名</th>
                                <th>単勝オッズ</th>
                                <th>オッズ</th>
                            </tr>
                        </thead>
                        <tbody id ='tansyo'>
        
                        </tbody>
                    </table>
                    <input type="text" name = 'bat_money'> 00￥
                    <input type="button" onclick="bat_tansyo()" value="セット" class ="buy_btn">
                    <button onclick='reset_tansyo();' id ='btn2' class ="buy_btn">リセット</button>
                    
                </form>
                
            </div>

        </div>

        <!--複勝-->
        <div class = 'bat_table'>
            <h2>複勝</h2>
            <h3 id='fukusyo_bat'></h3>
            <h3 id='fukusyo_bat_money'></h3>
            <div name = 'buy_table'>
                <form action="#" id = 'fukusyo_form'>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>枠番</th>
                                <th>トナカイ名</th>
                                <th>単勝オッズ</th>
                                <th>複勝</th>
                            </tr>
                        </thead>
                        <tbody id ='fukusyo'>
        
                        </tbody>
                    </table>
                    <input type="text" name = 'bat_money'> 00￥
                    <input type="button" onclick="bat_fukusyo()" value="セット" class ="buy_btn">
                    <button onclick='reset_fukusyo();' id ='btn3' class ="buy_btn">リセット</button>
                </form>
                
            </div>

        </div>

        <!--三連単-->
        <div class = 'bat_table'>
            <h2>三連単</h2>
            <h3 id='sanrentan_bat'></h3>
            <h3 id='sanrentan_bat_money'></h3>
            <div name = 'buy_table'>
                <form action="#" id = 'sanrentan_form'>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>枠番</th>
                                <th>トナカイ名</th>
                                <th>単勝オッズ</th>
                                <th>一着</th>
                                <th>二着</th>
                                <th>三着</th>
                            </tr>
                        </thead>
                        <tbody id ='sanrentan'>
        
                        </tbody>
                    </table>
                    <input type="text" name = 'bat_money'> 00￥
                    <input type="button" onclick="bat_sanrentan()" value="セット" class ="buy_btn">
                    <button onclick='reset_sanrentan();' id ='btn4' class ="buy_btn">リセット</button>
                </form>

                
            </div>

        </div>




    </div>







</body>
</html>
