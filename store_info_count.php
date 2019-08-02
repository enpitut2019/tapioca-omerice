<?php
//idの取得
$store_id = $_GET["store_id"];

// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
$stmt = $pdo->query('SELECT store_name FROM sample0801_db WHERE store_id = '.$store_id);
$result = $stmt -> fetch(PDO::FETCH_ASSOC);
 var_dump($result);//store_nameが取れているか確認
?>
<!-- 投票数のカウント -->
<?php
$stmt_vote = $pdo->query('SELECT * FROM sample0801_db LEFT JOIN sample0802_open ON sample0801_db.store_id = sample0802_open.store_id left join sample0802_close on sample0801_db.store_id = sample0802_close.store_id WHERE sample0801_db.store_id ='.$store_id);
while($result_vote = $stmt_vote -> fetch(PDO::FETCH_ASSOC)) {
  echo $result_vote['c0_2'];
}
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

<!-- 0時から2時の営業 -->
<?php
// if($reslut_vote['o0_2'] > $reslut_vote['c0_2']){
//   echo '営業中票数:<font color="RED">'.$reslut_vote['o0_2'].'</font><br />';
//   echo '閉店中票数:'.$reslut_vote['c0_2'];
// } else if($reslut_vote['o0_2'] < $reslut_vote['c0_2']){
//   echo '営業中票数:'.$reslut_vote['o0_2'].'<br />';
//   echo '閉店中票数:<font color="RED">'.$reslut_vote['c0_2'].'</font>';
// } else {
//   echo '営業中票数:'.$reslut_vote['o0_2'].'<br />';
//   echo '閉店中票数:'.$reslut_vote['c0_2'];
// }
var_dump($reslut_vote['c0_2']);

?>

</body>
</html>
