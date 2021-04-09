package com.example.keiba_ai

//import com.eclipsesource.json.Json
import android.content.Intent
import android.graphics.Bitmap
import android.graphics.BitmapFactory
import android.os.Bundle
import android.text.Html
import android.text.method.LinkMovementMethod
import android.util.Log
import android.view.Gravity
import android.view.View
import android.widget.*
import android.widget.LinearLayout
import androidx.appcompat.app.AppCompatActivity
import androidx.core.text.HtmlCompat
import androidx.core.text.HtmlCompat.FROM_HTML_MODE_COMPACT
import com.bumptech.glide.Glide
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import kotlinx.android.synthetic.main.activity_main.view.*
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.async
import kotlinx.coroutines.launch


class SecondActivity : AppCompatActivity() {
    val URL = "https://keiba-ai.ml/app_client.php" //サンプルとしてQiitaのAPIサービスを利用します
    var result = ""
    val push = "sub18"
    var data_list = mutableListOf<String>()
    var racecourse_list = mutableListOf<String>()
    var select_date = ""
    var radioGroup:RadioGroup? = null



    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_second)

        //var radioGroup = RadioGroup(this)

        onParallelGetButtonClick()


        // 戻るボタン
        val home = findViewById<Button>(R.id.button3)
        home.setOnClickListener{
            //Intentオブジェクト生成、遷移画面定義
            val nextIntent = Intent(this, MainActivity::class.java)

            //次のActivity実行
            startActivity(nextIntent)
        }
/*
        val getButton = findViewById<Button>(R.id.button4)
        getButton.setOnClickListener {

            onParallelGetButtonClick()
        }


 */


        val spinner = findViewById<Spinner>(R.id.spinner)
        // 日付選び
        spinner.onItemSelectedListener = object : AdapterView.OnItemSelectedListener{
            //　アイテムが選択された時
            override fun onItemSelected(
                    parent: AdapterView<*>?,
                    view: View?, position: Int, id: Long
            ) {
                val spinnerParent = parent as Spinner
                val item = spinnerParent.selectedItem as String
                //val textView = findViewById(R.id.textView3) as TextView
                //textView.text = item

                select_date = item
                racecourse(item)

                Log.v("test", "in")

            }


            //　アイテムが選択されなかった
            override fun onNothingSelected(parent: AdapterView<*>?) {
                //
            }
        }

/*
        //競馬場選び

        radioGroup!!.setOnCheckedChangeListener { _, i: Int ->
            val id: Int = this.radioGroup!!.checkedRadioButtonId
            Log.v("ccc", "1111")
            Log.v("test print", this.radioGroup!!.findViewById<RadioButton>(id).text.toString())
        }


 */



        /*
        // Get radio group selected item using on checked change listener
        radioGroup?.setOnCheckedChangeListener(
                RadioGroup.OnCheckedChangeListener { group, checkedId ->
                    val radio: RadioButton = findViewById(checkedId)
                    Log.v("ccc", "1111")
                    Toast.makeText(applicationContext," On checked change : ${radio.text}",
                            Toast.LENGTH_SHORT).show()
                })



        val tableLayout = findViewById(R.id.tableLayout) as TableLayout    // レイアウトファイルにあるレイアウトのidを指定して読み込みます
        val scrollView = ScrollView(this)
        // View に ScrollView を設定
        setContentView(scrollView)
        scrollView.addView(tableLayout)
         */




    }

    ///////////////////////

    //非同期処理でHTTP GETを実行します。
    fun onParallelGetButtonClick() = GlobalScope.launch(Dispatchers.Main) {
        val http = HttpUtil()

        val phsh_mode = "date"
        //Mainスレッドでネットワーク関連処理を実行するとエラーになるためBackgroundで実行
        async(Dispatchers.Default) { http.httpGet_date(URL, phsh_mode) }.await().let {

            //val result = Json.parse(it).asObject()
            //val parentJsonObj = JSONObject(it)
            //Log.v("TAG1", result.length())
            if (it != null) {
                Log.v("in", it)
            }

            val gson = Gson()
            //val json = JSONObject(it)
            val strJson = it
            val listType = object : TypeToken<List<dateData>>() {}.type
            val date_array = Gson().fromJson<List<dateData>>(strJson, listType)

            data_list.clear()

            for(i in date_array){
                data_list.add(i.date)
            }

            pulldown ()

        }
    }

    //pulldown 表示
    fun pulldown (){
        // Spinnerの取得
        val spinner = findViewById<Spinner>(R.id.spinner)

        // Adapterの生成
        val adapter = ArrayAdapter(this, android.R.layout.simple_spinner_item, data_list)

        // 選択肢の各項目のレイアウト
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)

        // AdapterをSpinnerのAdapterとして設定
        spinner.adapter = adapter
    }

    //競馬場 PHP
    fun racecourse(date: String) = GlobalScope.launch(Dispatchers.Main){
        val http = HttpUtil()
        val phsh_mode = "racecourse"

        //Mainスレッドでネットワーク関連処理を実行するとエラーになるためBackgroundで実行
        async(Dispatchers.Default) { http.httpGet_racecourse(URL, phsh_mode, date) }.await().let {

            //val result = Json.parse(it).asObject()
            //val parentJsonObj = JSONObject(it)
            //Log.v("TAG1", result.length())


            val gson = Gson()
            //val json = JSONObject(it)
            val strJson = it
            val listType = object : TypeToken<List<racecourseData>>() {}.type
            val date_array = Gson().fromJson<List<racecourseData>>(strJson, listType)

            racecourse_list.clear()
            var k = 0

            for(i in date_array){
                racecourse_list.add(i.racecourse)
                Log.v("debug$k:", i.racecourse)
                k++
            }
            race_button()
        }
    }

    //racecourse button
    fun race_button(){
        val linearLayout = findViewById(R.id.linearLayout3) as LinearLayout    // レイアウトファイルにあるレイアウトのidを指定して読み込みます
        linearLayout.gravity = Gravity.RIGHT   // 画面中央寄せ

        radioGroup = RadioGroup(this)
        radioGroup!!.gravity = Gravity.RIGHT
        radioGroup!!.orientation = RadioGroup.HORIZONTAL

        Log.v("ffin", racecourse_list.size.toString())

        linearLayout.removeAllViews()

        racecourse_list.forEach{
            val radioButton = RadioButton(this)
            radioButton.text = it
            radioButton.setPadding(15, 0, 15, 0)
            radioGroup!!.addView(radioButton) // radioGroupにradioButtonを追加する
            if(it.equals(racecourse_list[0])){
                radioButton.isChecked = true
                val id: Int = this.radioGroup!!.checkedRadioButtonId
                Log.v("test print", this.radioGroup!!.findViewById<RadioButton>(id).text.toString())

                table_content(
                        select_date,
                        this.radioGroup!!.findViewById<RadioButton>(id).text.toString()
                )
            }


            val id: Int = radioGroup!!.checkedRadioButtonId


            val radiotext: String = radioGroup!!.findViewById<RadioButton>(id).text.toString()
            Log.v("tttin", radiotext)
            Log.v("sel", id.toString())
        }
        linearLayout.addView(radioGroup)

        //押す時処理
        radioGroup!!.setOnCheckedChangeListener { _, i: Int ->
            val id: Int = this.radioGroup!!.checkedRadioButtonId
            Log.v("test print", this.radioGroup!!.findViewById<RadioButton>(id).text.toString())
            table_content(
                    select_date,
                    this.radioGroup!!.findViewById<RadioButton>(id).text.toString()
            )
        }

    }

    //テーブルレイアウト
    fun table_content(date: String, course: String) = GlobalScope.launch(Dispatchers.Main){


        val http = HttpUtil()
        val phsh_mode = "race"

        //Mainスレッドでネットワーク関連処理を実行するとエラーになるためBackgroundで実行
        async(Dispatchers.Default) { http.httpGet_race(URL, phsh_mode, date, course) }.await().let {

            //val result = Json.parse(it).asObject()
            //val parentJsonObj = JSONObject(it)
            //Log.v("TAG1", result.length())


            val gson = Gson()
            //val json = JSONObject(it)
            val strJson = it
            val listType = object : TypeToken<List<race>>() {}.type
            val date_array = Gson().fromJson<List<race>>(strJson, listType)

            var k = 0

            val tableLayout = findViewById(R.id.tableLayout) as TableLayout    // レイアウトファイルにあるレイアウトのidを指定して読み込みます

            tableLayout.gravity = Gravity.RIGHT   // 画面中央寄せ
            tableLayout.removeAllViews()

            /*
            val newRow = TableRow(this@SecondActivity)
            val th1 = TextView(this@SecondActivity)
            var html_text = "<div style ='text-align: center;'>レース情報</div>"
            th1.text = Html.fromHtml(html_text)
            newRow.addView(th1)
            val th2 = TextView(this@SecondActivity)
            html_text = "<div style ='text-align: center;'>予想馬番<br>オッズ</div>"
            th2.text = Html.fromHtml(html_text)
            newRow.addView(th2)
            val th3 = TextView(this@SecondActivity)
            html_text = "<div style ='text-align: center;'>信頼度<br>信頼差<br>閾値</div>"
            th3.text = Html.fromHtml(html_text)
            newRow.addView(th3)
            tableLayout.addView(newRow)

             */
            for(i in date_array){
                //Log.v("pre_num$k:", i.pre_num)
                //Log.v("frame_pre$k:", i.frame_pre)
                //Log.v("frame_pre$k:", k.toString())
                val newRow = TableRow(this@SecondActivity)
                val newText1 = TextView(this@SecondActivity)
                val newText2 = TextView(this@SecondActivity)
                val newText3 = TextView(this@SecondActivity)
                val newText4 = TextView(this@SecondActivity)
                var html_text = "<div style = 'width: 6em'><a href='https://race.netkeiba.com/race/shutuba.html?race_id=${i.race_id}&rf=race_list'> ${i.race_title} </a> <br><span style = 'border:solid 1px; background-color:#000000; color:#ffffff;'>R</span>${i.round}      ${i.total_horse}頭<br>発走:${i.start}</div>"
                newText1.text = Html.fromHtml(html_text)
                newText1.setMovementMethod(LinkMovementMethod.getInstance())
                newText1.width = 450
                newRow.addView(newText1)
                var pre_color = ""
                var font_color = "#ffffff"
                when(i.frame_pre){
                    "1" -> {
                        pre_color = "#ffffff"
                        font_color = "#000000"
                    }
                    "2" -> {
                        pre_color = "#000000"
                    }
                    "3" -> {
                        pre_color = "#ff0000"
                    }
                    "4" -> {
                        pre_color = "#4169e1"
                    }
                    "5" -> {
                        pre_color = "#ffd700"
                    }
                    "6" -> {
                        pre_color = "#98fb98"
                    }
                    "7" -> {
                        pre_color = "#f4a460"
                    }
                    "8" -> {
                        pre_color = "#ee82ee"
                    }
                }
                html_text = "<div style ='background-color: ${pre_color}; color:${font_color}; border:solid 1px;width: 1.5em;'><span style = 'border:solid 1px; background-color:${pre_color}; color:${font_color}; width:1.5em; border-color: black;'>${i.pre_num}</span></div>${i.odds}"
                newText2.text = Html.fromHtml(html_text)
                newText2.width = 220
                newRow.addView(newText2)


                html_text = "<div style ='text-align: center;'>${i.reliability}<br>${i.cre_dif}<br>${i.limen}</div>"
                newText3.text = Html.fromHtml(html_text)
                newRow.addView(newText3)

                var first_color = ""
                font_color = "#ffffff"
                when(i.frame_first){
                    "1" -> {
                        first_color = "#ffffff"
                        font_color = "#000000"
                    }
                    "2" -> {
                        first_color = "#000000"
                    }
                    "3" -> {
                        first_color = "#ff0000"
                    }
                    "4" -> {
                        first_color = "#4169e1"
                    }
                    "5" -> {
                        first_color = "#ffd700"
                    }
                    "6" -> {
                        first_color = "#98fb98"
                    }
                    "7" -> {
                        first_color = "#f4a460"
                    }
                    "8" -> {
                        first_color = "#ee82ee"
                    }
                }

                Log.v("pre$k:", i.pre_num)
                if(i.pre_rank =="0"){
                    i.pre_rank = "中止";
                }
                html_text = "<div style ='text-align: center;'>${i.pre_rank}<br><span style ='background-color:${first_color}; color:${font_color};'><br>${i.first}</span></div>"
                newText4.text = Html.fromHtml(html_text)
                newText4.width = 370
                newRow.addView(newText4)
                Log.v("color$k:", first_color)
                Log.v("first$k:", i.pre_rank)
                val imageView = ImageView(this@SecondActivity)

                val srcBitmap = BitmapFactory.decodeResource( getResources(),R.drawable.hit)
                val bitmap = Bitmap.createScaledBitmap(srcBitmap, 150, 150, false)
                imageView.setImageBitmap(bitmap)

                if(i.win == "1"){
                    newRow.addView(imageView)
                }
                //Log.v("frame_pre$k:", i.pre_num)
                //Log.v("color$k:", pre_color)
                tableLayout.addView(newRow)
                k++
            }



        }
    }



}


data class dateData(
        var date: String
)

data class racecourseData(
        var racecourse: String
)

data class race(
        var race_id: String,
        var racecourse: String,
        var race_title: String,
        var round: String,
        var start: String,
        var total_horse: String,
        var pre_num: String,
        var reliability: String,
        var cre_dif: String,
        var limen: String,
        var pre_rank: String,
        var odds: String,
        var first: String,
        var win: String,
        var frame_pre: String,
        var frame_first: String
)