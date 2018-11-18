<!DOCTYPE HTML>
<!--
	Striped by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Tutti Management System</title>
		<meta http-equiv="refresh" content="8" >
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
							if(isset($_POST['update'])) {
								$sql2 = sprintf('UPDATE history SET cooking_status = 1 WHERE order_id = "%d"', $_POST['update']);
								mysqli_query($db, $sql2) or die(mysqli_error($db));
								$_POST['update']= NULL;
							}

							$sql1 = sprintf('SELECT order_id, seat_num FROM history WHERE cooking_status = 0 AND item_id = 1 ORDER BY history_id DESC');
							$recordSet1 = mysqli_query($db, $sql1);

							?>

							<h2>新着注文一覧</h2>

							<?php
							while ($table1 = mysqli_fetch_assoc($recordSet1)) {
								$order_id = mysqli_real_escape_string($db, $table1['order_id']);
							?>

									<div class="box30">
    								<div class="box-title">注文ID：<?php print(htmlspecialchars($table1['order_id'])); ?> | 席番号：<?php print(htmlspecialchars($table1['seat_num'])); ?></div>
										<table width="100%">
											<tr>
												<th scope="col">商品名</th>
												<th scope="col">数量</th>
											</tr>

										<?php
										$sql = sprintf('SELECT m.item_name, m.value, m.value * i.quantity AS calc, i.* FROM items m, history i WHERE m.item_id = i.item_id AND i.order_id = "%d" AND i.cooking_status = 0 AND m.type != 2 ORDER BY item_id', $table1['order_id']);
										$recordSet = mysqli_query($db, $sql);
										while ($table = mysqli_fetch_assoc($recordSet)) {
											if ($table['quantity']>0) {
											?>
											<tr>
												<td><?php print(htmlspecialchars($table['item_name'])); ?></td>
												<td><?php print(htmlspecialchars($table['quantity'])); ?></td>
											</tr>
										<?php
											}
										}?>

										</table>

											<form action="orderviewer.php" method="post">
    										<input type="submit" value="調理完了" />
												<input type="hidden" name="update" value="<?php echo htmlspecialchars($order_id); ?>" >
											</form>

									</div>

								<?php
							}
								?>

						</article>

					</div>
					</div>

				<?php include('./frame.php');?>

	</body>

</html>
