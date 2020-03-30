<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>品目登録画面</title>
</head>
<body>
  <p>品目</p>
  <?php require 'menu.php';?>
<?php
    $pdo=new PDO('mysql:host=mysql624.db.sakura.ne.jp;dbname=keiosfchiyoshi_supermarket;charset=utf8', 'keiosfchiyoshi', '17-eiflew');?>

<?php
if (isset($_REQUEST['command'])) {
  date_default_timezone_set('Asia/Tokyo');
        $timestampdate = date("Y-m-d");
        $timestamp=date("Y-m-d H:i:s");
        $insertitem=$pdo->prepare("insert into item values(null,?,?,?,?,?,?)");
        $confirmitem=$pdo->prepare("select * from item where name=? and tag_id=?");
        $confirmitem->execute([$_REQUEST['item_name'],$_REQUEST['tag']]);
        if(empty($confirmitem->fetchAll())){
        if($insertitem->execute([$_REQUEST['tag'],$_REQUEST['item_name'],$_REQUEST['keyword1'],$_REQUEST['keyword2'],$_REQUEST['keyword3'],$timestamp])){
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
 <form action="./item_regi.php" method="post">
   <select name="tag">
   <option value="">タグを選択</option>
   <?php
    foreach ($pdo->query('select * from tag') as $row) {?>
   <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
 <?php } ?>
 </select>
   <input type="hidden" name="command" value="upload"><br>
 品目名<input type="text" name="item_name"><br>
 キーワード1<input type="text" name="keyword1">
 キーワード2<input type="text" name="keyword2">
 キーワード3<input type="text" name="keyword3">
 <br>
 <input type="submit" value="登録">
</form>
<table>
  <tr>
    <th>id</th>
    <th>タグ</th>
    <th>品目</th>
    <th>キーワード1</th>
    <th>キーワード2</th>
    <th>キーワード3</th>
  </tr>
  <?php
  foreach ($pdo->query('select tag.name as tag_name , item.name as item_name ,item.id as item_id, keyword1,keyword2,keyword3 from item join tag on item.tag_id=tag.id') as $row) { ?>
  <tr>
    <td><?php echo $row['item_id'];?></td>
    <td><?php echo $row['tag_name'];?></td>
    <td><?php echo $row['item_name'];?></td>
    <td><?php echo $row['keyword1'];?></td>
    <td><?php echo $row['keyword2'];?></td>
    <td><?php echo $row['keyword3'];?></td>
  </tr>
<?php } ?>
</table>
</body>
</html>
