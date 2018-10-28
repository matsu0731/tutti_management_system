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

							#print($_POST['date']);

							if ($_POST['date']=="") {
							$sql_sum = sprintf('SELECT SUM(m.value * i.quantity) AS sum FROM items m, history i
							WHERE m.item_id = i.item_id AND i.payment_status = 1');
							$sql_sales = sprintf('SELECT m.item_name, m.value, SUM(i.quantity) AS quantity, m.value * SUM(i.quantity) AS calc FROM items m, history i
							WHERE m.item_id = i.item_id AND i.payment_status = 1 GROUP BY m.item_name');
						  } else {
							$sql_sum = sprintf('SELECT SUM(m.value * i.quantity) AS sum FROM items m, history i
							WHERE m.item_id = i.item_id AND i.payment_status = 1 AND DATE(i.created) = "%s"', $_POST['date']);
							$sql_sales = sprintf('SELECT m.item_name, m.value, SUM(i.quantity) AS quantity, m.value * SUM(i.quantity) AS calc FROM items m, history i
							WHERE m.item_id = i.item_id AND i.payment_status = 1 AND DATE(i.created) = "%s" GROUP BY m.item_name', $_POST['date']);
						  }

							$Sum = mysqli_query($db, $sql_sum);
							$Sales = mysqli_query($db, $sql_sales);
							$table_sum = mysqli_fetch_assoc($Sum);

							#売上のあった日付を取得
							$Dates = mysqli_query($db, 'SELECT DATE(created) AS dates FROM history WHERE payment_status = 1 GROUP BY dates');
							$num = mysqli_num_rows($Dates);
							?>

							<h2>売上情報</h2>

							<form action="sales.php" method="post">
								<select name="date" id="date" onchange="submit(this.form)">
									<option value="">全日程</option>
									<?php
									for ($i = 0; $i<$num; $i++) {
									  $table_dates = mysqli_fetch_assoc($Dates);
										if($table_dates['dates'] == $_POST['date']) {
									  print('<option value="' . $table_dates['dates'] . '" selected>' . $table_dates['dates'] . '</option>');
										} else {
											print('<option value="' . $table_dates['dates'] . '">' . $table_dates['dates'] . '</option>');
										}
									}
									?></select>
							</form>


							<h3>売り上げ合計：<?php print(htmlspecialchars($table_sum['sum'])); ?>円</h3>

							<h3>内訳</h3>
							<table width="100%">
								<tr>
									<th scope="col">商品名</th>
									<th scope="col">単価</th>
									<th scope="col">数量</th>
									<th scope="col">小計</th>
								</tr>
							<?php
							while($table_sales = mysqli_fetch_assoc($Sales)){
							?>
								<tr>
									<td><?php print(htmlspecialchars($table_sales['item_name'])); ?></td>
									<td><?php print(htmlspecialchars($table_sales['value'])); ?></td>
									<td><?php print(htmlspecialchars($table_sales['quantity'])); ?></td>
									<td><?php print(htmlspecialchars($table_sales['calc'])); ?></td>
								</tr>
							<?php
							}
							?>
							</table>

						</article>

				</div>
			</div>

		<?php include('./frame.php'); ?>

	</body>
</html>
