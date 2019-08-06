<?php

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
// var_dump($_POST);



// 投票数を取得
$stmt_vote = $pdo->query('SELECT * FROM sample0801_db LEFT JOIN sample0802_open ON sample0801_db.store_id = sample0802_open.store_id left join sample0802_close on sample0801_db.store_id = sample0802_close.store_id');
$result_vote = $stmt_vote -> fetch(PDO::FETCH_ASSOC);

// hashのkeyの配列
$o_key = array('o0_2','o2_4','o4_6','o6_8','o8_10','o10_12','o12_14','o14_16','o16_18','o18_20','o20_22','o22_24');
$c_key = array('c0_2','c2_4','c4_6','c6_8','c8_10','c10_12','c12_14','c14_16','c16_18','c18_20','c20_22','c22_24');

// 現在時刻の取得
date_default_timezone_set('Asia/Tokyo');
$date = date("H");

$index = intval($date/2);

for($i=0; $i<8; $i++) {
  $j = $index-(4+$i);
  if($j<=-1){
    $j+=12;
  }
  $stmt_o = $pdo->prepare('UPDATE sample0802_open SET '.$o_key[$j].'= 0');
  $stmt_o->execute();
  $stmt_c = $pdo->prepare('UPDATE sample0802_close SET '.$c_key[$j].'= 0');
  $stmt_c->execute();
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ごっっはにゃさん</title>
</head>
<body>
  <h1>ごっっはにゃさん</h1>

<?php
    echo "Hello tapioka!";
?>

<!-- 検索フォーム -->
<form method="get" action="index.php">
<input type="search" name="kensaku" ><input type="submit" value="検索">
</form>

<!-- 今から営業中のチェックボックボックスを作りたい -->
<form method="post" action="index.php">
<input type="checkbox" name="eigyou[]" value="営業中">営業中
</form>

<?php
$eigyou = $_POST['eigyou'];
var_dump($eigyou);

// if(("[name=eigyou]:checked").val() = value){
//   echo '良い';
// }else{
//   echo 'だめ';
// }
?>
<br>
<br>

<?php
$word = $_GET["kensaku"]; //検索ワード
$stmt = $pdo->query('SELECT * FROM sample0801_db WHERE store_name LIKE \'%'.$word.'%\'');

if($stmt){
while($result = $stmt -> fetch(PDO::FETCH_ASSOC)) {
  echo $result['store_name'];
  echo '：<a href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result['store_id'].'">詳細情報</a><br>';
}
}else{
echo 'dame';
}
?>

<br>

<a href = "https://tapiome.herokuapp.com/result_open.php">営業中店舗</a>
<br>

<!-- 営業中の店舗表示 -->
<?php
$stmt2 = $pdo->query('SELECT * FROM info WHERE status = 1');
if($stmt2){
  while($result = $stmt2 -> fetch(PDO::FETCH_ASSOC)) {
    $stmt = $pdo->query('SELECT * FROM sample0801_db WHERE store_id = '.$result['store_id']);
    $result2 = $stmt -> fetch(PDO::FETCH_ASSOC);
    // var_dump($result);
    // echo '</br>';
    // var_dump($result2);
    // echo '</br>';
    echo $result2['store_name'];
    echo '：<a href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result['store_id'].'">詳細情報</a><br>';
  }

  // if($stmt){
  //   while($result = $stmt -> fetch(PDO::FETCH_ASSOC)) {
  //     echo $result['store_name'];
  //     echo '：<a href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result['store_id'].'">詳細情報</a><br>';
  //   }
  // }
 }else{
    echo '営業中の店舗はありません';
  }
?>



</body>
</html>
