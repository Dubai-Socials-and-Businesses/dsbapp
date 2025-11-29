<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6">
                <h4 class="text-h5">Add Event</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn @click="addEvent" color="green" density="compact" variant="elevated">Save</v-btn>
            </v-col>
            <v-col cols="12" md="8">
                <v-card class="border-sm">
                    <v-card-title>Details</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="title" density="compact" variant="underlined"
                                      placeholder="Event Title"></v-text-field>
                        <v-textarea v-model="excerpt" density="compact" variant="underlined"
                                      placeholder="Short Description" rows="3" counter="200" persistent-counter></v-textarea>
                        <v-textarea v-model="description" density="compact" variant="underlined" auto-grow
                                      placeholder="Event Description" counter persistent-counter></v-textarea>
                        <v-combobox v-model="tags" density="compact" variant="outlined" chips multiple closable-chips
                                    placeholder="Dubai, UAE" persistent-placeholder class="mt-3"
                                    prepend-inner-icon="mdi-tag" label="Tags"></v-combobox>
                    </v-card-text>
                </v-card>
                <v-card class="border-sm mt-5">
                    <v-card-title>Event Timing</v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-date-input v-model="start_date" :displayFormat="formatDate" variant="underlined" label="Start Date"
                                              prepend-icon="" prepend-inner-icon="mdi-calendar"></v-date-input>
                                <v-time-picker v-model="start_time" title="Start Time" density="compact"
                                            color="navy" variant="input"></v-time-picker>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-date-input v-model="end_date" :displayFormat="formatDate" variant="underlined" label="End Date"
                                              prepend-icon="" prepend-inner-icon="mdi-calendar"></v-date-input>
                                <v-time-picker v-model="end_time" title="End Time" density="compact"
                                            color="navy" variant="input"></v-time-picker>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card class="border-sm">
                    <v-card-text>
                        <v-file-upload v-model="image" density="compact" title="Main Image" clearable></v-file-upload>
                    </v-card-text>
                    <v-card-text>
                        <v-text-field v-model="location" density="comfortable" label="Location" variant="underlined"
                                      placeholder="The mall, Dubai" persistent-placeholder prepend-inner-icon="mdi-map-marker"
                                      clearable></v-text-field>
                        <v-number-input v-model="price" :min="0" variant="underlined" control-variant="default" label="Price"
                                        density="compact" prefix="AED"></v-number-input>
                        <v-autocomplete v-model="organizer" density="comfortable" label="Organizer" variant="underlined"
                                        :items="users" item-title="name" item-value="id" autoSelectFirst
                                      placeholder="Rakhee Mansukhani" persistent-placeholder prepend-inner-icon="mdi-account"
                                      clearable></v-autocomplete>
                    </v-card-text>
                    <v-card-text>
                        <v-file-upload v-model="video" density="compact" icon="mdi-video" accept="video/*" title="Main Video" clearable></v-file-upload>
                    </v-card-text>
                </v-card>
                <v-card class="mt-5">

                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";
import { VFileUpload } from 'vuetify/labs/VFileUpload'
import { VDateInput } from "vuetify/labs/components";

export default {
    name:'AdminEventAdd',
    components:{VFileUpload,VDateInput},
    data(){
        return{
            cdn:'https://dsbcdn.s3-accelerate.amazonaws.com/',
            title:'',
            excerpt:'',
            description:'',
            start_at:null,
            start_date:null,
            start_time:'09:00',
            end_at:null,
            end_date:null,
            end_time:'11:00',
            image:null,
            video:null,
            status:'active',
            organizer:2,
            location:'',
            price:0,
            tags:[],
            events:[],
            users:[],
            EventHeaders:[
                {title:'Title',key:'title'},
                {title:'Image',key:'main_image'},
                {title:'Description',key:'description'},
                {title:'start_at',key:'start_at'},
                {title:'Actions',key:'actions'},
            ]
        }
    },
    computed: {
        minDate(){
            return new Date();
        }
    },
    created() {
        this.getAllEvents();
    },
    methods:{
        formatDate(date) {
            if (!date) return '';
            return new Date(date).toLocaleString('en-GB', {
                timeZone: 'Asia/Dubai',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                // hour: '2-digit',
                // minute: '2-digit',
                // hour12: false
            });
        },
        getAllEvents(){
            axios.get('/admin/events')
                .then((resp)=>{
                    this.events = resp.data.events;
                    this.users = resp.data.users;
                    this.start_date = this.minDate;
                })
        },
        addEvent(){
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const ndata = {
                title:this.title,
                description:this.description,
                excerpt:this.excerpt,
                image:this.image,
                video:this.video,
                start_date:this.start_date,
                start_time:this.start_time,
                end_date:this.end_date,
                end_time:this.end_time,
                organizer:this.organizer,
                location:this.location,
                price:this.price,
                tags:this.tags,
                status:this.status,
            }
            axios.post('/admin/event/add',ndata,headers)
                .then((resp)=>{
                    console.log('resp',resp);
                })
            console.log('ndata',ndata);
        }
    }
}
</script>

<style scoped>

</style>
