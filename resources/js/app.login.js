
//require('./bootstrap');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue = require('vue');
import Swal from 'sweetalert2'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

import es from 'vuetify/es5/locale/es'

Vue.use(Vuetify);
const vuetify = new Vuetify({
    icons: {
        iconfont: 'md',
    },
    lang: {
        locales: { es },
        current: 'es',
    },
})
const app = new Vue({
    vuetify,
    data: () => ({
        alignment: 'center',
        justify: 'center',
        sliderItems: [
            {
              src: '/assets/images/Web-frase1.png',
            },
            {
              src: '/assets/images/Web-frase2.png',
            },
            {
              src: '/assets/images/Web-frase3.png',
            }
          ],
        usuario: '',
        contrasena: '',
        showPasswordLogin: false,
        disableBtn: false,
        dictionary: {
            custom: {
                usuario: {
                    required: () => 'Ingrese su usuario',
                    max: 'El nombre de usuario debe ser menor a 50 caracteres',
                    // custom messages
                }, contrasena: {
                    required: () => 'Ingrese su contraseña',
                    max: 'La contraseña debe ser menor a 50 caracteres',
                    // custom messages
                },
            }
        }
    }),
    created: () => {
    },
    mounted: function () {
        // this.$validator.localize('es', this.dictionary);
        $(".wrap-login100").css('display','');
        $(".beforewelcome_div").css('display','');
    },
    methods: {
        
        pantalla(url){
            let ref = window.location.href;
            switch (url) {
                case 'login':
                    ref = ref.substr(-1) == "/" ? ref.slice(0, -1) : ref;
                    window.open("/login", '_blank');
                    break;
                case 'inst_plataforma_web':
                    ref = ref.substr(-1) == "/" ? ref.slice(0, -1) : ref;
                    window.open("/InstructivoPlataformaCanastasPeru.pdf", '_blank');
                    break;
                case 'inst_plataforma_movil':
                    ref = ref.substr(-1) == "/" ? ref.slice(0, -1) : ref;
                    window.open("/InstructivoAppMovilCanastasPeru.pdf", '_blank');
                    break;
                case 'video_1':
                    ref = ref.substr(-1) == "/" ? ref.slice(0, -1) : ref;
                    window.open("/PlataformaCanastasPeru.mp4", '_blank');
                    break;
                case 'video_2':
                    ref = ref.substr(-1) == "/" ? ref.slice(0, -1) : ref;
                    window.open("/AppmovilCanastasPeru.mp4", '_blank');
                    break;
                case 'googleplay':
                    ref = ref.substr(-1) == "/" ? ref.slice(0, -1) : ref;
                    window.open('https://play.google.com/store/apps/details?id=canastas.servicios.gob.pe', '_blank');
                    break;
               
                default:
                    break;
            }
        },
        slide(id){
            $('html,body').animate({
                scrollTop: $("#"+id).offset().top},
                'slow');
        },
        login() {
            this.$validator.validateAll();

            if (!this.errors.any()) {
                let valores = { usuario: this.usuario, contrasena: this.contrasena };
                this.disableBtn = true;
                axios.post('loginVerificar', valores)
                    .then(response => {
                        console.log(response);
                        var data = response.data;
                        if (data.success === true) {
                            //location.href='dashboard';
                            document.getElementById('form-login').submit();
                            //return false;
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: 'Error',
                                text: 'Credenciales incorrectas, intente de nuevo.',
                            });
                            this.disableBtn = false;
                        }
                    })
                    .catch(errors => {
                        Swal.fire('', 'Ha ocurrido un error: ' + errors, 'danger');
                        this.disableBtn = false;

                    }).finally(function () {
                        this.disableBtn = false;
                        //self.btnImportarDisabled=!self.btnImportarDisabled;
                    });
            }
        }
    }

}).$mount("#app");
