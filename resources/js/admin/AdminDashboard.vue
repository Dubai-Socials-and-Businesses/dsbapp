<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="3">
                <v-card color="teal">
                    <v-card-text>
                        <v-icon size="48">mdi-account</v-icon>
                        {{users_count}} Users
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card color="navy">
                    <v-card-text>
                        <v-icon size="48">mdi-seat-outline</v-icon>
                        {{events_count}} Events
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card color="mint">
                    <v-card-text >
                        <v-icon size="48">mdi-creation</v-icon>
                        {{reviews_count}} Reviews
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card color="charcoal">
                    <v-card-text>
                        <v-icon size="48">mdi-post-outline</v-icon>
                        {{blogs_count}} Blogs
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card color="mint">
                    <v-card-text>
                        <v-icon size="48">mdi-view-gallery</v-icon>
                        {{galleries_count}} Media Gallery
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="3">
                <v-card color="teal">
                    <v-card-text>
                        <v-icon size="48">mdi-handshake</v-icon>
                        {{partners_count}} Partners
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
            galleries_count:'',
            partners_count:'',
        }
    },
    created() {
        this.getAdminDashboard();
    },
    methods:{
        getAdminDashboard(){
            axios.get('/dashboard')
                .then((resp)=>{
                    this.users_count = resp.data.users_count;
                    this.events_count = resp.data.events_count;
                    this.reviews_count = resp.data.reviews_count;
                    this.blogs_count = resp.data.blogs_count;
                    this.galleries_count = resp.data.galleries_count;
                    this.partners_count = resp.data.partners_count;
                })
        }
    }
}
</script>

<style scoped>

</style>
