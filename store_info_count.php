<?php
//idの取得
$store_id = $_GET["store_id"];

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
$stmt = $pdo->prepare('SELECT store_name FROM sample0801_db WHERE store_id = :store_id');
$stmt->bindValue(':store_id', $store_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt -> fetch(PDO::FETCH_ASSOC);
 // var_dump($result);//store_nameが取れているか確認

 function h($str) { //XSSのためのラッパー関数
    return htmlspecialchars($str, ENT_QUOTES, 'UTF=8');
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>
  <?php
    echo 'ごっっはにゃさん|'.h($result['store_name']);
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
<link rel="stylesheet" type="text/css" href="css/tapiome.css"></link>
</head>
<body>
  <div class="header">
  <a href=https://tapiome.herokuapp.com/>
  <img class = "head_img" src="img/title_logo.png" width="389px" height="60px">
  </a>
</div>


  <h1 class="first">
    <?php
      echo h($result['store_name']);
    ?>
  </h1> <!-- あとで変数 -->

<!-- 営業状態の投票 -->




<?php
// 詳細情報の取得
$stmt_detail_info = $pdo->prepare('SELECT * FROM info WHERE store_id = :store_id');
$stmt_detail_info->bindValue(':store_id', $store_id, PDO::PARAM_INT);
$stmt_detail_info->execute();
$result_detail_info = $stmt_detail_info -> fetch(PDO::FETCH_ASSOC);

// 3桁時間は0詰め
if(intval($result_detail_info["l_time_o"]) < 1000) {
  $result_detail_info["l_time_o"] = '0'.$result_detail_info["l_time_o"];
}
if(intval($result_detail_info["l_time_c"]) < 1000) {
  $result_detail_info["l_time_c"] = '0'.$result_detail_info["l_time_c"];
}
if(intval($result_detail_info["d_time_o"]) < 1000) {
  $result_detail_info["d_time_o"] = '0'.$result_detail_info["d_time_o"];
}
if(intval($result_detail_info["d_time_c"]) < 1000) {
  $result_detail_info["d_time_c"] = '0'.$result_detail_info["d_time_c"];
}

// 0字の0詰め
if(intval($result_detail_info["l_time_o"]) == 0) {
  $result_detail_info["l_time_o"] = '0000';
}
if(intval($result_detail_info["l_time_c"]) == 0) {
  $result_detail_info["l_time_c"] = '0000';
}
if(intval($result_detail_info["d_time_o"]) == 0) {
  $result_detail_info["d_time_o"] = '0000';
}
if(intval($result_detail_info["d_time_c"]) == 0) {
  $result_detail_info["d_time_c"] = '0000';
}

$l_time_o = substr_replace($result_detail_info["l_time_o"], ':', 2, 0);
$l_time_c = substr_replace($result_detail_info["l_time_c"], ':', 2, 0);
$d_time_o = substr_replace($result_detail_info["d_time_o"], ':', 2, 0);
$d_time_c = substr_replace($result_detail_info["d_time_c"], ':', 2, 0);

echo '<p>';
echo 'ランチ：'.h($l_time_o).'~'.h($l_time_c).'<br>';
echo 'ディナー：'.h($d_time_o).'~'.h($d_time_c).'<br>';
echo '定休日：'.h($result_detail_info["holiday"]).'<br>';
echo 'ジャンル：'.h($result_detail_info["genre"]).'<br>';
echo '価格帯：'.h($result_detail_info["price_min"]).'~'.h($result_detail_info["price_max"]).'円<br>';
echo 'TEL：'.h($result_detail_info["tel"]).'<br>';
// echo 'URL：<a class="link" href ='.h($result_detail_info["url"]).'>'.h($result_detail_info["url"]).'</a>';
echo 'URL：<a class="link" href ='.h($result_detail_info["url"]).'>お店のURL</a>';
echo '</p>';
var_dump( split("/", $result_detail_info["url"]) );
echo '<br>';


//投票数のカウント
$stmt_vote = $pdo->prepare('SELECT * FROM sample0801_db LEFT JOIN sample0802_open ON sample0801_db.store_id = sample0802_open.store_id left join sample0802_close on sample0801_db.store_id = sample0802_close.store_id WHERE sample0801_db.store_id = :store_id');
$stmt_vote->bindValue(':store_id', $store_id, PDO::PARAM_INT);
$stmt_vote->execute();
$result_vote = $stmt_vote -> fetch(PDO::FETCH_ASSOC);

// 投票のハッシュのキーの配列
$o_key = array('o0_2','o2_4', 'o4_6','o6_8','o8_10','o10_12','o12_14','o14_16','o16_18','o18_20','o20_22','o22_24');
$c_key = array('c0_2','c2_4', 'c4_6','c6_8','c8_10','c10_12','c12_14','c14_16','c16_18','c18_20','c20_22','c22_24');
$time = array( ' 0:00~ 2:00', ' 2:00~ 4:00', ' 4:00~ 6:00', ' 6:00~ 8:00', ' 8:00~10:00', '10:00~12:00', '12:00~14:00', '14:00~16:00', '16:00~18:00', '18:00~20:00', '20:00~22:00', '22:00~ 0:00');
$time_4h = array( ' 0:00~ 4:00', ' 2:00~ 6:00', ' 4:00~ 8:00', ' 6:00~ 10:00', ' 8:00~12:00', '10:00~14:00', '12:00~16:00', '14:00~18:00', '16:00~20:00', '18:00~22:00', '20:00~24:00', '22:00~ 26:00');
date_default_timezone_set('Asia/Tokyo');
$date = date("H");
$key = intval($date/2);

// 投票数の表示
echo '<p>';
echo '現在<br>';
if($key != 0) { echo (h($result_vote[$o_key[$key-1]]) + h($result_vote[$o_key[$key]])); }
else { echo (h($result_vote[$o_key[$key+11]]) + h($result_vote[$o_key[$key]])); }
echo '人が営業中と言っています<br>';
if($key != 0) { echo (h($result_vote[$c_key[$key-1]]) + h($result_vote[$c_key[$key]])); }
else { echo (h($result_vote[$c_key[$key+11]]) + h($result_vote[$c_key[$key]])); }
echo '人が閉店中と言っています';

echo '<div style="font-size:60%; margin-top:-10px">集計時間 :'.h($time_4h[$key-1]);
echo '<br>';

echo h($time[$key-1]).' ... ';
if($key != 0) { echo '営業中:'.h($result_vote[$o_key[$key-1]]).'人'; }
else { echo '営業中:'.h($result_vote[$o_key[$key+11]]).'人'; }
if($key != 0) { echo ' 閉店中: '.h($result_vote[$c_key[$key-1]]).'人'; }
else { echo ' 閉店中: '.h($result_vote[$c_key[$key+11]]).'人'; }
echo '<br>';
echo h($time[$key]).' ... ';
echo '営業中: '.h($result_vote[$o_key[$key]]).'人';
echo ' 閉店中: '.h($result_vote[$c_key[$key]]).'人';
echo '<br>';
echo '</div>';

echo '</p>';

?>

<p>
  <form method="POST" action="open_close.php"> <!-- open_close.phpに営業中か閉店中かを送る-->
  <input type="hidden" value=<?php echo h($store_id); ?> name="store_id">
  <input class = "button_open" type="submit" value="営業中" name="vote_open">　<!-- 営業中 -->
  <input class = "button_close" type="submit" value="閉店中" name="vote_close">　<!-- 閉店中 -->
  </form>
</p>

<!-- google map -->
<p>
<iframe src="https://maps.google.co.jp/maps?output=embed&q=<?php echo h($result['store_name']); ?>" class="map" frameborder="0" style="border:0" allowfullscreen></iframe>
</p>

<?php
if($result_detail_info['twitter']){
?>
<div class="twi_mobile">
  <a class="twitter-timeline" data-width="100%" data-height="1000px" data-link-color="#2B7BB9" href="https://twitter.com/<?php echo h($result_detail_info['twitter']); ?>?ref_src=twsrc%5Etfw">Tweets by <?php echo h($result_detail_info['twitter']); ?></a>
  <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>

<div class="twi_pc">
  <a class="twitter-timeline" data-width="50%" data-height="500px" data-link-color="#2B7BB9" href="https://twitter.com/<?php echo h($result_detail_info['twitter']); ?>?ref_src=twsrc%5Etfw">Tweets by <?php echo h($result_detail_info['twitter']); ?></a>
  <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>

<?php
}

echo '<p>';
echo '↓↓↓同じジャンルのお店を探す↓↓↓<br>';
$stmt_genre = $pdo->prepare('SELECT * FROM info LEFT JOIN sample0801_db ON info.store_id = sample0801_db.store_id WHERE genre=:genre');
$stmt_genre->bindValue(':genre', $result_detail_info['genre'], PDO::PARAM_STR);
$stmt_genre->execute();
if($stmt_genre) {
  $i = 0;
  while($result_genre = $stmt_genre -> fetch(PDO::FETCH_ASSOC)) {
    if($result_genre['store_id'] != $store_id && mt_rand()%2 == 0) {
      // ランダムで表示
      echo '<a style="text-decoration: none; color:#000000;" href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result_genre['store_id'].'">';
      echo '<div class="box1">';
      echo h($result_genre['store_name']);
      echo '</div></a>';
      $i+=1;
    }
    if($i >= 4) { break; }
  }
}

echo '</p>';
?>

</body>
</html>
