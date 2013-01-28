var player=null;
var firebase = new Firebase('https://adr2370.firebaseio.com/');
var songdb = firebase.child('songs');
var playerdb = firebase.child('playerdb');
var commentdb = firebase.child('comments');
var currentdb = firebase.child('current');

var songs=new Array();

window.setInterval(function(){
	if(player!=null) {
		currentdb.child('time').once('value', function(dataSnapshot) {
			if(dataSnapshot.val()<player.getCurrentTime()) {
				currentdb.child('time').set(player.getCurrentTime());
			} else if(Math.abs(dataSnapshot.val()-player.getCurrentTime())>0.10){
				player.seekTo(dataSnapshot.val(),true);
			}
		});
	}
}, 1000);

currentdb.on('child_changed', function(snapshot, prevChildName) {
	//CHANGE RATING OR SKIP VIDEO
	if(snapshot.name()=="rating") {
		setMeter(snapshot.val());
	} else if(snapshot.name()=="skip"&&snapshot.val()==1) {
		nextVideo();
	} else if(snapshot.name()=="play") {   
		if(snapshot.val()==1) {
			player.playVideo();
		} else if(snapshot.val()==2) {
			player.pauseVideo();
		}
	} else if(snapshot.name()=="fullScreen"&&snapshot.val()==1) {    
		currentdb.child('fullScreen').set(0);
		window.fullScreen();
	}
});

currentdb.on('child_added', function(snapshot, prevChildName) {
	//ADDED SONG
	if(snapshot.name()=="id") {
		startVideo();
	}
});

songdb.on('child_added', function(snapshot, prevChildName) {
	//ADDS SOMETHING TO QUEUE
	//snapshot.name() is youtube id, all else is below
	songs.push(snapshot.name());
	$("#queue").append('<tr style=\"font-size:30px;line-height:normal;\" id="'+snapshot.name()+'"><td style="line-height: normal;">'+snapshot.child('name').val()+'<\/td><td style="line-height: normal;">'+snapshot.child('length').val()+'<\/td><td><img src=\"'+snapshot.child('thumbnail').val()+'\" height="225" width=225"><\/td><td style="line-height: normal;">'+snapshot.child('numViews').val()+'<\/tr>');
	currentdb.on('value', function(snapshot, prevChildName) {
		if(snapshot.val()==null) {
			nextVideo();
		}
	});
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

function startVideo() {
	//youtube video
	currentdb.once('value', function(dataSnapshot) {
		var id=dataSnapshot.child('id').val();
		if(player==null) {
			player=new YT.Player('youtube', {
				height: '390',
				width: '640',
				videoId: id,
				playerVars: { autoplay:1, enablejsapi:1, modestbranding:1, rel:0, showinfo:0, iv_load_policy:3, volume:50 },
				events: {
					'onStateChange': onPlayerStateChange,
					//'onError': onError
				}});
		} else {
			player.loadVideoById(id, 5, "large");
		}
		$("#visuals canvas").hide();
		$("#"+id).remove();
		songdb.child(id).remove();
		removeA(songs, id);
	});
}

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
			currentdb.child('fullScreen').set(0);
			currentdb.child('time').set(0);
			currentdb.child('id').set(songs[0]);
		});
	} else {
		$("#youtube").replaceWith($('<div id="youtube"><\/div>'));
		player=null;
	}
}

function onPlayerStateChange(event) {        
	if(event.data == 0) {
		nextVideo();
	} else if(event.data == 1) {
		currentdb.child('play').set(1);
		currentdb.child('time').once('value', function(dataSnapshot) {
			player.seekTo(dataSnapshot.val(),true);
		});
		playerdb.child('volume').once('value', function(dataSnapshot) {
			player.setVolume(dataSnapshot.val());
		});
	} else if(event.data == 2) {
		currentdb.child('play').set(2);
	}
}

function onError(event) {
	nextVideo();
}

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

function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}