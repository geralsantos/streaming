<template>
  <div>
    <section class="content">
      <PanelContent :title="'Salas'">
        <template v-slot:optionsbuttonstop>
          <v-btn class @click="nuevaSala()">
            <v-icon left dark>add</v-icon>Nueva Sala
          </v-btn>
        </template>

        <template v-slot:content>
          <div id="videos-container" style="overflow:auto;margin: 20px 0;"></div>
          <TableContent
            :title="'Listado de Salas'"
            :headers="headers"
            :items="items"
            :loading="loadingTable"
          >
            <template v-slot:btnAcciones="{item}">
              <template>
                <v-btn
                  v-if="(item.admin==0 || item.admin==null)"
                  @click="join_room(item)"
                  title="Ingresar"
                  class="info"
                  small
                >
                  Ingresar&nbsp;
                  <v-icon class="white--text" small>send</v-icon>
                </v-btn>
                <v-btn
                  @click="open_room(item)"
                  v-if="(item.admin==1)"
                  title="Modificar"
                  class="success"
                  small
                >Iniciar Streaming</v-btn>
              </template>
            </template>
          </TableContent>
          <!--div class="row justify-content-center">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header">Example Component</div>

                <div class="card-body">
                  <div class="form-group" style="padding:50px">
                    <label>Tu ID:</label>
                    <br />
                    <textarea class="form-control" id="yourId"></textarea>
                    <br />
                    <label>Otro ID:</label>
                    <br />
                    <textarea class="form-control" id="otherId"></textarea>
                    <button class="btn btn-primary" id="connect">Conectar</button>
                    <br />

                    <label>Mensaje:</label>
                    <br />
                    <textarea class="form-control" id="yourMessage"></textarea>
                    <button class="btn btn-primary" id="send">Enviar</button>
                    <pre id="messages"></pre>
                  </div>
                </div>
              </div>
            </div>
          </div-->
        </template>
      </PanelContent>
      <DialogSimple>
        <template v-slot:bodycardNuevaSala>
          <v-form ref="form" lazy-validation>
            <v-container>
              <v-row>
                <v-col cols="10" sm="6" md="6">
                  <v-text-field
                    v-model="nombre"
                    v-validate="'required|max:100'"
                    :error-messages="errors.collect('nombre')"
                    data-vv-name="nombre"
                    required
                    :counter="100"
                    label="Nombre"
                  ></v-text-field>
                </v-col>
                <v-col cols="10" sm="6" md="6">
                  <v-text-field
                    type="number"
                    v-model="capacidad"
                    v-validate="'required'"
                    :error-messages="errors.collect('capacidad')"
                    data-vv-name="capacidad"
                    required
                    :counter="100"
                    label="Capacidad"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="10" sm="6" md="6">
                  <v-menu
                    ref="menuDateExpiraSala"
                    v-model="menuDateExpiraSala"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="fechaExpiraSala"
                        label="Vigencia de la sala"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        v-validate="'required'"
                        :error-messages="errors.collect('fechaExpiraSala')"
                        data-vv-name="fechaExpiraSala"
                        required
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="fechaExpiraSala"
                      :min="moment().format('YYYY-MM-DD')"
                      :max="moment().add(2, 'day').format('YYYY-MM-DD')"
                      @change="save"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="6" sm="3" md="6">
                  <v-menu
                    ref="menuHoraExpiraSala"
                    v-model="menuHoraExpiraSala"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="horaExpiraSala"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="horaExpiraSala"
                        label="Hora expira sala"
                        prepend-icon="access_time"
                        readonly
                        v-on="on"
                        v-validate="'required'"
                        :error-messages="errors.collect('horaExpiraSala')"
                        data-vv-name="horaExpiraSala"
                        required
                      ></v-text-field>
                    </template>
                    <v-time-picker
                      v-if="menuHoraExpiraSala"
                      v-model="horaExpiraSala"
                      full-width
                      @click:minute="$refs.menuHoraExpiraSala.save(horaExpiraSala)"
                      format="24hr"
                      scrollable
                    ></v-time-picker>
                  </v-menu>
                </v-col>
              </v-row>
            </v-container>
          </v-form>
        </template>
        <template v-slot:buttonsNuevaSala>
          <v-btn
            color="primary"
            :loading="loading"
            :disabled="loading"
            @click="guardar()"
          >{{'Guardar'}}</v-btn>
          <v-btn @click="dialogClose()">Cancelar</v-btn>
        </template>
      </DialogSimple>
    </section>
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
export default {
  name: "Dashboard",
  components: {
    PanelContent,
    DialogSimple,
    TableContent
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
      loading: false,
      loadingTable: false,
      modificando: false,
      dictionary: {
        custom: {
          fechaExpiraSala: {
            required: () => "Ingrese la fecha"
          },
          nombre: {
            required: () => "Ingrese el nombre",
            max: "No debe pasarse el límite de caracteres"
          },
          capacidad: {
            required: () => "Ingrese la capacidad"
          },
          horaExpiraSala: {
            required: () => "Ingrese la hora"
          }
        }
      },
      items: [],
      items_detalle: [],
      headers: [
        {
          text: "N°",
          align: "center",
          value: "id"
        },
        { text: "Sala", align: "center", value: "nombre" },
        { text: "Vigencia", align: "center", value: "expira_sala" },

        { text: "Fecha Creación", align: "center", value: "fecha_creacion" },
        { text: "Acciones", value: "action", sortable: false }
      ]
    };
  },
  watch: {
    menuDateExpiraSala(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = "YEAR"));
    }
  },
  mounted() {
    self = this;
    swal__ = this.$store.getters.getSwal;
    toast__ = this.$store.getters.getToastDefault;

    this.$validator.localize("es", this.dictionary);
    this.init();
    this.cargarSalas();
  },
  methods: {
    dialogClose() {
      this.$store.state.dialogSimple.dialog = false;
    },
    openDialogSimpleNuevaSala(data) {
      this.$store.commit("openDialogSimple", data);
    },
    nuevaSala() {
      this.resetValues();
      this.openDialogSimpleNuevaSala({
        openDialog: true,
        bodycard: "bodycardNuevaSala",
        buttons: "buttonsNuevaSala",
        titulo: "Registra nueva sala",
        maxwidth: "800px"
      });
      setTimeout(() => {
        this.$validator.validateAll();
        self.errors.any();
      }, 250);
    },
    cargarSalas() {
      this.loadingTable = true;
      axios
        .get("dashboard/cargar")
        .then(response => {
          var data = response.data;
          self.items = data["salas"];
          self.items_detalle = data["sala_detalle"];
          self.loadingTable = false;
          console.log(data);
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    entrarSala(item) {
      window.open("/streaming/" + item.id, "_blank");
    },
    guardar() {
      this.$validator.validateAll();
      if (this.errors.any()) return;

      let valores = {
        nombre: this.nombre,
        capacidad: this.capacidad,
        expira_sala: this.fechaExpiraSala + " " + this.horaExpiraSala
      };
      this.loading = true;
      axios
        .post("dashboard/guardar/", valores)
        .then(response => {
          this.items = [];
          let data = response.data;
          toast__.fire({
            icon: data.estado,
            title: data.mensaje
          });
        })
        .catch(errors => {
          console.log(errors);
          swal__.fire("ERROR!", "ha ocurrido un error: " + errors, "error");
        })
        .finally(() => {
          //self.cargarAll();
          this.cargarSalas();
          this.dialogClose("dialogSimple");
          this.loading = false;
        });
    },
    open_room(item) {
      //disableInputButtons();
      let valores = item;
      axios
        .post("dashboard/iniciarStream/", valores)
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
        OfferToReceiveAudio: false,
        OfferToReceiveVideo: true
      };
      connection.join(item.nombre);
      setTimeout(() => {
      window.open("/streaming/#" + item.nombre, "_blank");
        
      }, 1000);
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
              