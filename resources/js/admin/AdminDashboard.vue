<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="3">
                <v-card color="teal">
                    <v-card-text>
                        <v-icon size="x-large">mdi-account</v-icon>
                        {{users_count}}
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card color="navy">
                    <v-card-text>
                        <v-icon size="x-large">mdi-seat-outline</v-icon>
                        {{events_count}}
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card color="mint">
                    <v-card-text>
                        <v-icon size="x-large">mdi-creation</v-icon>
                        {{reviews_count}}
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card color="charcoal">
                    <v-card-text>
                        <v-icon size="x-large">mdi-post-outline</v-icon>
                        {{blogs_count}}
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";

export default {
    name:'AdminDashboard',
    data(){
        return{
            users_count:'',
            events_count:'',
            reviews_count:'',
            blogs_count:'',
        }
    },
    created() {
        this.getAdminDashboard();
    },
    methods:{
        getAdminDashboard(){
            axios.get('/admin/dashboard')
                .then((resp)=>{
                    this.users_count = resp.data.users_count;
                    this.events_count = resp.data.events_count;
                    this.reviews_count = resp.data.reviews_count;
                    this.blogs_count = resp.data.blogs_count;
                })
        }
    }
}
</script>

<style scoped>

</style>
