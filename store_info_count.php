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
<style>
.red{
  color:RED;
}
.gray{
  color:#4F4F4F;
}
</style>
</head>
<body>
  <h1>
    <?php
      echo $result['store_name'];
    ?>
  </h1> <!-- あとで変数 -->

<!-- 営業状態の投票 -->

<p>
  <form method="POST" action="open_close.php"> <!-- open_close.phpに営業中か閉店中かを送る-->
  <input type="hidden" value=<?php echo $store_id; ?> name="store_id">
  <input type="submit" value="営業中" name="vote_open">　<!-- 営業中 -->
  <input type="submit" value="閉店中" name="vote_close">　<!-- 閉店中 -->
  </form>
</p>


<!-- 投票数のカウント -->
<!-- 0時から2時の営業 -->
<?php
$stmt_vote = $pdo->query('SELECT * FROM sample0801_db LEFT JOIN sample0802_open ON sample0801_db.store_id = sample0802_open.store_id left join sample0802_close on sample0801_db.store_id = sample0802_close.store_id WHERE sample0801_db.store_id ='.$store_id);
$result_vote = $stmt_vote -> fetch(PDO::FETCH_ASSOC);

// 投票のハッシュのキーの配列
$o_key = array('o0_2','o2_4', 'o4_6','o6_8','o8_10','o10_12','o12_14','o14_16','o16_18','o18_20','o20_22','o22_24');
$c_key = array('c0_2','c2_4', 'c4_6','c6_8','c8_10','c10_12','c12_14','c14_16','c16_18','c18_20','c20_22','c22_24');
$time = array( ' 0:00~ 2:00', ' 2:00~ 4:00', ' 4:00~ 6:00', ' 6:00~ 8:00', ' 8:00~10:00', '10:00~12:00', '12:00~14:00', '14:00~16:00', '16:00~18:00', '18:00~20:00', '20:00~22:00', '22:00~ 0:00');
date_default_timezone_set('Asia/Tokyo');
$date = date("H");
$key = intval($date/2);

// 投票数の表示
echo '<p>';
for ($i = 0; $i < 12; $i++) {
  if($i != $key ) {

    echo '<span class="gray">';
    echo $time[$i].' ... ';
    echo $result_vote[$o_key[$i]].' : ';
    echo $result_vote[$c_key[$i]];
    echo '</span>';

  } else {
    if($result_vote[$o_key[$i]] > $result_vote[$c_key[$i]]){
      echo '<strong>';
      echo $time[$i].' ... ';
      echo '<span class="red">'.$result_vote[$o_key[$i]].'</span> : ';
      echo $result_vote[$c_key[$i]];
      echo '</strong>';
    } else if($result_vote[$o_key[$i]] < $result_vote[$c_key[$i]]){
      echo '<strong>';
      echo $time[$i].' ... ';
      echo $result_vote[$o_key[$i]].' : ';
      echo '<span class="red">'.$result_vote[$c_key[$i]].'</span>';
      echo '</strong>';
    } else {
      echo '<strong>';
      echo $time[$i].' ... ';
      echo $result_vote[$o_key[$i]].' : ';
      echo $result_vote[$c_key[$i]];
      echo '</strong>';
    }
  }
  echo '<br>';
}
echo '</p>';

// 詳細情報の取得
$stmt_detail_info = $pdo->query('SELECT * FROM info WHERE store_id = '.$store_id);
$result_detail_info = $stmt_detail_info -> fetch(PDO::FETCH_ASSOC);

echo '<p>';
echo 'ランチ：'.$result_detail_info["l_time_o"].'~'.$result_detail_info["l_time_c"].'<br>';
echo 'ディナー：'.$result_detail_info["d_time_o"].'~'.$result_detail_info["d_time_c"].'<br>';
echo '定休日：'.$result_detail_info["holiday"].'<br>';
echo 'ジャンル：'.$result_detail_info["genre"].'<br>';
echo '価格帯：'.$result_detail_info["price_min"].'~'.$result_detail_info["price_max"].'<br>';
echo 'TEL：'.$result_detail_info["tel"].'<br>';
echo 'URL：<a href ='.$result_detail_info["url"].'>'.$result_detail_info["url"].'</a>';
echo '</p>';
?>



</body>
</html>
