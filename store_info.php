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

<?php
  $vote_o = "2";
  $vote_c = "1";
?>
<p>
  営業中票数:
  <?php
    echo $vote_o;
  ?>
</p>

<p>
  閉店中票数:
  <?php
    echo $vote_c;
  ?>
</p> <!-- あとでDBから持ってくる -->

<?php
if($vote_o > $vote_c){
  echo "営業中票数:".$vote_o;
  echo "閉店中票数:".$vote_c;
} else if($vote_o < $vote_c){
  echo "営業中票数:".$vote_o;
  echo "閉店中票数:".$vote_c;
} else {
  echo "営業中票数:".$vote_o;
  echo "閉店中票数:".$vote_c;
}

?>

</body>
</html>
