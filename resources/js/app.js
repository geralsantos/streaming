
require('./bootstrap');

window.Vue = require('vue');
var moment = require('moment');
import 'moment/locale/es';
Vue.prototype.moment = moment;
//import 'material-design-icons-iconfont/dist/material-design-icons.css' // Ensure you are using css-loader
import VueRouter from 'vue-router';
import Swal from 'sweetalert2'
import Vuex from 'vuex'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import store from './store.js';
import * as VeeValidate from 'vee-validate';
import routes from './routes.js'
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(Vuetify);
Vue.use(VeeValidate,{
    useConstraintAttrs: false
});
// Translation provided by Vuetify (typescript)
import es from 'vuetify/es5/locale/es'
const vuetify = new Vuetify({
    icons: {
      iconfont: 'md',
    },
    lang: {
        locales: { es  },
        current: 'es',
      },
  })

const router = new VueRouter({
    routes: routes,
    mode: "history"
})
Vue.component('contentheader', {
    data: function () {
      return {
        count: 0
      }
    },
    template: `<section class="content-header">
    <h1>
        SISTEMA DE STREAMING
        <small></small>
    </h1>
    <ol class="breadcrumb">
    <li><a v-if="$router.currentRoute.name!='Dashboard'" href="/dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i v-if="$router.currentRoute.name=='Dashboard'" class="fa fa-dashboard"></i> {{$router.currentRoute.name}}</li>
    </ol>
</section>`
  })
  var self;
const app = new Vue({
    router,
    store,
    vuetify,
    data: () =>({
        usuario:'',
        contrasena:'',
        showPasswordLogin:false,
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
        self = this;
        this.$validator.localize('es', this.dictionary);
        console.log("abc2");

        this.mostrarModuloActivo();
    },
    methods:{
        mostrarModuloActivo(){
            var pathSplit = this.$router.currentRoute.path.split("/");
            var die = setInterval(() => {
                let new_path = pathSplit.length==2 ?this.$router.currentRoute.path : "/"+pathSplit[1]+"/"+pathSplit[2];
                new_path = self.modulosReferencia(new_path);
                let $sidebar_menu_tree = $('.sidebar-menu.tree').find('a[href="'+new_path+'"]');
                if ($sidebar_menu_tree.length>0) {
                    $sidebar_menu_tree.parents('li.treeview').addClass('menu-open');
                    $sidebar_menu_tree.parents('li').css('background-color','#1e282c');
                    $sidebar_menu_tree.parents('ul.treeview-menu').css('display','block');
                    clearInterval(die);
                }
            }, 200);
            setTimeout(() => {
                clearInterval(die);
            }, 10000);
        },
        modulosReferencia(path){
            switch (path) {
                case "/presupuesto/items":
                case "/presupuesto/insumos":
                    return "/presupuesto/proyectos";
                    break;
                case "/presupuesto/consolidado":
                    return "/presupuesto";
                    break;
                case "/diseno/solicitud":
                case "/diseno/solid":
                    return "/diseno/proyectos";
                    break;
                case "/pedido/documentaria":
                    return "/pedido";
                    break;
                default:
                    return path;
                    break;
            }
        },
        login(){
            this.$validator.validateAll();
            if (!this.errors.any()) {
                let valores = {usuario:this.usuario,contrasena:this.contrasena};
                var swal__ = this.$store.getters.getSwal;
                axios.post('loginVerificar', valores)
                .then(response => {
                    console.log(response);
                    var data = response.data;
                    if (data.success===true) {
                        //location.href='dashboard';
                        document.getElementById('form-login').submit();
                        //return false;
                    }else{
                        swal__.fire({
                            type: 'error',
                            title: 'Error',
                            text: 'Credenciales incorrectas, intente de nuevo.',
                          });
                    }
                })
                .catch(errors => {
                    swal__.fire('', 'Ha ocurrido un error: '+errors, 'danger');
                }).finally(function () {
                    //self.btnImportarDisabled=!self.btnImportarDisabled;
                });
            }
        }
    }

}).$mount('#app');


