<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Twitter REST API OAuth接続 ホームタイムライン取得[ GET statuses/home_timeline.json ] | WEPICKS!</title>
</head>
<body>

<h1>Twitter REST API OAuth接続 ホームタイムライン取得[ GET statuses/home_timeline.json ]</h1>

<?php
//tmhOAuth.phpをインクルードします。ファイルへのパスはご自分で決めて下さい。
require_once("./tmhOAuth.php");

//Access Tokenの設定 apps.twitter.com でご確認下さい。
//Consumer keyの値を格納
$sConsumerKey = 'lHLmAILskvafc4FaZS5Q3VcJA';
//Consumer secretの値を格納
$sConsumerSecret = 'gX6Umd9HiC4rCJMK0K3O60ObXHUlu8OexmRbkwZanXnGWyvaK8';
//Access Tokenの値を格納
$sAccessToken = '872286707508330496-5VoTe7nMKOWBDspljt0n124fRejhNXF';
//Access Token Secretの値を格納
$sAccessTokenSecret = '8VSlmw7tdctNLADqiI40mHwau2x6Ic1gn9rvYTq01pjQJ';

//OAuthオブジェクトを生成する
$twObj = new tmhOauth(
						array(
						"consumer_key" => $sConsumerKey,
						"consumer_secret" => $sConsumerSecret,
						"token" => $sAccessToken,
						"secret" => $sAccessTokenSecret,
						"curl_ssl_verifypeer" => false,
						)
					);

//Twitter REST API 呼び出し
$code = $twObj->request( 'GET', "https://api.twitter.com/1.1/statuses/home_timeline.json",array("count"=>"10"));

// statuses/home_timeline.json の結果をjson文字列で受け取り配列に格納
$aResData = json_decode($twObj->response["response"], true);

//配列を展開
if(isset($aResData['errors']) && $aResData['errors'] != ''){
	?>
	取得に失敗しました。<br/>
	エラー内容：<br/>
	<pre>
	<?php var_dump($aResData); ?>
	</pre>
<?php
}else{
	//配列を展開
	$iCount = sizeof($aResData);
	for($iTweet = 0; $iTweet<$iCount; $iTweet++){
		$iTweetId = $aResData[$iTweet]['id'];
		$sIdStr = (string)$aResData[$iTweet]['id_str'];
		$sText= $aResData[$iTweet]['text'];
		$sName= $aResData[$iTweet]['user']['name'];
		$sScreenName= $aResData[$iTweet]['user']['screen_name'];
		$sProfileImageUrl = $aResData[$iTweet]['user']['profile_image_url'];
		$sCreatedAt = $aResData[$iTweet]['created_at'];
		$sStrtotime= strtotime($sCreatedAt);
		$sCreatedAt = date('Y-m-d H:i:s', $sStrtotime);
		?>
		<hr/>
		<h3><?php echo $sName; ?>さんのつぶやき</h3>
		<ul>
		<li>IDNO[id] : <?php echo $iTweetId; ?></li>
		<li>名前[name] : <?php echo $sIdStr; ?></li>
		<li>スクリーンネーム[screen_name] : <?php echo $sScreenName; ?></li>
		<li>プロフィール画像[profile_image_url] : <img src="<?php echo $sProfileImageUrl; ?>" /></li>
		<li>つぶやき[text] : <?php echo $sText; ?></li>
		<li>ツイートタイム[created_at] : <?php echo $sCreatedAt; ?></li>
		</ul>
<?php
	}//end for
}
?>

</body>
</html>
