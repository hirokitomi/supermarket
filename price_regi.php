<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>価格登録画面</title>
</head>
<body>
  <p>価格</p>
  <?php require 'menu.php';?>
<?php
    $pdo=new PDO('mysql:host=mysql624.db.sakura.ne.jp;dbname=keiosfchiyoshi_supermarket;charset=utf8', 'keiosfchiyoshi', '17-eiflew');?>

<?php
if (isset($_REQUEST['command'])) {
  date_default_timezone_set('Asia/Tokyo');
        $timestampdate = date("Y-m-d");
        $timestamp=date("Y-m-d H:i:s");
        $insertpricelog=$pdo->prepare("insert into price_log values(null,?,?,?,?)");
        $insertprice=$pdo->prepare("insert into prices values(null,?,?,?,?)");
        $updateprice=$pdo->prepare("update prices set price=?, upload_at=? where store_id=? and foods_id=?");
        $confirmprice=$pdo->prepare("select * from prices where store_id=? and foods_id=?");
        $confirmprice->execute([$_REQUEST['store'],$_REQUEST['food']]);
        $confirmprice_date=$pdo->prepare("select * from prices where store_id=? and foods_id=? and price=? and CAST(upload_at as DATE)=?"); //最終更新が同じ日なら弾きたい
        $confirmprice_date->execute([$_REQUEST['store'],$_REQUEST['food'],$_REQUEST['price'],$timestampdate]);
        if(empty($confirmprice_date->fetchAll())){
          echo 'date_no';
          if(empty($confirmprice->fetchAll())){
            echo 'no';
            if($_REQUEST['store']=="" || $_REQUEST['food']=="" || $_REQUEST['price']==""){
              echo '選択して';
            }
            else{
            if($insertprice->execute([$_REQUEST['store'],$_REQUEST['food'],$_REQUEST['price'],$timestamp])){
              if($insertpricelog->execute([$_REQUEST['store'],$_REQUEST['food'],$_REQUEST['price'],$timestamp])){
          ?>
          <b>insert成功</b>
          <?php
        }
              else{
          ?>
          <b>insert失敗</b>
          <?php
        }
        }
        else{?>
        <b>insert失敗</b>
        <?php
      }
    }
        }
        else{
          echo 'yes';
          if($updateprice->execute([$_REQUEST['price'],$timestamp,$_REQUEST['store'],$_REQUEST['food']])){
            if($insertpricelog->execute([$_REQUEST['store'],$_REQUEST['food'],$_REQUEST['price'],$timestamp])){
            ?>
            <b>update成功</b>
            <?php
              }
            else{
            ?>
            <b>update失敗</b>
            <?php
              }
            }
            else{
              ?>
              <b>update失敗</b>
              <?php
                }
            }
          }
        else{
          echo 'すでにあります';
        }
    }
 ?>
 <form action="./price_regi.php?chain_id=<?php echo $_GET['chain_id'];?>" method="post">
   <input type="hidden" name="command" value="upload">
   チェーン：<?php
   $chain_name=$pdo->prepare("select name from chain where id=?");
   $chain_name->execute([$_GET['chain_id']]);
   foreach($chain_name as $row){
     echo $row['name'];
   }
   ?><input type="hidden" name="chain" value="<?php $_GET['chain_id'];?>"><br>
 <select name="store">
 <option value="">店舗を選択</option>
 <?php
 $store=$pdo->prepare("select id,name from store where chain_id=?");
 $store->execute([$_GET['chain_id']]);
 foreach($store as $row){
   ?><option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
     <?php
 }?>
</select>
<select name="food">
<option value="">食品を選択</option>
<?php
foreach ($pdo->query('select * from foods') as $row) {
  ?><option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
    <?php
}?>
</select><br>
価格<input type="text" name="price"><br>
 <input type="submit" value="登録">
</form>

</body>
</html>
