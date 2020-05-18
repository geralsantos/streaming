<template>
  <div>
      <v-data-table
    :headers="computedHeaders"
    :items="items"
    :search="search"
    sort-by="calories"
    :sort-field="sortfield" :sort-desc="sortdesc"
    class=" TableContent" :loading="loading" loading-text="Cargando... Por favor espere"
    style="border-radius:10px;"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <template v-if="title!=null">
          <v-toolbar-title>{{title}}</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        </template>
        <template>
            <slot name="inputsAsideSearch"></slot>
        </template>
        <v-spacer></v-spacer>

        <v-text-field
        v-model="search"
        append-icon="search"
        label="Buscar"
        single-line
        hide-details
      ></v-text-field>
      </v-toolbar>
    </template>
    
 <template v-slot:item.action="{ item }">
        <slot name="btnAcciones" :item="item"></slot>
    </template>
 
    <!--/template-->
    <template v-slot:item.id="{ item }"><!--para todas los registros que tengan la cabecera-->
        <v-chip dark>
          {{items.map(function(x) {return x.id; }).indexOf(item.id)+1}}
        </v-chip>
    </template>
    <template v-slot:item.fecha_creacion="{ item }"><!--para todas los registros que tengan la cabecera-->
        <v-chip>
          {{ moment(item.fecha_creacion).format("DD-MM-YYYY")}}
        </v-chip>
    </template> 
    <template v-slot:no-data>
      <v-alert :value="true" color="error" style="color:white;" icon="warning">
        Lo sentimos, no existen registros.
      </v-alert>
    </template>

  </v-data-table>
  <template>
      <div class="text-center pt-2">
        <slot name="footerDataTable"></slot>
    </div>
  </template>

  </div>
</template>
 <script>

  export default {
      props:['title','headers','items','loading','name','sortfield','sortdesc'],//name,sortfield,sortdesc es opcional
    data: () => ({
      dialog: false,
        search: '',

    }),

    created () {

    },
    computed: {
computedHeaders () {
  return this.headers.filter((x)=>{return x.hidden==null || x.hidden==false})
}
},
    methods: {

    },
  }
</script>
