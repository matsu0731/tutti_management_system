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

							$id = $_REQUEST['id'];
							$sql_items = sprintf('SELECT * FROM items WHERE item_id = %d', mysqli_real_escape_string($db, $id));
							$Items = mysqli_query($db, $sql_items);
							$table_items = mysqli_fetch_assoc($Items);

							?>

							<h2>商品情報編集</h2>

							<?php
							if ($_POST['flag']=="edited") {
								$sql_update = sprintf('UPDATE items SET item_name="%s", value=%d, stock=%d WHERE item_id=%d',
									mysqli_real_escape_string($db, $_POST['item_name']),
									mysqli_real_escape_string($db, $_POST['value']),
									mysqli_real_escape_string($db, $_POST['stock']),
									mysqli_real_escape_string($db, $_POST['item_id'])
								);
								mysqli_query($db, $sql_update) or die(mysqli_error($db));
								$Items = mysqli_query($db, $sql_items);
								$table_items = mysqli_fetch_assoc($Items);
								print("内容が変更されました");
							}
							 ?>

							<form id="itemUpdate" name="itemUpdate" method="post" action="items_update.php?id=<?php print(htmlspecialchars($table_items['item_id']));?>">

									<dl>
										<dt>
											<label for="item_id">商品ID</label>
										</dt>
										<dd>
											<?php print(htmlspecialchars($table_items['item_id'])); ?>
										</dd>
										<dt>
											<label for="item_name">商品名</label>
										</dt>
										<dd>
											<input name="item_name" type="text" id="item_name" size="35" maxlength="255" value = "<?php print(htmlspecialchars($table_items['item_name'], ENT_QUOTES)); ?>" />
										</dd>
										<dt>
											<label for="value">単価（円）</label>
										</dt>
										<dd>
											<input name="value" type="text" id="value" size="10" maxlength="10" value = "<?php print(htmlspecialchars($table_items['value'], ENT_QUOTES)); ?>" />
										</dd>
										<dt>
											<label for="item_name">在庫（個）</label>
										</dt>
										<dd>
											<input name="stock" type="text" id="stock" size="10" maxlength="10" value = "<?php print(htmlspecialchars($table_items['stock'], ENT_QUOTES)); ?>" />
										</dd>
									</dl>
									<input type="submit" value="変更" />
									<input type="hidden" name="item_id" value="<?php print(htmlspecialchars($table_items['item_id'], ENT_QUOTES)) ?>" />
									<input type="hidden" name="flag" value="edited" />

							</form>

						</article>

				</div>
			</div>

		<?php include('./frame.php'); ?>

	</body>
</html>
