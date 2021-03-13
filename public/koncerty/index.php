<!DOCTYPE HTML>
<?php
	require_once './fbCredentials.php';
	require_once __DIR__ . '/vendor/autoload.php';   

	$fb = new \Facebook\Facebook([
	  'app_id' => app_id,
	  'app_secret' => app_secret,
	  'graph_api_version' => graph_api_version,
	]);

	try 
	{
	  $response = $fb->get('glownyzaworjazzu?fields=events{cover,id,name,start_time,place,ticket_uri,description,is_online}', access_token);
	} 
	catch(\Facebook\Exceptions\FacebookResponseException $e) 
	{
			// Returns Graph API errors when they occur
	 
	}
	catch(\Facebook\Exceptions\FacebookSDKException $e) 
	{
			// Returns SDK errors when validation fails or other local issues
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
	$pageTitle = "Koncerty";
?>
<link href="./style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!-- This div is used as container for whole page-->
<div class="page-container">
	<?php
		require_once "../menu.php";
	?>
	<main>
	<!-- This is a container for main content of page. -->
	<div class="content">
		<div class="title"><h1><?=$pageTitle;?></h1></div>
		<div class="event-grid">
		<?php
			
		if(empty($actualEvents))
		{
		?>
			<div class="title"><h1>BRAK NADCHODZĄCYCH KONCERTÓW</h1></div>
		<?php
		}
		else 
		{
			foreach($actualEvents as $key=>$event)
			{
				?>
			<div class="event">
			
				<div class="event-cover" >
					<img src="<?=$event['cover']['source'];?>">
				</div>
				<div class="event-text">
					<div class="event-name">
						<h1><?=$event['name'];?></h1>
					</div>
					<div class="event-date">
						<?php echo strftime('%A %e.%m.%yr. godz.%H:%M',$event['start_time']->getTimestamp());?>
					</div>
				<?php				
					$re = '/wstęp wolny/miu';
					preg_match_all($re, $event['description'], $matches, PREG_SET_ORDER, 0);		
					if($event['is_online'] ==true) 
						echo '<div class="ticketInfo">Wydarzenie online</div>';
					else if(isset($event['ticket_uri'])) 
						echo '<button><a href="'.$event['ticket_uri'].'" target="_blank">Kup Bilet</a></button>';
					else if(!empty($matches)) 
						echo '<div class="ticketInfo">Wstęp wolny</div>';
					else 
						echo '<div class="ticketInfo">Bilety do nabycia u&nbsp;organizatora</div>';
				?>
						
					<a href="https://facebook.com/events/<?=$event['id'];?>"  target="_blank" class="event-social fb">
						<i class="icon-facebook-official"></i>
					</a>			
					<div style="clear:both"></div>
					<div class="event-place">
						<b><?=@$event['place']['name'];?></b><br />
				<?php	
					if(@isset($event['place']['location'])) 
						echo $event['place']['location']['city'].', '.$event['place']['location']['street'];
				?>
					</div>
				</div>
			</div>
		<?php
			}
		}
		?>
		</div>  
		
	</div>
	</main>
	<?php require_once "../footer.php";	?>
</div>
</body>
</html>