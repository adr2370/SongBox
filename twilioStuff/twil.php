<?php
	require "../Services/Twilio.php"; // for Twilio REST API

	session_start(); // start session for Twilio SMS response 

	$AccountSid = "AC015bae4a78bec6d2699d094ea378057b";
    $AuthToken = "bcb0ad9983482a41ecc92443ab210dfa";
	$client = new Services_Twilio($AccountSid, $AuthToken); // instantiate Twilio client
	
	// Twilio API Query 
	$counter = $_SESSION['counter']; // sets up a session / conversation (Twilio saves info for 4 hours...ish)
	$body = $_REQUEST['Body']; // gets the body of the message received
	$number = $_REQUEST['From']; // gets the sender of the message received	
	$isAdmin = false; //0 is not admin
	$url = './';
	
	$counter = 1;	
	//confirm user is admin
	$number = substr($number, 1);	
	if($number == "17145857755" || $number == "16572060254" || $number == "19494229778")
	{
		$isAdmin = true;
	}
	
	
	//initiates counter to reflect new session
	if(!strlen($counter))
	{
		$counter = 0;
	}

	//options
	$text = "Text any of the following commands: 'add <SONG NAME>', 'karaoke <SONG NAME>', 'info' (get song information),";
	$textA = "'queue' (see playlist queue), 'options' (list all commands), OR text us any comment you want";
	$textB = "ADMIN OPTIONS: 'skip', '+' (to increase volume), '-' (to decrease volume), 'play', 'pause'";

	//new session
	if($counter == 0)
	{
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $textA);
		if($isAdmin)
		{
			$sms = $client->account->sms_messages->create("949-391-4022",$number, $textB);
		}
		$counter++;
	}
	
	//handle adding new songs
	else if(strtolower(substr($body, 0, 3))=="add")
	{
		//strip out important stuff from string
		$song = substr($body, 4);
		
		$responseName = file_get_contents($url."addSong.php?song=".urlencode($song)."&number=".urlencode($number)."&type=".urlencode("music video"));
		//send song request to backend
		$text = $responseName . " has been added to the queue!";	

		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);	
	}
	
	//karaoke
	else if(strtolower(substr($body, 0, 7))=="karaoke")
	{
		//strip out important stuff from string
		$song = substr($body, 8);
		
		$responseName = file_get_contents($url."addSong.php?song=".urlencode($song)."%20lyrics&number=".urlencode($number)."&type=".urlencode("karaoke"));
		//send song request to backend
		$text = $responseName . " has been added to the queue!";	

		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);	
	}
	
	//get info
	else if(strtolower(substr($body, 0, 4))=="info")
	{
		$text = file_get_contents($url."getInfo.php");	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);	
	}
	
	//get queue
	else if(strtolower(substr($body, 0, 5))=="queue")
	{
		$text = file_get_contents($url."getQueue.php");	

		$convert = explode("<br>", $text); //create array separate by new line

		for ($i=0;$i<count($convert);$i++)  
		{		    
		    $sms = $client->account->sms_messages->create("949-391-4022",$number, $convert[$i]);
		}
		//$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
	}
	
	//options
	else if(strtolower(substr($body, 0, 7))=="options")
	{
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $textA);
		if($isAdmin)
		{
			$sms = $client->account->sms_messages->create("949-391-4022",$number, $textB);
		}
	}

	//ADMIN PANEL
	
	//skip
	else if(strtolower(substr($body, 0, 4))=="skip" && $isAdmin)
	{		
		$text = "the current song was skipped";	
		file_get_contents($url."skipSong.php");	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
	}
	
	//increase volume
	else if(strtolower(substr($body, 0, 1))=="+" && $isAdmin)
	{
		$text = "volume was increased";
		file_get_contents($url."increaseVolume.php?val=20");
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
	}
	
	//decrease volume
	else if(strtolower(substr($body, 0, 1))=="-" && $isAdmin)
	{
		$text = "volume was decreased";	
		file_get_contents($url."increaseVolume.php?val=-20");	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
	}
	
	//play
	else if(strtolower(substr($body, 0, 4))=="play" && $isAdmin)
	{		
		$text = "playing";	
		file_get_contents($url."playPause.php?which=play");	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
	}

	//pause
	else if(strtolower(substr($body, 0, 5))=="pause" && $isAdmin)
	{
		$text = "paused";
		file_get_contents($url."playPause.php?which=pause");	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
	}

	//other comment
	else
	{		
		$sentiment = file_get_contents($url."getSentiment.php?text=".urlencode($body));
		$text = "Your comment was " . $sentiment;
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);		
	}

	//save session for future
	$_SESSION['counter'] = $counter;
?>