<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
// var_dump($_POST);

// 投票数を取得
$store_id = $_POST['store_id'];
$stmt_vote = $pdo->prepare('SELECT * FROM sample0801_db LEFT JOIN sample0802_open ON sample0801_db.store_id = sample0802_open.store_id left join sample0802_close on sample0801_db.store_id = sample0802_close.store_id WHERE sample0801_db.store_id = :store_id');
$stmt_vote->bindValue(':store_id', $store_id, PDO::PARAM_INT);
$stmt_vote->execute();
$result_vote = $stmt_vote -> fetch(PDO::FETCH_ASSOC);

$stmt_info = $pdo->prepare('SELECT * FROM info WHERE store_id = :store_id');
$stmt_info->bindValue(':store_id', $store_id, PDO::PARAM_INT);
$stmt_info->execute();
$result_info = $stmt_info -> fetch(PDO::FETCH_ASSOC);


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

// var_dump($date);
// var_dump(intval($date));

$index = intval($date/2);

//セッションの有効期限を120分に設定
session_set_cookie_params(60 * 120);
// セッション管理開始
session_start();
// session_start([
//     'cookie_lifetime' => 300,
// ]);
//
// echo "セッションチェック:　　　　", $_SESSION[$session_key],"<br>";
// echo "現在のセッション名は 　　　". session_name() ." です。<br>";
// echo "現在のセッションIDは 　　　". session_id() ." です。<br>";
// echo "現在のセッションデータは　　". session_save_path() ."に保存されています。<br>";

$session_key = '\''.$store_id.'\'';
var_dump($_SESSION[$session_key]);
if (!isset($_SESSION[$session_key])) {
    // キー'$store_id'が登録されていなければ、1を設定
    echo "ない";
    $_SESSION[$session_key] = 1;
} else {
    //  キー'$store_id'が登録されていれば、その値をインクリメント
    echo "ある";
    $_SESSION[$session_key]++;
}
var_dump($_SESSION[$session_key]);

if($_SESSION[$session_key] == 1) {
  if(strcmp($_POST['vote_open'], '営業中') == 0) { // 営業中
    $result_vote[ $o_key[$index]]+=1;
    // $stmt = $pdo->prepare('UPDATE sample0802_open SET '.$o_key[$index].'='.$result_vote[$o_key[$index]].'WHERE store_id='.$_POST['store_id']);
    // $stmt->execute();
    $stmt = $pdo->prepare('UPDATE sample0802_open SET '.$o_key[$index].' = :result_vote WHERE store_id = :store_id');
    $stmt->bindValue(':result_vote', $result_vote[$o_key[$index]], PDO::PARAM_INT);
    $stmt->bindValue(':store_id', $_POST['store_id'], PDO::PARAM_INT);
    $stmt->execute();
  } else if(strcmp($_POST['vote_close'], '閉店中') == 0) { // 閉店中
    $result_vote[ $c_key[$index]]+=1;
    $stmt = $pdo->prepare('UPDATE sample0802_close SET '.$c_key[$index].' = :result_vote WHERE store_id = :store_id');
    $stmt->bindValue(':result_vote', $result_vote[$c_key[$index]], PDO::PARAM_INT);
    $stmt->bindValue(':store_id', $_POST['store_id'], PDO::PARAM_INT);
    $stmt->execute();
  }

  if(($result_vote[$o_key[$index-1]] + $result_vote[$o_key[$index]]) > ($result_vote[$c_key[$index-1]] + $result_vote[$c_key[$index]])){
    $stmt = $pdo->prepare('UPDATE info SET status= 1 WHERE store_id = :store_id');
    $stmt->bindValue(':store_id', $_POST['store_id'], PDO::PARAM_INT);
    $stmt->execute();
  } else if(($result_vote[$o_key[$index-1]] + $result_vote[$o_key[$index]]) < ($result_vote[$c_key[$index-1]] + $result_vote[$c_key[$index]])){
    $stmt = $pdo->prepare('UPDATE info SET status = 0 WHERE store_id = :store_id');
    $stmt->bindValue(':store_id', $_POST['store_id'], PDO::PARAM_INT);
    $stmt->execute();
  } else {
    $stmt = $pdo->prepare('UPDATE info SET status = 2 WHERE store_id = :store_id');
    $stmt->bindValue(':store_id', $_POST['store_id'], PDO::PARAM_INT);
    $stmt->execute();
  }
?>

<!DOCTYPE html>
<html lang="ja">
<meta charset="UTF-8">
<body>
  <!-- <p>ありがとうございます！</p> -->
  <center style="margin-top:50px;">
  <img class = "head_img" src="img/thanks.png" width="455px" height="55px">
  </center>

<?php
} else {
  echo "投票は2時間に1回までです。";
}
?>

<!-- <script type="text/javascript">
  setTimeout(function(){
 window.location.href = 'https://tapiome.herokuapp.com/store_info_count.php?store_id=<?php echo $_POST['store_id']; ?>';
}, 3*1000);
</script> -->
</body>
</html>
