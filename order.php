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
								$item = $_POST['item'];
								if ($item[0] == 0 && $item[1] == 0 && $item[2] == 0 && $item[3] == 0 && $item[4] == 0 && $item[5] == 0) {
									$error['item'] = 'zero';
								}

								if ($_POST['seat_number'] == '') {
									$error['seat'] = 'seat';
								}

								if (empty($error)) {
									$_SESSION['order'] = $_POST;
									header('Location: order_do.php');
									exit();
								}

							}
							?>

							<h2>新規注文</h2>

							<form action="" method="post">

							<!-- <h3>お客様ID</h3>
							<p></p>
							 <p><font size="8">
							<?php
							$recordSet = mysqli_query($db, 'SELECT MAX(customer_id) + 1 FROM history');
							$table = mysqli_fetch_assoc($recordSet);
							print(htmlspecialchars($table['MAX(customer_id) + 1'], ENT_QUOTES));
							?>
						</font></p>-->
						<!--エラー表示-->
							<?php if ($error['seat'] == 'seat'): ?>
								<p class="error">*満席です</p>
							<?php endif; ?>

							<?php if ($error['item'] == 'zero'): ?>
								<p class="error">*少なくとも一つ以上商品を注文してください</p>
							<?php endif; ?>


							<h3>席番号</h3>
							<select name="seat_number" id="seat_number">
							<?php

							#空いている座席を取得
							$recordSet = mysqli_query($db, 'SELECT seat_number FROM seat_status WHERE status = 0');
							$num = mysqli_num_rows($recordSet);

							for ($i = 0; $i<$num; $i++) {
							  $table = mysqli_fetch_assoc($recordSet);
							  print('<option value="' . $table['seat_number'] . '">' . $table['seat_number'] . '</option>');
							}
							?></select>


							<p></p>

							<h3>ドリンク</h3>
							<ul>
							<li>コーヒー　100円
							  <select name="item[]" id="item[]">
							  <?php
							  for ($i = 0; $i<=5; $i++) {
							    print('<option value="' . $i . '">' . $i . '個</option>');
							  }
							  ?></select></li>
							</li>
							<li>紅茶　100円
							  <select name="item[]" id="item[]">
							  <?php
							  for ($i = 0; $i<=5; $i++) {
							    print('<option value="' . $i . '">' . $i . '個</option>');
							  }
							  ?></select></li>
							</li>
							<li>オレンジジュース　150円
							  <select name="item[]" id="item[]">
							  <?php
							  for ($i = 0; $i<=5; $i++) {
							    print('<option value="' . $i . '">' . $i . '個</option>');
							  }
							  ?></select></li>
							</li>
							</ul>

							<h3>ケーキ</h3>
							<ul>
							<li>チョコレートケーキ　150円
							  <select name="item[]" id="item[]">
							  <?php
							  for ($i = 0; $i<=5; $i++) {
							    print('<option value="' . $i . '">' . $i . '個</option>');
							  }
							  ?></select></li>
							<li>アップルパイ　150円
							  <select name="item[]" id="item[]">
							  <?php
							  for ($i = 0; $i<=5; $i++) {
							    print('<option value="' . $i . '">' . $i . '個</option>');
							  }
							  ?></select></li>
							</li>
							<li>フルーツケーキ　200円
							  <select name="item[]" id="item[]">
							  <?php
							  for ($i = 0; $i<=5; $i++) {
							    print('<option value="' . $i . '">' . $i . '個</option>');
							  }
							  ?></select></li>
							</li>
							</ul>

							<input type="submit" value="新規注文" />
							</form>
						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
