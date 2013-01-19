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

		<div id="youtube"></div>
		<div id="queue"></div>
		<script type='text/javascript'>
		  // Get a reference to the root of the chat data.
		  var firebase = new Firebase('https://adr2370.firebaseio.com/songs');

		  // Add a callback that is triggered for each chat message.
		  firebase.on('child_added', function (snapshot) {
			//snapshot.name() is the url id
			//snapshot.
			if($("#youtube").html()=="") {
				$("#youtube").append('<object width="640" height="360"><param name="movie" value="https://www.youtube.com/v/'+snapshot.name()+'?autoplay=1&controls=0&enablejsapi=1&modestbranding=1&rel=0&showinfo=0&theme=light&version=3"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="https://www.youtube.com/v/'+snapshot.name()+'?autoplay=1&controls=0&enablejsapi=1&modestbranding=1&rel=0&showinfo=0&theme=light&version=3" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="360"></embed></object>');
			} else {
				$("#queue").append('<div id="'+snapshot.name()+'">Id: '+snapshot.name()+'<br/>Title: '+snapshot.child('name').val()+'<br/>Length: '+snapshot.child('length').val()+'<br/>Thumbnail: '+snapshot.child('thumbnail').val()+'<br/>Num Views: '+snapshot.child('numViews').val()+'</div>');
			}
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
