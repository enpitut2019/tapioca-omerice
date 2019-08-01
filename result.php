<?php
$url = parse_url(getenv('DATABASE_URL'));
try {
$pdo = new PDO('mysql:host='$url['host']';dbname='substr($url['path'], 1)';charset=utf8', $url['user'],$url['pass'],array(PDO::ATTR_EMULATE_PREPARES => false));
} catch (PDOException $e) {
 exit('データベース接続失敗。'.$e->getMessage());
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
