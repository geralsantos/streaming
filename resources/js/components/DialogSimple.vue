<template>
    <div>
        <v-dialog persistent v-model="$store.state.dialogSimple.dialog" :max-width="$store.state.dialogSimple.maxwidth" scrollable  >
          <v-card>
            <v-card-title class="headline red accent-2 white--text" primary-title > {{$store.state.dialogSimple.titulo}}
                <v-spacer></v-spacer>
                <template>
                        <slot :name="$store.state.dialogSimple.optionsbuttonstop"></slot>
                </template>
            </v-card-title>
            <v-card-text >
                <template>
                        <slot :name="$store.state.dialogSimple.bodycard"></slot>
                </template>
            </v-card-text>
            <v-card-actions >
                <v-spacer></v-spacer>
                <slot  :name="$store.state.dialogSimple.buttons"> </slot>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog persistent v-if="$store.state.dialogSimple2.dialog" v-model="$store.state.dialogSimple2.dialog" :max-width="$store.state.dialogSimple2.maxwidth" scrollable >
          <v-card>
              <v-card-title class="headline blue darken-1 white--text" primary-title > {{$store.state.dialogSimple2.titulo}}</v-card-title>
            <v-card-text>
                <template>
                     <slot  :name="$store.state.dialogSimple2.bodycard"> </slot>
                </template>
            </v-card-text>
            <v-card-actions >
                <v-spacer></v-spacer>

                <slot  :name="$store.state.dialogSimple2.buttons"></slot>

            </v-card-actions>
          </v-card>
        </v-dialog>
    </div>
</template>
<script>
    export default {
        name: 'DialogSimple',
data () {
      return {
        dialog:true,
        notifications: false,
        sound: true,
        widgets: false,
        titulo:'',
        maxwidth:'500px',
      }
    },
    mounted(){
        console.log(this.$store.state.dialogSimple.bodycard)
    }
    }
</script>
