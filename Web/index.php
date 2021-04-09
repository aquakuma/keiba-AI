<?php include('weather.php')?>
<?php include('./access_log.php')?>

<!DOCTYPE html>
<!--[if IE 7 ]><html class="ie ie7 lte9 lte8 lte7" lang="en-US"><![endif]-->
<!--[if IE 8]><html class="ie ie8 lte9 lte8" lang="en-US">	<![endif]-->
<!--[if IE 9]><html class="ie ie9 lte9" lang="en-US"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="noIE" lang="ja">
<!--<![endif]-->
	<head>
		<title>競馬予想AI</title>

		<!-- meta -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no"/>
		<link rel="icon" href="./img/icon.png" type="image/vnd.microsoft.icon"/>
		<!-- google fonts -->
		<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=PT+Sans'>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Serif:regular,bold"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alegreya+Sans:regular,italic,bold,bolditalic"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nixie+One:regular,italic,bold,bolditalic"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alegreya+SC:regular,italic,bold,bolditalic"/>

		<script src="https://kit.fontawesome.com/befd99437d.js" crossorigin="anonymous"></script>
		
		<!-- css -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">	
		<link rel="stylesheet" href="assets/css/style.css" media="screen"/>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.js"></script>
			<script src="assets/js/respond.js"></script>
		<![endif]-->

		<!--[if IE 8]>
	    	<script src="assets/js/selectivizr.js"></script>
	    <![endif]-->
	</head>
	
	<body>
		<div id="drawer-right">
			<div class="cross text-right">
				<a class="toggleDrawer" href="#"><i class="fa fa-times-circle fa-2x"></i></a>
			</div>
			<h2>Navigate</h2>
			<nav>
				<ul class="nav nav-pills nav-stacked">
					<li>
						<a href="#wrapper"><i class="fa fa-home"></i> ホーム</a>
					</li>
					<li>
						<a href="#predict"><i class="fa fa-brain"></i> 予想レース</a>
					</li>
					<li>
						<a href="#race"><i class="fa fa-list-alt"></i> レース結果</a>
					</li>
					<li>
						<a href="#statistics"><i class="fa fa-chart-bar"></i> 結果統計</a>
					</li>
					<li>
						<a href="#system"><i class="fa fa-project-diagram"></i> システン構成</a>
					</li>
					<li>
						<a href="#app"><i class="fa fa-android"></i> アプリDL</a>
					</li>
					<li>
						<a href="#model"><i class="fa fa-calculator"></i> モデル訓練</a>
					</li>
					<li>
						<a href="#auto_buy"><i class="fa fa-money-bill-alt"></i> 馬券自動購入</a>
					</li>
					
				</ul>
			</nav>
			<div class="social" id="nav_sub">
				<h2>作品集、ミニゲーム</h2>
				<ul>
					<li><a href="https://aquakuma.xrea.jp/portfolio" target="_blank" ref="noopener noreferrer"><i class="fa fa-cubes fa-3x"></i></a></li>
					<li><a href="https://github.com/aquakuma" target="_blank" ref="noopener noreferrer"><i class="fa fa-git-square fa-3x"></i></a></li>
					<li><a href="christmas-derby" target="_blank" ref="noopener noreferrer"><i class="fa fa-horse-head fa-3x"></i></a></li>
				</ul>
			</div>
		</div><!-- #drawer-right -->

		<div id="wrapper">
			
			<div id="header" class="content-block header-wrapper">
				<div class="header-wrapper-inner">
					<section class="top clearfix">

						<div class="pull-right">
							<a class="toggleDrawer" href="#"><i class="fa fa-bars fa-2x"></i></a>
						</div>
					</section>
					<section class="center">
						<div class="slogan">
							競馬予想 AI
						</div>
						<div class="secondary-slogan">
							機械学習で競馬予想
						</div>
					</section>
					<section class="bottom">
						<a id="scrollToContent" href="#predict">
							<img src="assets/images/arrow_down.png">
						</a>
					</section>
				</div>
			</div><!-- header -->

			
			<div class="content-block" id="predict">
				<div class="container">
					<header class="block-heading cleafix">
						<h1>予想レース</h1>
						<p>レース30分前公開</p>
					</header>
					<section class="block-body" id="msg">
						<div id = "predict_table">
                    
						</div>

						<div class = "right_side">
							<div>
								<h2>競馬場現在天気</h2>
							</div>
							<table id="weather" class="weather" >
				
							</table>
						</div>

					</section>
				</div>

	
			</div><!-- #portfolio -->

			<div class="content-block" id="race">
				<div class="container">
					<header class="block-heading cleafix">
						<h1>過去レース</h1>
						<p>過去一年分レース結果</p>
					</header>
					<section class="block-body">


						<div id = "content" class = "race_div">
							<div class="select">
								<form action="" id="select_form">
									<select name="date" id = "date"  onchange="entryChange();">
										<!--<option disabled selected value></option>-->
							
									</select>　
									<div id = "place">
										<!--<input type="radio" name="place" onclick="main_content();"><span></span>-->
										
									</div>
								</form>
							</div>

							<table name = "content_table" class="race_table" id="race_table">
								<thead>
									<tr>
										<th>レース名</th>
										<th>Round</th>
										<th>発走</th>
										<th>頭数</th>
										<th>予想馬番</th>
										<th>オッズ</th>
										<th>信頼度</th>
										<th>信頼差</th>
										<th>閾値</th>
										<th>着順</th>
										<th>一着</th>
										<th>結果</th>
									</tr>
								</thead>
								<tbody id = "race_rows">
			
								</tbody>
							</table>
						</div>
					</section>
				</div>
			</div><!-- #portfolio -->


			<div class="content-block parallax" id="bg4">
				<div class="container text-center">
					<header class="block-heading cleafix">
						<h1></h1>
						<p></p>
					</header>
					<section class="block-body">
						<div class="row">
							<div class="col-md-4">
								<div class="service">
									<i class="fa fa-poll"></i>
									<h2>信頼度</h2>
									<p>馬が一着の確率です。</p>
									
								</div>
							</div>
							<div class="col-md-4">
								<div class="service">
									<i class="fa fa-minus-square"></i>
									<h2>信頼差</h2>
									<p>信頼度が一番高い馬と二番目高い馬の信頼度の差。</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="service">
									<i class="fa fa-horse"></i>
									<h2>閾値</h2>
									<p>計算式 <br>
									信頼差 * 2 + 信頼度 * オッズ * 1.2 <br>
									重みの決め方は前年度レースのシミュレーションの結果で決めます｡ <br>
									この閾値で馬券買うか買わないかで決めます。
								</p>

								</div>
							</div>
						</div>
					</section>
				</div>
			</div><!-- #services -->

			<div class="content-block" id="statistics">
				<div class="container">
					<header class="block-heading cleafix">
						<h1>結果統計</h1>
						<p>一年間内、閾値0.8以上のみ集計。</p>
					</header>
					<section class="block-body">
						
						<div class="row" id="statistics_row">
							<div class="col-sm-6">
								<h2>競馬場回収率</h2>
								<canvas id="chart_race_recovery"></canvas>
							</div>
							<div class="col-sm-6">
								<h2>閾値回収率</h2>
								<canvas id="chart_limen_recovery"></canvas>
							</div>
						</div>

						<div class="row" id="statistics_row">
							<div class="col-sm-6">
								<h2>競馬場勝率</h2>
								<canvas id="chart_win_rate"></canvas>
							</div>
							<div class="col-sm-6">
								<h2>閾値勝率</h2>
								<canvas id="chart_limen_win_rate"></canvas>
							</div>
						</div>
						
						<div class="row" id="statistics_row">
							<div class="col-sm-6">
								<h2>競馬場レース数</h2>
								<canvas id="chart_race_num"></canvas>
							</div>
							<div class="col-sm-6">
								<h2>閾値レース数</h2>
								<canvas id="chart_limen_race_num"></canvas>
							</div>
						</div>

					</section>
				</div>
			</div><!-- #blog -->



			<div class="content-block parallax" id="system">
				<div class="container text-center">
					<header class="block-heading cleafix">
						<h1>使用技術</h1>
						<p>言語、フレームワーク</p>
					</header>
					<section class="block-body">
						<div class="row">
							<div class="col-md-4">
								<div class="service">
									<i class="fa fa-python"></i>
									<h2>Python3</h2>
									<p>ライブラリ Seleniumでブラウザを操作し、10年分のデータをWEBスクレイピンで収集します。</p>
									<p>ライブラリ Pandasでデータを整形します。</p>
									
								</div>
							</div>
							<div class="col-md-4">
								<div class="service">
									<i class="fa fa-microsoft"></i>
									<h2>LightGBM</h2>
									<p>LightGBM（読み：ライト・ジービーエム）決定木アルゴリズムに基づいた勾配ブースティング（Gradient Boosting）の機械学習フレームワークです。LightGBMは米マイクロソフト社2016年にリリースされました。</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="service">
									<i class="fa fa-android"></i>
									<h2>Kotlin</h2>
									<p>App版はkotlinで作りました。WebAPI使って、DBサーバ内のデータを取得、アプリで現在の予想馬番が見れます。</p>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div><!-- #services -->


			<div class="content-block" id="blog">
				<div class="container">
					<header class="block-heading cleafix">
						<h1>システム構成</h1>
						<p>本サイトはAWS EC2でWEBサーバを構築しています。</p>
					</header>
					<section class="block-body">
						<img src="./img/system.png" alt="">
					</section>
				</div>
			</div><!-- #blog -->


			<div class="content-block parallax" id="app">
				<div class="container text-center">
					<h1>アプリ APK ダウンロード</h1>
					<a href="https://github.com/aquakuma/keiba-AI/releases/tag/app" target="_blank" ref="noopener noreferrer" class="btn btn-o-white btn-lg">Download</a>
				</div>
			</div><!-- #parallax -->


			<div class="content-block" id="app_content">
				<div class="container">
					<header class="block-heading cleafix">
						<h1>実際の画面</h1>
					</header>
					<section class="block-body">
						<div class="row">
							<div class="col-sm-6 app_content">
							<img src="./img/app1.jpg" alt="">
							</div>
							<div class="col-sm-6 app_content">
							<img src="./img/app2.jpg" alt="">
							</div>
						</div>
					</section>
				</div>
			</div><!-- #blog -->




			<div class="content-block parallax" id="model">
			<div class="container text-center">
					<header class="block-heading cleafix">
						<h1>モデル訓練</h1>
						<p>使用の特徴量、学習に使ったフレームワーク。</p>
					</header>
					<section class="block-body">
						<div class="row">
							<div class="col-md-4">
								<div class="service">
									<i class="fa fa-code-branch"></i>
									<h2>特徴量</h2>
									<p>天気、コース、距離、前５走までのレース情報、５代血統、騎手、調教師、タイム指数、本賞金など全部
										３００個以上の特徴量を使いました。<br>
										前走のデータ使うため、新馬戦は予想しません。中には本賞金は重要な影響があり、本賞金はレースのレベル直接に関係があり、そのレースの着順でその馬の強さを数値化できます。 <br>
										オッズと人気はあえて使わない、その理由は回収率が下がります。オッズ使うなら、勝率は上がるが、予想した馬は人気上位ばかりので、オッズ的にはあまり美味しくないです。
									</p>
									
								</div>
							</div>
							<div class="col-md-4">
								<div class="service">
									<i class="fa fa-cogs"></i>
									<h2>フレームワーク</h2>
									<p>最初はディープラーニングのKerasを使いましたが、パラメータの設定には複雑ので断念しました。その後マイクロソフト社のLightGBMを切り替えました。LightGBMはKaggleに人気なフレームワークで、ネット上も色んなリソースがあります。その上使いやすいし、訓練時間も短いし、とてもおすすめです。<br>
										そしてフレームワークOptunaを使ってハイパーパラメータを自動にチュウニングして、最低限のパラメータ設定すればいいです。</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="service">
									<i class="fa fas fa-hammer"></i>
									<h2>工夫した所</h2>
									<p>データの整形は一番力入れたところです。訓練にするため、全部のデータを数値型に変更しなければならないので、カテゴリ種類が少ない特徴量（例：天気、コース距離、コース種類）ならば、OneHotEncoderでいけますが、馬、騎手、調教師、血統ようなカテゴリの数がとても多い場合、Target Encoding（目的変数の平均値）で変更します。つまりこの場合は、馬、騎手、調教師の特徴量をそれぞれの勝率に変更することです。<br>
										私は勝率を計算の時、一つの罠にはまった、それは未来のデータを使いました。正しい計算はそのレース前のレースのみで勝率を計算することです。そのためデータの整形はとても大変です、多くの時間をデータの整形に費やしました。
									</p>
								</div>
							</div>

						</div>
					</section>
				</div>
			</div><!-- #parallax -->

			<div class="content-block" id="feature">
				<div class="container">
					<header class="block-heading cleafix">
						<h1>特徴量重要度</h1>
						<p>予想に使った特徴量は全部300個があります。下の図は重要度の高い上位20の特徴量です。</p>
					</header>
					<section class="block-body">
						<img src="./img/feature_importance.png" alt="">
					</section>
				</div>
			</div><!-- #blog -->


			<div class="content-block parallax" id="auto_buy">
				<div class="container text-center">
					<h1>馬券自動購入</h1>
					<a href="https://github.com/aquakuma/keiba-AI/blob/master/buy_script.ipynb" target="_blank" ref="noopener noreferrer" class="btn btn-o-white btn-lg">スクリプトDL</a>
				</div>
			</div><!-- #parallax -->

			<div class="content-block" id="auto_buy_content">
				<div class="container">
					<header class="block-heading cleafix">
						<h1>実際の動作</h1>
						<p>scheduleライブラリでレース５分前自動予測して、閾値が予め決めた数値以上であれば自動に馬券を購入します。</p>
					</header>
					<section class="block-body">
						<video src="video/demo.mp4" controls>
						</video>
					</section>
				</div>
			</div><!-- #blog -->



			<div class="content-block" id="footer">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 text-center">&copy; Copyright KeibaAI 2021</div>
					</div>
				</div>
			</div><!-- #footer -->


		</div><!--/#wrapper-->




		<script src="assets/js/jquery-2.1.3.min.js"></script>
		<script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/jquery.actual.min.js"></script>
		<script src="assets/js/jquery.scrollTo.min.js"></script>
		<script src="assets/js/script.js"></script>
		<script src="assets/js/main.js"></script>
		<script src="assets/js/graphic.js"></script>

	</body>
</html>
