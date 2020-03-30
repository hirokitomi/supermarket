<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>店舗一覧</title>
</head>
<body>
  <p>店舗一覧</p>
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

        if($_REQUEST['chain']==""){
          if($_REQUEST['search']==""){
          echo 'タグ指定なし';
        }else{
          echo '検索：'.$_REQUEST['search'];
        }
      }
        else{
          echo 'チェーン：'.$_REQUEST['chain'];
        }

      //}
 ?>
 <form action="./store_list.php" method="post">
   <select name="chain">
   <option value="">チェーンでソート</option>
   <?php
    foreach ($pdo->query('select * from chain') as $row) {?>
   <option value="<?php echo $row['name'];?>"><?php echo $row['name'];?></option>
 <?php } ?>
 </select>
検索(店舗名と住所で検索できる)：<input type="text" name="search">
 <input type="submit" value="検索">
</form>
<table>
  <tr>
    <th>チェーン名</th>
    <th>店舗名</th>
  </tr>
  <?php
  if($_REQUEST['chain']!="" && $_REQUEST['search']!=""){
    foreach ($pdo->query('select chain.name as chain_name, store.name as store_name,store.id from store join chain on store.chain_id=chain.id order by store.upload_at DESC ') as $row) { ?>
    <tr>
      <td><?php echo $row['chain_name'];?></td>
      <td><a href="./store.php?store_id=<?php echo $row['id'];?>"><?php echo $row['store_name'];?></a>店</td>
    </tr>
  <?php }
  }
  else{
  if($_REQUEST['chain']==""){
    if($_REQUEST['search']==""){
      foreach ($pdo->query('select chain.name as chain_name, store.name as store_name,store.id from store join chain on store.chain_id=chain.id order by store.upload_at DESC ') as $row) { ?>
      <tr>
        <td><?php echo $row['chain_name'];?></td>
        <td><a href="./store.php?store_id=<?php echo $row['id'];?>"><?php echo $row['store_name'];?></a>店</td>
      </tr>
    <?php }
  }else{//変更
    $list=$pdo->prepare("select chain.name as chain_name, store.name as store_name,store.id from store  join chain on store.chain_id=chain.id where store.name LIKE ? or store.address LIKE ? order by store.upload_at DESC");
    $list->execute(['%'.$_REQUEST['search'].'%','%'.$_REQUEST['search'].'%']);
    foreach ($list->fetchAll() as $row) {  ?>
    <tr>
      <td><?php echo $row['chain_name'];?></td>
      <td><a href="./store.php?store_id=<?php echo $row['id'];?>"><?php echo $row['store_name'];?></a>店</td>
    </tr>
  <?php }
  }
}
  else{
    $list=$pdo->prepare("select chain.name as chain_name, store.name as store_name,store.id from store join chain on store.chain_id=chain.id where chain.name=? order by store.upload_at DESC");
    $list->execute([$_REQUEST['chain']]);
    foreach ($list->fetchAll() as $row) { ?>
    <tr>
      <td><?php echo $row['chain_name'];?></td>
      <td><a href="./store.php?store_id=<?php echo $row['id'];?>"><?php echo $row['store_name'];?></a>店</td>
    </tr>
  <?php }
  }
}
  ?>
</table>
</body>
</html>
