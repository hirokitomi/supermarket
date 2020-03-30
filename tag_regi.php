<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>タグ登録画面</title>
</head>
<body>
  <p>タグ</p>
  <?php require 'menu.php';?>
<?php
    $pdo=new PDO('mysql:host=mysql624.db.sakura.ne.jp;dbname=keiosfchiyoshi_supermarket;charset=utf8', 'keiosfchiyoshi', '17-eiflew');?>

<?php
if (isset($_REQUEST['command'])) {
  date_default_timezone_set('Asia/Tokyo');
        $timestampdate = date("Y-m-d");
        $timestamp=date("Y-m-d H:i:s");
        $insertfoods=$pdo->prepare("insert into tag values(null,?,?)");
        $confirmfoods=$pdo->prepare("select * from tag where name=?");
        $confirmfoods->execute([$_REQUEST['tag']]);
        if(empty($confirmfoods->fetchAll())){
        if($insertfoods->execute([$_REQUEST['tag'],$timestamp])){
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
          echo 'すでにあります';
        }
      }
 ?>
 <form action="./tag_regi.php" method="post">
   <input type="hidden" name="command" value="upload">
 タグ名：<input type="text" name="tag">
 <input type="submit" value="登録">
</form>
<table>
  <tr>
    <th>id</th>
    <th>タグ名</th>
  </tr>
  <?php
  foreach ($pdo->query('select * from tag') as $row) { ?>
  <tr>
    <td><?php echo $row['id'];?></td>
    <td><?php echo $row['name'];?></td>
  </tr>
<?php } ?>
</table>
</body>
</html>
