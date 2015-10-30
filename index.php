<?php
	$token = "your facebook access token here";

	require('fb-php-sdk/facebook.php');

	$facebook = new Facebook(array(
	   'appId' => '',
	   'secret' => '',
	   'cookie' => true
	));
	// fetch friends data with birthdate.
	try
	{
		$parameters['access_token'] = $token;
		$userData = $facebook->api('/me/friends?fields=name,birthday', $parameters);
	}
	catch (FacebookApiException $e)
	{
		echo "token expired.";
		mail('ganganimaulik@gmail.com', 'AutoBirthDay Poster', 'your token for automatic birthday post is expired');
	}
	date_default_timezone_set ("Asia/Kolkata");
	$todayDate = date('d');
	$todayMonth = date('m');

	$message = "Happy Birthday 🎈 😄";
	$attachment = array('message' => $message, 'access_token' => $token);
	$mailMsg = "";
	foreach ($userData['data'] as $key => $value) {
		if ($todayMonth == $value['birthday'][0] . $value['birthday'][1] && $todayDate == $value['birthday'][3]. $value['birthday'][4]) {
			echo "name : ". $value['name']. " link : <a href='http://facebook.com/". $value["id"]. "'>". $value['id'] . "</a> birthdate : " . $value["birthday"];
			
			try {
				$result = $facebook->api("/". $value["id"] ."/feed/",'post', $attachment);
				echo " status: <div style='color:green'><em><strong>Success!</strong></em></div>";
				$mailMsg = $mailMsg."\n". $value['name']. " : Success";
			} catch (Exception $e) {
				echo " status: <div style='color:red'><em><strong>No permission for posting</strong></em></div>";
				        echo $e->getMessage();
				$mailMsg = $mailMsg."\n". $value['name']. " : failed";
			}
			echo "<br>";
		}
	}
		mail('ganganimaulik@gmail.com', 'AutoBirthDay Poster', 'attempt Success....\n'.$mailMsg);