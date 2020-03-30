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
 <form action="./checklist.php" method="post">
    <input type="submit" value="検索">
    <?php
    $keywordlist=array();
    foreach($pdo->query('select name,keyword1,keyword2,keyword3 from foods order by tag DESC') as $row){
      $keywordlist[]=$row['name'];
      $insert1='';
      $insert2='';
      $insert3='';
      for($i=0;$i<count($keywordlist);$i++){
        if($keywordlist[$i]==$row['keyword1']){
          $insert1='no';
        }
    }
        if($insert1!='no' && $row['keyword1']!=''){
          $keywordlist[]=$row['keyword1'].'(種類・産地指定なし)';
        }
        for($i=0;$i<count($keywordlist);$i++){
        if($keywordlist[$i]==$row['keyword2']){
          $insert2='no';
        }}
        if($insert2!='no' && $row['keyword2']!=''){
          $keywordlist[]=$row['keyword2'].'(種類・産地指定なし)';
        }
        for($i=0;$i<count($keywordlist);$i++){
        if($keywordlist[$i]==$row['keyword3']){
          $insert3='no';
        }
      }
      if($insert3!='no' && $row['keyword3']!=''){
        $keywordlist[]=$row['keyword3'].'(種類・産地指定なし)';
      }
    }
    for($h=0;$h<5;$h++){?>
食品名：<input type="text" name="food" autocomplete="on" list="foods">
<datalist id="foods">
  <?php
  foreach($keywordlist as $keyword){ ?>
  <option value="<?php echo $keyword;?>">
<?php
}
  ?>
</datalist>
<br>
<?php }
//print_r($keywordlist);
?>
</form>
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
