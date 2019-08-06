<?php
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF=8');
}

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
<link rel="stylesheet" type="text/css" href="css/tapiome.css"></link>
</head>
<body>
  <p>
    <img src="img/title.png" width="335px" height="60">
  </p>


<!-- 検索フォーム -->
<form method="post" action="index.php">
<input type="search" name="kensaku" >
<input type="checkbox" name="eigyou" value="営業中">営業中店舗のみ表示
<input type="submit" value="検索">
</form>

<?php
  $word = $_POST['kensaku']; //検索ワード
  $eigyou = $_POST['eigyou'];
  if($word != NULL){
    if(isset($eigyou)){
      $stmt11 = $pdo->prepare('SELECT * FROM sample0801_db LEFT JOIN info ON sample0801_db.store_id = info.store_id WHERE store_name like :word AND status=1');
      $stmt11->bindValue(':word', '%'.$word.'%', PDO::PARAM_STR);
      $stmt11->execute();
      if($stmt11){
        while($result11 = $stmt11 -> fetch(PDO::FETCH_ASSOC)) {
         echo h($result11['store_name']);
         echo h('：<a class = "link" href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result11['store_id'].'">詳細情報</a><br>');
       }
     }
     } else {
       $stmt10 = $pdo->prepare('SELECT * FROM sample0801_db LEFT JOIN info ON sample0801_db.store_id = info.store_id WHERE store_name like :word');
       $stmt10->bindValue(':word', '%'.$word.'%', PDO::PARAM_STR);
       $stmt10->execute();
       if($stmt10){
         while($result10 = $stmt10 -> fetch(PDO::FETCH_ASSOC)) {
          echo h($result10['store_name']);
          echo h('：<a class = "link" href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result10['store_id'].'">詳細情報</a><br>');
        }
      }
    }
   } else {
   if(isset($eigyou)){
     $stmt01 = $pdo->prepare('SELECT * FROM sample0801_db LEFT JOIN info ON sample0801_db.store_id = info.store_id WHERE status=1');
     $stmt01->bindValue(':word', '%'.$word.'%', PDO::PARAM_STR);
     $stmt01->execute();
     if($stmt01){
       while($result01 = $stmt01 -> fetch(PDO::FETCH_ASSOC)) {
        echo h($result01['store_name']);
        echo h('：<a class = "link" href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result01['store_id'].'">詳細情報</a><br>');
      }
    }
   } else {
     $stmt00 = $pdo->prepare('SELECT * FROM sample0801_db LEFT JOIN info ON sample0801_db.store_id = info.store_id ');
     $stmt00->bindValue(':word', '%'.$word.'%', PDO::PARAM_STR);
     $stmt00->execute();
     if($stmt00){
       while($result00 = $stmt00 -> fetch(PDO::FETCH_ASSOC)) {
        echo h($result00['store_name']);
        echo h('：<a class = "link" href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result00['store_id'].'">詳細情報</a><br>');
      }
    }
   }
 }
  //$stmt = $pdo->prepare('SELECT * FROM sample0801_db WHERE store_name LIKE :word');
  //$stmt->bindValue(':word', '%'.$word.'%', PDO::PARAM_STR);
  //$stmt->execute();



  // $eigyou = $_POST['eigyou'];
  // if(isset($eigyou)){
  //   $stmt2 = $pdo->query('SELECT * FROM info WHERE status = 1');
  //   if($stmt2){
  //     while($result = $stmt2 -> fetch(PDO::FETCH_ASSOC)) {
  //       $stmt4 = $pdo->prepare('SELECT * FROM sample0801_db WHERE store_id = :store_id');
  //       $stmt4->bindValue(':store_id', $result['store_id'], PDO::PARAM_INT);
  //       $stmt4->execute();
  //       $result2 = $stmt4-> fetch(PDO::FETCH_ASSOC);
  //       echo $result2['store_name'];
  //       echo '：<a href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result2['store_id'].'">詳細情報</a><br>';
  //     }
  //   }
  // }else{
  //   //全部を表示
  //   $stmt3 = $pdo->query('SELECT * FROM sample0801_db');
  //   if($stmt3){
  //     while($result_eigyou = $stmt3 -> fetch(PDO::FETCH_ASSOC)){
  //       echo $result_eigyou['store_name'];
  //       echo '：<a href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result_eigyou['store_id'].'">詳細情報</a><br>';
  //     }
  //   }
  // }
?>
<br>
<br>

<?php
// $word = $_GET["kensaku"]; //検索ワード
// $stmt = $pdo->prepare('SELECT * FROM sample0801_db WHERE store_name LIKE :word');
// $stmt->bindValue(':word', '%'.$word.'%', PDO::PARAM_STR);
// $stmt->execute();
//
// if($stmt){
// while($result = $stmt -> fetch(PDO::FETCH_ASSOC)) {
//   echo $result['store_name'];
//   echo '：<a href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result['store_id'].'">詳細情報</a><br>';
// }
// }else{
// echo 'dame';
// }
?>

<br>
<!--営業中の店舗表示 -->
<!-- <a href = "https://tapiome.herokuapp.com/result_open.php">営業中店舗</a> -->
<br>



<?php
//$stmt2 = $pdo->query('SELECT * FROM info WHERE status = 1');
//if($stmt2){
  //while($result = $stmt2 -> fetch(PDO::FETCH_ASSOC)) {
    //$stmt = $pdo->prepare('SELECT * FROM sample0801_db WHERE store_id = :store_id');
    //$stmt->bindValue(':store_id', $result['store_id'], PDO::PARAM_INT);
    //$stmt->execute();
    //$result2 = $stmt -> fetch(PDO::FETCH_ASSOC);

    //echo $result2['store_name'];
    //echo '：<a href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result['store_id'].'">詳細情報</a><br>';
  //}

 //}else{
    //echo '営業中の店舗はありません';
  //}
//?>



</body>
</html>
