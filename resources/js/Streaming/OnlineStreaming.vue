<template>
  <div>
    <section class="content">
      <PanelContent :title="'Salas'">
        <template v-slot:content>
          <div id="videos-container" style="overflow:auto;margin: 20px 0;"></div>
        </template>
      </PanelContent>
      <DialogSimple></DialogSimple>
    </section>
  <!--SideBarOptions></SideBarOptions-->
  </div>
</template>

<script>
var connection = new RTCMultiConnection();

var self;
var swal__;
var toast__;
import PanelContent from "../components/PanelContent";
import TableContent from "../components/TableContent";
import DialogSimple from "../components/DialogSimple";
import SideBarOptions from "./components/SideBarOptions";
export default {
  name: "Dashboard",
  components: {
    PanelContent,
    DialogSimple,
    TableContent,SideBarOptions
  },
  props: {
    etapa: {
      required: true
    }
  },
  data() {
    return {
      peer1: null,
      displayMedia: {
        audio: true,
        video: {
          mediaSource: "screen",
          width: { max: "1920" },
          height: { max: "1080" },
          frameRate: { max: "1000" }
        }
      },
      video: null,
      stream: { active: false },
      nombre: null,
      capacidad: null,
      menuDateExpiraSala: false,
      fechaExpiraSala: null,
      menuHoraExpiraSala: false,
      horaExpiraSala: null,
      loading: false
    };
  },
  watch: {},
  mounted() {
    self = this;
    swal__ = this.$store.getters.getSwal;
    toast__ = this.$store.getters.getToastDefault;

    this.init();
    this.entrarSala();
  },
  methods: {
    dialogClose() {
      this.$store.state.dialogSimple.dialog = false;
    },
    entrarSala() {
      let valores = { room: self.$route.params.id };
      axios
        .post(this.etapa + "/online/entrarStreaming/", valores)
        .then(response => {
          var data = response.data;

          if (data.estado != "error") {
            if (data.response.isadmin) {
              connection.open(self.$route.params.id, function() {
                self.stream.active = true;
                self.showRoomURL(connection.sessionid);
              });
            } else {
              connection.sdpConstraints.mandatory = {
                OfferToReceiveAudio: false,
                OfferToReceiveVideo: true
              };
              connection.join(data.response.salatoken);
            }
          } else {
            swal__.fire("ERROR!", data.mensaje, data.estado);
          }
          console.log(data);
        })
        .catch(errors => {
          swal__.fire("ERROR!", "ha ocurrido un error: " + errors, "error");
          console.log(errors);
        });
    },

    open_room(item) {
      //disableInputButtons();
      let valores = item;
      axios
        .post(this.etapa + "/iniciarStream/", valores)
        .then(response => {
          let data = response.data;
          if (data.estado != "error") {
            connection.open(item.nombre, function() {
              self.stream.active = true;
              self.showRoomURL(connection.sessionid);
            });
          }
        })
        .catch(errors => {
          console.log(errors);
          swal__.fire("ERROR!", "ha ocurrido un error: " + errors, "error");
        });
    },
    join_room(item) {
      //disableInputButtons();

      connection.sdpConstraints.mandatory = {
        OfferToReceiveAudio: true,
        OfferToReceiveVideo: true
      };
      connection.join(item.nombre);
      /*setTimeout(() => {
      window.open("/streaming/#" + item.nombre, "_blank");
        
      }, 1000);*/
    },
    showRoomURL(roomid) {
      var roomHashURL = "#" + roomid;
      var roomQueryStringURL = "?roomid=" + roomid;

      var html = "<h2>Unique URL for your room:</h2><br>";

      html +=
        'Hash URL: <a href="' +
        roomHashURL +
        '" target="_blank">' +
        roomHashURL +
        "</a>";
      html += "<br>";
      html +=
        'QueryString URL: <a href="' +
        roomQueryStringURL +
        '" target="_blank">' +
        roomQueryStringURL +
        "</a>";

      /* var roomURLsDiv = document.getElementById("room-urls");
        roomURLsDiv.innerHTML = html;

        roomURLsDiv.style.display = "block";*/
    },
    init() {
      // ......................................................
      // .......................UI Code........................
      // ......................................................

      /* document.getElementById("open-or-join-room").onclick = function() {
        disableInputButtons();
        connection.openOrJoin(
          document.getElementById("room-id").value,
          function(isRoomExist, roomid) {
            if (isRoomExist === false && connection.isInitiator === true) {
              // if room doesn't exist, it means that current user will create the room
              showRoomURL(roomid);
            }

            if (isRoomExist) {
              connection.sdpConstraints.mandatory = {
                OfferToReceiveAudio: false,
                OfferToReceiveVideo: true
              };
            }
          }
        );
      };*/

      // ......................................................
      // ..................RTCMultiConnection Code.............
      // ......................................................

      // by default, socket.io server is assumed to be deployed on your own URL
      connection.socketURL = ":9001/";

      // comment-out below line if you do not have your own socket.io server
      // connection.socketURL = 'https://rtcmulticonnection.herokuapp.com:443/';

      connection.socketMessageEvent = "screen-sharing-demo";

      connection.session = {
        screen: true,
        oneway: true
      };

      connection.sdpConstraints.mandatory = {
        OfferToReceiveAudio: false,
        OfferToReceiveVideo: false
      };

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

      connection.videosContainer = document.getElementById("videos-container");
      connection.onstream = function(event) {
        var existing = document.getElementById(event.streamid);
        if (existing && existing.parentNode) {
          existing.parentNode.removeChild(existing);
        }

        event.mediaElement.removeAttribute("src");
        event.mediaElement.removeAttribute("srcObject");
        event.mediaElement.muted = true;
        event.mediaElement.volume = 0;
        alert();

        var video = document.createElement("video");

        try {
          video.setAttributeNode(document.createAttribute("autoplay"));
          video.setAttributeNode(document.createAttribute("playsinline"));
        } catch (e) {
          video.setAttribute("autoplay", true);
          video.setAttribute("playsinline", true);
        }

        if (event.type === "local") {
          video.volume = 0;
          try {
            video.setAttributeNode(document.createAttribute("muted"));
          } catch (e) {
            video.setAttribute("muted", true);
          }
        }
        video.srcObject = event.stream;

        var width = innerWidth - 80;
        var mediaElement = getHTMLMediaElement(video, {
          title: event.userid,
          buttons: ["full-screen"],
          width: width,
          showOnMouseEnter: false
        });

        connection.videosContainer.appendChild(mediaElement);

        setTimeout(function() {
          mediaElement.media.play();
        }, 5000);

        mediaElement.id = event.streamid;
      };

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
    },
    gotMedia(stream) {
      this.paramsStream = {
        initiator: true,
        trickle: false
        /*wrtc: wrtc, stream: stream*/
      };
      this.peer1 = new Peer(this.paramsStream);

      this.peer1.on("signal", data => {
        //GET MY DATA ON THE
        let dat = JSON.stringify(data);
        this.token = dat;
        this.guardar();
        localStorage.setItem("stream", dat);
        document.getElementById("yourId").value = dat;
      });
    },

    resetValues() {
      this.nombre = null;
      this.capacidad = null;
      this.fechaExpiraSala = null;
      this.horaExpiraSala = null;
      this.modificando = false;
    },
    save(date) {
      this.$refs.menuDateExpiraSala.save(date);
    }
  }
};
</script>
              