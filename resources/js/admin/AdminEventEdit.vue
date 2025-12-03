<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6" class="d-flex ga-3 align-center">
                <v-icon @click="this.$router.push({name:'AdminEvents'})">mdi-arrow-left</v-icon>
                <h4 class="text-h5">Edit Event</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn @click="editEvent" :loading="eloading" :disabled="eloading" color="green" density="compact" variant="elevated">Update</v-btn>
                <v-btn v-if="event.status === 'active'" append-icon="mdi-eye" color="info" density="compact"
                       :href="domain+'event/'+event?.slug" target="_blank" class="ms-2">Preview</v-btn>
            </v-col>
            <v-col cols="12" md="8">
                <v-card class="border-sm">
                    <v-card-title>Details</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="event.title" density="compact" variant="underlined"
                                      placeholder="Event Title"></v-text-field>
                        <v-textarea v-model="event.excerpt" density="compact" variant="underlined"
                                      placeholder="Short Description" rows="3" counter="200" persistent-counter></v-textarea>
                        <v-textarea v-model="event.description" density="compact" variant="underlined" auto-grow
                                      placeholder="Event Description" counter persistent-counter></v-textarea>
                        <v-combobox v-model="event.tags" density="compact" variant="outlined" chips multiple closable-chips
                                    placeholder="Dubai, UAE" persistent-placeholder class="mt-3"
                                    prepend-inner-icon="mdi-tag" label="Tags"></v-combobox>
                    </v-card-text>
                </v-card>
                <v-card class="border-sm mt-5">
                    <v-card-title>Event Timing</v-card-title>
                    <v-card-text>
                        <v-row align="end">
                            <v-col cols="12" md="6">
                                <div v-if="event.start_date">{{ dayjs(`${event.start_date}`).format('D MMM, YYYY') }}</div>
                                <v-date-input v-model="event.start_date" variant="underlined" label="Start Date"
                                              prepend-icon="" prepend-inner-icon="mdi-calendar"></v-date-input>
                                <v-time-picker v-model="event.start_time" title="Start Time" density="compact"
                                            color="navy" variant="input"></v-time-picker>
                            </v-col>
                            <v-col cols="12" md="6">
                                <div v-if="event.end_date">{{ dayjs(`${event.end_date}`).format('D MMM, YYYY') }}</div>
                                <v-date-input v-model="event.end_date" variant="underlined" label="End Date"
                                              prepend-icon="" prepend-inner-icon="mdi-calendar"></v-date-input>
                                <v-time-picker v-model="event.end_time" title="End Time" density="compact"
                                            color="navy" variant="input"></v-time-picker>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
                <v-card class="mt-5 border-sm">
                    <v-card-title class="d-flex align-center">
                        Attendees list
                        <v-spacer/>
                        <div>Count {{event.attendees_count}}</div>
                    </v-card-title>
                    <v-card-text>
                        <v-data-table v-if="attendees" :items="attendees" :headers="attendeesHeader" :hide-default-footer="attendees?.length < 10">

                        </v-data-table>
                    </v-card-text>

                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card class="border-sm">
                    <v-card-title>Status</v-card-title>
                    <v-card-text>
                            <v-checkbox-btn v-model="event.status" value="active" label="Active" color="green"/>
                            <v-checkbox-btn v-model="event.status" value="inactive" label="Inactive" color="red"/>
                    </v-card-text>
                    <v-card-text>
                        <v-img v-if="event.image" :src="cdn+event.image" contain></v-img>
                        <v-file-upload v-model="image" density="compact" title="change Main Image" class="mt-1" clearable></v-file-upload>
                    </v-card-text>
                    <v-card-text>
                        <v-text-field v-model="event.location" density="comfortable" label="Location" variant="underlined"
                                      placeholder="The mall, Dubai" persistent-placeholder prepend-inner-icon="mdi-map-marker"
                                      clearable></v-text-field>
                        <v-number-input v-model="event.price" :min="0" variant="underlined" control-variant="default" label="Price"
                                        density="compact" prefix="AED"></v-number-input>
                        <v-autocomplete v-model="event.organizer" density="comfortable" label="Organizer" variant="underlined"
                                        :items="users" item-title="name" item-value="id" autoSelectFirst
                                      placeholder="Rakhee Mansukhani" persistent-placeholder prepend-inner-icon="mdi-account"
                                      clearable></v-autocomplete>
                    </v-card-text>
                    <v-card-text>
                        <v-video v-if="event.video" :src="cdn+event.video" controlsVariant="tube"
                                 image="https://cdn.jsek.work/cdn/vt-sunflowers.jpg"></v-video>
                        <v-file-upload v-model="video" density="compact" icon="mdi-video" accept="video/*"
                                       title="Change Main Video" class="mt-3" clearable></v-file-upload>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";
import { VFileUpload } from 'vuetify/labs/VFileUpload'
import { VDateInput, VVideo } from "vuetify/labs/components";
import dayjs from "dayjs";

export default {
    name:'AdminEventEdit',
    components:{VFileUpload,VDateInput,VVideo},
    props:{
        event_id:[Number,String]
    },
    data(){
        return{
            domain:'https://www.dubaisocialsandbusinesses.com/',
            cdn:'https://dsbcdn.s3-accelerate.amazonaws.com/',
            eloading:false,
            event:{},
            attendees:[],
            attendeesHeader:[
                {title:'Name',value:'name'},
                {title:'Email',value:'email'},
                {title:'Phone',value:'phone'},
                {title:'Actions',value:'action'},
            ],
            users:[],
            image:null,
            video:null,
            tags:[],
            events:[],
        }
    },
    computed: {
        dayjs() {
            return dayjs
        },
        minDate(){
            return new Date();
        }
    },
    created() {
        this.getEventById();
    },
    methods:{
        // formatDate(date) {
        //     if (!date) return '';
        //     return new Date(date).toLocaleString('en-US', {
        //         timeZone: 'Asia/Dubai',
        //         day: '2-digit',
        //         month: '2-digit',
        //         year: 'numeric',
        //         // hour: '2-digit',
        //         // minute: '2-digit',
        //         // hour12: false
        //     });
        // },
        getEventById(){
            axios.get('/event/edit/'+this.event_id)
                .then((resp)=>{
                    this.event = resp.data.event;
                    this.users = resp.data.users;
                    this.event.price = Number(resp.data?.event?.price);
                    this.attendees = resp.data?.attendees || [];
                })
        },
        getAllEvents(){
            axios.get('/events')
                .then((resp)=>{
                    this.events = resp.data.events;
                    this.users = resp.data.users;
                })
        },
        editEvent(){
            this.eloading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const ndata = {
                id:this.event_id,
                title:this.event.title,
                description:this.event.description,
                excerpt:this.event.excerpt,
                image:this.image,
                video:this.video,
                start_date:dayjs(this.event.start_date).format('YYYY-MM-DD'),
                start_time:this.event.start_time,
                end_date:dayjs(this.event.end_date).format('YYYY-MM-DD'),
                end_time:this.event.end_time,
                organizer:this.event.organizer,
                location:this.event.location,
                price:this.event.price,
                tags:this.event.tags,
                status:this.event.status,
            }
            axios.post('/event/edit/',ndata,headers)
                .then((resp)=>{
                    this.getEventById();
                    this.image = null;
                    this.video = null;
                    window.Toast.success('Event Updated')
                    console.log('resp',resp);
                })
                .catch((err)=>{
                    window.Toast.error(err.message);
                })
                .finally(()=>{
                    this.eloading = false;
                })
        }
    }
}
</script>

<style scoped>

</style>
