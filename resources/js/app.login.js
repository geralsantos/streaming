
//require('./bootstrap');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//window.Vue = require('vue');
import Swal from 'sweetalert2'/*
import Vuetify from 'vuetify'*/
//import 'vuetify/dist/vuetify.min.css'
import VeeValidate from 'vee-validate';
import store from './store.js';
Vue.use(VeeValidate,{
    useConstraintAttrs: false
});/*
Vue.use(Vuetify);
/*
import es from 'vuetify/es5/locale/es'
const vuetify = new Vuetify({
    icons: {
      iconfont: 'md',
    },
    lang: {
        locales: { es  },
        current: 'es',
      },
  })*/

const app = new Vue({
    vuetify:new Vuetify(),
    store,
    data: () =>({
        usuario:'',
        contrasena:'',
        showPasswordLogin:false,
        disableBtn:false,
        dictionary: {
            custom: {
                usuario: {
                required: () => 'Ingrese su usuario',
                max: 'El nombre de usuario debe ser menor a 50 caracteres',
                // custom messages
                },contrasena: {
                    required: () => 'Ingrese su contraseña',
                    max: 'La contraseña debe ser menor a 50 caracteres',
                    // custom messages
                },
            }
        }
    }),
    created:()=>{
    },
    mounted:function(){
        this.$validator.localize('es', this.dictionary);
        document.querySelector(".login-box").style.display = '';
    },
    methods:{
        login(){
            this.$validator.validateAll();

            if (!this.errors.any()) {
                let valores = {usuario:this.usuario,contrasena:this.contrasena};
                this.disableBtn = true;
                axios.post('loginVerificar', valores)
                .then(response => {
                    console.log(response);
                    var data = response.data;
                    if (data.success===true) {
                        //location.href='dashboard';
                        document.getElementById('form-login').submit();
                        //return false;
                    }else{
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: 'Credenciales incorrectas, intente de nuevo.',
                          });
                          this.disableBtn = false;
                    }
                })
                .catch(errors => {
                    Swal.fire('', 'Ha ocurrido un error: '+errors, 'danger');
                    this.disableBtn = false;

                }).finally(function () {
                    this.disableBtn = false;
                    //self.btnImportarDisabled=!self.btnImportarDisabled;
                });
            }
        }
    }

}).$mount("#app");
