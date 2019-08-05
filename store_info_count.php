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

// 投票数の表示
if($result_vote['o0_2'] > $result_vote['c0_2']){
  echo '営業中票数:<font color="RED">'.$result_vote['o0_2'].'</font><br />';
  echo '閉店中票数:'.$result_vote['c0_2'];
} else if($result_vote['o0_2'] < $result_vote['c0_2']){
  echo '営業中票数:'.$result_vote['o0_2'].'<br />';
  echo '閉店中票数:<font color="RED">'.$result_vote['c0_2'].'</font>';
} else {
  echo '営業中票数:'.$result_vote['o0_2'].'<br />';
  echo '閉店中票数:'.$result_vote['c0_2'];
}
?>

</body>
</html>
