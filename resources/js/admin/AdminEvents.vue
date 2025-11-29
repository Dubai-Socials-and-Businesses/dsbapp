<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="3">
                <v-card color="teal">
                    <v-card-text></v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card color="navy">
                    <v-card-text></v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card color="mint">
                    <v-card-text></v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-btn :to="{name:'AdminEventAdd'}" color="navy" density="default" block append-icon="mdi-plus">Add Event</v-btn>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                   <v-data-table :items="events" :headers="eventsHeaders" :hide-default-footer="events.length < 20"></v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";

export default {
    name:'AdminEvents',
    data(){
        return{
            events:[],
            eventsHeaders:[
                {title:'Title',key:'title'},
                {title:'Image',key:'image'},
                {title:'Excerpt',key:'excerpt'},
                {title:'Start at',key:'start_at'},
                {title:'Actions',key:'actions'},
            ]
        }
    },
    created() {
        this.getAllEvents();
    },
    methods:{
        getAllEvents(){
            axios.get('/admin/events')
                .then((resp)=>{
                    this.events = resp.data.events;
                })
        }
    }
}
</script>

<style scoped>

</style>
