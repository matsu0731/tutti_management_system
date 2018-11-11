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
							$page = $_REQUEST['page'];
							if ($page == '') {
								$page = 1;
							}
							$page = max($page, 1);

							//最終ページを取得
							$sql = 'SELECT COUNT(*) AS cnt FROM history';
							$recordSet = mysqli_query($db, $sql);
							$table = mysqli_fetch_assoc($recordSet);
							$maxPage = ceil($table['cnt'] / 60);
							$page = min($page, $maxPage);

							$start = ($page - 1)* 10;
							$recordSet = mysqli_query($db, 'SELECT m.history_id, m.order_id, m.customer_id, m.seat_num, i.item_name, i.type, m.value, m.quantity, m.created, m.modified, m.payment_status
							                                FROM history m, items i
																							WHERE m.item_id = i.item_id
																							ORDER BY m.order_id DESC
																							LIMIT ' . $start . ',50
							                                ');
							?>

							<h2>注文履歴</h2>

							<table width="100%">
							  <tr>
							    <th scope="col">注文ID</th>
							    <th scope="col">お客様ID</th>
							    <th scope="col">席番号</th>
									<th scope="col">商品名</th>
									<th scope="col">種別</th>
									<th scope="col">単価</th>
									<th scope="col">数量</th>
							    <!-- <th scope="col">注文時間</th> -->
							    <th scope="col">注文時間</th>
									<th scope="col">精算</th>
							  </tr>
							<?php
							while ($table = mysqli_fetch_assoc($recordSet)){
								if( $table['order_id']!=0 && $table['quantity']>0) {
							?>
							  <tr>
							    <td><?php print(htmlspecialchars($table['order_id'])); ?></td>
							    <td><?php print(htmlspecialchars($table['customer_id'])); ?></td>
							    <td><?php print(htmlspecialchars($table['seat_num'])); ?></td>
							    <td><?php print(htmlspecialchars($table['item_name'])); ?></td>
							    <td><?php if($table['type']==0){print("ドリンク");} ?>
											<?php if($table['type']==1){print("ケーキ");} ?>
											<?php if($table['type']==2){print("割引");} ?>
									</td>
							    <td><?php print(htmlspecialchars($table['value'])); ?></td>
									<td><?php print(htmlspecialchars($table['quantity'])); ?></td>
							    <td><?php print(htmlspecialchars($table['created'])); ?></td>
									<td><?php if($table['payment_status']==0){print("未済");} ?>
											<?php if($table['payment_status']==1){print("済");} ?>
									</td>
							  </tr>
							<?php
								}
							}
							?>
							</table>

							<ul class="paging">
								<?php if ($page > 1) {
								 ?>
								<li><a href="history.php?page=<?php print($page - 1); ?>">前ページ</a></li>
								<?php
							} else {
								?>
								<li>前ページ</li>
								<?php
								}
								 ?>
								<?php
								if ($page < $maxPage) {
									?>
								<li><a href="history.php?page=<?php print($page + 1); ?>">次ページ</a></li>
								<?php
							} else {
								?>
								<li>次ページ</li>
								<?php
							}
								 ?>
							</ul>

						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
