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
  <p>
    <a href=https://tapiome.herokuapp.com/>ごっっはにゃさん</a>
  </p>
  <h1>ごっっはにゃさん結果</h1>
  <?php
  $stmt2 = $pdo->query('SELECT * FROM info WHERE status = 1');
  if($stmt2){
    while($result = $stmt2 -> fetch(PDO::FETCH_ASSOC)) {
      $stmt = $pdo->query('SELECT * FROM sample0801_db WHERE store_id = '.$result['store_id']);
      $result2 = $stmt -> fetch(PDO::FETCH_ASSOC);
      var_dump($result);
      echo '</br>';
      var_dump($result2);
      echo '</br>';
    }

    if($stmt){
      while($result = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        echo $result['store_name'];
        echo '：<a href ="https://tapiome.herokuapp.com/store_info_count.php?store_id='.$result['store_id'].'">詳細情報</a><br>';
      }
    }else{
      echo '営業中の店舗はありません';
    }
  }

   
  ?>

</body>
</html>
