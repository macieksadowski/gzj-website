<!DOCTYPE HTML>
<?php
	require_once './fbCredentials.php';
	require_once __DIR__ . '/vendor/autoload.php';   
	
	
	
	$fb = new \Facebook\Facebook([
	  'app_id' => app_id,           //Replace {your-app-id} with your app ID
	  'app_secret' => app_secret,   //Replace {your-app-secret} with your app secret
	  'graph_api_version' => graph_api_version,
	]);


	try {
	   
	// Get your UserNode object, replace {access-token} with your token
	  $response = $fb->get('glownyzaworjazzu?fields=events{cover,id,name,start_time,place,ticket_uri,description}', access_token);
	  

	} catch(\Facebook\Exceptions\FacebookResponseException $e) {
			// Returns Graph API errors when they occur
	  //header('Location: ..\index.php');
		//exit();
	} catch(\Facebook\Exceptions\FacebookSDKException $e) {
			// Returns SDK errors when validation fails or other local issues
	  //header('Location: ..\index.php');
		//exit();
	}
	
	$response_decoded = $response->getGraphObject()->asArray();
	$events = $response_decoded['events'];
	$actualTime = new DateTime();
	$actualEvents = array();
	foreach($events as $event)
	{
		
		$time = $event['start_time'];
		if($actualTime < $time) 
		{
			array_push($actualEvents,$event);
		}
	}
	$actualEvents = array_reverse($actualEvents);

	setlocale(LC_ALL, "pl_PL.UTF-8");

?>

<html lang="pl">
<head>
<?php 
	require_once "../head.php"; 
	
?>
<link href="./style.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<?php
	require_once "../menu.php";
	
	?>
	<div class="title"><h1>Koncerty</h1></div>
	<div class="event-grid">
	<?php
	
		
	if(empty($actualEvents))
	echo '<div class="title"><h1>BRAK NADCHODZĄCYCH KONCERTÓW</h1></div>';
	else {
		//$event = $actualEvents[0];
		foreach($actualEvents as $key=>$event)
		{
			echo '<div class="event">
	
				<div class="event-cover" >
				
						<img src="'.$event['cover']['source'].'">
				</div>
				<div class="event-text">
					
					<div class="event-name">
						<h1>'.$event['name'].'</h1>
					</div>
					
					<div class="event-date">
						'.strftime('%A %e.%m.%yr. godz.%H:%M',$event['start_time']->getTimestamp()).'					
						
					</div>';
					
					
					$re = '/wstęp wolny/miu';
					preg_match_all($re, $event['description'], $matches, PREG_SET_ORDER, 0);
					
					
					if(isset($event['ticket_uri'])) echo '
					<label><div class="button"><a href="'.$event['ticket_uri'].'" target="_blank">Kup Bilet</a></div></label>';
					else if(!empty($matches)) echo '<div class="ticketInfo">Wstęp wolny</div>';
					else echo '<div class="ticketInfo">Bilety do nabycia u&nbsp;organizatora</div>';
					
					echo '
					
					
					<a href="https://facebook.com/events/'.$event['id'].'"  target="_blank"><div class="event-social fb">
						<i class="icon-facebook-official"></i>
					</div></a>
					
					<div style="clear:both"></div>
					
					
					<div class="event-place">
						<b>'.$event['place']['name'].'</b><br /> ';
						if(isset($event['place']['location'])) 
						echo 
					
						$event['place']['location']['city'].', '.$event['place']['location']['street'];
					echo '	
					</div>
					
					
					

				</div>
			</div>';  
		}
	}
	?>
		
	
	
	</div>
	<?php
	require_once "../footer.php";
	?>



</body>
</html>