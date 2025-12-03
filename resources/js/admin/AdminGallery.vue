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
                    <v-data-table :items="galleries" :headers="galleryHeaders" :hide-default-footer="galleries.length < 10">
                        <template v-slot:item.main_image="{item}">
                            <v-img v-if="item.main_image" :src="cdn+item.main_image" class="my-2"></v-img>
                        </template>
                        <template v-slot:item.photos="{item}">
                            <div class="d-flex flex-wrap ga-1">
                                <v-btn color="green" density="compact" v-if="item.photos">{{item.photos.length}} Photos</v-btn>
                                <v-btn color="info" density="compact" v-if="item.video">Video</v-btn>
                            </div>
                        </template>
                        <template v-slot:item.gdate="{item}">
                            <div>
                                <div>{{formatDate(item.gdate)}}</div>
                            </div>
                        </template>
                        <template v-slot:item.actions="{item}">
                            <v-btn :to="{name:'AdminGalleryEdit',params:{gallery_id:item.id}}" density="compact" color="navy" variant="outlined">Edit</v-btn>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";
import dayjs from "dayjs";

export default {
    name:'AdminGallery',
    data(){
        return{
            cdn:'https://dsbcdn.s3-accelerate.amazonaws.com/',
            galleries:[],
            galleryHeaders:[
                {title:'Title',key:'title'},
                {title:'Main Image',key:'main_image'},
                {title:'Description',key:'description'},
                {title:'Other Photos',key:'photos'},
                {title:'Datetime',key:'gdate'},
                {title:'Actions',key:'actions'},
            ]
        }
    },
    created() {
        this.getAllGalleries();
    },
    methods:{
        dayjs,
        formatDate(date) {
            if (!date) return '';
            return new Date(date).toLocaleString('en-GB', {
                timeZone: 'Asia/Dubai',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                // hour: '2-digit',
                // minute: '2-digit',
                // hour12: true
            });
        },
        getAllGalleries(){
            axios.get('/galleries')
                .then((resp)=>{
                    this.galleries = resp.data.galleries;
                })
        }
    }
}
</script>

<style scoped>

</style>
