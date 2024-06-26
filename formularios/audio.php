<script src="//cdn.webrtc-experiment.com/RTCMultiConnection.js"></script>

<input type="file">
<button id="openNewSessionButton" disabled>Open New Room</button><br />
<script>
var connection = new RTCMultiConnection();
connection.session = {
    audio: true,
    oneway: true
};
connection.onstream = function (e) {
    document.body.appendChild(e.mediaElement);
};

// connect to signaling gateway
connection.connect();

// open new session
document.getElementById('openNewSessionButton').onclick = function () {
    this.disabled = true;
    connection.open();
};

window.AudioContext = window.AudioContext || window.webkitAudioContext;

var context = new AudioContext();
var gainNode = context.createGain();
gainNode.connect(context.destination);

// don't play for self
gainNode.gain.value = 0;

document.querySelector('input[type=file]').onchange = function () {
    this.disabled = true;

    var reader = new FileReader();
    reader.onload = (function (e) {
        // Import callback function that provides PCM audio data decoded as an audio buffer
        context.decodeAudioData(e.target.result, function (buffer) {
            // Create the sound source
            var soundSource = context.createBufferSource();

            soundSource.buffer = buffer;
            soundSource.start(0, 0 / 1000);
            soundSource.connect(gainNode);

            var destination = context.createMediaStreamDestination();
            soundSource.connect(destination);

            connection.attachStreams.push(destination.stream);
            connection.dontCaptureUserMedia = true;

            document.getElementById('openNewSessionButton').disabled = false;
        });
    });

    reader.readAsArrayBuffer(this.files[0]);
};
</script>
<style>
input, 
button[disabled] { 
    background: none;
    border: 1px solid rgb(226, 223, 223);
    color: rgb(219, 217, 217); 
    }
</style>