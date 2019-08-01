<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);

$stmt = $pdo->query('SELECT * FROM sample0801_db');

if($stmt){
  while($result = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $result['store_id'];
    echo $result['store_name'];
  }
}else{
  echo 'dame';
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>Good Bye tapioka</title>
</head>
<body>
  <h1>検索結果</h1>
<?php
echo $_GET["kensaku"]."です!!";
?>
<a href = "http://tapiome.herokuapp.com/store_info.php">店舗情報</a>
</body>
</html>
