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

							<?php
							require('dbconnect.php');

							session_start();
							if (!empty($_POST)) {
								//エラー項目の確認
								if ($_POST['seat_number'] == '') {
									$error['seat'] = 'seat';
								}

								if ($_POST['dummy'] == '') {
									$error['seat'] = 'seat';
								}

								if (empty($error)) {
									$_SESSION['payment'] = $_POST;
									header('Location: payment_confirm.php');
									exit();
								}
							}
							?>

							<h2>精算</h2>
							<form action="" method="post">
							<!--エラー表示-->
								<?php if ($error['seat'] == 'seat'): ?>
									<p class="error">*席番号が指定されていません</p>
								<?php endif; ?>

							<p>席番号を選択してください</p>

							<select name="seat_number" id="seat_number">
							<?php

							#客のいる座席を取得
							$recordSet = mysqli_query($db, 'SELECT seat_number FROM seat_status WHERE status = 1');
							$num = mysqli_num_rows($recordSet);

							for ($i = 0; $i<$num; $i++) {
							  $table = mysqli_fetch_assoc($recordSet);
							  print('<option value="' . $table['seat_number'] . '">' . $table['seat_number'] . '</option>');
							}
							?></select>
							<p></p>
							<input type="submit" value="内容確認" />
							<input type="hidden" name="dummy" value="dummy" />
							</form>
						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
