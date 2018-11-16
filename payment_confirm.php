<?php session_start();
			if (!isset($_SESSION['payment'])) {
				header('Location: payment.php');
				exit();
			}

			if (!empty($_POST)) {
				if (empty($error)) {
					$_SESSION['payment_do'] = $_POST;
					unset($_SESSION['payment']);
					header('Location: payment_do.php');
					exit();
				}
			}
?>
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
							$flag = 0;
							$drinkcount = 0;
							?>

							<h2>精算内容確認</h2>

							<form action="" method="post">

							<h3>お客様ID</h3>
							<?php
							$sql = sprintf('SELECT customer_id FROM seat_status WHERE seat_number = "%d" ', $_SESSION['payment']['seat_number']);
							$recordSet = mysqli_query($db, $sql);
							$table = mysqli_fetch_assoc($recordSet);
							echo htmlspecialchars($table['customer_id']);
							?>

							<input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($table['customer_id']); ?>" >

							<h3>席番号</h3>
							<p></p>
							<p><font size="10" color="#ff0000"><?php echo htmlspecialchars($_SESSION['payment']['seat_number']); ?></font></p>
							<input type="hidden" name="seat_number" value="<?php echo htmlspecialchars($_SESSION['payment']['seat_number']); ?>" >

							<h3>注文内容</h3>
							<table width="100%">
							  <tr>
							    <th scope="col">注文ID</th>
							    <th scope="col">商品名</th>
									<th scope="col">種別</th>
							    <th scope="col">単価</th>
							    <th scope="col">数量</th>
							    <th scope="col">小計</th>
							  </tr>
							<?php

							$sql = sprintf('SELECT m.item_name, i.value, m.type, i.value * i.quantity AS calc, i.* FROM items m, history i WHERE m.item_id = i.item_id AND i.customer_id = "%d" ORDER BY history_id DESC', $table['customer_id']);
							$recordSet = mysqli_query($db, $sql);
							$sql1 = sprintf('SELECT SUM(i.value * i.quantity) AS sum FROM items m, history i WHERE m.item_id = i.item_id AND i.customer_id = "%d" ORDER BY history_id DESC', $table['customer_id']);
							$recordSet1 = mysqli_query($db, $sql1);

							while ($table = mysqli_fetch_assoc($recordSet)){
							  if ($table['quantity']>0) {
							?>
							  <tr>
							    <td><?php print(htmlspecialchars($table['order_id'])); ?></td>
							    <td><?php print(htmlspecialchars($table['item_name'])); ?></td>
									<td><?php if ($table['type'] == 0){$flag=1; print("ドリンク");}
														if ($table['type'] == 1){print("ケーキ");}?>
									</td>
							    <td><?php print(htmlspecialchars($table['value'])); ?></td>
							    <td><?php print(htmlspecialchars($table['quantity'])); ?></td>
							    <td><?php print(htmlspecialchars($table['calc'])); ?>円</td>
							  </tr>
							<?php
							  }
								if($flag == 1){$drinkcount++; $flag=0;}
							}?>
							<?php #割引表示
							$discount=($drinkcount-1)*50;
							if ($drinkcount > 1) {print('
							<tr>
								<td></td><td>ドリンクおかわり</td><td>割引</td><td>50円引き</td><td>'.htmlspecialchars($drinkcount-1, ENT_QUOTES).'回</td>
								<td><font size="5" color="#ff0000">'.htmlspecialchars($discount, ENT_QUOTES).'円引き</font></td>
							</tr>
							<input type="hidden" name="discount" value="'.htmlspecialchars($drinkcount-1, ENT_QUOTES). '" >
							');
						}?>

							</table>

							<h3>合計金額</h3>
							<p></p>
							<p><font size="10" color="#ff0000"><?php
							$table = mysqli_fetch_assoc($recordSet1);
							print(htmlspecialchars($table['sum']-$discount, ENT_QUOTES));
							?>
							円<br />
							</font></p>
							<p></p>
							<input type="submit" value="精算確定" />
							</form>
						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
