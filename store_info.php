<?php
  $store_name = "龍郎"
?> <!-- POSTで店名を引っ張ってくる -->


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>
  <?php
    echo $store_name;
  ?>
</title> <!-- あとで変数 -->
</head>
<body>
  <h1>
    <?php
      echo $store_name;
    ?>
  </h1> <!-- あとで変数 -->

<p>営業中票数</p>
<p>閉店中票数</p> <!-- あとでDBから持ってくる -->

</body>
</html>
