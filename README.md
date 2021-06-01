# 競馬予想AI
### 作品概要
WEBスクレイピンで10年分のレースデータを収集して、そのデータで訓練したモデルを使って、単勝馬番を予想します。レース30分前、WEBサイトに公開します。Android版はWebAPIを使ってWEBサーバにHTTPリクエストします。  

### こだわったポイント
去年一年間のレースシミュレーション結果、回収率116％。 
自動購入スクリプト使って、予測から馬券購入まで全部自動ので、パソコン前の拘束時間かからないです。

### サイトリンク
[予想公開サイト](https://keiba-ai.ml/)

## 開発環境
### 開発環境
Anaconda  
Jupyter Notebook  
Visual Studio Code  

### 開発言語
Python、PHP、JavaScript、Mysql、Kotlin

### 使用フレームワーク
機械学習：LightGBM  
WEB：jQuery、Bootstrap

### インフラ構築
AWS EC2、ALB、Route53、AWS Certificate Manager

## アプリケーション機能

### 機能一覧
- WEBサイト：レース30分前、予測結果をWEBサイトに公開する。
- Androidアプリ：WEBAPI使って、WEBサイトの情報を公開する。
- 馬券自動購入：API　seleniumでブラウザを自動操作で馬券購入。
- 結果統計：直近一年勝率と回収率の統計結果をWEBサイトに表示する。