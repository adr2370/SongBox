<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
		<script type="text/javascript" src="https://cdn.firebase.com/v0/firebase.js"></script>
		<script src="http://www.youtube.com/player_api"></script>

		<div id="youtube"></div>
		<div id="queue"></div>
		<div id="comments"></div>
		<div id="rating">0</div>
		<script type='text/javascript'>
		var first=true;
	    var player;
		var firebase = new Firebase('https://adr2370.firebaseio.com/songs');
		var playerdb = new Firebase('https://adr2370.firebaseio.com/playerdb');
		var commentDB = new Firebase('https://adr2370.firebaseio.com/comments');
		var current = new Firebase('https://adr2370.firebaseio.com/current');

		var songs=new Array();
		function addFirstYoutubeVideo() {
			$("#rating").text("0");
			$("#comments").html("");
			commentDB.remove();
			//CREATES YOUTUBE PLAYER
			//Modify as you see fit, just don't screw with events or videoId
			player=new YT.Player('youtube', {
	              height: '390',
	              width: '640',
	              videoId: songs[0],
				  playerVars: { autoplay:1, enablejsapi:1, modestbranding:1, rel:0, showinfo:0, iv_load_policy:3, volume:50 },
	              events: {
	                'onStateChange': onPlayerStateChange,
					'onError': onError
	              }});
			firebase.child(songs[0]).once('value', function(dataSnapshot) {
				current.set(dataSnapshot.val());
				current.child('rating').set(0);
				current.child('skip').set(0);
				$("#"+songs[0]).remove();
				firebase.child(songs[0]).remove();
				songs.splice(0,1);
				});

			playerdb.child('volume').set(50);

		}
		
		function nextVideo() {
			$("#rating").text("0");
			$("#comments").html("");
			commentDB.remove();
			if(songs.length>0) {
				player.loadVideoById(songs[0], 5, "large");
				firebase.child(songs[0]).once('value', function(dataSnapshot) {
					current.set(dataSnapshot.val());
					current.child('rating').set(0);
					current.child('skip').set(0);
					$("#"+songs[0]).remove();
					firebase.child(songs[0]).remove();
					songs.splice(0,1);
					});
			} else {
				first=true;
				$("#youtube").remove();
				$("#queue").before("<div id='youtube'></div>");
				player=null;
			}
		}
		
        function onPlayerStateChange(event) {        
            if(event.data == 0) {
				nextVideo();
            }
        }
		
		function onError(event) {
			nextVideo();
		}

		current.on('child_changed', function(snapshot, prevChildName) {
			//CHANGE RATING OR SKIP VIDEO
			if(snapshot.name()=="rating") {
				$("#rating").text(snapshot.val());
			} else if(snapshot.name()=="skip"&&snapshot.val()=="1") {
				nextVideo();
			}
		});

			firebase.on('child_added', function(snapshot, prevChildName) {
				//ADDS SOMETHING TO QUEUE
				//snapshot.name() is youtube id, all else is below
			  	songs.push(snapshot.name());
				$("#queue").append('<div id="'+snapshot.name()+'">Id: '+snapshot.name()+'<br/>Title: '+snapshot.child('name').val()+'<br/>Length: '+snapshot.child('length').val()+'<br/>Thumbnail: '+snapshot.child('thumbnail').val()+'<br/>Num Views: '+snapshot.child('numViews').val()+'<br/>Priority: '+snapshot.getPriority()+'</div>');
				if(first) {
					first=false;
					addFirstYoutubeVideo();
				}
			});

			firebase.on('child_moved', function(snapshot, prevChildName) {
				//SWITCHES ORDER OF STUFF IN QUEUE
				//prevChildName is the name of the previous one, snapshot.name is the id of the snapshot
				if(prevChildName==null) {
					$("#queue").prepend($("#"+snapshot.name()).remove());
				} else {
					$("#"+prevChildName).after($("#"+snapshot.name()).remove());
				}
			});

			playerdb.on('child_changed', function(snapshot, prevChildName) {
				player.setVolume(snapshot.val());
			});
			
			commentDB.on('child_added', function(snapshot, prevChildName) {
				//COMMENTS ADDED
				//snapshot.val() is each comment
				$("#comments").append(snapshot.val()+"<br/>");
			});
		</script>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
