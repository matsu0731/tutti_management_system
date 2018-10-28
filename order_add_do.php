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

							<?php require('dbconnect.php');

							session_start();

							if (!isset($_SESSION['order_add'])) {
								header('Location: order_add.php');
								exit();
							}

							?>

							<h2>追加注文完了</h2>

							<?php $item = $_SESSION['order_add']['item'];
							      $seat_num = $_SESSION['order_add']['seat_number'];
							 ?>

							<h3>お客様ID</h3>
							<?php
							$sql_customer = sprintf('SELECT customer_id FROM seat_status WHERE seat_number = %d', $seat_num);
              $recordSet = mysqli_query($db, $sql_customer);
              $table = mysqli_fetch_assoc($recordSet);
              $customer_id = $table['customer_id'];
							echo htmlspecialchars($customer_id, ENT_QUOTES);
							?>

							<h3>席番号</h3>
							<p></p><font size="10" color="#ff0000"><?php echo htmlspecialchars($seat_num, ENT_QUOTES); ?></font><p></p>

							<h3>ドリンク</h3>
							<ul>
							<li>コーヒー　100円： <?php echo $item[0]; ?>個</li>
							<li>紅茶　100円： <?php echo $item[1]; ?>個</li>
							<li>オレンジジュース　150円： <?php echo $item[2]; ?>個</li>
							</ul>

							<h3>ケーキ</h3>
							<ul>
							<li>チョコレートケーキ　150円： <?php echo $item[3]; ?>個</li>
							<li>アップルパイ　150円： <?php echo $item[4]; ?>個</li>
							<li>フルーツケーキ　200円： <?php echo $item[5]; ?>個</li>
							</ul>

							<?php

							#注文IDの繰り上げ
							$recordSet = mysqli_query($db, 'SELECT MAX(order_id) + 1 FROM history');
							$order_id= mysqli_fetch_assoc($recordSet);

							#座席情報更新
							$sql = sprintf('UPDATE seat_status SET status = 1, customer_id = "%d" WHERE seat_number = "%d"', $customer_id, $seat_num);
							mysqli_query($db, $sql) or die(mysqli_error($db));

							#注文内容のDB書き込み
							for ($i=1; $i<=6; $i++){
							  $sql = sprintf('INSERT INTO history SET order_id = "%d", customer_id = "%d", seat_num = "%d", item_id = "%d", quantity="%d", created= NOW() ',
							  mysqli_real_escape_string($db, $order_id['MAX(order_id) + 1']),
							  mysqli_real_escape_string($db, $customer_id),
							  mysqli_real_escape_string($db, $seat_num),
							  $i,
							  mysqli_real_escape_string($db, $item[$i - 1])
							  );
							  mysqli_query($db, $sql) or die(mysqli_error($db));
							}

							#stockの変更
							$sql_stock = sprintf('UPDATE items SET stock = stock - %d WHERE item_id=%d', $item[$i-1], $i);
							if ($item[$i-1] > 0) {
								mysqli_query($db, $sql_stock) or die (mysqli_error($db));
							}

							?>
						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
