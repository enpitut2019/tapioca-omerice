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

<form method="get" action="result.php">
<input type="search" name="kensaku" ><input type="submit" value="検索">
</form>

<a href = "https://tapiome.herokuapp.com/result_open.php">営業中店舗</a>


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


</body>
</html>
