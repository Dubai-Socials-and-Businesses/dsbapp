<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6" class="d-flex ga-3 align-center">
                <v-icon @click="$router.push({name:'AdminGallery'})">mdi-arrow-left</v-icon>
                <h4 class="text-h5">Edit Gallery</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn @click="editGallery" color="green" :loading="gloading" density="compact" variant="elevated">update</v-btn>
            </v-col>
            <v-col cols="12" md="8">
                <v-card class="border-sm">
                    <v-card-title>Details</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="gallery.title" density="compact" variant="underlined"
                                      placeholder="Gallery Title"></v-text-field>
                        <v-textarea v-model="gallery.description" density="compact" variant="underlined"
                                      placeholder="Gallery Description"></v-textarea>
                    </v-card-text>
                </v-card>
                <v-card class="mt-5 border-sm">
                    <v-card-title class="d-flex">Existing Gallery Photos</v-card-title>
                    <v-card-text v-if="gphotos.length > 0">
                        <v-row>
                            <v-col cols="12" md="6" v-for="(gphoto, index) in gphotos" :key="index">
                                <v-text-field v-model="gphoto.title" :placeholder="gallery.title"
                                              :label="`Photo ${index + 1} Title`" persistent-placeholder
                                              variant="outlined" density="compact"
                                />
                                <v-img v-if="gphoto.image" :src="cdn+gphoto.image" contain></v-img>
                                <VFileUpload class="mt-2"
                                             v-model="gphoto.nimage" clearable
                                             accept="image/*" title="Change Image"
                                             icon="mdi-camera"
                                             variant="outlined"
                                             density="compact"
                                />
                                <v-btn variant="outlined" color="error" density="compact" class="mt-2"
                                       @click="removeGPhoto(gphoto.id)">
                                    <v-icon>mdi-delete</v-icon> Remove
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-title class="d-flex">New Gallery Photos
                        <v-spacer />
                        <v-btn color="primary" size="small" @click="addPhoto">
                            {{ photos.length === 0 ? 'Add More Photo' : 'Add More' }}
                        </v-btn>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="6" v-for="(photo, index) in photos" :key="index">
                                <v-text-field v-model="photo.title" :placeholder="gallery.title"
                                              :label="`Photo ${index + 1} Title`" persistent-placeholder
                                              variant="outlined" density="compact"
                                />
                                <VFileUpload class="mt-2"
                                    v-model="photo.nimage" clearable
                                    accept="image/*" title="Upload Image"
                                    icon="mdi-camera"
                                    variant="outlined"
                                    density="compact"
                                />
                                <v-btn variant="outlined" color="error" density="compact" class="mt-2"
                                       @click="removePhoto(index)">
                                    <v-icon>mdi-delete</v-icon> Remove
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
                <v-card class="mt-5 border-sm">
                    <v-card-title class="d-flex">Existing Reels</v-card-title>
                    <v-card-text v-if="ereels.length > 0">
                        <v-row>
                            <v-col cols="12" md="4" v-for="(ereel, index) in ereels" :key="index">
                                <v-text-field v-model="ereel.title" :placeholder="ereel.title"
                                              :label="`Reel ${index + 1} Title`" persistent-placeholder
                                              variant="outlined" density="compact"
                                />
                                <v-video v-if="ereel.reel_url" :src="cdn+ereel.reel_url" aspectRatio="9/16" controlsVariant="tube"
                                         image="https://cdn.jsek.work/cdn/vt-sunflowers.jpg"></v-video>
                                <VFileUpload class="mt-2"
                                             v-model="ereel.nreel_url" clearable
                                             accept="video/*" title="Change Reel"
                                             icon="mdi-video"
                                             variant="outlined"
                                             density="compact"
                                />
                                <v-btn variant="outlined" color="error" density="compact" class="mt-2"
                                       @click="removeEreel(ereel.id)">
                                    <v-icon>mdi-delete</v-icon> Remove
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-title class="d-flex">New Reels
                        <v-spacer />
                        <v-btn color="primary" size="small" @click="addReel">
                            {{ reels.length === 0 ? 'Add More Reel' : 'Add More' }}
                        </v-btn>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="4" v-for="(reel, index) in reels" :key="index">
                                <v-text-field v-model="reel.title" :placeholder="gallery.title"
                                              :label="`Reel ${index + 1} Title`" persistent-placeholder
                                              variant="outlined" density="compact"
                                />
                                <VFileUpload class="mt-2"
                                             v-model="reel.nreel_url" clearable
                                             accept="video/*" title="Upload Reel"
                                             icon="mdi-video"
                                             variant="outlined"
                                             density="compact"
                                />
                                <v-btn variant="outlined" color="error" density="compact" class="mt-2"
                                       @click="removeReel(index)">
                                    <v-icon>mdi-delete</v-icon> Remove
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card class="border-sm">
                    <v-card-text>
                        <v-img v-if="gallery.main_image" :src="cdn+gallery.main_image"></v-img>
                        <v-file-upload v-model="main_image" density="compact" title="Change Main Image"
                                       accept="image/*" clearable></v-file-upload>
                    </v-card-text>
                    <v-card-text>
                       <v-date-picker v-model="gallery.gdate" title="Gallery Date/Month/Year" color="primary" width="100%"/>
                    </v-card-text>
                </v-card>
                <v-card class="border-sm mt-5">
                    <v-card-title>Video</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="video.title" density="compact" variant="underlined"
                                      placeholder="Video Title"></v-text-field>
                        <v-video v-if="video.video_url" :src="cdn+video.video_url" controlsVariant="tube"
                                 image="https://cdn.jsek.work/cdn/vt-sunflowers.jpg"></v-video>
                        <v-file-upload v-model="video.nvideo" accept="video/*" clearable density="compact"
                                       title="Change Video"></v-file-upload>
                        <v-text-field v-model="video.youtube" density="compact" variant="underlined" class="mt-3"
                                      placeholder="Youtube Url" prepend-inner-icon="mdi-youtube"></v-text-field>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";
import { VFileUpload } from 'vuetify/labs/VFileUpload'
import {VVideo} from "vuetify/labs/components";
export default {
    name:'AdminGalleryAdd',
    components:{VVideo, VFileUpload},
    props:{
        gallery_id:[Number,String]
    },
    data(){
        return{
            cdn:'https://dsbcdn.s3-accelerate.amazonaws.com/',
            gloading:false,
            gallery:{},
            showPhotosSection:false,
            showReelsSection:false,
            main_image:null,
            gdate:null,
            gphotos:[],
            photos:[],
            photo:{
                title:'',
                nimage:null,
            },
            ereels:[],
            reels:[],
            reel:{
                title:'',
                nreel_url:null,
            },
            video:{
                id:'',
                title:'',
                video_url:'',
                youtube:'',
                nvideo:null,
            },
        }
    },
    created() {
        this.getGalleryById();
    },
    methods:{
        addPhoto() {
            this.photos.push({
                title: '',
                image: null,
            });
        },
        addReel() {
            this.reels.push({
                title: '',
                reel_url: null,
            });
        },
        removeReel(index) {
            this.reels.splice(index, 1);
            if (this.reels.length === 0) {
                this.showReelsSection = false;
            }
        },
        removePhoto(index) {
            this.photos.splice(index, 1);
            if (this.photos.length === 0) {
                this.showPhotosSection = false;
            }
        },
        removeGPhoto(id) {
            console.log('id',id)
            this.gphotos = this.gphotos.filter(photo => photo.id !== id);
        },
        removeEreel(id) {
            console.log('id',id)
            this.ereels = this.ereels.filter(reel => reel.id !== id);
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
        getGalleryById(){
            axios.get('/gallery/edit/'+this.gallery_id)
                .then((resp)=>{
                    const gdata = resp.data.gallery;
                    this.gallery = gdata;
                    this.gphotos = gdata?.photos || [];
                    this.ereels = gdata?.reels || [];
                    this.video = gdata?.video || {};
                })
        },
        editGallery(){
            this.gloading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const ndata = {
                id:this.gallery_id,
                title:this.gallery.title,
                description:this.gallery.description,
                main_image:this.main_image,
                gdate:this.gallery.gdate,
                gphotos:this.gphotos,
                photos:this.photos,
                ereels:this.ereels,
                reels:this.reels,
                video: {
                    id:this.video.id,
                    title:this.video.title,
                    video_url:this.video.nvideo,
                    youtube:this.video.youtube,
                }
            }
            console.log('ndata',ndata);
            axios.post('/gallery/edit',ndata,headers)
                .then((resp)=>{
                    console.log('resp',resp);
                    this.gloading = false;
                    this.main_image = null;
                    this.video.nvideo = null;
                    this.photos = [];
                    this.reels = [];
                    this.photo = {};
                    this.reel = {};
                    this.getGalleryById();
                    window.Toast.success(resp.data.message);
                })
                .catch((err)=>{
                    window.Toast.error(err.response.data.message)
                })
                .finally(()=>{
                    this.gloading = false;
                })

        }
    }
}
</script>

<style scoped>
</style>
