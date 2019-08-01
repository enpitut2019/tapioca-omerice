<?php
$url = parse_url(getenv('DATABASE_URL'));

$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));

$pdo = new PDO($dsn, $url['user'], $url['pass']);
var_dump($pdo->getAttribute(PDO::ATTR_SERVER_VERSION));
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
