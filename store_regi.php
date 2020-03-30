<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>店舗登録画面</title>
</head>
<body>
  <p>店舗</p>
  <?php require 'menu.php';?>
<?php
    $pdo=new PDO('mysql:host=mysql624.db.sakura.ne.jp;dbname=keiosfchiyoshi_supermarket;charset=utf8', 'keiosfchiyoshi', '17-eiflew');?>

<?php
if (isset($_REQUEST['command'])) {
  date_default_timezone_set('Asia/Tokyo');
        $timestampdate = date("Y-m-d");
        $timestamp=date("Y-m-d H:i:s");
        $insertstore=$pdo->prepare("insert into store values(null,?,?,?,?,?,?,?,?)");
        $confirmstore=$pdo->prepare("select * from chain where name=? and chain_id=?");
        $confirmstore->execute([$_REQUEST['store_name'],$_REQUEST['chain']]);
        if(empty($confirmstore->fetchAll())){
          print($_REQUEST['chain']);
        if($insertstore->execute([$_REQUEST['chain'],$_REQUEST['store_name'],$_REQUEST['address'],$_REQUEST['business_hours'],$_REQUEST['access'],$_REQUEST['payment'],$_REQUEST['point_system'],$timestamp])){
          ?>
          <b>成功</b>
          <?php
        }
        else{
          ?>
          <b>失敗</b>
          <?php
        }
        }
        else{
          print('すでにあります');
        }
      }
 ?>
 <form action="./store_regi.php" method="post">
   <select name="chain">
   <option value="">チェーンを選択</option>
   <?php
    foreach ($pdo->query('select * from chain') as $row) {?>
   <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
 <?php } ?>
 </select>
   <input type="hidden" name="command" value="upload"><br>
 店名<input type="text" name="store_name">店<br>
住所<input type="text" name="address"><br>
営業時間<input type="text" name="business_hours"><br>
アクセス<input type="text" name="access" size="40"><br>
決済方法<input type="text" name="payment" size="80"><br>
ポイントシステム<textarea name="point_system" cols="50" rows="3"></textarea><br>
 <input type="submit" value="登録">
</form>
<table>
  <tr>
    <th>id</th>
    <th>チェーン名</th>
    <th>店名</th>
    <th>住所</th>
    <th>営業時間</th>
    <th>アクセス</th>
    <th>決済方法</th>
    <th>ポイントシステム</th>
  </tr>
  <?php
  foreach ($pdo->query('select chain.name as chain_name,store.id as store_id,store.name as store_name,address,access,business_hours,access,payment,point_system from store join chain on chain.id=store.chain_id') as $row) { ?>
  <tr>
    <td><?php echo $row['store_id'];?></td>
    <td><?php echo $row['chain_name'];?></td>
    <td><?php echo $row['store_name'];?>店</td>
    <td><?php echo $row['address'];?></td>
    <td><?php echo $row['business_hours'];?></td>
    <td><?php echo $row['access'];?></td>
    <td><?php echo $row['payment'];?></td>
    <td><?php echo $row['point_system'];?></td>
  </tr>
<?php } ?>
</table>
</body>
</html>
