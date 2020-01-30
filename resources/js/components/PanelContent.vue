<template>
    <div>
        <v-row>
            <v-col cols="12" sm="12">
                <v-card >
                    <v-card-title class="blue white--text">
                        <span class="headline">{{title}} <v-chip v-if="subtitle!=undefined && subtitle!='' && subtitle!=null && subtitle!='null'" class="font-italic font-weight-medium">{{subtitle}}</v-chip></span>
                        <v-spacer></v-spacer>
                        <v-menu bottom left>
                            <template v-slot:activator="{ on }">
                                    <slot name="optionsbuttonstop"></slot>
                            </template>
                        </v-menu>
                    </v-card-title>
                    <br>
                    <v-card-text >
                        <template>
                                <slot name="header"> </slot>
                        </template>
                        <template>
                                <slot name="content"> </slot>
                        </template>
                    </v-card-text>
                    <v-card-actions >
                        <template>
                                <slot name="footer"> </slot>
                        </template>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
    export default {
        name: 'PanelContent',
	props: ['title','options','subtitle']//subtitle optional

    }
</script>



