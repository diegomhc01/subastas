<video id = "video" autoplay="true" style="width: 680px; heigth: 320px;"></video>
<div id = "logger"></div>
 
<script type="text/javascript">
 
    var video = document.getElementById('video');
    var logger = document.getElementById('logger');
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    context.width = 120;
    context.height = 120;
 
 
    function log(message) {
       logger.innerHTML = logger.innerHTML + message + "<br/>";
    };
 
 
    if(navigator.getUserMedia) {
        navigator.getUserMedia('video', successCallback, errorCallback);
 
        function successCallback( stream ) {
        	log('Broadcasting...');
            video.src = stream;
        };
 
        function errorCallback( error ) {
            log('Error broadcasting: ' + error.code );
        };
    } else {
        log('Cannot access to the camera');
    };
 
 
    /* SOCKET.IO */
 
    var socket = io.connect(window.document.location.host);
 
 
    socket.on('connect', function () {
    	log('connected');
    });
 
    socket.on('disconnect', function () {
    	log('disconnected');
    });
 
    function emit(message) {
    	socket.emit('data', message);
    }
 
    /* END SOCKET.IO */
 
    function sendFrame(video, context) {
        context.drawImage(video, 0, 0, context.width, context.height);
        emit(canvas.toDataURL('image/webp'));
    }
 
    setInterval(function() { sendFrame(video, context); }, 1000);
 
</script>
   