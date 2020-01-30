import Vue from 'vue'
import Vuex from 'vuex'
import Swal from 'sweetalert2'

import { isContext } from 'vm';
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
      count: 10,
      sweetAlertBlocked:{

      },
      swal:Swal,
      dialogSimple:{
        bodycard:'',
        buttons:'',
        dialog:false,
        titulo:'',
        maxwidth:'',
        data:'',
      },
      dialogSimple2:{
        bodycard:'',
        buttons:'',
        dialog:false,
        titulo:'',
        maxwidth:'',
        data:'',
      },
      dialogItems:{
        dialog:false,
      },
      presupuesto:{
          proyecto:{}//datos del proyecto
      }
    },
    mutations: {
        openSweetAlertBlocked(state,data){
            Swal.fire({
                title: 'Cargando...',
                html: 'Este mensaje se cerrará apenas termine la importación.',
                timer: data.time,
                allowOutsideClick:false,
                allowEscapeKey:false,
                allowEnterKey:false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                onClose: () => {
                }
            }).then((result) => {
            })
        },
        openDialogSimple (state,data) {
            state.dialogSimple.bodycard=data.bodycard;
            state.dialogSimple.buttons=data.buttons;
            state.dialogSimple.optionsbuttonstop=data.optionsbuttonstop;
            state.dialogSimple.dialog=data.openDialog;
            state.dialogSimple.titulo=data.titulo;
            state.dialogSimple.maxwidth=data.maxwidth;
            state.dialogSimple.data=data.data;
        },
        openDialogSimple2 (state,data) {
            state.dialogSimple2.bodycard=data.bodycard;
            state.dialogSimple2.buttons=data.buttons;
            state.dialogSimple.optionsbuttonstop=data.optionsbuttonstop;
            state.dialogSimple2.dialog=data.openDialog;
            state.dialogSimple2.titulo=data.titulo;
            state.dialogSimple2.maxwidth=data.maxwidth;
            state.dialogSimple2.data=data.data;
        },
        openDialogItems(state,data){//no se utiliza
            state.dialogItems.dialog=data.response;
        },
        setProyecto(state,data){
            console.log(data);
            state.presupuesto.proyecto = data;
        }
    },
    getters:{
        getBodyCard(state){//no se utiliza
            return state.dialogSimple.bodycard;
        },
        getSwal(state){
            return state.swal;
        },
        getToastDefault(state){
            return state.swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 5000
            });
        },
        getProyecto(state){
            return state.presupuesto.proyecto;
        }
    },
    actions:{
        setProyecto(context,data){
            context.commit('setProyecto',data);
        }
    }
  });
  export default store;
