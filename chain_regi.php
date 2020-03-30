<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>チェーン登録画面</title>
</head>
<body>
  <p>チェーン</p>
  <?php require 'menu.php';?>
<?php
    $pdo=new PDO('mysql:host=mysql624.db.sakura.ne.jp;dbname=keiosfchiyoshi_supermarket;charset=utf8', 'keiosfchiyoshi', '17-eiflew');?>

<?php
if (isset($_REQUEST['command'])) {
  date_default_timezone_set('Asia/Tokyo');
        $timestampdate = date("Y-m-d");
        $timestamp=date("Y-m-d H:i:s");
        $insertchain=$pdo->prepare("insert into chain values(null,?,?)");
        $confirmchain=$pdo->prepare("select * from chain where name=?");
        $confirmchain->execute([$_REQUEST['chain_name']]);
        if(empty($confirmchain->fetchAll())){
          print('ok');
        if($insertchain->execute([$_REQUEST['chain_name'],$timestamp])){
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
 <form action="./chain_regi.php" method="post">
   <input type="hidden" name="command" value="upload">
 <input type="text" name="chain_name">
 <input type="submit" value="登録">
</form>
<table>
  <tr>
    <th>id</th>
    <th>チェーン名</th>
  </tr>
  <?php
  foreach ($pdo->query('select * from chain') as $row) { ?>
  <tr>
    <td><?php echo $row['id'];?></td>
    <td><a href="price_regi.php?chain_id=<?php echo $row['id'];?>"><?php echo $row['name'];?></a></td>
  </tr>
<?php } ?>
</table>
</body>
</html>
