<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6">
                <h4 class="text-h5">Gallery List</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn color="navy" density="compact" variant="outlined">More Actions</v-btn>
            </v-col>
            <v-col cols="12" md="9">
                <v-text-field prepend-inner-icon="mdi-magnify" density="compact" variant="outlined"
                              hide-details placeholder="Search Gallery"></v-text-field>
            </v-col>
            <v-col cols="12" md="3">
                <v-btn :to="{name:'AdminGalleryAdd'}" color="navy" density="default" block append-icon="mdi-plus">Add Gallery</v-btn>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                    <v-data-table :items="galleries" :headers="galleryHeaders" :hide-default-footer="galleries.length < 20">

                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";

export default {
    name:'AdminGallery',
    data(){
        return{
            galleries:[],
            galleryHeaders:[
                {title:'Title',key:'title'},
                {title:'Image',key:'main_image'},
                {title:'Description',key:'description'},
                {title:'Datetime',key:'datetime'},
                {title:'Actions',key:'actions'},
            ]
        }
    },
    created() {
        this.getAllGalleries();
    },
    methods:{
        getAllGalleries(){
            axios.get('/admin/galleries')
                .then((resp)=>{
                    this.galleries = resp.data.galleries;
                })
        }
    }
}
</script>

<style scoped>

</style>
