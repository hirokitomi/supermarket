<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>登録価格一覧</title>
</head>
<body>
  <p>登録価格一覧</p>
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
          echo '検索：'.$_GET['search'];
        }
      }
        else{
          echo 'タグ：'.$_GET['tag'];
        }
      }

      //}
 ?>
 <form action="./price_list.php" method="get">
   <select name="tag">
       <option value="">種類を選択</option>
     <?php
      foreach ($pdo->query('select * from tag') as $row) {?>
     <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
   <?php } ?>
  </select>
食品検索：<input type="text" name="search">
 <input type="submit" value="検索">
</form>
<table>
  <tr>
    <th>チェーン名</th>
    <th>店舗名</th>
    <th>食材</th>
    <th>価格</th>
  </tr>
  <?php
  if($_GET['tag']!="" && $_GET['search']!=""){
    foreach ($pdo->query('select chain.name as chain_name, store.name as store_name, foods.name as food_name , prices.price,prices.store_id from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id order by prices.price ASC ') as $row) { ?>
    <tr>
      <td><?php echo $row['chain_name'];?></td>
      <td><a href="./store.php?store_id=<?php echo $row['store_id'];?>"><?php echo $row['store_name'];?></a>店</td>
      <td><a href="./price_list.php?tag=&search=<?php echo $row['food_name'];?>"><?php echo $row['food_name'];?></a></td>
      <td><?php echo $row['price'];?></td>
    </tr>
  <?php }
  }
  else{
  if($_GET['tag']=="" || $_GET['tag']=="none"){
    if($_GET['search']==""){
      foreach ($pdo->query('select chain.name as chain_name, store.name as store_name, foods.name as food_name , prices.price,prices.store_id from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id order by prices.price ASC ') as $row) { ?>
      <tr>
        <td><?php echo $row['chain_name'];?></td>
        <td><a href="./store.php?store_id=<?php echo $row['store_id'];?>"><?php echo $row['store_name'];?></a>店</td>
        <td><a href="./price_list.php?tag=&search=<?php echo $row['food_name'];?>"><?php echo $row['food_name'];?></a></td>
        <td><?php echo $row['from_area'];?></td>
        <td><?php echo $row['price'];?></td>
      </tr>
    <?php }
  }else{//変更
    $list=$pdo->prepare("select chain.name as chain_name, store.name as store_name, foods.name as food_name , prices.price,prices.store_id from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id where foods.name LIKE ? or foods.keyword1 LIKE ? or foods.keyword2 LIKE ? order by prices.price ASC");
    $list->execute(['%'.$_GET['search'].'%','%'.$_GET['search'].'%','%'.$_GET['search'].'%']);
    foreach ($list->fetchAll() as $row) {  ?>
    <tr>
      <td><?php echo $row['chain_name'];?></td>
      <td><a href="./store.php?store_id=<?php echo $row['store_id'];?>"><?php echo $row['store_name'];?></a>店</td>
      <td><a href="./price_list.php?tag=&search=<?php echo $row['food_name'];?>"><?php echo $row['food_name'];?></a></td>
      <td><?php echo $row['price'];?></td>
    </tr>
  <?php }
  }
}
  else{
    $list=$pdo->prepare("select chain.name as chain_name, store.name as store_name, foods.name as food_name , prices.price,prices.store_id from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id where foods.tag_id=? order by prices.price ASC");
    $list->execute([$_GET['tag']]);
    foreach ($list->fetchAll() as $row) { ?>
    <tr>
      <td><?php echo $row['chain_name'];?></td>
      <td><a href="./store.php?store_id=<?php echo $row['store_id'];?>"><?php echo $row['store_name'];?></a>店</td>
      <td><a href="./price_list.php?tag=&search=<?php echo $row['food_name'];?>"><?php echo $row['food_name'];?></a></td>
      <td><?php echo $row['price'];?></td>
    </tr>
  <?php }
  }
}
  ?>
</table>
<object data="https://www.recipe-blog.jp/search/?type=recipe&keyword=<?php echo $_GET['search'] ;?>&source=web" width="1000" height="400"></object>
</body>
</html>
