package com.example.keiba_ai

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.View
import com.eclipsesource.json.Json

import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.async
import kotlinx.coroutines.launch
import android.content.Intent
import android.graphics.Bitmap
import android.graphics.BitmapFactory
import android.text.Html
import android.text.method.LinkMovementMethod
import android.util.Log
import android.view.Gravity
import android.widget.*
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken


class MainActivity : AppCompatActivity() {
    val URL = "https://keiba-ai.ml/app_client.php" //サンプルとしてQiitaのAPIサービスを利用します
    var result = ""
    val push = "11"



    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)


        Log.v("fr", "11")
        //過去レースボタン
        val sub1 = findViewById(R.id.button2) as Button
        sub1.setOnClickListener{
            //Intentオブジェクト生成、遷移画面定義
            val nextIntent = Intent(this, SecondActivity::class.java)

            //次のActivity実行
            startActivity(nextIntent)
        }
        onParallelGetButtonClick()
        //Log.v("TAG3", "22222")

    }

    //非同期処理でHTTP GETを実行します。
    fun onParallelGetButtonClick() = GlobalScope.launch(Dispatchers.Main) {
        val http = HttpUtil()
        val push = "predict"

        //Mainスレッドでネットワーク関連処理を実行するとエラーになるためBackgroundで実行
        async(Dispatchers.Default) { http.httpGet_pre_race(URL,push) }.await().let {


            val gson = Gson()
            //val json = JSONObject(it)
            val strJson = it
            val listType = object : TypeToken<List<race>>() {}.type
            val date_array = Gson().fromJson<List<race>>(strJson, listType)

            var k = 0

            val tableLayout = findViewById(R.id.pre_table) as TableLayout    // レイアウトファイルにあるレイアウトのidを指定して読み込みます
            tableLayout.gravity = Gravity.RIGHT   // 画面中央寄せ
            tableLayout.removeAllViews()

            if(date_array!= null){
                for(i in date_array){
                    //Log.v("pre_num$k:", i.pre_num)
                    //Log.v("frame_pre$k:", i.frame_pre)
                    //Log.v("frame_pre$k:", k.toString())
                    val newRow = TableRow(this@MainActivity)
                    val newText1 = TextView(this@MainActivity)
                    val newText2 = TextView(this@MainActivity)
                    val newText3 = TextView(this@MainActivity)
                    val newText4 = TextView(this@MainActivity)
                    val newText5 = TextView(this@MainActivity)
                    var html_text = "<div style = 'width: 6em'><a href='https://race.netkeiba.com/race/shutuba.html?race_id=${i.race_id}&rf=race_list'> ${i.race_title} </a> <br><span style = 'border:solid 1px; background-color:#000000; color:#ffffff;'>R</span>${i.round}   <b>${i.racecourse}</b>   ${i.total_horse}頭<br>発走:${i.start}</div>"
                    newText1.text = Html.fromHtml(html_text)
                    newText1.setMovementMethod(LinkMovementMethod.getInstance())
                    newText1.width = 340
                    newRow.addView(newText1)
                    var pre_color = ""
                    var font_color = "#000000"
                    when(i.frame_pre){
                        "1" -> {
                            pre_color = "#ffffff"
                            font_color = "#000000"
                        }
                        "2" -> {
                            pre_color = "#000000"
                            font_color = "#ffffff"
                        }
                        "3" -> {
                            pre_color = "#ff0000"
                            font_color = "#ffffff"
                        }
                        "4" -> {
                            pre_color = "#4169e1"
                            font_color = "#ffffff"
                        }
                        "5" -> {
                            pre_color = "#ffd700"
                            font_color = "#ffffff"
                        }
                        "6" -> {
                            pre_color = "#98fb98"
                            font_color = "#ffffff"
                        }
                        "7" -> {
                            pre_color = "#f4a460"
                            font_color = "#ffffff"
                        }
                        "8" -> {
                            pre_color = "#ee82ee"
                            font_color = "#ffffff"
                        }
                    }
                    html_text = "<div style ='background-color: ${pre_color}; color:${font_color}; border:solid 1px;width: 1.5em;'><p style = 'border:solid 1px; background-color:${pre_color}; color:${font_color}; width:20px; border-color: black;'>${i.pre_num}</p></div>${i.odds}"
                    newText2.text = Html.fromHtml(html_text)
                    newText2.width = 160
                    newRow.addView(newText2)


                    html_text = "<div style ='text-align: center;'><br>${i.reliability}<br></div>"
                    newText3.text = Html.fromHtml(html_text)
                    newText3.width = 180
                    newRow.addView(newText3)

                    html_text = "<div style ='text-align: center;'><br>${i.cre_dif}<br></div>"
                    newText4.text = Html.fromHtml(html_text)
                    newText4.width = 180
                    newRow.addView(newText4)

                    html_text = "<div style ='text-align: center;'><br>${i.limen}<br></div>"
                    newText5.text = Html.fromHtml(html_text)
                    newText5.width = 180
                    newRow.addView(newText5)

                    Log.v("pre$k:", i.pre_num)
                    Log.v("frame_pre$k:", i.frame_pre)
                    Log.v("color$k:", pre_color)
                    tableLayout.addView(newRow)
                    k++
                }
            }

        }
    }
}