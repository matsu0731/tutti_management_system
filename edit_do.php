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
		<meta http-equiv="refresh" content="3;URL=edit.php">
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">

		<!-- Content -->
			<div id="content">
				<div class="inner">

					<!-- Post -->
						<article class="box post post-excerpt">

							<?php require('dbconnect.php'); ?>

							<h2>注文内容修正</h2>
							<p>注文内容の修正が完了しました</p>

							<?php $item = $_POST['item'];
							      $order_id = $_POST['order_id'];
							      $customer_id = $_POST['customer_id'];
							      $seat_num = $_POST['seat_num'];
							 ?>

							 <h3>注文ID</h3>
							 <?php echo htmlspecialchars($order_id, ENT_QUOTES); ?>

							 <h3>お客様ID</h3>
							 <?php echo htmlspecialchars($customer_id, ENT_QUOTES); ?>

							 <h3>席番号</h3>
							 <?php echo htmlspecialchars($seat_num, ENT_QUOTES); ?>

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
							#変更前の個数を取得
							$sql_original = sprintf('SELECT quantity FROM history WHERE order_id = %d',
							mysqli_real_escape_string($db, $order_id)
							);
							$Quantity = mysqli_query($db, $sql_original);

							#注文内容のDB書き込み
							for ($i=0; $i<=5; $i++){

							  $sql = sprintf('UPDATE history SET quantity = %d, modified = NOW() WHERE order_id = %d AND item_id = %d',
							  mysqli_real_escape_string($db, $item[$i]),
							  mysqli_real_escape_string($db, $order_id),
							  mysqli_real_escape_string($db, $i+1)
							  );
							  mysqli_query($db, $sql) or die(mysqli_error($db));

								#stockの変更
								$table_quantity = mysqli_fetch_assoc($Quantity);
								$diff = $item[$i]-$table_quantity['quantity'];
								$sql_stock = sprintf('UPDATE items SET stock = stock + %d WHERE item_id=%d', $diff, $i+1);
								mysqli_query($db, $sql_stock) or die (mysqli_error($db));

								}

							?>
						</article>

					</div>
				</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
