function setMeter(rating) {
	if(rating>=6) {
		var x=500;
	} else if(rating<=-6) {
		var x=-500;
	} else {
		var x=rating*100;
	}
	var r=$("#meterRed");
	var g=$("#meterGreen");
	g.animate({width: (583+x)}, Math.abs(g.width()-(583+x)));
	r.animate({width: (583-x)}, Math.abs(r.width()-(583-x)));
}

var first=true;
var player;
var firebase = new Firebase('https://adr2370.firebaseio.com/songs');
var songdb = firebase.child('songs');
var playerdb = firebase.child('playerdb');
var commentdb = firebase.child('comments');
var currentdb = firebase.child('current');

var songs=new Array();

function nextVideo() {
	setMeter(0);
	$("#comments").html("");
	commentdb.remove();
	if(songs.length>0) {  
		songdb.child(songs[0]).once('value', function(dataSnapshot) {
			currentdb.set(dataSnapshot.val());
			currentdb.child('rating').set(0);
			currentdb.child('skip').set(0);
			currentdb.child('play').set(0);
			currentdb.child('pause').set(0);
			currentdb.child('fullScreen').set(0);
			//youtube video
			if(player==null) {
				player=new YT.Player('youtube', {
						height: '390',
						width: '640',
						videoId: songs[0],
				  playerVars: { autoplay:1, enablejsapi:1, modestbranding:1, rel:0, showinfo:0, iv_load_policy:3, volume:50 },
						events: {
							'onStateChange': onPlayerStateChange,
							'onError': onError
						}});
			} else {
				player.loadVideoById(songs[0], 5, "large");
			}
			playerdb.child('volume').once('value', function(dataSnapshot) {
				player.setVolume(dataSnapshot.val());
			});
			$("#visuals canvas").hide();
			$("#"+songs[0]).remove();
			songdb.child(songs[0]).remove();
			songs.splice(0,1);
		});
	} else {
		first=true;
		$("#youtube").replaceWith($('<div id="youtube"><\/div>'));
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

currentdb.on('child_changed', function(snapshot, prevChildName) {
	//CHANGE RATING OR SKIP VIDEO
	if(snapshot.name()=="rating") {
		setMeter(snapshot.val());
	} else if(snapshot.name()=="skip"&&snapshot.val()==1) {
		nextVideo();
	} else if(snapshot.name()=="play"&&snapshot.val()==1) {   
		currentdb.child('play').set(0);
		player.playVideo();
	} else if(snapshot.name()=="pause"&&snapshot.val()==1) {  
		currentdb.child('pause').set(0);
		player.pauseVideo();
	}  else if(snapshot.name()=="fullScreen"&&snapshot.val()==1) {    
		currentdb.child('fullScreen').set(0);
		window.fullScreen();
	}
});

songdb.on('child_added', function(snapshot, prevChildName) {
	//ADDS SOMETHING TO QUEUE
	//snapshot.name() is youtube id, all else is below
	songs.push(snapshot.name());
	$("#queue").append('<tr style=\"font-size:30px;line-height:normal;\" id="'+snapshot.name()+'"><td style="line-height: normal;">'+snapshot.child('name').val()+'<\/td><td style="line-height: normal;">'+snapshot.child('length').val()+'<\/td><td><img src=\"'+snapshot.child('thumbnail').val()+'\" height="225" width=225"><\/td><td style="line-height: normal;">'+snapshot.child('numViews').val()+'<\/tr>');
	if(first) {
		first=false;
		nextVideo();
	}
});

songdb.on('child_moved', function(snapshot, prevChildName) {
	//SWITCHES ORDER OF STUFF IN QUEUE
	//prevChildName is the name of the previous one, snapshot.name is the id of the snapshot
	if(prevChildName==null) {
		$("#topQueue").after($("#"+snapshot.name()).remove());
	} else {
		$("#"+prevChildName).after($("#"+snapshot.name()).remove());
	}
});

playerdb.on('child_changed', function(snapshot, prevChildName) {
	player.setVolume(snapshot.val());
});

commentdb.on('child_added', function(snapshot, prevChildName) {
	//COMMENTS ADDED
	//snapshot.val() is each comment
	$("#comments").prepend("<p style=\"font-size:30px;line-height:normal;\">" + snapshot.val()+"<\/p>");
});