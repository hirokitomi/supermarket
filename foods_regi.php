<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>食品登録画面</title>
</head>
<body>
  <p>食品</p>
  <?php require 'menu.php';?>
<?php
    $pdo=new PDO('mysql:host=mysql624.db.sakura.ne.jp;dbname=keiosfchiyoshi_supermarket;charset=utf8', 'keiosfchiyoshi', '17-eiflew');?>

<?php
if (isset($_REQUEST['command'])) {
  date_default_timezone_set('Asia/Tokyo');
        $timestampdate = date("Y-m-d");
        $timestamp=date("Y-m-d H:i:s");
        $insertfoods=$pdo->prepare("insert into foods values(null,?,?,?,?,?,?,?)");
        $confirmfoods=$pdo->prepare("select * from foods where name=? and gram=?");
        $confirmfoods->execute([$_REQUEST['food_name']]);
        if(empty($confirmfoods->fetchAll())){
        if($insertfoods->execute([$_REQUEST['food_name'],$_REQUEST['gram'],$_REQUEST['keyword1'],$_REQUEST['keyword2'],$_REQUEST['keyword3'],$_REQUEST['tag'],$timestamp])){
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
 <form action="./foods_regi.php" method="post">
   <input type="hidden" name="command" value="upload">
 食品名：<input type="text" name="food_name">
グラム(グラム単位の肉類のみ)：<input type="text" name="gram">
 <select name="tag">
     <option>種類を選択</option>
   <?php
    foreach ($pdo->query('select * from tag') as $row) {?>
   <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
 <?php } ?>
</select><br>
キーワード1：<input type="text" name="keyword1">
キーワード2：<input type="text" name="keyword2">
 <input type="submit" value="登録">
</form>
<table>
  <tr>
    <th>id</th>
    <th>食品名</th>
    <th>グラム</th>
    <th>種類</th>
    <th>キーワード1</th>
    <th>キーワード2</th>
    <th>キーワード3</th>
  </tr>
  <?php
  foreach ($pdo->query('select foods.id as food_id,foods.name as food_name,foods.gram,tag.name as tag_name,keyword1,keyword2,keyword3 from foods join tag on foods.tag_id=tag.id') as $row) { ?>
  <tr>
    <td><?php echo $row['food_id'];?></td>
    <td><?php echo $row['food_name'];?></td>
    <td><?php echo $row['gram'];?></td>
    <td><?php echo $row['tag_name'];?></td>
    <td><?php echo $row['keyword1'];?></td>
    <td><?php echo $row['keyword2'];?></td>
    <td><?php echo $row['keyword3'];?></td>
  </tr>
<?php } ?>
</table>
</body>
</html>
