<?php
// データベースに接続するよ
$url = parse_url(getenv('DATABASE_URL'));
$dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
$pdo = new PDO($dsn, $url['user'], $url['pass']);
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ごっっはにゃさん検索結果</title>
</head>
<body>
  <h1>ごっっはにゃさん結果</h1>
<?php
$stmt2 = $pdo->query('SELECT store_id FROM info WHERE status = 1');
$stmt = $pdo->query('SELECT * FROM sample0801_db WHERE store_id LIKE \'%'.$stmt2.'%\'');

  if($stmt){
    while($result = $stmt -> fetch(PDO::FETCH_ASSOC)) {
      echo $result['store_name'];
      echo '：<a href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result['store_id'].'">詳細情報</a><br>';
    }
  }else{
    echo 'dame';
  }
 
?>

</body>
</html>
