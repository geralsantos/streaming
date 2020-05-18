<template>
  <div>
    <v-row>
      <v-col cols="12" sm="12">
        <v-card style="border-radius:10px;margin-top:25px;" elevation="5">
          <v-card
            :elevation="8"
            height="100"
            width="100"
            color="red darken-1"
            style="border-radius:10px;position: absolute;margin-left: 20px;margin-top:-25px;z-index: 1;"
          >
            <span
              class="material-icons white--text"
              style="font-size: 45px;  margin: auto; padding: 28px;"
            >{{icono_panel==null?'bookmark_border':icono_panel}}</span>
          </v-card>
          <v-card-title
            :style="{'color':'#bf0909','border-bottom':(borderBotom==null?'1px solid #4242422e':borderBotom)}"
          >
            <span class="headline" style="margin-left:130px">
              {{title}}
              <v-chip
                v-if="subtitle!=undefined && subtitle!='' && subtitle!=null && subtitle!='null'"
                class="font-italic font-weight-medium"
              >{{subtitle}}</v-chip>
            </span>
              <v-spacer></v-spacer>
           
<div class="panel-optionsbuttonstop-responsive">
            <template>
              <slot name="optionsbuttonstop"></slot>
            </template>
            </div>
          </v-card-title>
          <br />
          <v-card-text>
            <template>
              <slot name="header"></slot>
            </template>
            <template>
              <slot name="content"></slot>
            </template>
          </v-card-text>
          <v-card-actions>
            <template>
              <slot name="footer"></slot>
            </template>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>
<script>
export default {
  name: "PanelContent",
  props: ["title", "options", "subtitle","icono_panel","borderBotom"] //subtitle optional
};
</script>



