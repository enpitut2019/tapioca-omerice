<?php
// データベースに接続
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
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
$word = $_GET["kensaku"]; //検索ワード
$stmt = $pdo->query('SELECT * FROM sample0801_db WHERE store_name LIKE \'%'.$word.'%\'');

if($stmt){
  while($result = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    echo $result['store_name'];
    echo '<a href ="https://tapiome.herokuapp.com/store_info.php?store_id='.$result['store_id'].'">詳細情報</a>'
  }
}else{
  echo 'dame';
}
?>

<a href = "http://tapiome.herokuapp.com/store_info.php">店舗情報</a>
</body>
</html>
