<img src="" id="frame" style="width:680px; height:320px"/>
<div id="logger"></div>
 
<script type="text/javascript">
 
    var img = document.getElementById("frame"); 
    var logger = document.getElementById('logger');
 
    /* SOCKET.IO */
 
    var socket = io.connect(window.document.location.host);
 
    socket.on('connect', function () {
        log('connected');
    });
 
    socket.on('disconnect', function () {
        log('disconnected');
    });
 
    socket.on('data', function(data) {
        img.src = data;
    });
 
 
    /* END SOCKET.IO */
 
    function log(message) {
       logger.innerHTML = logger.innerHTML + message + "<br/>";
    };
 
</script>