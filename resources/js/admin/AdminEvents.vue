<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6">
                <h4 class="text-h5">Events List</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn color="navy" density="compact" variant="outlined">More Actions</v-btn>
            </v-col>
            <v-col cols="12" md="9">
                <v-text-field prepend-inner-icon="mdi-magnify" density="compact" variant="outlined"
                              hide-details placeholder="Search Event"></v-text-field>
            </v-col>
            <v-col cols="12" md="3">
                <v-btn :to="{name:'AdminEventAdd'}" color="navy" density="default" block append-icon="mdi-plus">Add Event</v-btn>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                   <v-data-table :items="events" :headers="eventsHeaders" :hide-default-footer="events.length < 20">
                       <template v-slot:item.image="{item}">
                           <v-img :src="cdn+item.image" max-width="122" max-height="52" contain class="my-1"></v-img>
                       </template>
                       <template v-slot:item.excerpt="{item}">
                           <div>{{item.excerpt}}</div>
                           <div>{{item.location}}</div>
                       </template>
                       <template v-slot:item.start_date="{item}">
                           <div>
                               <div>{{ dayjs(`${item.start_date} ${item.start_time}`).format('D MMM, YYYY h:mm a') }}</div>
                               <div>{{ dayjs(`${item.end_date} ${item.end_time}`).format('D MMM, YYYY h:mm a') }}</div>
                           </div>
                       </template>
                       <template v-slot:item.actions="{item}">
                           <div class="d-flex ga-1">
                               <v-btn :to="{name:'AdminEventEdit',params:{event_id:item.id}}" color="primary" density="compact" variant="outlined">
                                   <v-icon size="x-small">mdi-pencil</v-icon> edit
                               </v-btn>
                           </div>
                       </template>
                   </v-data-table>
                </v-card>
            </v-col>
            <v-col cols="12" md="12">
                <v-card class="border-sm">
                    <v-card-title>Events Calendar</v-card-title>
                    <v-card-text>
                        <v-calendar ref="calendar"
                                    v-model="focus"
                                    color="primary"
                                    :events="formattedEvents" type="month" locale="en">
                        </v-calendar>
                    </v-card-text>
                </v-card>

            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";
import dayjs from "dayjs";

export default {
    name:'AdminEvents',
    data(){
        return{
            cdn:'https://dsbcdn.s3-accelerate.amazonaws.com/',
            focus: new Date().toISOString().slice(0, 10),
            formattedEvents: [],
            events:[],
            eventsHeaders:[
                {title:'Title',key:'title'},
                {title:'Image',key:'image'},
                {title:'Short Description',key:'excerpt'},
                {title:'Start at',key:'start_date'},
                {title:'Actions',key:'actions'},
            ]
        }
    },
    async mounted() {
        await this.getAllEvents()
        this.$nextTick(() => {
            this.$forceUpdate();
        });
    },
    methods:{
        dayjs,
        formatEvents(){
            this.formattedEvents = this.events.map(event => {
                return {
                    name: event.title.substring(0, 30) + '...' + event.start_time,
                    start: event.start_date,
                    end: event.end_date,
                    color: 'primary',
                }
            })
        },
        async getAllEvents() {
            try {
                const resp = await axios.get('/events');
                this.events = resp.data.events || [];

                await this.$nextTick(() => {
                    this.formatEvents();
                });
            } catch (err) {
                console.error('❌ API Error:', err);
            }
        }
    }
}
</script>

<style scoped>

</style>
