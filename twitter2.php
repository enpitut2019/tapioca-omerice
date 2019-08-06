<?php
define('TWITTER_API_KEY', 'lHLmAILskvafc4FaZS5Q3VcJA'); //Consumer Key (API Key)
define('TWITTER_API_SECRET', 'gX6Umd9HiC4rCJMK0K3O60ObXHUlu8OexmRbkwZanXnGWyvaK8');//Consumer Secret (API Secret)

// twitteroauth の読み込み
require_once $fullpath.'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

//TwitterOAuthのインスタンスを生成
$connection = new TwitterOAuth(TWITTER_API_KEY, TWITTER_API_SECRET, $access_token, $access_token_secret);

//「search/tweets」エンドポイントを利用し「4ndan.com」を含むツイートを15件取得
$statuses = $connection->get("search/tweets", ["q" => "火曜","count" => 15,"tweet_mode" => "extended"]);

if(count($statuses['statuses']) != 0){ ?>
				<ul class="sp_list tweetslist">
					<?php foreach($statuses['statuses'] as $value){ ?>
					<li class="stream-item-header">
						<div class="account-group">
							<img src="<?php echo $value['user']['profile_image_url_https']; ?>" alt="<?php echo $value['user']['name']; ?>">
							<span class="FullNameGroup"><?php echo $value['user']['name']; ?></span>
							<span class="username"><a href="https://twitter.com/<?php echo $value['user']['screen_name']; ?>" target="_blank">@<?php echo $value['user']['screen_name']; ?></a></span>
							<small class="time"><?php echo $value['created_at']; ?></small>
						</div>
						<div class="js-tweet-text-container">
							<p><?php echo nl2br($value['full_text']); ?></p>
						</div>
					</li>
					<?php } ?>
				</ul>
