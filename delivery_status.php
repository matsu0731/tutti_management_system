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
.white{
 background-color: white;
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
        </style>
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
							?>

							<h2>追加注文・配達状況管理</h2>

							<?php
                            $status_data = array();
                            $count = 0;
                            $recordset = mysqli_query($db, 'SELECT customer_id, status FROM seat_status');
                            while($record = mysqli_fetch_assoc($recordset)){
                                $count++;
                                if($record['status'] == 0){
                                    $array = array('-1');
                                    //echo 0;
                                }else{
                                    $record_his = $db->prepare('SELECT cooking_status, delivery_status FROM history WHERE customer_id = ?');
                                    $record_his->bind_param("i", $record["customer_id"]);
                                    $record_his->execute();
                                    $record_his->bind_result($c_status, $d_status);
                                    $var = 0;
                                    while($record_his->fetch()){
                                        $array = array($c_status, $d_status);
                                        //print_r($array);
                                        if($array[0]==0&&$array[1]==1){
                                            $var = 3;
                                            break;
                                        }else if($array[0]==1&&$array[1]==0){
                                            if($var==2) continue;
                                            $var = 1;
                                        }else if($array[0]==0&&$array[1]==0){
                                            $var = 2;
                                        }
                                    }
                                    $record_his->close();
                                    $array = array($var);
                                }
                                $status_data = array_merge($status_data, $array);
                            }
                            //echo $count;
                            //print_r($status_data);
                            print("\n");
                            ?>
                            <!--<table width="100%">-->
  <!--<tr>
    <th scope="col">ID</th>
    <th scope="col">注文ID</th>
    <th scope="col">お客様ID</th>
    <th scope="col">商品名</th>
    <th scope="col">数量</th>
    <th scope="col">注文時間</th>
    <th scope="col">変更時間</th>
  </tr>-->
<?php
for($i=1;$i<=$count;$i++){
    switch($status_data[$i-1]){
        case -1:
    }
}
//while ($nums = mysqli_fetch_assoc($recordSet)){
  //  echo $num['seat_number'];
//if(!in_array($num['seat_number'], $seat_ids)){
  //  $seat_ids[] = $num['seat_number'];
//}else{
  //  continue;
//}
//print_r($seat_ids);
?>
<?php for($i=1;$i<=$count;$i++){
    if($i%5==1){
        print("<div class=\"parent\">");
    }
    switch($status_data[$i-1]){
        case 3:print("<div class=\"child black\">");
        break;
        case 2:print("<div class=\"child red\">");
        break;
        case 1:print("<div class=\"child yellow\">");
        break;
        case 0:print("<div class=\"child green\">");
        break;
        case -1:print("<div class=\"child white\">");
        //文字色薄く
        break;
    }
    //注文ないところはリンク削除
    //枠を作成
    if($status_data[$i-1]==-1){
        print($i);
        print("</div>");
    }else{
        print("<a href=\"status_confirm.php?id=" . $i . "\">" . $i . "</a></div>");
    }
    if($i%5==0){
        print("</div>");
    }
}
?>
<p>
  <tr>
    <td><?php //print(htmlspecialchars($table['history_id'])); ?></td>
    <td><?php //print(htmlspecialchars($table['order_id'])); ?></td>
    <td><?php //print(htmlspecialchars($table['customer_id'])); ?></td>
    <td><?php //print(htmlspecialchars($table['item_name'])); ?></td>
    <td><?php //print(htmlspecialchars($table['quantity'])); ?></td>
    <td><?php //print(htmlspecialchars($table['created'])); ?></td>
    <td><?php //print(htmlspecialchars($table['modified'])); ?></td>
  </tr>
  <?php
//}
?>
</table>
						</article>

					</div>
					</div>

						<?php include('./frame.php'); ?>

	</body>
</html>
