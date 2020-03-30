<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>登録価格一覧</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="all.js" charset="utf-8"></script>
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
 <form action="./checklist2.php" method="post">
<select name="fruit" multiple class="fruit">
  <option value="">果物類を選択</option>
    <?php

    foreach($pdo->query('select foods.name as food_name from foods join tag on foods.tag_id=tag.id where foods.tag_id=2 order by foods.name DESC') as $row){
      ?>
      <option value="<?php echo $row['food_name'];?>"><?php echo $row['food_name'];?></option>
      <?php
 }
//print_r($keywordlist);
?>
</select>
<select name="vegetable" multiple class="vegetable">
  <option value="">野菜類を選択</option>
    <?php

    foreach($pdo->query('select foods.name as food_name from foods join tag on foods.tag_id=tag.id where foods.tag_id=1 order by foods.name DESC') as $row){
      ?>
      <option value="<?php echo $row['food_name'];?>"><?php echo $row['food_name'];?></option>
      <?php
 }
//print_r($keywordlist);
?>
</select>

<select name="seafood" multiple class="seafood">
  <option value="">魚介類を選択</option>
    <?php

    foreach($pdo->query('select foods.name as food_name from foods join tag on foods.tag_id=tag.id where foods.tag_id=3 order by foods.name DESC') as $row){
      ?>
      <option value="<?php echo $row['food_name'];?>"><?php echo $row['food_name'];?></option>
      <?php
 }
//print_r($keywordlist);
?>
</select>

<select name="meet" multiple class="meet">
  <option value="">肉類を選択</option>
    <?php

    foreach($pdo->query('select foods.name as food_name from foods join tag on foods.tag_id=tag.id where foods.tag_id=4 order by foods.name DESC') as $row){
      ?>
      <option value="<?php echo $row['food_name'];?>"><?php echo $row['food_name'];?></option>
      <?php
 }
//print_r($keywordlist);
?>
</select>

<select name="milkegg" multiple class="milkegg">
  <option value="">乳製品・卵を選択</option>
    <?php

    foreach($pdo->query('select foods.name as food_name from foods join tag on foods.tag_id=tag.id where foods.tag_id=5 order by foods.name DESC') as $row){
      ?>
      <option value="<?php echo $row['food_name'];?>"><?php echo $row['food_name'];?></option>
      <?php
 }
//print_r($keywordlist);
?>
</select>
<input type="submit" value="検索">
</form>
<p>果物類：<c id="fruit"></c></p>
<p>野菜類：<c  id="vegetable"></c></p>
<p>魚介類：<c id="seafood"></c></p>
<p>肉類：<c  id="meet"></c></p>
<p>乳製品・卵：<c  id="milkegg"></c></p>
<table>
  <tr>
    <th>チェーン名</th>
    <th>店舗名</th>
    <th>価格</th>
  </tr>
  <?php/*
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
    $list=$pdo->prepare("select chain.name as chain_name, store.name as store_name, foods.name as food_name , prices.price,prices.store_id from prices join store on prices.store_id=store.id join chain on store.chain_id=chain.id join foods on prices.foods_id=foods.id where foods.tag=? order by prices.price ASC");
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
} */
  ?>
</table>
</body>
</html>
