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

							<h2>初期化</h2>

							<p>注文履歴と着席情報が初期化されました</p>

							<?php
							require('dbconnect.php');
							$sql_clearhistory = sprintf('DELETE FROM history');
							$sql_increment = sprintf('ALTER TABLE history AUTO_INCREMENT = 1');
							$sql_inithistory = sprintf('INSERT INTO history VALUES (0,0,0,0,0,0,0,0,0,2018-11-01,2018-11-01)');
							$sql_zeroseat = sprintf('UPDATE seat_status SET status = 0, customer_id =0');
							mysqli_query($db, $sql_clearhistory) or die (mysqli_error($db));
							mysqli_query($db, $sql_increment) or die (mysqli_error($db));
							mysqli_query($db, $sql_inithistory) or die (mysqli_error($db));
							mysqli_query($db, $sql_zeroseat) or die (mysqli_error($db));
							?>

						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
