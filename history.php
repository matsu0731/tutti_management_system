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
							$recordSet = mysqli_query($db, 'SELECT order_id, customer_id, seat_num,
							                                max(CASE WHEN item_id = 1 THEN quantity ELSE 0 END) AS "coffee",
							                                max(CASE WHEN item_id = 2 THEN quantity ELSE 0 END) AS "tea",
							                                max(CASE WHEN item_id = 3 THEN quantity ELSE 0 END) AS "orange",
							                                max(CASE WHEN item_id = 4 THEN quantity ELSE 0 END) AS "choco",
							                                max(CASE WHEN item_id = 5 THEN quantity ELSE 0 END) AS "apple",
							                                max(CASE WHEN item_id = 6 THEN quantity ELSE 0 END) AS "fruit",
							                                created, modified
							                                FROM history
							                                GROUP BY order_id DESC
																							LIMIT ' . $start . ',10
							                                ');
							?>

							<h2>注文履歴</h2>

							<table width="100%">
							  <tr>
							    <th scope="col">注文ID</th>
							    <th scope="col">お客様ID</th>
							    <th scope="col">席番号</th>
							    <th scope="col">コーヒー</th>
							    <th scope="col">紅茶</th>
							    <th scope="col">オレンジジュース</th>
							    <th scope="col">チョコレートケーキ</th>
							    <th scope="col">アップルパイ</th>
							    <th scope="col">フルーツケーキ</th>
							    <!-- <th scope="col">注文時間</th> -->
							    <th scope="col">変更時間</th>
							    <th scope="col">修正</th>
							  </tr>
							<?php
							while ($table = mysqli_fetch_assoc($recordSet)){
								if( $table['order_id']!=0) {
							?>
							  <tr>
							    <td><?php print(htmlspecialchars($table['order_id'])); ?></td>
							    <td><?php print(htmlspecialchars($table['customer_id'])); ?></td>
							    <td><?php print(htmlspecialchars($table['seat_num'])); ?></td>
							    <td><?php print(htmlspecialchars($table['coffee'])); ?></td>
							    <td><?php print(htmlspecialchars($table['tea'])); ?></td>
							    <td><?php print(htmlspecialchars($table['orange'])); ?></td>
							    <td><?php print(htmlspecialchars($table['choco'])); ?></td>
							    <td><?php print(htmlspecialchars($table['apple'])); ?></td>
							    <td><?php print(htmlspecialchars($table['fruit'])); ?></td>
							    <!-- <td><?php #print(htmlspecialchars($table['created'])); ?></td> -->
							    <td><?php print(htmlspecialchars($table['modified'])); ?></td>
							    <td><a href="edit.php?order_id=<?php print(htmlspecialchars($table['order_id'])); ?>">修正</a></td>
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
