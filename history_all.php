<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"
/>
<title>注文履歴 - Tutti Management System</title>
</head>

<body>
<!-- ここにプログラムを記述します -->
<?php
require('dbconnect.php');
$recordSet = mysqli_query($db, 'SELECT m.item_name, i.* FROM items m, history i WHERE m.item_id = i.item_id ORDER BY history_id DESC');
$page = $_REQUEST['page'];
?>
<a href="index.php">トップ画面</a>
<a href="order.php">注文</a>
<h2>注文履歴</h2>

<table width="100%">
  <tr>
    <th scope="col">ID</th>
    <th scope="col">注文ID</th>
    <th scope="col">お客様ID</th>
    <th scope="col">商品名</th>
    <th scope="col">数量</th>
    <th scope="col">注文時間</th>
    <th scope="col">変更時間</th>
  </tr>
<?php
while ($table = mysqli_fetch_assoc($recordSet)){
?>
  <tr>
    <td><?php print(htmlspecialchars($table['history_id'])); ?></td>
    <td><?php print(htmlspecialchars($table['order_id'])); ?></td>
    <td><?php print(htmlspecialchars($table['customer_id'])); ?></td>
    <td><?php print(htmlspecialchars($table['item_name'])); ?></td>
    <td><?php print(htmlspecialchars($table['quantity'])); ?></td>
    <td><?php print(htmlspecialchars($table['created'])); ?></td>
    <td><?php print(htmlspecialchars($table['modified'])); ?></td>
  </tr>
<?php
}
?>
</table>
</body>
</html>
