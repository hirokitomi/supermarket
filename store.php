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
        if($_REQUEST['tag']!="" && $_REQUEST['search']!=""){
          echo 'どっちかだけで検索して';
        }
        else{
        if($_REQUEST['tag']=="" || $_REQUEST['tag']=="none"){
          if($_REQUEST['search']==""){
          echo 'タグ指定なし';
        }else{
          echo '検索：'.$_REQUEST['search'];
        }
      }
        else{
          echo 'タグ：'.$_REQUEST['tag'];
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
 <form action="./store.php?store_id=<?php echo $_GET['store_id'];?>" method="post">
   <input type="hidden" name="command" value="upload">
   <select name="tag">
     <option value="">タグでソート</option>
     <option value="none">指定なし</option>
     <option value="野菜">野菜</option>
     <option value="果物">果物</option>
   <option value="肉類">肉類</option>
 <option value="魚介類">魚介類</option>
</select>
食品検索：<input type="text" name="search">
 <input type="submit" value="検索">
</form>
<table>
  <tr>
    <th>食材</th>
    <th>価格</th>
  </tr>
  <?php
  if($_REQUEST['tag']!="" && $_REQUEST['search']!=""){
    $list=$pdo->prepare("select foods.name as food_name , prices.price from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id where prices.store_id=? order by prices.price DESC");
    $list->execute([$_GET['store_id']]);
    foreach ($list->fetchAll() as $row) {  ?>
    <tr>
      <td><?php echo $row['food_name'];?></td>
      <td><?php echo $row['price'];?></td>
    </tr>
  <?php }
  }
  else{
  if($_REQUEST['tag']=="" || $_REQUEST['tag']=="none"){
    if($_REQUEST['search']==""){
      $list=$pdo->prepare("select foods.name as food_name , prices.price from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id where prices.store_id=? order by prices.price DESC");
      $list->execute([$_GET['store_id']]);
      foreach ($list->fetchAll() as $row) {  ?>
      <tr>
        <td><?php echo $row['food_name'];?></td>
        <td><?php echo $row['price'];?></td>
      </tr>
    <?php }
  }else{//変更
    $list=$pdo->prepare("select foods.name as food_name , prices.price from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id where prices.store_id=? and foods.name LIKE ? order by prices.price DESC");
    $list->execute([$_GET['store_id'] ,'%'.$_REQUEST['search'].'%']);
    foreach ($list->fetchAll() as $row) {  ?>
    <tr>
      <td><?php echo $row['food_name'];?></td>
      <td><?php echo $row['price'];?></td>
    </tr>
  <?php }
  }
}
  else{
    $list=$pdo->prepare("select foods.name as food_name, prices.price from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id where prices.store_id=? and foods.tag=? order by prices.price DESC");
    $list->execute([$_GET['store_id'],$_REQUEST['tag']]);
    foreach ($list->fetchAll() as $row) { ?>
    <tr>
      <td><?php echo $row['food_name'];?></td>
      <td><?php echo $row['price'];?></td>
    </tr>
  <?php }
  }
}
  ?>
</table>
</body>
</html>
