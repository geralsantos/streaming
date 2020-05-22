<template>
  <div>
    <section class="content">
      <PanelContent :icono_panel="'settings'" :title="'Registrar de usuarios'">
        <template v-slot:optionsbuttonstop>
          <v-btn class="info" @click="nuevoUsuario()">
            <v-icon left dark>add</v-icon>Nuevo
          </v-btn>
        </template>
        <template v-slot:content>
          <TableContent
            :title="'Lista de usuarios'"
            :headers="headers"
            :items="items"
            :loading="loadingTable"
          >
            <template v-slot:btnAcciones="{item}">
              <Menu
                :small="false"
                :icon="true"
                :clase="'warning'"
                :color="'white'"
                :_item="item"
                :icon_name="'more_vert'"
                :list_menu="listar_menu_tabla()"
              ></Menu>
            </template>
          </TableContent>
        </template>
      </PanelContent>
      <DialogSimple>
        <template v-slot:bodycardNuevoUsuario>
          <v-container>
            <v-form ref="form" v-model="valid" :lazy-validation="true">
            <v-row>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  :disabled="modificando"
                  v-model="usuario"
                  v-on:change="verificar"
                  autofocus
                  v-validate="'required|max:70'"
                  :error-messages="errors.collect('usuario')"
                  data-vv-name="usuario"
                  required
                  :counter="70"
                  label="Usuario"
                ></v-text-field>
              </v-col>
              <v-col v-if="!modificando" cols="12" sm="6" md="4">
                <v-text-field
                  v-model="password"
                  v-validate="'required|max:70'"
                  :error-messages="errors.collect('password')"
                  :type="showPasswordLogin ? 'text' : 'password'"
                  :append-icon="showPasswordLogin ? 'visibility' : 'visibility_off'"
                  @click:append="showPasswordLogin = !showPasswordLogin"
                  data-vv-name="password"
                  required
                  :counter="70"
                  label="Contraseña"
                ></v-text-field>
              </v-col>

              <v-col v-if="!modificando" cols="12" sm="6" md="4">
                <v-text-field
                  v-model="repassword"
                  v-validate="'required|max:70'"
                  :error-messages="errors.collect('repassword')"
                  data-vv-name="repassword"
                  :type="showPasswordLogin ? 'text' : 'password'"
                  :append-icon="showPasswordLogin ? 'visibility' : 'visibility_off'"
                  required
                  @click:append="showPasswordLogin = !showPasswordLogin"
                  :counter="70"
                  label="Confirmar contraseña"
                ></v-text-field>
                {{errorConfirmedPassword}}
              </v-col> 
               <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="primer_nombre" 
                  v-validate="'max:30'"
                  :error-messages="errors.collect('primer_nombre')"
                  data-vv-name="primer_nombre"
                  :counter="30"
                  label="Primer Nombre"
                ></v-text-field>
              </v-col>
               <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="segundo_nombre" 
                  v-validate="'max:30'"
                  autofocus
                  :error-messages="errors.collect('segundo_nombre')"
                  data-vv-name="segundo_nombre"
                  :counter="30"
                  label="Segundo nombre"
                ></v-text-field>
              </v-col>
               <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="primer_apellido" 
                  v-validate="'max:30'"
                  autofocus
                  :error-messages="errors.collect('primer_apellido')"
                  data-vv-name="primer_apellido"
                  :counter="30"
                  label="Primer apellido"
                ></v-text-field>
              </v-col>
               <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="segundo_apellido" 
                  v-validate="'max:30'"
                  :error-messages="errors.collect('segundo_apellido')"
                  data-vv-name="segundo_apellido"
                  :counter="30"
                  label="Segundo apellido"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="correo" 
                  autofocus
                  v-validate="'max:100'"
                  :rules="[v => ((v!=''&&v!=null) ?validEmail(v):true)  || 'El correo no tiene el formato correcto']"
                  :error-messages="errors.collect('correo')"
                  data-vv-name="correo"
                  :counter="100"
                  label="Correo"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field
                  v-model="nro_documento" 
                  :rules="[v => ((v!=null&&v!=''?v.length==8:true)  ) || 'El DNI tiene que tener 8 caracteres']"
                  autofocus
                  :error-messages="errors.collect('nro_documento')"
                  data-vv-name="nro_documento"
                  :counter="8"
                  label="DNI"
                  type="number"
                ></v-text-field>
              </v-col>
             
              <v-col cols="12" sm="6" md="4">
                <span>Seleccione Perfíl</span>
                <v-select
                  :disabled="modificando"

                  v-model="perfil"
                  v-validate="'required'"
                  :error-messages="errors.collect('perfil')"
                  data-vv-name="perfil"
                  required
                  :items="perfiles"
                  item-text="nombre"
                  item-value="id"
                  menu-props="auto"
                  label="Perfíl"
                  single-line
                ></v-select>
              </v-col>
              
            </v-row>
            </v-form>
          </v-container>
        </template>
        <template v-slot:buttonsNuevoUsuario>
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
import PanelContent from "../components/PanelContent";
import TableContent from "../components/TableContent";
import DialogSimple from "../components/DialogSimple";
import Menu from "../components/Menu";
var self;
var swal__;
var toast__;
export default {
  name: "RegistrarUsuarios",
  components: {
    PanelContent,
    TableContent,
    DialogSimple,
    Menu
  },
  props: {
    etapa: {
      required: true
    }
  },
  data: () => ({
    valid:true,
    modificando: false,
    usuario: null,
   usuario_sistema:null,
    loadingTable: false,
    usuario_id: null,
     password: null,
    repassword: null,
    showPasswordLogin: false,
    errorConfirmedPassword: "",
    correo:null,
    nro_documento:null,
    primer_nombre:null,
    segundo_nombre:null,
    primer_apellido:null,
    segundo_apellido:null,
    loading: false,
    perfil: null,
    perfiles: null,
    departamentos: [],
    departamento: null,
    provincias: [],
    provincia: null,
    distritos: [],
    distrito: null,
    dictionary: {
      custom: {
        usuario: {
          required: () => "Ingrese el nombre del usuario",
          max: "El usuario debe ser menor a 70 caracteres"
        },
        primer_nombre: {
          max: "El primer nombre debe ser menor o igual a 30 caracteres"
        },
        segundo_nombre: {
          max: "El segundo nombre debe ser menor o igual a 30 caracteres"
        },
        primer_apellido: {
          max: "El primer apellido debe ser menor o igual a 30 caracteres"
        },
        segundo_apellido: {
          max: "El segundo apellido debe ser menor o igual a 30 caracteres"
        },
      password: {
          required: () => "Ingrese una contraseña",
          max: "La contraseña debe ser menor a 70 caracteres"
        },
        repassword: {
          required: () => "Confirmar contraseña"
        },
        correo: {
          max: "El correo debe ser menor o igual a 100 caracteres"
        },
        perfil: {
          required: () => "Ingrese el perfil"
        }, 
      }
    },
    headers: [
      {
        text: "N°",
        align: "center",
        value: "id"
      }, 
      {
        text: "Nombres",
        align: "center",
        value: "nombre_completo"
      },

      { text: "Usuario", align: "center", value: "usuario" },
      { text: "Perfíl", align: "center", value: "perfil_nombre" },

      { text: "Fecha", align: "center", value: "fecha_creacion" },
      { text: "Acciones", value: "action", sortable: false }
    ],
    items: []
  }),
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
    this.cargarAll();
    this.cargarPerfiles();
  },
  watch:{
     
  },
  methods: {
    listar_menu_tabla() {
      return [
        {
          click: this.cargar,
          icono: "edit",
          class: "warning",
          nombre: "Editar"
        },
        {
          click: this.eliminar,
          icono: "delete",
          class: "error",
          nombre: "Eliminar"
        },
        {
          click: this.resetPass,
          icono: "refresh",
          class: "error",
          nombre: "Resetear Contraseña"
        }
      ];
    },
    dialogClose() {
      this.$store.state.dialogSimple.dialog = false;
    }, 
    cargarPerfiles() {
      axios
        .get(this.etapa + "/perfiles/cargar")
        .then(response => {
          this.perfiles = response.data;
        })
        .catch(errors => {
          console.log(errors);
        });
    }, 
    cargarAll() {
      this.loadingTable = true;
      axios
        .get(this.etapa + "/usuarios/cargar")
        .then(response => {
          var data = response.data;
          //self.items = data. [{empleado_id:data.empleado_id,usuario:data.usuario,password:data.password,estado:data.estado}];
          self.items = data;
          self.loadingTable = false;
          console.log(data);
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    verificar() {
      let valores = {
        usuario: this.usuario,
        tipo:this.modificando?'modificar':'guardar',
        id:this.modificando?this.usuario_id:null,
      };
      axios
        .post(this.etapa + "/usuarios/verificar/", valores)
        .then(response => {
          var data = response.data;
          if (data === 1) {
            swal__.fire(
              "Información",
              "Ya existe este usuario, favor de usar otro",
              "warning"
            );
            this.usuario = null;
          }

          console.log(data);
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    cargar(item) {
      this.resetValues();
      let valores = { id: item.id };
      this.modificando = true;
      axios
        .post(this.etapa + "/usuarios/buscar/", valores)
        .then(response => {
          var data = response.data;
          this.usuario = data.usuario;
          //this.password = data.password;
          //this.repassword = data.password;
          this.correo = data.correo,
          this.nro_documento = data.numero_documento,  
          this.primer_nombre = data.primer_nombre,  
          this.segundo_nombre = data.segundo_nombre,  
          this.primer_apellido = data.primer_apellido,  
          this.segundo_apellido = data.segundo_apellido,  
          this.perfil = data.perfil_id;
          this.usuario_id = data.id;
         
          self.openDialogSimpleNuevoUsuario({
            openDialog: true,
            bodycard: "bodycardNuevoUsuario",
            buttons: "buttonsNuevoUsuario",
            titulo: "Modificar usuario",
            maxwidth: "800px"
          });
          setTimeout(function() {
            self.$validator.validateAll();
            self.errors.any();
          }, 500);
          console.log(data);
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    editar() {
      this.$validator.validateAll();
      if (this.errors.any()) return false;
     /* this.errorConfirmedPassword = "";
      if (this.password != this.repassword) {
        this.errorConfirmedPassword = "Las contraseñas deben ser iguales";
        return false;
      }*/
      let valores = {
        id: this.usuario_id,
        usuario: this.usuario,
        password: this.modificando?"": this.password,
        correo: this.correo,
        numero_documento: this.nro_documento,
        primer_nombre: this.primer_nombre,
        segundo_nombre: this.segundo_nombre,
        primer_apellido: this.primer_apellido,
        segundo_apellido: this.segundo_apellido,
        perfil_id: this.perfil,
      };
      this.loading = true;
      axios
        .patch(this.etapa + "/usuarios/editar/", valores)
        .then(response => {
          var data = response.data;
          if (response.data == 1) {
            this.items = [];
            toast__.fire({
              icon: "success",
              title: "El usuario ha sido modificado"
            });
          }
        })
        .catch(errors => {
          swal__.fire("ERROR!", "ha ocurrido un error: " + errors, "error");
        })
        .finally(() => {
          self.cargarAll();
          this.dialogClose();
          this.loading = false;
        });
    },
    eliminar(item) {
      let valores = {
        id: item.id,
        estado: 0
      };
      swal__
        .fire({
          title: "¿ Desea eliminar al usuario " + item.usuario + " ?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Si, eliminar",
          cancelButtonText: "No"
        })
        .then(result => {
          if (result.value) {
            axios
              .post(this.etapa + "/usuarios/eliminar/", valores)
              .then(response => {
                var data = response.data;
                if (response.data == 1) {
                  this.items = [];
                  self.cargarAll();
                  toast__.fire({
                    icon: "success",
                    title: "El usuario ha sido eliminado"
                  });
                }
              })
              .catch(errors => {
                swal__.fire(
                  "ERROR!",
                  "ha ocurrido un error: " + errors,
                  "error"
                );
              });
          }
        });
    },
    guardar() {
      this.$validator.validateAll();
      if (this.errors.any()) return;
      this.errorConfirmedPassword = "";
      if (this.password != this.repassword) {
        this.errorConfirmedPassword = "Las contraseñas deben ser iguales";
        return false;
      }
      console.log(this.usuario_sistema.nivel)
      let valores = {
        usuario: this.usuario,
        password: this.modificando?"": this.password,
        correo: this.correo,
        numero_documento: this.nro_documento,
        primer_nombre: this.primer_nombre,
        segundo_nombre: this.segundo_nombre,
        primer_apellido: this.primer_apellido,
        segundo_apellido: this.segundo_apellido,
        perfil_id: this.perfil,
      };
      console.log(valores);
      this.loading = true;
      axios
        .post(this.etapa + "/usuarios/guardar/", valores)
        .then(response => {
          this.items = [];
          toast__.fire({
            icon: "success",
            title: "El usuario ha sido registrado"
          });
          var data = response.data;
        })
        .catch(errors => {
          swal__.fire("ERROR!", "ha ocurrido un error: " + errors, "error");
        })
        .finally(() => {
          self.cargarAll();
          this.dialogClose();
          this.loading = false;
        });
    },
    openDialogSimpleNuevoUsuario(data) {
      this.$store.commit("openDialogSimple", data);
    },
    nuevoUsuario() {
      this.resetValues();
      this.openDialogSimpleNuevoUsuario({
        openDialog: true,
        bodycard: "bodycardNuevoUsuario",
        buttons: "buttonsNuevoUsuario",
        titulo: "Registra nuevo usuario",
        maxwidth: "800px"
      });
      setTimeout(() => {
        this.$validator.validateAll();
        self.errors.any();
      }, 250);
    },
    validEmail: function (email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            resetPass(item){
              let valores = {
        id: item.id,
        estado: 0
      };
      swal__
        .fire({
          title: "¿ Desea reiniciar la contraseña al usuario " + item.usuario + " ?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Si",
          cancelButtonText: "No"
        })
        .then(result => {
          if (result.value) {
            axios
              .post(this.etapa + "/usuarios/reset/", valores)
              .then(response => {
                var data = response.data;
                if (response.data == 1) {
                  self.cargarAll();
                  toast__.fire({
                    icon: "success",
                    title: "La contraseña ha sido modificada"
                  });
                }
              })
              .catch(errors => {
                swal__.fire(
                  "ERROR!",
                  "ha ocurrido un error: " + errors,
                  "error"
                );
              });
          }
        });
            },
    resetValues() {
      this.usuario = null;
      this.password = null;
      this.correo=null;
      this.nro_documento=null;
      this.primer_nombre=null;
      this.segundo_nombre=null;
      this.primer_apellido=null;
      this.segundo_apellido=null;
      this.repassword = null;
      this.perfil = null;
      this.modificando = false;
      this.usuario_id=null;
    }
  }
};
</script>



