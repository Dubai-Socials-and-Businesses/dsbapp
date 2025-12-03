<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6" class="d-flex ga-3 align-center">
                <v-icon @click="this.$router.push({name:'AdminGallery'})">mdi-arrow-left</v-icon>
                <h4 class="text-h5">Add Gallery</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn @click="addGallery" color="green" :loading="gloading" density="compact" variant="elevated">Save</v-btn>
            </v-col>
            <v-col cols="12" md="8">
                <v-card class="border-sm">
                    <v-card-title>Details</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="title" density="compact" variant="underlined"
                                      placeholder="Gallery Title"></v-text-field>
                        <v-textarea v-model="description" density="compact" variant="underlined"
                                      placeholder="Gallery Description"></v-textarea>
                    </v-card-text>
                </v-card>
                <v-card class="mt-5 border-sm" v-if="photos.length > 0 || showPhotosSection">
                    <v-card-title class="d-flex">Gallery Photos
                        <v-spacer />
                        <v-btn color="primary" size="small" @click="addPhoto"
                            :disabled="photos.length >= 10">
                            {{ photos.length === 0 ? 'Add First Photo' : 'Add More' }}
                        </v-btn>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="6" v-for="(photo, index) in photos" :key="index">
                                <v-text-field v-model="photo.title" :placeholder="title"
                                              :label="`Photo ${index + 1} Title`" persistent-placeholder
                                              variant="outlined" density="compact"
                                />
                                <VFileUpload
                                    v-model="photo.image" clearable
                                    accept="image/*" title="Upload Image"
                                    icon="mdi-camera"
                                    variant="outlined"
                                    density="compact"
                                />
                                <v-btn variant="outlined" color="error" density="compact" class="mt-2"
                                       @click="removePhoto(index)">
                                    <v-icon>mdi-delete</v-icon> Remove
                                </v-btn>
                                <v-divider v-if="index < photos.length - 2" />
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
                <v-btn v-if="photos.length === 0" color="secondary" class="mt-2" density="comfortable"
                       @click="showPhotosSection = !showPhotosSection">
                    {{ showPhotosSection ? 'Hide Gallery' : 'Add Gallery Photos' }}
                </v-btn>
                <v-card class="border-sm mt-5">
                    <v-card-title>Video</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="video.title" density="compact" variant="underlined"
                                      placeholder="Video Title"></v-text-field>
                        <v-file-upload v-model="video.video_url" accept="video/*" clearable density="comfortable"
                                       title="Upload Video"></v-file-upload>
                        <v-text-field v-model="video.youtube" density="compact" variant="underlined" class="mt-3"
                                      placeholder="Youtube Url" prepend-inner-icon="mdi-youtube"></v-text-field>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card class="border-sm">
                    <v-card-text>
                        <v-file-upload v-model="main_image" density="comfortable" title="Main Image"
                                       accept="image/*" clearable></v-file-upload>
                    </v-card-text>
                    <v-card-text>
                       <v-date-picker v-model="gdate" title="Gallery Date/Month/Year" color="primary" width="100%"/>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";
import { VFileUpload } from 'vuetify/labs/VFileUpload'
import dayjs from "dayjs";
export default {
    name:'AdminGalleryAdd',
    components:{VFileUpload},
    data(){
        return{
            cdn:'https://dsbcdn.s3-accelerate.amazonaws.com/',
            gloading:false,
            showPhotosSection:false,
            title:'',
            main_image:null,
            description:'',
            gdate:null,
            photos:[],
            photo:{
                title:'',
                image:null,
            },
            video:{
                title:'',
                video_url:null,
                youtube:'',
            },
            galleries:[],
            galleryHeaders:[
                {title:'Title',key:'title'},
                {title:'Image',key:'main_image'},
                {title:'Description',key:'description'},
                {title:'gdate',key:'gdate'},
                {title:'Actions',key:'actions'},
            ]
        }
    },
    computed:{
        defaultDate(){
            return new Date();
        }
    },
    created() {
        this.getAllGalleries();
    },
    methods:{
        addPhoto() {
            this.photos.push({
                title: '',
                image: null,
            });
        },
        removePhoto(index) {
            this.photos.splice(index, 1);
            if (this.photos.length === 0) {
                this.showPhotosSection = false;
            }
        },
        formatDate(date) {
            if (!date) return '';
            return new Date(date).toLocaleString('en-GB', {
                timeZone: 'Asia/Dubai',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        },
        getAllGalleries(){
            axios.get('/galleries')
                .then((resp)=>{
                    this.galleries = resp.data.galleries;
                    this.gdate = dayjs(this.defaultDate).format('YYYY-MM-DD');
                })
        },
        addGallery(){
            this.gloading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const ndata = {
                title:this.title,
                description:this.description,
                main_image:this.main_image,
                gdate:dayjs(this.gdate).format('YYYY-MM-DD'),
                photos:this.photos,
                video:this.video
            }
            axios.post('/gallery/add',ndata,headers)
                .then((resp)=>{
                    console.log('resp',resp);
                    this.gloading = false;
                    this.$router.push({name:'AdminGallery'});
                    window.Toast.success(resp.data.message);
                })
                .catch((err)=>{
                    window.Toast.error(err.response.data.message)
                })
                .finally(()=>{
                    this.gloading = false;
                })
            console.log('ndata',ndata);
        }
    }
}
</script>

<style scoped>
</style>
