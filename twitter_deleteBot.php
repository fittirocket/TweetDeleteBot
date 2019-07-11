<?php

require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

//User Settings
$app_key = "APT_KEX";
$app_secret = "APP_SECRET";

$access_token_secret = "ACCESS_TOKEN_SECRET";
$access_token = "ACCESS_TOKEN";

$your_screenname = 'TWITTER_USERNAME';
//

$connection = new TwitterOAuth($app_key, $app_secret, $access_token, $access_token_secret);
$content = $connection->get("account/verify_credentials");

if ($connection->getLastHttpCode() == 200) {
  echo 'login works<br>';
} else {
  echo 'login error;'.$connection->getLastHttpCode();
  die();
}

$parameters = [
  'screen_name' => $your_screenname,
  'count' => '200'
];

$statuses = $connection->get("statuses/user_timeline", $parameters);


if ($connection->getLastHttpCode() == 200) {
   $tweetsArray = $connection->getLastBody();

   for($i=0;$i<count($tweetsArray);$i++) {
     $temp = $tweetsArray[$i];
     $datetime = new DateTime($temp -> created_at);
     $datetime->setTimezone(new DateTimeZone('Europe/Zurich'));

     $tweetTime = $datetime->format('U');

     if(time() > $tweetTime+60*60*24*30) {

     echo $temp -> id.' is deleted<br>';
       $statues = $connection->post("statuses/destroy/".$temp -> id."", ['id' => $temp -> id]);
       print_r($connection->getLastBody());
     } else {
     echo $temp -> id.' is not deleted<br>';
     }
   }

} else {
   echo 'Error: '.$connection->getLastHttpCode().' ';
}


?>
