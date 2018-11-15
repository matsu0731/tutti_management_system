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
		<meta http-equiv="refresh" content="3;URL=payment.php">
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">

		<!-- Content -->
			<div id="content">
				<div class="inner">

					<!-- Post -->
						<article class="box post post-excerpt">

							<?php require('dbconnect.php');

							if (!isset($_SESSION['payment_do'])) {
								header('Location: payment_confirm.php');
								exit();
							}
							 ?>

							<h2>精算完了</h2>

							<p>
							  席番号：<?php echo htmlspecialchars($_SESSION['payment_do']['seat_number']); ?>
							  　お客様ID：<?php echo htmlspecialchars($_SESSION['payment_do']['customer_id']); ?>
							  の精算が完了しました．
							</p>

							<?php

							#割引のための注文IDの繰り上げ
							$recordSet = mysqli_query($db, 'SELECT MAX(order_id) + 1 FROM history');
							$order_id= mysqli_fetch_assoc($recordSet);

							#割引処理（割引という商品をhistoryに挿入）
							$discounttypeSet = mysqli_query($db, 'SELECT * FROM items WHERE type = 2');
							$discount= mysqli_fetch_assoc($discounttypeSet);

							$sql_discount = sprintf('INSERT INTO history
																			SET order_id = "%d", customer_id = "%d", seat_num = "%d", item_id = "%d", value="%d", quantity="%d", created= NOW()',
																			mysqli_real_escape_string($db, $order_id['MAX(order_id) + 1']),
																			mysqli_real_escape_string($db, $_SESSION['payment_do']['customer_id']),
																			mysqli_real_escape_string($db, $_SESSION['payment_do']['seat_number']),
																			mysqli_real_escape_string($db, $discount['item_id']),
																			mysqli_real_escape_string($db, $discount['value']),
																			mysqli_real_escape_string($db, $_SESSION['payment_do']['discount']));
							mysqli_query($db, $sql_discount) or die(mysqli_error($db));

							#座席情報更新
							$sql1 = sprintf('UPDATE seat_status SET status = 0, customer_id = 0 WHERE seat_number = "%d"',
							        mysqli_real_escape_string($db, $_SESSION['payment_do']['seat_number'])
							      );
							$sql2 = sprintf('UPDATE history SET payment_status = 1, cooking_status = 1 WHERE customer_id = "%d"',
										  mysqli_real_escape_string($db, $_SESSION['payment_do']['customer_id'])
										 );
							mysqli_query($db, $sql1) or die(mysqli_error($db));
							mysqli_query($db, $sql2) or die(mysqli_error($db));
							unset($_SESSION['payment_do']);

							?>

						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
