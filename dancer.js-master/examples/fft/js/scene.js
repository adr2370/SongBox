(function() {
  var container;
  var renderer, particle;
  var mouseX = 0, mouseY = 0;

  var stats = new Stats();
  stats.domElement.id = 'stats';

  var windowHalfX = window.innerWidth / 2;
  var windowHalfY = window.innerHeight / 2;
  
  // Expose these for the demo
  window.rotateSpeed = 1;
  window.scene = new THREE.Scene();
  window.group = new THREE.Object3D();
  window.camera;
  window.renderer;
  init();
  animate();

  function init() {
    container = document.createElement( 'div' );
	container.id = "visuals";
    document.getElementById("thevisuals").insertBefore( container );
    camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 1, 3000 );
    camera.position.z = 1000;

    scene.add( camera );
    scene.add( group );

    renderer = new THREE.CanvasRenderer();
    renderer.setSize( 680, 472 );
    container.appendChild( renderer.domElement );
	$("#visuals canvas").css("background-color","black");

    /*document.addEventListener( 'mousemove', onDocumentMouseMove, false );
    document.addEventListener( 'touchstart', onDocumentTouch, false );
    document.addEventListener( 'touchmove', onDocumentTouch, false );*/
  }
  var onWindowResize = function(){

    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();

    renderer.setSize( window.innerWidth , window.innerHeight );
}
window.onWindowResize = onWindowResize;
  


  function onDocumentMouseMove( event ) {
    mouseX = event.clientX - windowHalfX;
    mouseY = event.clientY - windowHalfY;
  }

  function onDocumentTouch( event ) {
    if ( event.touches.length == 1 ) {
      event.preventDefault();
      mouseX = event.touches[ 0 ].pageX - windowHalfX;
      mouseY = event.touches[ 0 ].pageY - windowHalfY;
    }
  }

  function animate() {
    requestAnimationFrame( animate );
    render();
    stats.update();
  }

  var t = 0;
  function render() {
    camera.position.x = Math.sin(t * 0.005 * rotateSpeed) * 1000;
    camera.position.z = Math.cos(t * 0.005 * rotateSpeed) * 1000;
    camera.position.y += ( - mouseY - camera.position.y ) * 0.01;
    camera.lookAt( scene.position );
    t++;
    renderer.render( scene, camera );
  }
window.render=render;
})();