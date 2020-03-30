<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>各店舗画面</title>
</head>
<body>
  <p>各店舗画面</p>
  <?php require 'menu.php';?>
<?php
    $pdo=new PDO('mysql:host=mysql624.db.sakura.ne.jp;dbname=keiosfchiyoshi_supermarket;charset=utf8', 'keiosfchiyoshi', '17-eiflew');?>

<?php
//食品名をで検索できるようにする(セレクトボックスor検索窓)
//タグをセレクトボックスで選択すると、そのタグでソートされた一案が安い順に出る
//if (isset($_REQUEST['command'])) {

  date_default_timezone_set('Asia/Tokyo');
        $timestampdate = date("Y-m-d");
        $timestamp=date("Y-m-d H:i:s");
        $list=$pdo->prepare("select chain.name as chain_name,store.name as store_name from store join chain on store.chain_id=chain.id where store.id=?");
        $list->execute([$_GET['store_id']]);
        foreach ($list->fetchAll() as $row) {
          echo $row['chain_name'].$row['store_name'].'店';
        }
        ?>
        <br>
        <?php
        //$list_normal=$pdo->prepare("select chain.name as chain_name, store.name as store_name, price.price from price join store on price.store_id=store.id join chain on store.chain_id=chain.id where order by price.price DESC");
        //$list_tag=$pdo->prepare("select chain.name as chain_name, store.name as store_name, price.price from price join foods on price.foods_id=foods.id join store on price.store_id=store.id join chain on store.chain_id=chain.id where foods.tag=? order by price.price DESC");
        //$list_tag->execute([$_REQUEST['tag']]);
        if($_GET['tag']!="" && $_GET['search']!=""){
          echo 'どっちかだけで検索して';
        }
        else{
        if($_GET['tag']=="" || $_GET['tag']=="none"){
          if($_GET['search']==""){
          echo 'タグ指定なし';
        }else{
          //echo '検索：'.$_GET['search'];
        }
      }
        else{
          //echo 'タグ：'.$_GET['tag'];
        }
      }
      //}
 ?>
 <table>
   <?php
   $store_detail=$pdo->prepare("select * from store where id=?");
   $store_detail->execute([$_GET['store_id']]);
   foreach ($store_detail->fetchAll() as $row) {
     ?>
   <tr>
     <th>住所</th>
     <td><?php echo $row['address'];?></td>
   </tr>
   <tr>
     <th>営業時間</th>
     <td><?php echo $row['business_hours'];?></td>
   </tr>
   <tr>
     <th>アクセス</th>
     <td><?php echo $row['access'];?></td>
   </tr>
   <tr>
     <th>決済手段</th>
     <td><?php echo $row['payment'];?></td>
   </tr>
   <tr>
     <th>ポイントシステム</th>
     <td><?php echo $row['point_system'];?></td>
   </tr>
 </table>
<?php } ?>
 <form action="./store.php" method="get">
   <input type="hidden" name="store_id" value="<?php echo $_GET['store_id'];?>">
   <select name="tag">
       <option value="">種類を選択</option>
     <?php
      foreach ($pdo->query('select * from tag') as $row) {?>
     <option value="<?php echo $row['id'];?>" <?php if($_GET['tag']==$row['id']) echo 'selected';?>><?php echo $row['name'];?></option>
   <?php } ?>
  </select>
食品検索：<input type="text" name="search" value="<?php echo $_GET['search'];?>">
 <input type="submit" value="検索">
</form>
<table>
  <tr>
    <th>食材</th>
    <th>価格</th>
  </tr>
  <?php
  if($_GET['tag']!="" && $_GET['search']!=""){
    $list=$pdo->prepare('select chain.name as chain_name, store.name as store_name,item.name as item_name ,foods.species,foods.production_area, foods.amount ,prices.price,prices.store_id from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id join item on foods.item_id=item.id where prices.store_id=? order by prices.price ASC ');
      $list->execute([$_GET['store_id']]);
    foreach ($list->fetchAll() as $row) { ?>
    <tr>
      <td><?php
      echo $row['item_name'];?>(<?php if($row['species']!=''){echo $row['species'].'・';}echo $row['production_area'].'・'.$row['amount'];?>)</td>
      <td><?php echo $row['price'];?>円</td>
    </tr>
  <?php }
  }
  else{
  if($_GET['tag']=="" || $_GET['tag']=="none"){
    if($_GET['search']==""){
      $list=$pdo->prepare('select chain.name as chain_name, store.name as store_name,item.name as item_name ,foods.species,foods.production_area, foods.amount ,prices.price,prices.store_id from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id join item on foods.item_id=item.id where prices.store_id=? order by prices.price ASC ');
      $list->execute([$_GET['store_id']]);
      foreach ($list->fetchAll() as $row) { ?>
        <tr>
          <td><?php
        echo $row['item_name'];?>(<?php if($row['species']!=''){echo $row['species'].'・';}echo $row['production_area'].'・'.$row['amount'];?>)</td>
        <td><?php echo $row['price'];?>円</td>
      </tr>
    <?php }
  }else{//変更
    $list=$pdo->prepare("select chain.name as chain_name, store.name as store_name,item.name as item_name ,foods.species,foods.production_area, foods.amount ,prices.price,prices.store_id from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id join item on foods.item_id=item.id where store_id=? and (item.name LIKE ? or item.keyword1 LIKE ? or item.keyword2 LIKE ? or item.keyword3 LIKE ? or foods.keyword1 LIKE ? or foods.keyword2 LIKE ?) order by prices.price ASC");
    $list->execute([$_GET['store_id'],'%'.$_GET['search'].'%','%'.$_GET['search'].'%','%'.$_GET['search'].'%','%'.$_GET['search'].'%','%'.$_GET['search'].'%','%'.$_GET['search'].'%']);
    foreach ($list->fetchAll() as $row) {  ?>
    <tr>
      <td><?php
      echo $row['item_name'];?>(<?php if($row['species']!=''){echo $row['species'].'・';}echo $row['production_area'].'・'.$row['amount'];?>)</td>
      <td><?php echo $row['price'];?>円</td>
    </tr>
  <?php }
  }
}
  else{
    $list=$pdo->prepare("select chain.name as chain_name, store.name as store_name,item.name as item_name ,foods.species,foods.production_area, foods.amount ,prices.price,prices.store_id from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id join item on foods.item_id=item.id where store_id=? and item.tag_id=? order by prices.price ASC");
    $list->execute([$_GET['store_id'],$_GET['tag']]);
    foreach ($list->fetchAll() as $row) { ?>
    <tr>
      <td><?php
      echo $row['item_name'];?>(<?php if($row['species']!=''){echo $row['species'].'・';}echo $row['production_area'].'・'.$row['amount'];?>)</td>
      <td><?php echo $row['price'];?>円</td>
    </tr>
  <?php }
  }
}
  ?>
</table>
</body>
</html>
