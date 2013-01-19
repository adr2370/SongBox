(function () {

  var
    // AUDIO_FILE = 'http://r7---sn-a8au-p5qs.c.youtube.com/videoplayback?sparams=cp%2Cid%2Cip%2Cipbits%2Citag%2Cratebypass%2Csource%2Cupn%2Cexpire&ipbits=8&fexp=920704%2C912806%2C922403%2C922405%2C929901%2C913605%2C925710%2C929104%2C929110%2C908493%2C920201%2C913302%2C919009%2C911116%2C910221%2C901451&expire=1358611399&source=youtube&upn=UeYOIpN8aiE&ip=158.130.103.122&key=yt1&itag=43&cp=U0hUTVZMT19OU0NONF9ORlNEOmFYV1Z5ZlJiNXZW&mt=1358586551&id=ac473948df168d34&mv=m&sver=3&ratebypass=yes&ms=au&title=Youtube%20Poop%3A%20Pride%20Patties&signature=3C79AF48039CE5B3814142165EACCE105A181C80.74EEA2E09D4FF4239834242B172BC8AAD686C994',
    AUDIO_FILE = '../songs/14  Basshunter - Dota',
	fft = document.getElementById( 'fft' ),
    ctx = fft.getContext( '2d' ),
    dancer, kick;

  /*
   * Dancer.js magic
   */
  Dancer.setOptions({
    flashSWF : '../../lib/soundmanager2.swf',
    flashJS  : '../../lib/soundmanager2.js'
  });

  dancer = new Dancer();
  kick = dancer.createKick({
    onKick: function () {
      ctx.fillStyle = 'ff0077';
    },
    offKick: function () {
      ctx.fillStyle = '#666';
    }
  }).on();

  dancer
    .fft( fft, { fillStyle: '#666' })
    .load({ src: AUDIO_FILE, codecs: ['mp3', 'webm']});

  Dancer.isSupported() || loaded();
  !dancer.isLoaded() ? dancer.bind( 'loaded', loaded ) : loaded();

  /*
   * Loading
   */

  function loaded () {
    var
      loading = document.getElementById( 'loading' ),
      anchor  = document.createElement('A'),
      supported = Dancer.isSupported(),
      p;

    anchor.appendChild( document.createTextNode( supported ? 'Play!' : 'Close' ) );
    anchor.setAttribute( 'href', '#' );
    loading.innerHTML = '';
    loading.appendChild( anchor );

    if ( !supported ) {
      p = document.createElement('P');
      p.appendChild( document.createTextNode( 'Your browser does not currently support either Web Audio API or Audio Data API. The audio may play, but the visualizers will not move to the music; check out the latest Chrome or Firefox browsers!' ) );
      loading.appendChild( p );
    }

    anchor.addEventListener( 'click', function () {
      dancer.play();
      document.getElementById('loading').style.display = 'none';
    });
  }

  // For debugging
  window.dancer = dancer;

})();
