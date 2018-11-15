<?php session_start(); ?>
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

							if (!empty($_POST)) {
								//エラー項目の確認
								$drink = $_POST['drink'];
								$food = $_POST['food'];
								if (array_sum($drink)+array_sum($food)==0) {
									$error['item'] = 'zero';
								}

								if ($_POST['seat_number'] == '') {
									$error['seat'] = 'seat';
								}

								if (empty($error)) {
									$_SESSION['order_add'] = $_POST;
									header('Location: order_add_do.php');
									exit();
								}
							}
							?>

							<h2>追加注文</h2>
							<!--エラー表示-->

							<?php if ($error['seat'] == 'seat'): ?>
								<p class="error">*席番号が指定されていません</p>
							<?php endif; ?>
							<?php if ($error['item'] == 'zero'): ?>
								<p class="error">*少なくとも一つ以上商品を注文してください</p>
							<?php endif; ?>

							<form action="" method="post">

							<h3>席番号</h3>

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

							<?php #商品リスト取得
							$sql_drink = sprintf('SELECT * FROM items WHERE type = 0');
							$sql_food = sprintf('SELECT * FROM items WHERE type = 1');
							$drinkSet = mysqli_query($db, $sql_drink);
							$foodSet = mysqli_query($db, $sql_food);
							?>
							<div class="box_order">
							<div class="box-title">ドリンク</div>
							<ul>
								<?php while($item = mysqli_fetch_assoc($drinkSet)) {
									if($item['item_name']!="") {
									$display = sprintf('%s　%d円 （在庫：%d個）',
															htmlspecialchars($item['item_name'], ENT_QUOTES),
															htmlspecialchars($item['value'], ENT_QUOTES),
															htmlspecialchars($item['stock'], ENT_QUOTES));?>
							<li><?php print($display); ?>
							  <select name="drink[]" id="drink[]">
							  <?php
							  for ($i = 0; $i<=5; $i++) {
							    print('<option value="' . $i . '">' . $i . '個</option>');
							  }
							  ?></select></li>
							<? }
							} ?>
							</ul>
						</div>
						<div class="box_order">
							<div class="box-title">ケーキ</div>
							<ul>
								<?php while($item = mysqli_fetch_assoc($foodSet)) {
									if($item['item_name']!="") {
									$display = sprintf('%s　%d円 （在庫：%d個）',
															htmlspecialchars($item['item_name'], ENT_QUOTES),
															htmlspecialchars($item['value'], ENT_QUOTES),
															htmlspecialchars($item['stock'], ENT_QUOTES));?>
							<li><?php print($display); ?>
							  <select name="food[]" id="food[]">
							  <?php
							  for ($i = 0; $i<=5; $i++) {
							    print('<option value="' . $i . '">' . $i . '個</option>');
							  }
							  ?></select></li>
							<? }
							} ?>
							</ul>
						</div>
							<p></p>
							<input type="submit" value="追加注文" />
							</form>
						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
