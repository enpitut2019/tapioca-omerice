<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

// 投票数を取得
$stmt_vote = $pdo->query('SELECT * FROM sample0801_db LEFT JOIN sample0802_open ON sample0801_db.store_id = sample0802_open.store_id left join sample0802_close on sample0801_db.store_id = sample0802_close.store_id WHERE sample0801_db.store_id ='.$store_id);
$result_vote = $stmt_vote -> fetch(PDO::FETCH_ASSOC);

var_dump($_POST);

if(strcmp($_POST['vote_open'], '営業中') == 0) { // 営業中
  echo '営業中';
  $result_vote['o0_2']+=1;
  $stmt = $pdo->prepare('UPDATE sample0802_open SET o0_2='.$result_vote['o0_2'].'WHERE store_id='.$_POST['store_id']);
  $stmt->execute();

} else if(strcmp($_POST['vote_close'], '閉店中') == 0) { // 閉店中
  $result_vote['c0_2']+=1;
  $stmt = $pdo->prepare('UPDATE sample0802_close SET c0_2='.$result_vote['c0_2'].'WHERE store_id='.$_POST['store_id']);
  $stmt->execute();
}



// header('Location:https://tapiome.herokuapp.com/store_info_count.php?store_id='.$_POST['store_id']);
// exit();

?>
