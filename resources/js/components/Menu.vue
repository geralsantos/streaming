<template>
  <div>
    <v-menu transition="slide-x-transition" bottom right>
      <template v-slot:activator="{ on }">
        <v-btn :small="small" :icon="icon" :fab="!icon" :class="clase" :color="color" dark v-on="on">
          <v-icon dark>{{icon_name}}</v-icon>{{btn_name}}
        </v-btn>
      </template>
      <v-list>
        <v-list-item  small v-for="(item, i) in list_menu" :key="i" v-if="(typeof item._if != 'undefined')?item._if:true"  @click="item.click(_item,item.params)">
          <v-list-item-title >
              <v-btn small icon :class="item.class">
                <v-icon class="white--text" small>{{item.icono}}</v-icon>
              </v-btn>&nbsp;&nbsp;{{item.nombre}}
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>
<script>
export default {
  name: "DialogSimple",
  props: ['list_menu','_item','small','clase','icon','icon_name','btn_name','color'],
  data() {
    return {};
  },
  mounted() {}
};
</script>
