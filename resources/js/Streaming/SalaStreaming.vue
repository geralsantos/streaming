<template>
  <div>
    <section class="content">
      <PanelContent :title="'Salas'">
        <template v-slot:optionsbuttonstop>
          <v-btn v-if="usuario_sistema.nivel!='cliente'" class @click="nuevaSala()">
            <v-icon left dark>add</v-icon>Nueva Sala
          </v-btn>
        </template>

        <template v-slot:content>
          <TableContent :headers="headers" :items="items" :loading="loadingTable">
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
              <!--v-row>
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
              </v-row-->
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
var self;
var swal__;
var toast__;
import PanelContent from "../components/PanelContent";
import TableContent from "../components/TableContent";
import DialogSimple from "../components/DialogSimple";
import connection from '../connection.js'
export default {
  name: "Dashboard",
  components: {
    PanelContent,
    DialogSimple,
    TableContent
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
      loading: false,
      loadingTable: false,
      modificando: false,
      usuario_sistema: null,
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
        { text: "Capacidad", align: "center", value: "capacidad" },

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
  created: function() {
    let user = document.getElementById("usuario_id");
    this.usuario_sistema = {
      nivel: user.getAttribute("data-nivel")
    };
  },
  mounted() {
    self = this;
    swal__ = this.$store.getters.getSwal;
    toast__ = this.$store.getters.getToastDefault;

    this.$validator.localize("es", this.dictionary);
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
        .get(this.etapa + "/cargar")
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
    guardar() {
      this.$validator.validateAll();
      if (this.errors.any()) return;

      let valores = {
        nombre: this.nombre,
        capacidad: this.capacidad
      };
      this.loading = true;
      axios
        .post(this.etapa + "/guardar/", valores)
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
        .post(this.etapa + "/iniciarStream/", valores)
        .then(response => {
          let data = response.data;
          if (data.estado != "error") {
            console.log(connection);

            let ref = window.location.href;
            ref = ref.substr(-1) == "/" ? ref.slice(0, -1) : ref;
            window.open(ref + "/../online/" + data.salatoken, "_blank");
          }
        })
        .catch(errors => {
          console.log(errors);
          swal__.fire("ERROR!", "ha ocurrido un error: " + errors, "error");
        });
    },
    join_room(item) {
      let valores = { room: item.id };
      axios
        .post(this.etapa + "/encriptarStream/", valores)
        .then(response => {
          let data = response.data;
          if (data.estado != "error") {
            connection.checkPresence(data.salatoken, function(
              isRoomExist,
              roomid,
              error
            ) {
              if (isRoomExist === true) {
                let ref = window.location.href;
                ref = ref.substr(-1) == "/" ? ref.slice(0, -1) : ref;
                window.open(ref + "/../online/" + data.salatoken, "_blank");
              } else {
                //connection.open(roomid);
                swal__.fire(
              "Información",
              "La videoconferencia aún no ha iniciado",
              "warning"
            );
              }
            });
          } else {
            swal__.fire(
              "ERROR!",
              "ha ocurrido un error: " + data.mensaje,
              "error"
            );
          }
        })
        .catch(errors => {
          console.log(errors);
          swal__.fire("ERROR!", "ha ocurrido un error: " + errors, "error");
        });
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
              