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

							session_start();

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
									$_SESSION['items_add'] = $_POST;
									header('Location: items_add.php');
									exit();
								}

							}

							$Id = mysqli_query($db, 'SELECT MAX(item_id)+1 AS nextid FROM items');
							$nextid = mysqli_fetch_assoc($Id);

							?>

							<h2>商品追加</h2>

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
							if ($_SESSION['items_add']['flag']=="added") {
								$sql_update = sprintf('INSERT INTO items VALUES (%d, "%s", %d, %d, %d)',
									mysqli_real_escape_string($db, $_SESSION['items_add']['item_id']),
									mysqli_real_escape_string($db, $_SESSION['items_add']['item_name']),
									mysqli_real_escape_string($db, $_SESSION['items_add']['type']),
									mysqli_real_escape_string($db, $_SESSION['items_add']['value']),
									mysqli_real_escape_string($db, $_SESSION['items_add']['stock'])
								);
								mysqli_query($db, $sql_update) or die(mysqli_error($db));

								//print("商品が追加されました");
								unset($_SESSION['items_update']);
								unset($_SESSION['items_add']);
								header('Location: items.php');
							} else {
							print('
							<form id="itemAdd" name="Add" method="post" action="items_add.php">

									<dl>
										<dt>
											<label for="item_id">商品ID</label>
										</dt>
										<dd>
											'.htmlspecialchars($nextid['nextid'], ENT_QUOTES).'
										</dd>
										<dt>
											<label for="item_name">商品名</label>
										</dt>
										<dd>
											<input name="item_name" type="text" id="item_name" size="35" maxlength="255" value = "" />
										</dd>
										<dt>
											<label for="type">種別</label>
										</dt>
										<dd>
											<select name="type" id="type">

												<option value="0">ドリンク</option>
												<option value="1">ケーキ</option>
												<option value="2">割引</option>
										  </select>
										</dd>
										<dt>
											<label for="value">単価（円）</label>
										</dt>
										<dd>
											<input name="value" type="text" id="value" size="10" maxlength="10" value = "" />
										</dd>
										<dt>
											<label for="item_name">在庫（個）</label>
										</dt>
										<dd>
											<input name="stock" type="text" id="stock" size="10" maxlength="10" value = "" />
										</dd>
									</dl>
									<input type="submit" value="追加" />
									<input type="hidden" name="item_id" value="'.htmlspecialchars($nextid['nextid'], ENT_QUOTES).'" />
									<input type="hidden" name="flag" value="added" />

							</form>
							'); }?>

							<?php
							if (!isset($_SESSION['update_flag'])) {
								header('Location: items.php');
								exit();
							}
							?>

						</article>

				</div>
			</div>

		<?php include('./frame.php'); ?>

	</body>
</html>
