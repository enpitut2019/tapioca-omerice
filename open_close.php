<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
// var_dump($_POST);

// 投票数を取得
$stmt_vote = $pdo->query('SELECT * FROM sample0801_db LEFT JOIN sample0802_open ON sample0801_db.store_id = sample0802_open.store_id left join sample0802_close on sample0801_db.store_id = sample0802_close.store_id WHERE sample0801_db.store_id ='.$_POST['store_id']);
$result_vote = $stmt_vote -> fetch(PDO::FETCH_ASSOC);

// hashのkeyの配列
$o_key = array('o0_2','o2_4','o4_6','o6_8','o8_10','o10_12','o12_14','o14_16','o16_18','o18_20','o20_22','o22_24');
$c_key = array('c0_2','c2_4','c4_6','c6_8','c8_10','c10_12','c12_14','c14_16','c16_18','c18_20','c20_22','c22_24');

// 現在時刻の取得
date_default_timezone_set('Asia/Tokyo');
$date = date("H");

// 投票データの更新
// o_key[0]=o0_2
// o_key[1]=o2_4
// o_key[2]=o4_6
// ....
// o_key[11]=o22_24

var_dump($date);
var_dump(intval($date));



if(strcmp($_POST['vote_open'], '営業中') == 0) { // 営業中
  $result_vote[ $o_key[intval($date)/2]]+=1;
  $stmt = $pdo->prepare('UPDATE sample0802_open SET '.$o_key[intval($date)/2].'='.$result_vote[$o_key[intval($date)/2]].'WHERE store_id='.$_POST['store_id']);
  $stmt->execute();
} else if(strcmp($_POST['vote_close'], '閉店中') == 0) { // 閉店中
  $result_vote[ $c_key[intval($date)/2]]+=1;
  $stmt = $pdo->prepare('UPDATE sample0802_close SET '.$c_key[intval($date)/2].'='.$result_vote[$c_key[intval($date)/2]].'WHERE store_id='.$_POST['store_id']);
  $stmt->execute();
}



 header('Location:https://tapiome.herokuapp.com/store_info_count.php?store_id='.$_POST['store_id']);
 exit();

?>
</html>
