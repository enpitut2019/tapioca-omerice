<?php
//idの取得
$store_id = $_GET["store_id"];

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
$stmt = $pdo->query('SELECT store_name FROM sample0801_db WHERE store_id = '.$store_id);
$result = $stmt -> fetch(PDO::FETCH_ASSOC);
 // var_dump($store_name);//store_nameが取れているか確認
?>

<!-- 投票数のカウント -->
<?php
$stmt_vote = $pdo->query('SELECT * FROM sample0801_db LEFT JOIN sample0802_open ON sample0801_db.store_id = sample0802_open.store_id left join sample0802_close on sample0801_db.store_id = sample0802_close.store_id WHERE sample0801_db.store_id ='.$store_id);
$result_vote = $stmt_vote -> fetch(PDO::FETCH_ASSOC);
var_dump($result_vote);
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

<?php
  $vote_o = "4";
  $vote_c = "4";
?>


<?php
if($vote_o > $vote_c){
  echo '営業中票数:<font color="RED">'.$vote_o.'</font><br />';
  echo '閉店中票数:'.$vote_c;
} else if($vote_o < $vote_c){
  echo '営業中票数:'.$vote_o.'<br />';
  echo '閉店中票数:<font color="RED">'.$vote_c.'</font>';
} else {
  echo '営業中票数:'.$vote_o.'<br />';
  echo '閉店中票数:'.$vote_c;
}

?>

</body>
</html>
