<!DOCTYPE HTML>
<!--
	Striped by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Tutti Management System</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">

		<!-- Content -->
			<div id="content">
				<div class="inner">

					<!-- Post -->
						<article class="box post post-excerpt">
							<!-- <header>
								<!--
									Note: Titles and subtitles will wrap automatically when necessary, so don't worry
									if they get too long. You can also remove the <p> entirely if you don't
									need a subtitle.

								<h2><a href="#">Tutti Management System</a></h2>
							</header> -->

							<h2>Tutti Management System</h2>
							<span id="view_clock"></span>

							<script type="text/javascript">
							timerID = setInterval('clock()',500); //0.5秒毎にclock()を実行

							function clock() {
								document.getElementById("view_clock").innerHTML = getNow();
							}

							function getNow() {
								var now = new Date();
								var year = now.getFullYear();
								var mon = now.getMonth()+1; //１を足すこと
								var day = now.getDate();
								var hour = now.getHours();
								var min = now.getMinutes();
								var sec = now.getSeconds();

								//出力用
								var s = year + "年" + mon + "月" + day + "日" + hour + "時" + min + "分" + sec + "秒";
								return s;
							}
							</script>

							<a href="#" class="image featured"><img src="images/komaba.png" alt="" /></a>
							<h3>お役立ちリンク集</h3>
							<p>
							<a href="https://www.komabasai.net/69/pre/">駒場祭公式HP</a><br/>
							<a href="https://www.u-tokyo.ac.jp/ja/about/campus-guide/map02_01.html#">駒場キャンパスマップ</a><br/>
							<a href="http://www.chor.jp/#">コールアカデミー公式HP</a><br/>
							<a href="http://letizia.web.fc2.com/index.html#">コーロレティツィア公式HP</a>
							</p>
						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
