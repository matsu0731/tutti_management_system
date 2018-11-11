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
		<meta http-equiv="refresh" content="3;URL=order_add.php">
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">

		<!-- Content -->
			<div id="content">
				<div class="inner">

					<!-- Post -->
						<article class="box post post-excerpt">

							<?php require('dbconnect.php'); ?>

							<h2>注文完了</h2>

							<?php
							session_start();

							if (!isset($_SESSION['order_add'])) {
								header('Location: order_add.php');
								exit();
							}

							$drink_ordered = $_SESSION['order_add']['drink'];
							$food_ordered = $_SESSION['order_add']['food'];
							$seat_num = $_SESSION['order_add']['seat_number'];
							 ?>

							<h3>お客様ID</h3>
							<?php
							$sql_customer = sprintf('SELECT customer_id FROM seat_status WHERE seat_number = %d', mysqli_real_escape_string($db, $seat_num));
							$recordSet = mysqli_query($db, $sql_customer);
							$customer_id = mysqli_fetch_assoc($recordSet);
							echo htmlspecialchars($customer_id['customer_id'], ENT_QUOTES);
							?>

							<h3>席番号</h3>
							<p></p><font size="10" color="#ff0000"><?php echo htmlspecialchars($seat_num, ENT_QUOTES); ?></font><p></p>

							<?php #商品リスト取得
							$sql_drink = sprintf('SELECT * FROM items WHERE type = 0');
							$sql_food = sprintf('SELECT * FROM items WHERE type = 1');
							$drinkSet = mysqli_query($db, $sql_drink);
							$foodSet = mysqli_query($db, $sql_food);
							$drinkcount = 0;
							$foodcount = 0;

							#注文IDの繰り上げ
							$recordSet = mysqli_query($db, 'SELECT MAX(order_id) + 1 FROM history');
							$order_id= mysqli_fetch_assoc($recordSet);
							?>

							<h3>ドリンク</h3>
							<ul>
								<?php while($item = mysqli_fetch_assoc($drinkSet)) {
									if($item['item_name']!="") {
									$display = sprintf('%s　%d円：%d個',
															htmlspecialchars($item['item_name'], ENT_QUOTES),
															htmlspecialchars($item['value'], ENT_QUOTES),
															htmlspecialchars($drink_ordered[$drinkcount], ENT_QUOTES));
									$sql = sprintf('INSERT INTO history SET order_id = "%d", customer_id = "%d", seat_num = "%d", item_id = "%d", value = "%d", quantity="%d", created= NOW() ',
									mysqli_real_escape_string($db, $order_id['MAX(order_id) + 1']),
									mysqli_real_escape_string($db, $customer_id['customer_id']),
									mysqli_real_escape_string($db, $seat_num),
									mysqli_real_escape_string($db, $item['item_id']),
									mysqli_real_escape_string($db, $item['value']),
									mysqli_real_escape_string($db, $drink_ordered[$drinkcount])
									);
									mysqli_query($db, $sql) or die(mysqli_error($db));

									#stockの変更
									$sql_stock = sprintf('UPDATE items SET stock = stock - %d WHERE item_id=%d', $drink_ordered[$drinkcount], mysqli_real_escape_string($db, $item['item_id']));
									if ($drink_ordered[$drinkcount] > 0) {
										mysqli_query($db, $sql_stock) or die (mysqli_error($db));
									}
									?>

								<li><?php print($display); ?></li>
								<?php $drinkcount++; }
							} ?>

							</ul>

							<h3>ケーキ</h3>
							<ul>
							<?php while($item = mysqli_fetch_assoc($foodSet)) {
								if($item['item_name']!="") {
								$display = sprintf('%s　%d円：%d個',
														htmlspecialchars($item['item_name'], ENT_QUOTES),
														htmlspecialchars($item['value'], ENT_QUOTES),
														htmlspecialchars($food_ordered[$foodcount], ENT_QUOTES));
								$sql = sprintf('INSERT INTO history SET order_id = "%d", customer_id = "%d", seat_num = "%d", item_id = "%d", value = "%d", quantity="%d", created= NOW() ',
								mysqli_real_escape_string($db, $order_id['MAX(order_id) + 1']),
								mysqli_real_escape_string($db, $customer_id['customer_id']),
								mysqli_real_escape_string($db, $seat_num),
								mysqli_real_escape_string($db, $item['item_id']),
								mysqli_real_escape_string($db, $item['value']),
								mysqli_real_escape_string($db, $food_ordered[$foodcount])
								);
								mysqli_query($db, $sql) or die(mysqli_error($db));

								#stockの変更
								$sql_stock = sprintf('UPDATE items SET stock = stock - %d WHERE item_id=%d', $food_ordered[$foodcount], mysqli_real_escape_string($db, $item['item_id']));
								if ($food_ordered[$foodcount] > 0) {
									mysqli_query($db, $sql_stock) or die (mysqli_error($db));
								}
								?>
							<li><?php print($display); ?></li>
							<?php $foodcount++; }
						} ?>
					</ul>
						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
