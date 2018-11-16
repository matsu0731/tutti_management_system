<?php
if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
		$seat_id = $_REQUEST['id'];
}else{
		header('Location: waiter.php');
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
        <style>
        .yellow{
 background-color: yellow;
}
.green{
 background-color: green;
}
.blue{
 background-color: blue;
}
.red{
 background-color: red;
}
.black{
 background-color: black;
}
.parent {
 width: 100%;
 height: 80px;
 display: flex;
}
.child {
 width: 20%;
 text-align:center;
 font-size:400%;
 height: 80px;
 line-height: 80px;
 vertical-align:middle;
}
.child2 {
 width: 25%;
 text-align:center;
 font-size:100%;
 height: 10%;
 line-height: 80px;
 vertical-align:middle;
}
form{
    margin:0px;
}
        </style>
		<meta charset="utf-8" />
		<meta http-equiv="refresh" content="8" >
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
							?>

							<h2>配達・調理状況</h2>

							<?php
                            $items = array();
                            $record_itm = mysqli_query($db, 'SELECT item_name, item_id FROM items');
                            while($item = mysqli_fetch_assoc($record_itm)){
                                $items[$item['item_id']] = $item['item_name'];
                            }
                            $record_itm->free();
                            if($_POST["del"] != ''){
                                //print($_POST["item_name"]);
                                //print(array_search($_POST["item_name"], $items));
                                $id = array_search($_POST["item_name"], $items);
                                switch($_POST["del"]){
                                    case "done":$update = $db->prepare('UPDATE history SET delivery_status=1 WHERE order_id = ? AND item_id = ?');
                                    $update->bind_param("ii", $_POST["order_id"], $id);
                                    $update->execute();
                                    $update->close();
                                    break;
                                    case "undone":$update = $db->prepare('UPDATE history SET delivery_status=0 WHERE order_id = ? AND item_id = ?');
                                    $update->bind_param("ii", $_POST["order_id"], array_search($_POST["item_name"], $items));
                                    $update->execute();
                                    $update->close();
                                    break;
                                    case "alldone":$update = $db->prepare('UPDATE history SET delivery_status=1 WHERE order_id = ?');
                                    $update->bind_param("i", $_POST["order_id"]);
                                    $update->execute();
                                    $update->close();
                                    break;
                                    case "allundone":$update = $db->prepare('UPDATE history SET delivery_status=0 WHERE order_id = ?');
                                    $update->bind_param("i", $_POST["order_id"]);
                                    $update->execute();
                                    $update->close();
                                    break;
                                }
                                print("<h3>配達状況を更新しました</h3>");
                            }
                            $cnt = array();
                            $record_cus = $db->prepare('SELECT customer_id FROM seat_status WHERE seat_number = ?');
                            $record_cus->bind_param("i", $seat_id);
                            $record_cus->execute();
                            $record_cus->bind_result($customer_id);
                            $record_cus->fetch();
                            $record_cus->close();
                            //print($customer_id);
                            $record_ord = $db->prepare('SELECT order_id FROM history WHERE customer_id = ?');
                            $record_ord->bind_param("i", $customer_id);
                            $record_ord->execute();
                            $record_ord->bind_result($var);
                            $order_id = array();
                            $cnt['ord'] = 0;
                            while($record_ord->fetch()){
                                $array = array($var);
                                if(in_array($var, $order_id)){
                                    continue;
                                }
                                $order_id = array_merge($order_id, $array);
                                $cnt['ord']++;
                            }
                            $record_ord->close();
                            //print_r($order_id);
                            $order = array();
                            for($i=0;$i<$cnt['ord'];$i++){
                                $record_his = $db->prepare('SELECT item_id, quantity, delivery_status, cooking_status FROM history WHERE order_id=?');
                                $record_his->bind_param("i", $order_id[$i]);
                                $record_his->execute();
                                $record_his->bind_result($item_id, $quantity, $d_status, $c_status);
                                while($record_his->fetch()){
                                    if($quantity!=0){
                                        switch($c_status){
                                            case 0:$c_status = "未調理";
                                            break;
                                            case 1:$c_status = "調理完了";
                                            break;
                                        }
                                        switch($d_status){
                                            case 0:$d_status = "未配達";
                                            break;
                                            case 1:$d_status = "配達完了";
                                            break;
                                        }
                                        $array = array($item_id, $quantity, $d_status, $c_status);
                                        $order[$i][] = $array;
                                        $cnt['itm'][$i]++;
                                    }
                                }
                                $record_his->close();
                            }
                            //print_r($order);

                            print("<h3>席番号</h3>");
                            print("<h2>" . $seat_id . "</h2>");
                            //席番号横に追加注文位置変更
                                                        //print("<h3>席番号</h3>");
                            ?>
                            <!--
                            <form action="added_order.php" method="post">
                            <input type="hidden" name="customer_id" value="<?php //print(htmlspecialchars($customed_id)); ?>" >
                            <input type="submit" value="追加注文">
                            </form>
                            -->
                            <?php
                            for($i=0;$i<$cnt['ord'];$i++){
                                print("<h3>注文ID</h3>");
																print("<h2>" . $order_id[$i] . "</h2>");?>
															<button type="button" onclick="location.href='edit.php?order_id=<?php print(htmlspecialchars($order_id[$i], ENT_QUOTES));?>'">注文内容編集</button>
                                <?php //注文番号ごとにバックグラウンドカラー変更
                                print("<table width=\"100%\"><tr>
                                <th scope=\"col\">商品名</th>
                                <th scope=\"col\">数量</th>
                                <th scope=\"col\">配達状況</th>
                                <th scope=\"col\">配達確認</th>
                              </tr>");
                              $order[$i]["uncookedflag"]="調理完了";
                              //<th scope=\"col\">調理状況</th>
                              for($j=0;$j<$cnt['itm'][$i];$j++){
                              ?>
                              <tr>
                                <td><?php print(htmlspecialchars($items[$order[$i][$j][0]])); ?></td>
                                <td><?php print(htmlspecialchars($order[$i][$j][1])); ?></td>
                                <td><?php print(htmlspecialchars($order[$i][$j][2])); ?></td>
                                <td>
                                <?php
                                if($order[$i][$j][3]=="未調理"){
                                   print("未調理です");
                                   $order[$i]["uncookedflag"]="未調理";
                                }else if($order[$i][$j][3]=="調理完了"){
                                    if($order[$i][$j][2]=="未配達"){
                                        print("<form action=\"\" method=\"post\">
                                        <input type=\"hidden\" name=\"del\" value=\"done\" >
                                        <input type=\"hidden\" name=\"item_name\" value=\"" . htmlspecialchars($items[$order[$i][$j][0]]) . "\" >
                                        <input type=\"hidden\" name=\"order_id\" value=" . $order_id[$i] . " >
                                        <input type=\"submit\" value=\"配達完了\"></form>");
                                    }else{
                                        print("<form action=\"\" method=\"post\">
                                        <input type=\"hidden\" name=\"del\" value=\"undone\" >
                                        <input type=\"hidden\" name=\"item_name\" value=\"" . htmlspecialchars($items[$order[$i][$j][0]]) . "\" >
                                        <input type=\"hidden\" name=\"order_id\" value=" . $order_id[$i] . " >
                                        <input type=\"submit\" value=\"配達取消\"></form>");
                                    }
                                }
                                //未調理なのに配達完了した場合はエラー表示
                                 ?>
                                </td>
                              </tr>
                            <?php
                              }
                              print("</table>");
                              ?>
                              <div class="parent">
                              <?php if($order[$i]["uncookedflag"]=="未調理"){?>
                              <h3 style="font-size:12px">いずれかが未調理のため全配達完了できません。</h3>
                              <?php }else if($order[$i]["uncookedflag"]=="調理完了"){?>
                              <div class="child2">
                              <form action="" method="post">
                              <input type="hidden" name="del" value="alldone" >
                              <input type="hidden" name="order_id" value="<?php print($order_id[$i]); ?>" >
                              <input type="submit" value="全配達完了"></form>
                              </div>
                              <?php }?>
                              <div class="child2">
                              <form action="" method="post">
                              <input type="hidden" name="del" value="allundone" >
                              <input type="hidden" name="order_id" value="<?php print($order_id[$i]); ?>" >
                              <input type="submit" value="全配達取消"></form>
                              </div>
                              </div>
                            <?php
                            }
                            ?>
                            <!--<table width="100%">-->
						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
