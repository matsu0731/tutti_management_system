<?php session_start();
			if (!empty($_POST)) {
				//エラー項目の確認
				if ($_POST['item_name'] == '') {
					$error['item_name'] = 'name';
				}
				if ($_POST['value'] == '') {
					$error['value'] = 'value';
				}
				if (!is_numeric($_POST['value'])) {
					$error['value'] = 'value_n';
				}
				if ($_POST['stock'] == '') {
					$error['stock'] = 'stock';
				}
				if (!is_numeric($_POST['stock'])) {
					$error['stock'] = 'stock_n';
				}

				if (empty($error)) {
					$_SESSION['items_update'] = $_POST;
					$_SESSION['items_update']['flag']="edited";
					header('Location: items_update.php?id='.$_POST['item_id'].'');
					exit();
				}
			}

			if (!isset($_SESSION['update_flag'])) {
				header('Location: items.php');
				exit();
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

							$id = $_REQUEST['id'];
							$sql_items = sprintf('SELECT * FROM items WHERE item_id = %d', mysqli_real_escape_string($db, $id));
							$Items = mysqli_query($db, $sql_items);
							$table_items = mysqli_fetch_assoc($Items);

							?>

							<h2>商品情報編集</h2>

							<!--エラー表示-->
								<?php if ($error['item_name'] == 'name'): ?>
									<p class="error">*商品名を入力してください</p>
								<?php endif; ?>

								<?php if ($error['value'] == 'value'): ?>
									<p class="error">*価格を入力してください</p>
								<?php endif; ?>

								<?php if ($error['value'] == 'value_n'): ?>
									<p class="error">*価格は数値を入力してください</p>
								<?php endif; ?>

								<?php if ($error['stock'] == 'stock'): ?>
									<p class="error">*在庫を入力してください</p>
								<?php endif; ?>

								<?php if ($error['stock'] == 'stock_n'): ?>
									<p class="error">*在庫は数値を入力してください</p>
								<?php endif; ?>

							<?php
							if ($_SESSION['items_update']['flag']=="edited") {
								$sql_update = sprintf('UPDATE items SET item_name="%s", type=%d, value=%d, stock=%d WHERE item_id=%d',
									mysqli_real_escape_string($db, $_SESSION['items_update']['item_name']),
									mysqli_real_escape_string($db, $_SESSION['items_update']['type']),
									mysqli_real_escape_string($db, $_SESSION['items_update']['value']),
									mysqli_real_escape_string($db, $_SESSION['items_update']['stock']),
									mysqli_real_escape_string($db, $_SESSION['items_update']['item_id'])
								);
								mysqli_query($db, $sql_update) or die(mysqli_error($db));
								$Items = mysqli_query($db, $sql_items);
								$table_items = mysqli_fetch_assoc($Items);
								print("内容が変更されました");
								unset($_SESSION['items_update']);
								unset($_SESSION['update_flag']);
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
											<label for="type">種別</label>
										</dt>
										<dd>
											<select name="type" id="type">
												<option value="0" <?php if($table_items['type']==0) print("selected")?>>ドリンク</option>
												<option value="1" <?php if($table_items['type']==1) print("selected")?>>ケーキ</option>
												<option value="2" <?php if($table_items['type']==2) print("selected")?>>割引</option>
										  </select>
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

							</form>

						</article>

				</div>
			</div>

		<?php include('./frame.php'); ?>

	</body>
</html>
