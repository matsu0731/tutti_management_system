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
		<meta http-equiv="refresh" content="3;URL=status_confirm.php?id=<?php print(htmlspecialchars($_POST['seat_num'], ENT_QUOTES)); ?>">
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
							if (!isset($_SESSION['edit'])) {
								header('Location: index.php');
								exit();
							}
							 ?>

							<h2>注文内容修正</h2>
							<p>注文内容の修正が完了しました</p>

							<?php $drink = $_POST['drink'];
										$food = $_POST['food'];
							      $order_id = $_POST['order_id'];
							      $customer_id = $_POST['customer_id'];
							      $seat_num = $_POST['seat_num'];

										$sql_drink = sprintf('SELECT i.item_name, i.item_id, m.value, m.quantity FROM history m, items i WHERE m.item_id = i.item_id AND i.type = 0 AND m.order_id = "%d"', mysqli_real_escape_string($db, $order_id));
										$sql_food = sprintf('SELECT i.item_name, i.item_id, m.value, m.quantity FROM history m, items i WHERE m.item_id = i.item_id AND i.type = 1 AND m.order_id = "%d"', mysqli_real_escape_string($db, $order_id));
										$drinkSet = mysqli_query($db, $sql_drink);
										$foodSet = mysqli_query($db, $sql_food);
										$drinkcount = 0;
										$foodcount = 0;
										unset($_SESSION['edit']);
							 ?>

							 <h3>注文ID</h3>
							 <?php echo htmlspecialchars($order_id, ENT_QUOTES); ?>

							 <h3>お客様ID</h3>
							 <?php echo htmlspecialchars($customer_id, ENT_QUOTES); ?>

							 <h3>席番号</h3>
							 <?php echo htmlspecialchars($seat_num, ENT_QUOTES); ?>

							 <h3>ドリンク</h3>
 							<ul>
 								<?php while($item = mysqli_fetch_assoc($drinkSet)) {

 									$display = sprintf('%s　%d円：%d個',
 															htmlspecialchars($item['item_name'], ENT_QUOTES),
 															htmlspecialchars($item['value'], ENT_QUOTES),
 															htmlspecialchars($drink[$drinkcount], ENT_QUOTES));

									$sql = sprintf('UPDATE history SET quantity="%d" WHERE order_id = "%d" AND item_id = "%d"',
 								 				mysqli_real_escape_string($db, $drink[$drinkcount]),
 												mysqli_real_escape_string($db, $order_id),
 												mysqli_real_escape_string($db, $item['item_id']));
 									mysqli_query($db, $sql) or die(mysqli_error($db));

 									#stockの変更
									$difference = $drink[$drinkcount] - $item['quantity'];
 									$sql_stock = sprintf('UPDATE items SET stock = stock - %d WHERE item_id=%d', $difference, mysqli_real_escape_string($db, $item['item_id']));
 									if ($difference != 0) {
 										mysqli_query($db, $sql_stock) or die (mysqli_error($db));
 									}
 									?>

 								<li><?php print($display); ?></li>
 								<?php $drinkcount++;
 							} ?>

 							</ul>

							<h3>ケーキ</h3>
						 <ul>
							 <?php while($item = mysqli_fetch_assoc($foodSet)) {

								 $display = sprintf('%s　%d円：%d個',
														 htmlspecialchars($item['item_name'], ENT_QUOTES),
														 htmlspecialchars($item['value'], ENT_QUOTES),
														 htmlspecialchars($food[$foodcount], ENT_QUOTES));

								 $sql = sprintf('UPDATE history SET quantity="%d" WHERE order_id = "%d" AND item_id = "%d"',
								 				mysqli_real_escape_string($db, $food[$foodcount]),
												mysqli_real_escape_string($db, $order_id),
												mysqli_real_escape_string($db, $item['item_id']));
								 mysqli_query($db, $sql) or die(mysqli_error($db));

								 #stockの変更
								 $difference = $food[$foodcount] - $item['quantity'];
								 $sql_stock = sprintf('UPDATE items SET stock = stock - %d WHERE item_id=%d', $difference, mysqli_real_escape_string($db, $item['item_id']));
								 if ($difference != 0) {
									 mysqli_query($db, $sql_stock) or die (mysqli_error($db));
								 }
								 ?>

							 <li><?php print($display); ?></li>
							 <?php $foodcount++;
						 } ?>
						</article>

					</div>
				</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
