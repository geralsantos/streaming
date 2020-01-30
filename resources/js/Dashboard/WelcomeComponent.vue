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
          <div class="row justify-content-center">
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
          </div>
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
                      :min="((new Date().getFullYear())+'-0'+(new Date().getMonth()+1)+'-'+new Date().getDate())"
                      :max="((new Date().getFullYear())+'-0'+(new Date().getMonth()+1)+'-'+(new Date().getDate()+1))"
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
            @click="modificando?editar():guardar()"
          >{{modificando?'Modificar':'Guardar'}}</v-btn>
          <v-btn @click="dialogClose()">Cancelar</v-btn>
        </template>
      </DialogSimple>
    </section>
  </div>
</template>

<script>
var Peer = require("simple-peer");
var self;
var swal__;
var toast__;
import PanelContent from "../components/PanelContent";
import DialogSimple from "../components/DialogSimple";
export default {
  name: "Dashboard",
  components: {
    PanelContent,
    DialogSimple
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
      paramsStream: {},
      nombre: null,
      capacidad: null,
      menuDateExpiraSala: false,
      fechaExpiraSala: null,
      menuHoraExpiraSala: false,
      horaExpiraSala: null,
      loading: false,
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
          },horaExpiraSala: {
            required: () => "Ingrese la hora"
          }
        }
      },
      items:[],
      headers: [
      {
        text: "N°",
        align: "center",
        value: "id"
      },
      { text: "Sala", align: "center", value: "nombre" },
      { text: "Vigencia", align: "center", value: "expira_sala" },
    
      { text: "Fecha", align: "center", value: "fecha_creacion" },
      { text: "Acciones", value: "action", sortable: false }
    ],
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
      this.cargarSalas();
    },
    cargarSalas() {},
    guardar(){
      this.$validator.validateAll();
      if (this.errors.any()) return;
      let valores = {
        nombre: this.nombre,
        capacidad: this.capacidad,
        expira_sala: this.fechaExpiraSala+" "+this.horaExpiraSala,
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
          this.dialogClose("dialogSimple");
          this.loading = false;
        });
    },
    async getMedia() {
      let stream = null;
      try {
        console.log(navigator.mediaDevices.getUserMedia);
        if (location.hash === "#host") {
          navigator.getUserMedia_ = navigator.mediaDevices.getDisplayMedia(
            this.displayMedia
          );
          console.log("gerallll");
        } else {
          navigator.getUserMedia_ = navigator.mediaDevices.getUserMedia({
            audio: true
          });
        }
        stream = await navigator.getUserMedia_
          .then(function(stream2) {
            self.gotMedia(stream2);
          })
          .catch(function(err) {
            alert("then " + err);
          });
      } catch (err) {
        alert("ERROR " + err);
      }
    },
    init() {
      var video = document.createElement("video");
      document.body.appendChild(video);
      this.getMedia();
    },
    gotMedia(stream) {
      this.paramsStream = {
        initiator: true,
        trickle: false,
        /*wrtc: wrtc,*/ stream: stream
      };
      this.peer1 = new Peer(
        location.hash === "#host" ? this.paramsStream : { trickle: false }
      );

      this.peer1.on("signal", data => {
        //GET MY DATA ON THE
        let dat = JSON.stringify(data);
        if (location.hash === "#host") {
          localStorage.setItem("stream", dat);
        } else {
          localStorage.setItem("cliente", dat);
        }
        document.getElementById("yourId").value = dat;
      });

      document.getElementById("connect").addEventListener("click", function() {
        let dat = localStorage.getItem("stream");
        if (location.hash === "#host") {
          let cl = localStorage.getItem("cliente");
          self.peer1.signal(JSON.parse(cl));
        } else {
          localStorage.setItem("cliente", dat);
          console.log(dat);
          self.peer1.signal(JSON.parse(dat));
        }

        // alert('connect: ' + dat);
      });
      this.peer1.on("data", data => {
        document.getElementById("messages").textContent += data + "\n";
        console.log("got a message from peer1: " + data);
      });

      this.peer1.on("stream", stream => {
        // got remote video stream, now let's show it in a video tag
        var video = document.querySelector("video");
        if ("srcObject" in video) {
          video.srcObject = stream;
        } else {
          video.src = window.URL.createObjectURL(stream); // for older browsers
        }
        video.play();
      });
      document.getElementById("send").addEventListener("click", function() {
        var yourMessage = document.getElementById("yourMessage").value;
        console.log(
          "yourMessage JSON.stringify(data): " + JSON.stringify(yourMessage)
        );
        self.peer1.send(yourMessage);
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
