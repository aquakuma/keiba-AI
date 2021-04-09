function init() {

    $.ajax({
        url:'./connectDB.php', //送信先
        type:'POST', //送信方法
        datatype: 'json', //受け取りデータの種類
        data:{
            'mode': "init"
        }


    })
    // Ajax通信が成功した時
    .done( function(data) {
        
        //console.log('通信成功');
        //console.log(data);

        for(let i = 0; i < data.length;i++){
            // selectタグを取得する
            var select = document.getElementById("date");
            // optionタグを作成する
            var option = document.createElement("option");
            // optionタグのテキストを4に設定する
            option.text = data[i]['date']+" ("+data[i]['week']+")";
            // optionタグのvalueを4に設定する
            option.value = data[i]['date'];
            // selectタグの子要素にoptionタグを追加する
            select.appendChild(option);
            //document.write(date[i]+" ("+week[i]+")");
        }
        entryChange();


    })
    // Ajax通信が失敗した時
    .fail( function(data) {
        console.log('通信失敗');
        console.log(data);
    })

}


function entryChange() {
    if(document.getElementById('date')){
        var select_date = document.getElementById('date').value;
        
        //document.write(select_date);
        //console.log(select_date);
        connectDB_racecourse(select_date);
    }
}

function connectDB_racecourse(date){
    $.ajax({
        url:'./connectDB.php', //送信先
        type:'POST', //送信方法
        datatype: 'json', //受け取りデータの種類
        data:{
            'date': date,
            'mode': "racecourse"
        }

    })
    // Ajax通信が成功した時
    .done( function(data) {
        
        //console.log('通信成功');
        //console.log(data);
        var place = document.getElementById('place');
        while (place.querySelector('input')) {
            place.querySelector('input').remove();
        }
        while (place.querySelector('span')) {
            place.querySelector('span').remove();
        }

        for (var i = 0; i < data.length; i++){
            
            var row = document.createElement("input");
            row.setAttribute('value', data[i]);
            row.setAttribute('type', "radio");
            row.setAttribute('name', "racecourse");
            row.setAttribute('onclick', "main_content();");
            if (i == 0) {
                row.setAttribute('checked', "checked");
            }
            var row_text = document.createElement("span");
            row_text.innerHTML = data[i];
            place.appendChild(row);
            place.appendChild(row_text);
            
        }
        main_content();
    })
    // Ajax通信が失敗した時
    .fail( function(data) {
        console.log('通信失敗');
        console.log(data);
    })
}


function main_content() {
    if(document.getElementsByName('racecourse')){
        var predict = document.getElementsByName('racecourse')
        for (let i = 0; i < predict.length; i++){
            if(predict[i].checked){ //(color2[i].checked === true)と同じ
                racecourse = predict[i].value;
                
                break;
            }
        }



        var select_date = document.getElementById('date').value;

        connectDB_predict(select_date, racecourse);
    }
    
}

function connectDB_predict(date,racecourse){
    $.ajax({
        url:'./connectDB.php', //送信先
        type:'POST', //送信方法
        datatype: 'json', //受け取りデータの種類
        data:{
            'date': date,
            'racecourse': racecourse,
            'mode': "predict"
        }

    })
    // Ajax通信が成功した時
    .done( function(data) {
        

        var race = document.getElementById('race_rows');
        var race_table = document.getElementById('race_table');

        if (document.body.clientWidth > 1000) {
            race_table.style.width = 800 + "px";
        }
        var clientRect = race_table.getBoundingClientRect();
        var x = clientRect.right - clientRect.left;
        var select_form = document.getElementById('select_form');
        if (document.body.clientWidth > 720) {
            select_form.style.marginLeft = x + "px";
        }
        else {
            select_form.style.float = "left";
        }
        
        
        while (race.querySelector('tr')) {
            race.querySelector('tr').remove();
        }
        
        for (var i = 0; i < data.length; i++){
            
            var row = document.createElement("tr");
            row.style.textAlign = "center";
            var race_title = document.createElement("td");
            var race_title_a = document.createElement("a");
            var race_id = data[i]['race_id'];

            race_title_a.textContent = data[i]['race_title'];
            race_title_a.href = `https://race.netkeiba.com/race/shutuba.html?race_id=${race_id}`;
            race_title_a.target = '_blank';
            race_title_a.rel = 'noopener noreferrer';

            race_title.style.textAlign = "left";
            race_title.style.paddingLeft = "0.5em";
            race_title.style.paddingRight = "0.5em";
            race_title.appendChild(race_title_a);
            row.appendChild(race_title);

            var round = document.createElement("td");
            round.textContent = data[i]['round'];
            row.appendChild(round);

            var start = document.createElement("td");
            start.textContent = data[i]['start'];
            row.appendChild(start);

            var total_horse = document.createElement("td");
            total_horse.textContent = data[i]['total_horse'];
            row.appendChild(total_horse);

            var td_pre_num = document.createElement("td");
            //枠色
            var pre_num  = document.createElement("div");
            pre_num.textContent = data[i]['pre_num'];
            pre_num.style.width = "1.5em";
            pre_num.style.textAlign = "center";
            pre_num.style.margin = "0 auto";
            //td_pre_num.style.paddingLeft = "1.2em";


            
            var frame_number = 0;
            if (data[i]['total_horse'] <= 8) {
                frame_number = Number(data[i]['pre_num']);
            }
            if (data[i]['total_horse'] > 8 & data[i]['total_horse'] <= 16) {
                if (data[i]['pre_num'] <= 16 - Number(data[i]['total_horse'])) {
                    frame_number = Number(data[i]['pre_num']);
                }
                else {
                    frame_number = 16 - Number(data[i]['total_horse']) + Math.ceil(((Number(data[i]['pre_num']) + Number(data[i]['total_horse']) - 16) / (Number(data[i]['total_horse']) * 2 - 16))*(Number(data[i]['total_horse'])-8));
                }
            }
            if (data[i]['total_horse'] > 16)  {
                if (data[i]['total_horse'] == 17) {
                    if (data[i]['pre_num'] == 17) {
                        frame_number = 8;
                    }
                    else {
                        frame_number = Math.ceil(Number(data[i]['pre_num'])/2);
                    }
                    
                }
                if (data[i]['total_horse'] == 18) {
                    if (data[i]['pre_num'] == 18) {
                        frame_number = 8;
                    }
                    else if (data[i]['pre_num'] == 17) {
                        frame_number = 8;
                    }
                    else if (data[i]['pre_num'] == 16) {
                        frame_number = 8;
                    }
                    else if (data[i]['pre_num'] == 15) {
                        frame_number = 7;
                    }
                    else {
                        frame_number = Math.ceil(Number(data[i]['pre_num'])/2);
                    }
                }
                
            }


            var color = "";
            pre_num.style.color = 'white';
            pre_num.style.border = 'solid 1px';
            pre_num.style.borderColor = 'white';
            switch (frame_number){
                case 1:
                    color = "white";
                    pre_num.style.color = 'black';
                    pre_num.style.borderColor = 'black';
                    break;
                case 2:
                    color = "black";
                    break;
                case 3:
                    color = "red";
                    break;
                case 4:
                    color = "blue";
                    break;
                case 5:
                    color = "#e1da3a";
                    break;
                case 6:
                    color = "green";
                    break;
                case 7:
                    color = "orange";
                    break;
                case 8:
                    color = "pink";
                    break;
                default:
                    color = "gray";
            }

            pre_num.style.backgroundColor = color;
            ///////////////////////////////////////////
            td_pre_num.appendChild(pre_num);
            row.appendChild(td_pre_num);

            var odds = document.createElement("td");
            odds.textContent = data[i]['odds'];
            row.appendChild(odds);

            var reliability = document.createElement("td");
            reliability.textContent = data[i]['reliability'];
            row.appendChild(reliability);

            var cre_dif = document.createElement("td");
            cre_dif.textContent = data[i]['cre_dif'];
            row.appendChild(cre_dif);

            var limen = document.createElement("td");
            limen.textContent = data[i]['limen'];
            row.appendChild(limen);

            var pre_rank = document.createElement("td");
            if (data[i]['pre_rank'] == 0) {
                pre_rank.textContent = '中止';
            }
            else {
                pre_rank.textContent = data[i]['pre_rank'];
            }
            row.appendChild(pre_rank);



            var first_td = document.createElement("td");
            //枠色
            var first = document.createElement("div");
            first.textContent = data[i]['first'];


            first.style.width = "1.5em";

            
            first.style.textAlign = "center";
            first.style.margin = "0 auto";



            
            var frame_number = 0;
            if (data[i]['total_horse'] <= 8) {
                frame_number = Number(data[i]['first']);
            }
            if (data[i]['total_horse'] > 8 & data[i]['total_horse'] <= 16) {
                if (data[i]['first'] <= 16 - Number(data[i]['total_horse'])) {
                    frame_number = Number(data[i]['first']);
                }
                else {
                    frame_number = 16 - Number(data[i]['total_horse']) + Math.ceil(((Number(data[i]['first']) + Number(data[i]['total_horse']) - 16) / (Number(data[i]['total_horse']) * 2 - 16))*(Number(data[i]['total_horse'])-8));
                }
            }
            if (data[i]['total_horse'] > 16)  {
                if (data[i]['total_horse'] == 17) {
                    if (data[i]['first'] == 17) {
                        frame_number = 8;
                    }
                    else {
                        frame_number = Math.ceil(Number(data[i]['first'])/2);
                    }
                    
                }
                if (data[i]['total_horse'] == 18) {
                    if (data[i]['first'] == 18) {
                        frame_number = 8;
                    }
                    else if (data[i]['first'] == 17) {
                        frame_number = 8;
                    }
                    else if (data[i]['first'] == 16) {
                        frame_number = 8;
                    }
                    else if (data[i]['first'] == 15) {
                        frame_number = 7;
                    }
                    else {
                        frame_number = Math.ceil(Number(data[i]['first'])/2);
                    }
                }
                
            }


            var color = "";
            first.style.color = 'white';
            first.style.border = 'solid 1px';
            first.style.borderColor = 'white';
            switch (frame_number){
                case 1:
                    color = "white";
                    first.style.color = 'black';
                    first.style.borderColor = 'black';
                    break;
                case 2:
                    color = "black";
                    break;
                case 3:
                    color = "red";
                    break;
                case 4:
                    color = "blue";
                    break;
                case 5:
                    color = "#e1da3a";
                    break;
                case 6:
                    color = "green";
                    break;
                case 7:
                    color = "orange";
                    break;
                case 8:
                    color = "pink";
                    break;
                default:
                    color = "gray";
            }
            if (data[i]['first'] == null) {
                if ((i + 1) % 2 == 0) {
                    first.style.borderColor = 'rgb(240, 240, 240)';
                    color = 'rgb(240, 240, 240)';
                }
                else {
                    color = "white";
                }
            }

            first.style.backgroundColor = color;
            

            ///////////////////////////////////////////

            first_td.appendChild(first);
            row.appendChild(first_td);

            var win = document.createElement("td");
            if (data[i]['win'] == 1) {
                var img = document.createElement("img");
                img.src = "img/hit.png";
                //img.style.marginTop = '5px';
                img.style.height = '25px';
                img.style.width = '25px';
                win.appendChild(img);
                win.style.verticalAlign = 'middle';
                //win.style.paddingTop = '6px';
            }
            //win.textContent = data[i]['win'];
            row.appendChild(win);
            race.appendChild(row);
            
        }
        

    })
    // Ajax通信が失敗した時
    .fail( function(data) {
        console.log('通信失敗');
        console.log(data);
    })
}

/*予測テーブル表示*/
function predict_show(){
    $.ajax({
        url:'./connectDB.php', //送信先
        type:'POST', //送信方法
        datatype: 'json', //受け取りデータの種類
        data:{
            'mode': "predict_show"
        }

    })
    // Ajax通信が成功した時
    .done( function(data) {
        //console.log('in');
        //console.log('通信成功');
        //console.log(data);

        var race = document.getElementById('predict_table');
        
        while (race.querySelector('div')) {
            race.querySelector('div').remove();
        }
        //console.log(data);

        if (data != null) {
            for (var i = 0; i < data.length; i++){
                
                if (i == 0 || i % 3 == 0) {
                    var div = document.createElement("div");
                    div.classList.add('row');
                }
                
                
                var pre_race = document.createElement("div");
                pre_race.classList.add('col-sm-4', 'baken','blog-post');
                
                var table = document.createElement("table");
                var tr = document.createElement("tr");
                var td = document.createElement("td");

                var race_title = document.createElement("div");
                //race_title.classList.add('baken_content');
    
                var race_title_a = document.createElement("a");
                var race_id = data[i]['race_id'];
    
                race_title_a.textContent = data[i]['race_title'];
                race_title_a.href = `https://race.netkeiba.com/race/shutuba.html?race_id=${race_id}`;
                race_title_a.target = '_blank';
                race_title_a.rel = 'noopener noreferrer';
                //race_title.style.height = '3em';
                //race_title.style.backgroundColor = 'pink';
    
    
      
    
                race_title.appendChild(race_title_a);
                td.appendChild(race_title);
                tr.appendChild(td);
                var td = document.createElement("td");

                var start = document.createElement("div");
                //start.classList.add('baken_content');
                start.textContent = data[i]['start'] + " 発走";
                //start.style.backgroundColor = 'pink';
                start.style.verticalAlign = 'top';
                //start.style.height = '3em';
    
    
                td.appendChild(start);
                tr.appendChild(td);
                table.appendChild(tr);

                var tr = document.createElement("tr");
                var td = document.createElement("td");

                var racecourse = document.createElement("div");
                
                racecourse.textContent = data[i]['racecourse'];
                //racecourse.style.textAlign = 'center';
                //racecourse.style.backgroundColor = 'pink';
                racecourse.style.width = '5em';
                racecourse.style.fontSize = '22px';
                racecourse.style.verticalAlign = 'middle';
                racecourse.style.marginBottom = '0.5em';
    
                //pre_race.appendChild(racecourse);
    
    
                var round_waku = document.createElement("div");
                var round_text = document.createElement("div");
                //round_waku.style.backgroundColor = 'pink';
     
                round_text.textContent = 'レース';
                round_text.style.verticalAlign = 'text-bottom';
                round_text.style.display = 'inline-block';
                round_text.style.width = '3em';
                round_text.style.fontSize = '12px';
                round_text.style.marginLeft = '10px';
                var round = document.createElement("div");
                round_waku.style.width = '230px';
                round_waku.style.marginTop = '-5px';
                round_waku.style.verticalAlign = 'text-top';
                //round_waku.style.backgroundColor = 'pink';
                round.textContent = data[i]['round'];
                round.style.color = 'white';
                round.style.backgroundColor = 'black';
                round.style.textAlign = 'center';
                round.style.verticalAlign = 'text-bottom';
                round.style.display = 'inline-block';
                round.style.width = '2.5em';
                round.style.marginTop = '-20px';
                round.style.marginLeft = '-0em';
    
    
    
                td.appendChild(racecourse);
                tr.appendChild(td);

                var td = document.createElement("td");

                td.appendChild(round);
                td.appendChild(round_text);
                tr.appendChild(td);
                table.appendChild(tr);

                var tr = document.createElement("tr");
                var td = document.createElement("td");

                td.style.textAlign = "center";
                td.style.float = "left";
                //round_waku.appendChild(round);
                //round_waku.appendChild(round_text);
    
                //round_waku.classList.add('baken_content');
                //pre_race.appendChild(round_waku);
    
                //var text = document.createElement("div");
    
                //text.classList.add('baken_content');
                //pre_race.appendChild(text);
    
    
                var pre_num = document.createElement("div");
                var pre_waku = document.createElement("div");
                pre_waku.textContent = '予想馬番';
                pre_num.textContent = data[i]['pre_num'];
                //pre_waku.style.backgroundColor = "pink";
                /*
                pre_waku.style.textAlign = "center";
                pre_waku.style.width = "4em";
                pre_waku.style.marginBottom = "10px";
                pre_waku.style.float = "left";
                */
                pre_num.style.margin = "0 auto";
                pre_num.style.width = "1.5em";
                pre_num.style.fontSize = "30px";
                pre_num.style.textAlign = "center";
                pre_num.style.display = 'inline-block';
                //td_pre_num.style.paddingLeft = "1.2em";
    
    
                
                var frame_number = 0;
                if (data[i]['total_horse'] <= 8) {
                    frame_number = Number(data[i]['pre_num']);
                }
                if (data[i]['total_horse'] > 8 & data[i]['total_horse'] <= 16) {
                    if (data[i]['pre_num'] <= 16 - Number(data[i]['total_horse'])) {
                        frame_number = Number(data[i]['pre_num']);
                    }
                    else {
                        frame_number = 16 - Number(data[i]['total_horse']) + Math.ceil(((Number(data[i]['pre_num']) + Number(data[i]['total_horse']) - 16) / (Number(data[i]['total_horse']) * 2 - 16))*(Number(data[i]['total_horse'])-8));
                    }
                }
                if (data[i]['total_horse'] > 16)  {
                    if (data[i]['total_horse'] == 17) {
                        if (data[i]['pre_num'] == 17) {
                            frame_number = 8;
                        }
                        else {
                            frame_number = Math.ceil(Number(data[i]['pre_num'])/2);
                        }
                        
                    }
                    if (data[i]['total_horse'] == 18) {
                        if (data[i]['pre_num'] == 18) {
                            frame_number = 8;
                        }
                        else if (data[i]['pre_num'] == 17) {
                            frame_number = 8;
                        }
                        else if (data[i]['pre_num'] == 16) {
                            frame_number = 8;
                        }
                        else if (data[i]['pre_num'] == 15) {
                            frame_number = 7;
                        }
                        else {
                            frame_number = Math.ceil(Number(data[i]['pre_num'])/2);
                        }
                    }
                    
                }
    
    
                var color = "";
                pre_num.style.color = 'white';
                pre_num.style.border = 'solid 1px';
                pre_num.style.borderColor = 'white';
                switch (frame_number){
                    case 1:
                        color = "white";
                        pre_num.style.color = 'black';
                        pre_num.style.borderColor = 'black';
                        break;
                    case 2:
                        color = "black";
                        break;
                    case 3:
                        color = "red";
                        break;
                    case 4:
                        color = "blue";
                        break;
                    case 5:
                        color = "#e1da3a";
                        break;
                    case 6:
                        color = "green";
                        break;
                    case 7:
                        color = "orange";
                        break;
                    case 8:
                        color = "pink";
                        break;
                    default:
                        color = "gray";
                }
    
                pre_num.style.backgroundColor = color;
                ///////////////////////////////////////////

                

                td.appendChild(pre_waku);
                td.appendChild(pre_num);
                tr.appendChild(td);

                var td = document.createElement("td");
                //pre_race.appendChild(pre_waku);
    
                //信頼度
                var limen = document.createElement("div");
                limen.innerText = "オッズ:" + data[i]['odds']
                    + "\n信頼度:" + Math.floor(data[i]['reliability'] * Math.pow(10, 3)) / Math.pow(10, 3)
                    + "\n信頼差:" + Math.floor(data[i]['cre_dif'] * Math.pow(10, 3)) / Math.pow(10, 3)
                    + "\n閾値:"+ Math.floor(data[i]['limen'] * Math.pow(10, 3)) / Math.pow(10, 3);
    
                limen.style.backgroundColor = 'white';
                limen.style.border = 'solid 1px';
                limen.style.padding = '5px';
                limen.style.width = '130px';
    
      
                //start.style.height = '3em';
                //pre_race.appendChild(limen);
                /*
                var bottom_waku = document.createElement("div");
                bottom_waku.style.width = '240px';
                bottom_waku.style.marginTop = '-30px';
                bottom_waku.style.marginBottom = '10px';
                bottom_waku.appendChild(limen);
                */
                //bottom_waku.style.float = 'right';
                //bottom_waku.classList.add('baken_content');
                limen.style.marginBottom = "10px";
                td.appendChild(limen);
                tr.appendChild(td);
                table.appendChild(tr);


                pre_race.appendChild(table);

               
                
                if (i % 3 == 2) {
                    div.appendChild(pre_race);
                    race.appendChild(div);
                }
                else {
                    div.appendChild(pre_race);
                    
                }

                if (i % 3 != 2 && i == data.length - 1) {
                    race.appendChild(div);
                }
                

            

    
            }
        }
        else {
            var predict_table = document.getElementById('predict_table');
            var div = document.createElement("div");
            predict_table.style.textAlign = "center";
            predict_table.style.marginTop = "150px";
            predict_table.style.marginLeft = "20%";
            var h2 = document.createElement("h2");
    
            h2.textContent = '現在レース開催されていません';
    
            predict_table.appendChild(h2);

        }

    })
    // Ajax通信が失敗した時
    .fail( function(data) {
        //console.log('通信失敗');
        //console.log(data);
        var predict_table = document.getElementById('predict_table');
        var div = document.createElement("div");
        predict_table.style.textAlign = "center";
        predict_table.style.marginTop = "150px";
        predict_table.style.marginLeft = "20%";
        var h2 = document.createElement("h2");

        h2.textContent = '現在レース開催されていません';

        predict_table.appendChild(h2);


    })
}


function set() {
    
    var race_table = document.getElementById('race_table');
    if (document.body.clientWidth > 1000) {
        race_table.style.width = 800 + "px";
    }
    var clientRect = race_table.getBoundingClientRect();
    var x = clientRect.right - clientRect.left;
    var select_form = document.getElementById('select_form');
    if (document.body.clientWidth > 720) {
        select_form.style.marginLeft = x + "px";
    }
    else {
        select_form.style.float = "left";
    }


}


//window.onload = init;
window.addEventListener('DOMContentLoaded', init);
window.addEventListener('DOMContentLoaded', predict_show);
window.addEventListener('load', set);
