import RTCMultiConnection from './rtc/RTCMultiConnection.js';
       
       var connection =new RTCMultiConnection();
    // ......................................................
    // ..................RTCMultiConnection Code.............
    // ......................................................

    // by default, socket.io server is assumed to be deployed on your own URL
 
      connection.socketURL = ":9001/";

    // comment-out below line if you do not have your own socket.io server
    // connection.socketURL = 'https://rtcmulticonnection.herokuapp.com:443/';

    //connection.socketMessageEvent = "screen-sharing-demo";

    connection.session = {
      screen: true,
      oneway: true,
      audio: true,
      data: true //------ data-channels
    }; 
    connection.sdpConstraints.mandatory = {
      OfferToReceiveAudio: true,
      OfferToReceiveVideo: true
    };
      connection.onmute = function(e) {
        if(e.session.audio && !e.session.video) {
            e.mediaElement.muted = true;
            return;
        }

        e.mediaElement.src = null;
        e.mediaElement.pause();
        e.mediaElement.setAttribute('poster', 'photo.jpg');
      };

      // if local or remote stream is unmuted
      connection.onunmute = function(e) {
        if(e.session.audio && !e.session.video) {
              e.mediaElement.muted = false;
              return;
        }

        e.mediaElement.removeAttribute('poster');
        e.mediaElement.src = URL.createObjectURL(e.stream);
        e.mediaElement.play();
      };

      // it is recommended to checked above three features inside "DetectRTC.load" method
     /* connection.DetectRTC.load(function() {
          if (connection.DetectRTC.hasMicrophone === true) {
              // enable microphone
              alert("mic detected");
              //connection.mediaConstraints.audio = true;
              //connection.session.audio = true;
          }

          if (connection.DetectRTC.hasWebcam === true) {
              // enable camera
              connection.mediaConstraints.video = true;
              connection.session.video = true;
          }

          if (connection.DetectRTC.hasSpeakers === false) { // checking for "false"
              alert('Please attach a speaker device. You will unable to hear the incoming audios.');
          }

          // you can access all microphones using "DetectRTC.audioInputDevices"
          connection.DetectRTC.audioInputDevices.forEach(function(device) {
              var option = document.createElement('option');

              // this is what people see
              option.innerHTML = device.label;

              // but this is what inernally used to select relevant device
              option.value = device.id;

              // append to your choice
              document.querySelector('#selectmic').appendChild(option);
          });

          // you can access all cameras using "DetectRTC.videoInputDevices"
          connection.DetectRTC.videoInputDevices.forEach(function(device) {
              var option = document.createElement('option');

              // this is what people see
              option.innerHTML = device.label;

              // but this is what inernally used to select relevant device
              option.value = device.id;

              // append to your choice
              document.querySelector('#selectcam').appendChild(option);
          });
      });*/


    // https://www.rtcmulticonnection.org/docs/iceServers/
    // use your own TURN-server here!
    connection.iceServers = [
      {
        urls: [
          "stun:stun.l.google.com:19302",
          "stun:stun1.l.google.com:19302",
          "stun:stun2.l.google.com:19302",
          "stun:stun.l.google.com:19302?transport=udp"
        ]
      }
    ];
    console.log('connection.codecs.video',connection.codecs.video);
    

    connection.onstreamended = function(event) {
      var mediaElement = document.getElementById(event.streamid);
      if (mediaElement) {
        mediaElement.parentNode.removeChild(mediaElement);

        if (
          event.userid === connection.sessionid &&
          !connection.isInitiator
        ) {
          alert(
            "Broadcast is ended. We will reload this page to clear the cache."
          );
          location.reload();
        }
      }
    };

    connection.onMediaError = function(e) {
      if (e.message === "Concurrent mic process limit.") {
        if (DetectRTC.audioInputDevices.length <= 1) {
          alert(
            "Please select external microphone. Check github issue number 483."
          );
          return;
        }

        var secondaryMic = DetectRTC.audioInputDevices[1].deviceId;
        connection.mediaConstraints.audio = {
          deviceId: secondaryMic
        };

        connection.join(connection.sessionid);
      }
    };

    // ..................................
    // ALL below scripts are redundant!!!
    // ..................................

    function disableInputButtons() {
      document.getElementById("room-id").onkeyup();
      document.getElementById("open-or-join-room").disabled = true;
      document.getElementById("open-room").disabled = true;
      document.getElementById("join-room").disabled = true;
      document.getElementById("room-id").disabled = true;
    }

    // ......................................................
    // ......................Handling Room-ID................
    // ......................................................

    (function() {
      var params = {},
        r = /([^&=]+)=?([^&]*)/g;

      function d(s) {
        return decodeURIComponent(s.replace(/\+/g, " "));
      }
      var match,
        search = window.location.search;
      while ((match = r.exec(search.substring(1))))
        params[d(match[1])] = d(match[2]);
      window.params = params;
    })();

    var roomid = "";
    if (localStorage.getItem(connection.socketMessageEvent)) {
      roomid = localStorage.getItem(connection.socketMessageEvent);
    } else {
      roomid = connection.token();
    }
    /* document.getElementById("room-id").value = roomid;
    document.getElementById("room-id").onkeyup = function() {
      localStorage.setItem(
        connection.socketMessageEvent,
       // document.getElementById("room-id").value
      );
    };*/

    var hashString = location.hash.replace("#", "");
    if (hashString.length && hashString.indexOf("comment-") == 0) {
      hashString = "";
    }

    var roomid = params.roomid;
    if (!roomid && hashString.length) {
      roomid = hashString;
    }

    if (roomid && roomid.length) {
      //  document.getElementById("room-id").value = roomid;
      localStorage.setItem(connection.socketMessageEvent, roomid);

      // auto-join-room
      (function reCheckRoomPresence() {
        connection.checkPresence(roomid, function(isRoomExist) {
          if (isRoomExist) {
            connection.join(roomid);
            return;
          }

          setTimeout(reCheckRoomPresence, 5000);
        });
      })();

      disableInputButtons();
    }

    // detect 2G
    if (
      navigator.connection &&
      navigator.connection.type === "cellular" &&
      navigator.connection.downlinkMax <= 0.115
    ) {
      alert("2G is not supported. Please use a better internet service.");
    } 
    export default  connection;
