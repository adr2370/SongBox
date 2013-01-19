<?php
	require "Services/Twilio.php"; // for Twilio REST API

	session_start(); // start session for Twilio SMS response 

	$AccountSid = "AC015bae4a78bec6d2699d094ea378057b";
    $AuthToken = "bcb0ad9983482a41ecc92443ab210dfa";
	$client = new Services_Twilio($AccountSid, $AuthToken); // instantiate Twilio client
	
	// Twilio API Query 
	$counter = $_SESSION['counter']; // sets up a session / conversation (Twilio saves info for 4 hours...ish)
	$body = $_REQUEST['Body']; // gets the body of the message received
	$number = $_REQUEST['From']; // gets the sender of the message received	
	$isAdmin = 0; //0 is not admin
	
	$counter = 1;
	//confirm user is admin
	if($number == "+17145857755")
	{
		$isAdmin = 1;		
	}
	
	
	//initiates counter to reflectnew session
	if(!strlen($counter))
	{
		$counter = 0;
	}

	//new session
	if($counter == 0)
	{
		$text = "Text back any of the following commands: 'add <SONG NAME>', 'info' (get song information),";
		$textA = "'queue' (see playlist queue), 'options' (list all commands), OR text us how you feel about the song";		
		$textB = "ADMIN OPTIONS: 'skip', '+' (to increase volume), '-' (to decrease volume)";
	
	
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
		
		$responseName = file_get_contents("http://testappshahid.aws.af.cm/addSong.php?song=".urlencode($song));
		//send song request to backend
		$text = $responseName . " has been added to the queue!";	

		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);	
	}
	//get info
	else if(strtolower(substr($body, 0, 4))=="info")
	{
		$text = file_get_contents("http://testappshahid.aws.af.cm/getInfo.php");	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);	
	}
	//get queue
	else if(strtolower(substr($body, 0, 6))=="queue")
	{
		$text = "here is the list of songs";	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);	
	}
	else if(strtolower(substr($body, 0, 8))=="options")
	{
		$text = "Text back any of the following commands: 'add <SONG NAME>', 'info' (get song information),";
		$textA = "'queue' (see playlist queue), 'options' (list all commands), OR text us how you feel about the song";		
		$textB = "ADMIN OPTIONS: 'skip', '+' (to increase volume), '-' (to decrease volume)";
	
	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $textA);
		if($isAdmin)
		{
			$sms = $client->account->sms_messages->create("949-391-4022",$number, $textB);
		}
	}
	else if(strtolower(substr($body, 0, 6))=="clear")
	{
		$counter = 0;
		$text = "everything was cleared";	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
	}

	
	//ADMIN PANEL
	//volume control ONLY FOR OWNER OF THE ROOM
	else if(strtolower(substr($body, 0, 2))=="+" && isAdmin)
	{
		$text = "volume increased by 10%";	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
	}
	else if(strtolower(substr($body, 0, 2))=="-" && isAdmin)
	{
		$text = "volume decreased by 10%";	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
	}
	else if(strtolower(substr($body, 0, 5))=="skip" && isAdmin)
	{
		$text = "song was skipped";	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);
	}
	else
	{		
		$sentiment = file_get_contents("http://testappshahid.aws.af.cm/getSentiment.php?text=".$body);
		$text = "Your comment was " . $sentiment;
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);		
	}

	//save session for future
	$_SESSION['counter'] = $counter;


	//sends text to user
	/*$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);*/
	
	
	
	
?>