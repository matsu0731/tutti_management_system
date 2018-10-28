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

							$sql_items = sprintf('SELECT item_id, item_name, value, stock FROM items');
							$Items = mysqli_query($db, $sql_items);
							#$table_items = mysqli_fetch_assoc($Items);

							?>

							<h2>商品管理</h2>

							<h3>商品一覧</h3>
							<form id="itemUpdate" name="itemUpdate" method="post" action="items.php">
							<table width="100%">
								<tr>
									<th scope="col">商品ID</th>
									<th scope="col">商品名</th>
									<th scope="col">単価（円）</th>
									<th scope="col">在庫（個）</th>
									<th scope="col">変更</th>
								</tr>
							<?php
							while($table_items = mysqli_fetch_assoc($Items)){
							?>
								<tr>
									<td><?php print(htmlspecialchars($table_items['item_id'])); ?></td>
									<td><?php print(htmlspecialchars($table_items['item_name'])); ?></td>
									<td><?php print(htmlspecialchars($table_items['value'])); ?></td>
									<td><?php print(htmlspecialchars($table_items['stock'])); ?></td>
									<td><a href="items_update.php?id=<?php print(htmlspecialchars($table_items['item_id']));?>">編集</a></td>
								</tr>
							<?php
							}
							?>
							</table>
							</form>

						</article>

				</div>
			</div>

		<?php include('./frame.php'); ?>

	</body>
</html>
