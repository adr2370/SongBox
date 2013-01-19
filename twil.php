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
		$text = "Text back any of the following commands: 'add' <SONG NAME>, 'info' (get song information),";
		$textA = "'queue' (see playlist queue), 'help' (list all commands), OR text us how you feel about the song";		
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
		$song = "Gangnam Style";
		//if song matches, add to queue

		//send song request to backend
		$text = $song . " has been added to the queue!";	
		$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);	
	}
	/*//get info
	else if(strtolower(substr($body, 0, 8))=="info")
	{
		//$text = getCurrentSongInfo();
	}
	//get queue
	else if(strtolower(substr($body, 0, 6))=="queue")
	{
		//$text = getQueue();	
	}
	else if(strtolower(substr($body, 0, 6))=="help")
	{
		$text = "Text back any of the following commands.\n".
				"'add <SONG NAME>'\n".
				"'info (to get song information)'\n".
				"'queue (to see the current playlist queue'\n".
				"'help' (to get a list of commands)\n".
				"OR just text us how you feel about the song\n";
		if($isAdmin)
		{
			$text = "ADMIN OPTIONS:\n".
			"'skip'\n".
			"'+' (to increase volume)\n".
			"'-' (to decrease volume)\n" . $text;		
		}		
	}*/

	
	/*//ADMIN PANEL
	//volume control ONLY FOR OWNER OF THE ROOM
	else if(strtolower(substr($body, 0, 1))=="++" && isAdmin)

	else if(strtolower(substr($body, 0, 1))=="--" && isAdmin)

	else if(strtolower(substr($body, 0, 1))=="skip" && isAdmin)
	{
		//skipCurrentSong()
	}
		

	else
	{
		//handle up/downvoting song currently playing

		//getCurrentSong
		
		//strip up/vote from song

		//send text info to backend for sentiment analysis and voting		
		//$sentiment = getVoteResponse()
		$sentiment = "";
		if($sentiment == -1)
		{
			$text = "That's too bad";					
		}
		else if($sentiment == 0)
		{
			$text = "Meh";					
		}
		else
		{
			$text = "Awesome";					
		}
		
	}
*/
	//save session for future
	$_SESSION['counter'] = $counter;


	//sends text to user
	/*$sms = $client->account->sms_messages->create("949-391-4022",$number, $text);*/
	
	
	
	
?>