<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>Hello tapioka</title>
</head>
<body>
  <h1>龍郎公式サイト(仮)</h1>
<?php
    echo "Hello tapioka!";
?>

<form method="get" action="result.php">
<input type="search" name="kensaku" ><input type="submit" value="検索">
</form>

<a href = "http://tapiome.herokuapp.com/store_info.php">店舗情報</a>

<?php
  $url = parse_url(getenv('DATABASE_URL'));

  $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
  $pdo = new PDO($dsn, $url['user'], $url['pass']);

  $stmt = $pdo->query("SELECT * FROM sample0801_db");
  $row = $stmt -> fetch(PDO::FETCH_ASSOC

  echo $row["store_id"];
  
 ?>

</body>
</html>
