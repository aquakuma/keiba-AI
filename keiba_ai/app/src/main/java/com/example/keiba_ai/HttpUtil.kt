package com.example.keiba_ai

import okhttp3.*
import okhttp3.MediaType.Companion.toMediaTypeOrNull
import okhttp3.RequestBody.Companion.toRequestBody
import org.json.JSONObject
import java.net.URI.create


class HttpUtil {

    //date php get
    fun httpGet_date(url: String,push_mode: String): String? {
    /*
        val formBody = FormBody.Builder(charset("UTF-8"))
            .add("push",push)
            .build()
*/
        val json = JSONObject()
        json.put("mode", push_mode)


        val postBody =
            json.toString().toRequestBody("application/json; charset=utf-8".toMediaTypeOrNull())
        val request = Request.Builder()
                .url(url)
                .post(postBody)
                .build()

        val response = HttpClient.instance.newCall(request).execute()
        val body = response.body?.string()
        return body
    }

    //racecourse php get
    fun httpGet_racecourse(url: String,push_mode: String,date:String): String? {
        /*
            val formBody = FormBody.Builder(charset("UTF-8"))
                .add("push",push)
                .build()
    */
        val json = JSONObject()
        json.put("mode", push_mode)
        json.put("date", date)

        val postBody =
            json.toString().toRequestBody("application/json; charset=utf-8".toMediaTypeOrNull())
        val request = Request.Builder()
            .url(url)
            .post(postBody)
            .build()

        val response = HttpClient.instance.newCall(request).execute()
        val body = response.body?.string()
        return body
    }

    //race php get
    fun httpGet_race(url: String,push_mode: String,date:String,course:String): String? {
        /*
            val formBody = FormBody.Builder(charset("UTF-8"))
                .add("push",push)
                .build()
    */
        val json = JSONObject()
        json.put("mode", push_mode)
        json.put("date", date)
        json.put("course", course)

        val postBody =
                json.toString().toRequestBody("application/json; charset=utf-8".toMediaTypeOrNull())
        val request = Request.Builder()
                .url(url)
                .post(postBody)
                .build()

        val response = HttpClient.instance.newCall(request).execute()
        val body = response.body?.string()
        return body
    }

    //予想レース
    fun httpGet_pre_race(url: String,push_mode: String): String? {
        /*
            val formBody = FormBody.Builder(charset("UTF-8"))
                .add("push",push)
                .build()
    */
        val json = JSONObject()
        json.put("mode", push_mode)


        val postBody =
            json.toString().toRequestBody("application/json; charset=utf-8".toMediaTypeOrNull())
        val request = Request.Builder()
            .url(url)
            .post(postBody)
            .build()

        val response = HttpClient.instance.newCall(request).execute()
        val body = response.body?.string()
        return body
    }
}




object HttpClient {
    //OKHttp3はシングルトンで使う
    val instance = OkHttpClient()
}