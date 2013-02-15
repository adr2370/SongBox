<?php
require_once 'twilioStuff/firebase.php';
$room=$_GET['phonenum'];
$name=$_GET['name'];
if($room[0]!="1") $room="1".$room;
$data=array($room => $room);
patchFirebase('numbers/',$data,"PATCH");
//initialize room here
$data=array('highestPriority' => '1');
patchFirebase('rooms/'.$room,$data,"PATCH");
$data=array('volume' => '60');
patchFirebase('rooms/'.$room.'/playerdb',$data,"PATCH");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>
			SongBox
		</title>
		<meta name="description" content="Creating a table with Twitter Bootstrap. Learn how to use Twitter Bootstrap toolkit to create Tables with examples.">
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
		<link href="css/G.css" rel="stylesheet" type="text/css">
		<link href="css/home.css" rel="stylesheet" type="text/css">
		<script src="js/vendor/modernizr-2.6.2.min.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="container" style="background-color:#000000;width:100%;min-height:60px;font-size:55px;padding-top:30px;color:#FFFFFF;">
			Text "Join <?php echo $room; ?>" to (949)-391-4022 to start.
		</div>
		<p>
			<br>
		</p>
		<div class="container" style="padding-left:45px;padding-right:45px;">
			<div class="progress" style="text-align:center;border-top-left-radius: 10px;border-top-right-radius: 10px;border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;">
				<div id="meterRed" class="meter animate red" style="width: 583px; position: relative; left: -1px;">
					<span style="width: 1170px;border-top-right-radius:0px;border-bottom-right-radius:0px;z-index: 1;"><span></span></span>
				</div>
				<div id="meterGreen" class="meter animate" style="width: 583px;">
					<span style="width: 2000px;border-top-left-radius:0px;z-index: 2;border-bottom-left-radius:0px;"><span></span></span>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span6" style="background-color:#666666;height:700px;overflow:hidden;">
				<br>
				<div id="thevisuals"></div>
				<div id="youtube"></div><br>
				<blockquote style="font-size:100">
					<div id="comments"></div>
				</blockquote>
			</div>
			<div class="span6" style="background-color:#333333;color:#888888;height:700px;overflow:hidden;">
				<table class="table">
					<tbody id="queue">
						<tr id="topQueue">
							<td>
								Title
							</td>
							<td>
								Length
							</td>
							<td>
								Thumbnail
							</td>
							<td>
								Views
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<script type="text/javascript" src="https://cdn.firebase.com/v0/firebase.js"></script>
		<script src="http://www.youtube.com/player_api" type="text/javascript"></script>
		<script type="text/javascript">
			var room=<?php echo $room; ?>;
		</script>
		<script src="js/room.js" type="text/javascript"></script>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
		<script src="js/plugins.js"></script>

		<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
		<script>
			var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>
		<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	</body>
</html>
