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


$index = intval($date/2);

// 複数回投票を防ぐためのセッション管理
//セッションの有効期限を120分に設定
// session_set_cookie_params(30);
// ini_set('session.gc_maxlifetime', 20);
// ini_set('session.gc_probability', 1);
// ini_set('session.gc_divisor', 1);
// セッション管理開始
session_start();

function now_time(){
  return intval(date("H"))*3600+intval(date("i"))*60+intval(date("s"));
}


$session_key = '\''.$store_id.'\'';
// リセット
if(now_time() - $_SESSION[$session_key] > 2*60*60 || now_time() - $_SESSION[$session_key] <= 0) {
  unset($_SESSION[$session_key]);
}

$flag = 0;

if (!isset($_SESSION[$session_key])) {
    // キー'$store_id'が登録されていなければ、1を設定
    $_SESSION[$session_key] = now_time();
    $flag = 1;
} else {
    //  キー'$store_id'が登録されていれば、その値をインクリメント
    // $_SESSION[$session_key]++;
    $flag = 0;
}
// var_dump($_SESSION[$session_key]);
// var_dump($flag);



if($flag == 1) {
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

<script type="text/javascript">
  setTimeout(function(){
 window.location.href = 'https://tapiome.herokuapp.com/store_info_count.php?store_id=<?php echo $_POST['store_id']; ?>';
}, 2*1000);
</script>
</body>
</html>
