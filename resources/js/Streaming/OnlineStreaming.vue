<template>
  <div>
    <section class="content">
      <v-slide-x-transition>
        <v-navigation-drawer
          style="box-shadow: 0 6px 6px -3px rgba(0,0,0,.2),0 10px 14px 1px rgba(0,0,0,.14),0 4px 18px 3px rgba(0,0,0,.12)!important;z-index:100;width:350px;padding-top: 60px;"
          absolute
          right
          v-show="show_chat"
        >
          <v-list dense>
            <v-list-item>
              <v-card style="height:100%;width:100%;" elevation="5" :style="{}" class="mx-auto">
                <v-card-text>
                  <v-row>chat</v-row>
                </v-card-text>
              </v-card>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>
      </v-slide-x-transition>

      <v-row>
        <v-col cols="12" sm="12" md="12" :lg="dataStream.stream_active ?(show_camara?(expander_video?10:9):12):12">
          <v-card class="mx-auto" elevation="10">
            <v-card-text>
              <v-progress-circular
                style="margin-left:50%;margin-top:10px;margin-bottom:10px;"
                v-if="dataStream.loading"
                indeterminate
                color="primary"
              ></v-progress-circular>
              <v-alert
                v-else-if="!dataStream.stream_active"
                style="margin:10px;"
                colored-border
                type="error"
                elevation="2"
              >No se ha encontrado la video-coferencia activa</v-alert>
              
              <v-row v-show="dataStream.stream_active">
                <div
                id="videos-container"
                style="overflow:auto;margin-bottom: 20px;"
              ></div>
                <v-col cols="12" sm="12" md="12">
                  <v-tabs  show-arrows>
                    <v-tabs-slider ></v-tabs-slider>
                    <v-tab  @click="activarVideo">
                      Video <v-icon right>{{videoActive?'visibility':'visibility_off'}}</v-icon>
                    </v-tab>
                    <v-tab @click="activarAudio">
                      Audio <v-icon right>{{audioActive?'volume_up':'volume_off'}} </v-icon>
                    </v-tab>
                    <v-tab @click="show_chat=!show_chat">
                      Chat  <v-icon right>{{show_chat?'insert_comment':'speaker_notes_off'}}</v-icon>
                    </v-tab>
                    <v-tab  @click="camaraActive=!camaraActive" >
                        Cámara <v-icon right> {{camaraActive?'videocam':'videocam_off'}}</v-icon>
                    </v-tab>
                    <v-tab @click="expander()">
                      {{expander_video?'Contraer':'Expander'}}
                        <v-icon right>{{expander_video?'arrow_left':'arrow_right'}}</v-icon>
                    </v-tab>
                  
                  </v-tabs>
                </v-col>
                <v-col cols="12" sm="12" md="12" lg="2">
                  <select name id="selectmic" @change="changeMic"></select>
                </v-col>
                <v-col cols="12" sm="12" md="12" lg="2">
                  <select name id="selectcam"></select>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col v-show="dataStream.stream_active" cols="12" sm="12" md="12" :lg="expander_video?2:3">
          <v-slide-x-reverse-transition hide-on-leave>
            <v-card
              v-show="show_camara || (show_camara && show_chat)"
              class="mx-auto"
              elevation="10"
              style="margin-bottom:20px;"
            >
              <v-card-text>
                <v-row>
                  <v-col cols="12">
                    <v-card
                      data-simplebar
                      class="mx-auto"
                      :style="{'height':'75vh','overflow-y': 'auto','overflow-x': 'hidden'}"
                    >
                      <v-tabs show-arrows vertical>
                        <v-tab v-for="i in 20" :key="i" :href="'#tab-' + i" style="height:150px;">
                          <v-img height="120px" width="20px" src="https://i.picsum.photos/id/40/500/300.jpg"></v-img>
                        </v-tab>
                      </v-tabs>
                    </v-card>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-slide-x-reverse-transition>
        </v-col>
      </v-row>

      <DialogSimple></DialogSimple>
    </section>
    <!--SideBarOptions></SideBarOptions-->
  </div>
</template>

<script>
var self;
var swal__;
var toast__;

import PanelContent from "../components/PanelContent";
import TableContent from "../components/TableContent";
import DialogSimple from "../components/DialogSimple";
import SideBarOptions from "./components/SideBarOptions";
import connection from "../connection.js";
export default {
  name: "Dashboard",
  components: {
    PanelContent,
    DialogSimple,
    TableContent,
    SideBarOptions
  },
  props: {
    etapa: {
      required: true
    }
  },
  data() {
    return {
      items: [
        { title: "Home", icon: "mdi-home-city" },
        { title: "My Account", icon: "mdi-account" },
        { title: "Users", icon: "mdi-account-group-outline" }
      ],

      show_chat: false,
      show_camara: true,
      expander_video: false,
      audioActive: true,
      videoActive: true,
      camaraActive: false,
      stream_active: false,
      loading: false,
      dataStream: { loading: false, event: null, stream_active: false },
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
    $(".fixed-sidebar").addClass("closed-sidebar");
    $(".hamburger.close-sidebar-btn.hamburger--elastic").css(
      "pointer-events",
      "none"
    );
    this.init();
    this.entrarSala();
  },
  methods: {
    expander() {
      this.expander_video = !this.expander_video;
    },
    activarVideo() {
      connection.attachStreams.forEach(function(stream) {
        stream.getVideoTracks().forEach(t => (t.enabled = !t.enabled));
      });
      this.videoActive = !this.videoActive;
    },
    activarAudio() {
      connection.StreamsHandler.onSyncNeeded(
        self.dataStream.event.streamid,
        "unmute",
        "both"
      );
      //connection.socket.emit('remotemute', { audio: false, video: false });
      //connection.socket.emit('remoteunmute', { audio: true, video: false });
      /*connection.attachStreams.forEach(function(stream) {
          stream.getAudioTracks().forEach(t => (t.enabled = !t.enabled));
        });*/
      connection.attachStreams.forEach(function(stream) {
        /* console.log(stream);
          if (stream.audio) {
            stream.mute('audio');
          }else{
            stream.unmute('audio');
          }*/
        stream.getAudioTracks().forEach(t => (t.enabled = !t.enabled));
      });

      //connection.socket.emit('remotemute', { audio: false, video: false });
      this.audioActive = !this.audioActive;
    },
    changeMic(sourceId) {
      sourceId = $("#selectmic").val();
      console.log(sourceId);
      //connection.mediaConstraints.audio.optional[0].sourceId = sourceId

      connection.mediaConstraints = {
        audio: {
          mandatory: {},
          optional: [
            {
              echoCancellation: false,
              sourceId: sourceId
            }
          ]
        },
        video: {
          mandatory: {},
          optional: [
            {
              sourceId: "your-camera-id"
            }
          ]
        }
      };

      if (connection.DetectRTC.browser.name === "Firefox") {
        connection.mediaConstraints = {
          audio: {
            deviceId: sourceId
          },
          video: {
            deviceId: "your-camera-id"
          }
        };
      }
      // if local or remote stream is muted
    },
    dialogClose() {
      this.$store.state.dialogSimple.dialog = false;
    },
    entrarSala() {
      let valores = { room: self.$route.params.id };
      this.loading = true;
      axios
        .post(this.etapa + "/online/entrarStreaming/", valores)
        .then(response => {
          var data = response.data;
          if (data.estado != "error") {
            connection.videosContainer = document.getElementById(
              "videos-container"
            );
            //this.stream_active = connection.dataStream.stream_active;
            if (data.response.isadmin) {
              connection.open(self.$route.params.id, function() {});
            } else {
              connection.sdpConstraints.mandatory = {
                OfferToReceiveAudio: true,
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
        })
        .finally(() => {
          self.loading = false;
        });
    },

    init() {
      connection.onstream = function(event) {
        self.dataStream.loading = false;
        self.dataStream.event = event;
        var existing = document.getElementById(event.streamid);

        if (existing && existing.parentNode) {
          existing.parentNode.removeChild(existing);
        }

        event.mediaElement.removeAttribute("src");
        event.mediaElement.removeAttribute("srcObject");
        event.mediaElement.muted = false;
        event.mediaElement.volume = 0;
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
        video.setAttribute("controls", true);
        connection.videosContainer.appendChild(mediaElement);

        setTimeout(function() {
          mediaElement.media.play();
        }, 5000);

        mediaElement.id = event.streamid;
        self.dataStream.stream_active = true;
      };
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
              
             
              
              /*
connection.codecs.video = 'vp9';
connection.codecs.audio = 'opus';*/
/*var BandwidthHandler = connection.BandwidthHandler;
connection.bandwidth = {
	audio: 128,
	video: 256, //para bajar calidad al compartir camara(creo xd)
	screen: 300 //para bajar calidad al compartir pantalla
};*/
connection.processSdp = function(sdp) {
    if (DetectRTC.browser.name === 'Safari') {
        return sdp;
    }
  console.log('connection.codecs.video',connection.codecs.video,sdp);
    if (connection.codecs.video.toUpperCase() === 'VP9') {
        sdp = CodecsHandler.preferCodec(sdp, 'vp9');
    }
     
    if (DetectRTC.browser.name === 'Firefox') {
        return sdp;
    }

    if (connection.bandwidth.video || connection.bandwidth.screen) {
        sdp = CodecsHandler.setApplicationSpecificBandwidth(sdp, connection.bandwidth, !!connection.session.screen);
    }
    if (connection.bandwidth.video) {
        sdp = CodecsHandler.setVideoBitrates(sdp, {
            min: connection.bandwidth.video * 8 * 1024,
            max: connection.bandwidth.video * 8 * 1024
        });
    }

    if (connection.bandwidth.audio) {
        sdp = CodecsHandler.setOpusAttributes(sdp, {
            maxaveragebitrate: connection.bandwidth.audio * 8 * 1024,
            maxplaybackrate: connection.bandwidth.audio * 8 * 1024,
            stereo: 1,
            maxptime: 3
        });
    }

    return sdp;
};
              */