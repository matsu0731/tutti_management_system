<?php session_start();?>
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

							$_SESSION['edit'] = 'edit';

							$order_id = $_REQUEST['order_id'];
							$sql_data = sprintf('SELECT * FROM history WHERE order_id = "%d"', mysqli_real_escape_string($db, $order_id));
							$sql_drink = sprintf('SELECT i.item_name, m.value, m.quantity FROM history m, items i WHERE m.item_id = i.item_id AND i.type = 0 AND m.order_id = "%d"', mysqli_real_escape_string($db, $order_id));
							$sql_food = sprintf('SELECT i.item_name, m.value, m.quantity FROM history m, items i WHERE m.item_id = i.item_id AND i.type = 1 AND m.order_id = "%d"', mysqli_real_escape_string($db, $order_id));
							$dataSet = mysqli_query($db, $sql_data);
							$data = mysqli_fetch_assoc($dataSet);
							$drinkSet = mysqli_query($db, $sql_drink);
							$foodSet = mysqli_query($db, $sql_food);
							?>

							<h2>注文内容修正</h2>

							<form id="frmUpdate" name="frmUpdate" action="edit_do.php" method="post">

							<h3>注文ID</h3>
							<?php echo htmlspecialchars($data['order_id'], ENT_QUOTES); ?>
							<input type="hidden" name="order_id" value="<?php echo htmlspecialchars($data['order_id'], ENT_QUOTES); ?>" >

							<h3>お客様ID</h3>
							<?php echo htmlspecialchars($data['customer_id'], ENT_QUOTES); ?>
							<input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($data['customer_id'], ENT_QUOTES); ?>" >

							<h3>席番号</h3>
							<?php echo htmlspecialchars($data['seat_num'], ENT_QUOTES); ?>
							<input type="hidden" name="seat_num" value="<?php echo htmlspecialchars($data['seat_num'], ENT_QUOTES); ?>" >

							<h3>ドリンク</h3>
							<ul>
								<?php while($item = mysqli_fetch_assoc($drinkSet)) {
									$display = sprintf('%s　%d円',
															htmlspecialchars($item['item_name'], ENT_QUOTES),
															htmlspecialchars($item['value'], ENT_QUOTES));?>
							<li><?php print($display); ?>
							  <select name="drink[]" id="drink[]">
							  <?php
								for ($i = 0; $i<=5; $i++) {
									if ($i == $item['quantity']) {
										print('<option value="' . $i . '" selected>' . $i . '個</option>');
									} else {
										print('<option value="' . $i . '">' . $i . '個</option>');
									}
								}
							  ?></select>
							</li>
							<?}?>
							</ul>

							<h3>ケーキ</h3>
							<ul>
								<?php while($item = mysqli_fetch_assoc($foodSet)) {
									$display = sprintf('%s　%d円',
															htmlspecialchars($item['item_name'], ENT_QUOTES),
															htmlspecialchars($item['value'], ENT_QUOTES));?>
							<li><?php print($display); ?>
							  <select name="food[]" id="food[]">
							  <?php
								for ($i = 0; $i<=5; $i++) {
									if ($i == $item['quantity']) {
										print('<option value="' . $i . '" selected>' . $i . '個</option>');
									} else {
										print('<option value="' . $i . '">' . $i . '個</option>');
									}
								}
							  ?></select></li>
							<?} ?>
							</ul>

							<input type="submit" value="注文内容修正" />
							</form>
						</article>

					</div>
				</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
