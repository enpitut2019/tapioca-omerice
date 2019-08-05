<?php
//idの取得
$store_id = $_GET["store_id"];

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
$stmt = $pdo->query('SELECT store_name FROM sample0801_db WHERE store_id = '.$store_id);
$result = $stmt -> fetch(PDO::FETCH_ASSOC);
 // var_dump($result);//store_nameが取れているか確認
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>
  <?php
    echo $result['store_name'];
  ?>
</title> <!-- あとで変数 -->
</head>
<body>
  <h1>
    <?php
      echo $result['store_name'];
    ?>
  </h1> <!-- あとで変数 -->

<!-- 営業状態の投票 -->
<form method="POST" action="open_close.php"> <!-- open_close.phpに営業中か閉店中かを送る-->
<input type="hidden" value=<?php echo $store_id; ?> name="store_id">
<input type="submit" value="営業中" name="vote_open">　<!-- 営業中 -->
<input type="submit" value="閉店中" name="vote_close">　<!-- 閉店中 -->
</form>


<!-- 投票数のカウント -->
<!-- 0時から2時の営業 -->
<?php
$stmt_vote = $pdo->query('SELECT * FROM sample0801_db LEFT JOIN sample0802_open ON sample0801_db.store_id = sample0802_open.store_id left join sample0802_close on sample0801_db.store_id = sample0802_close.store_id WHERE sample0801_db.store_id ='.$store_id);
$result_vote = $stmt_vote -> fetch(PDO::FETCH_ASSOC);

// 投票のハッシュのキーの配列
$o_key = array('o4_6','o6_8','o8_10','o10_12','o12_14','o14_16','o16_18','o18_20','o20_22','o22_24','o0_2','o2_4');
$c_key = array('c4_6','c6_8','c8_10','c10_12','c12_14','c14_16','c16_18','c18_20','c20_22','c22_24','c0_2','c2_4');
$time = array(' 4:00~ 6:00', ' 6:00~ 8:00', ' 8:00~10:00', '10:00~12:00', '12:00~14:00', '14:00~16:00', '16:00~18:00', '18:00~20:00', '20:00~22:00', '22:00~ 0:00', ' 0:00~ 2:00', ' 2:00~ 4:00');
date_default_timezone_set('Asia/Tokyo');
$date = date("H");

// 投票数の表示
for ($i = 0; $i < 12; $i++) {
  for($j = 4; $j < 28; j += 2) {
    if($j <= $date && $date < j+2) {
      if($result_vote[$o_key[$i]] > $result_vote[$c_key[$i]]){
        echo '<strong>'.$time[$i].' ... </strong>';
        echo '<strong>'.$result_vote[$o_key[$i]].' : </strong>';
        echo '<strong>'.$result_vote[$c_key[$i]]'</strong>';
      } else if($result_vote[$o_key[$i]] < $result_vote[$c_key[$i]]){
        echo '<strong>'.$time[$i].' ... </strong>';
        echo '<strong>'.$result_vote[$o_key[$i]].' : </strong>';
        echo '<strong>'.$result_vote[$c_key[$i]]'</strong>';
      } else {
        echo '<strong>'.$time[$i].' ... </strong>';
        echo '<strong>'.$result_vote[$o_key[$i]].' : </strong>';
        echo '<strong>'.$result_vote[$c_key[$i]]'</strong>';
      }
    }else {
      echo $time[$i].' ... ';
      echo $result_vote[$o_key[$i]].' : ';
      echo $result_vote[$c_key[$i]];
    } else if($result_vote[$o_key[$i]] < $result_vote[$c_key[$i]]){
      echo $time[$i].' ... ';
      echo $result_vote[$o_key[$i]].' : ';
      echo $result_vote[$c_key[$i]];
    } else {
      echo $time[$i].' ... ';
      echo $result_vote[$o_key[$i]].' : ';
      echo $result_vote[$c_key[$i]];
    }
    echo '<br>';
  }
}

  date_default_timezone_set('Asia/Tokyo');
  echo date("H");

?>



</body>
</html>
