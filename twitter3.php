<?php
//twitteroauth.phpを読み込み
require_once dirname(__FILE__) .'/tmhOAuth.php';
//検索ワード配列
$keyword_list = array("ポケモン","パズドラ");
//最大検索数
$countmax = 10;
//twitterAppsで取得
$consumerKey = '*****';
$consumerSecret = '*****';
$accessToken = '*****';
$accessTokenSecret = '*****';
$to = new TwitterOAuth(
    $consumerKey,
    $consumerSecret,
    $accessToken,
    $accessTokenSecret
);
//Twitterで検索するワード
//複数の場合はORかANDを使う
//「ポケモン OR パズドラ」のような形になればいい
$key = "";
$size = count($keyword_list);
for($i=0;$i<$size;$i++){
    $keyword = $keyword_list[$i];
    $key .= $keyword;
    if($i<$size-1){
        $key .= " AND ";
    }
}
//オプション設定
//countmaxは最大検索数
$options = array('q'=>$key,'count'=>$countmax,'lang'=>'ja');
//検索
$json = $to->OAuthRequest(
    'https://api.twitter.com/1.1/search/tweets.json',
    'GET',
    $options
);
$jset = json_decode($json, true);
//tweetidを取得
foreach ($jset['statuses'] as $result) {
    //ローマ字の名前
    $screen_name = $result['user']['screen_name'];
    //ユーザーID(数字)
    $id = $result['user']['id'];
    //ユーザー名
    $name = $result['user']['name'];
    //ユーザーアイコン画像URL
    $link = $result['user']['profile_image_url'];
    //該当ツイート
    $content = $result['text'];
    //更新日
    $updated = $result['created_at'];
    $time = date("Y-m-d H:i:s",strtotime($updated));
    //不明なものはprint_rで見ればいい
    //print_r($result);
    echo "<img src='".$link."''>"." | ".$screen_name." | ".$id." | ".$name." | ".$content." | ".
        $time."<br>";
}
?>
