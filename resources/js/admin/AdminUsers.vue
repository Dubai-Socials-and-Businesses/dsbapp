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
                <v-card color="charcoal">
                    <v-card-text></v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                    <v-data-table :items="users" :headers="usersHeaders" density="comfortable" :hide-default-footer="users.length < 20">
                        <template v-slot:item.email_verified_at="{item}">
                            <v-icon v-if="item.email_verified_at" color="green" title="verified">mdi-account-check</v-icon>
                            <v-icon v-else color="red" title="Unverified">mdi-account-question</v-icon>
                        </template>
                        <template v-slot:item.created_at="{item}">
                            {{dayjs(item.created_at).format('D MMM [at] h:mm a')}}
                        </template>
                        <template v-slot:item.actions="{item}">
                            <div class="d-flex ga-1">
                                <v-btn icon color="green" density="compact" variant="outlined">
                                    <v-icon size="x-small">mdi-email-edit</v-icon>
                                </v-btn>
                                <v-btn icon color="primary" density="compact" variant="outlined">
                                    <v-icon size="x-small">mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon color="red" density="compact" variant="outlined">
                                    <v-icon color="red" size="x-small">mdi-delete</v-icon>
                                </v-btn>
                            </div>
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
    name:'AdminUsers',
    data(){
        return{
            users:[],
            usersHeaders:[
                {title:"Name",key:'name'},
                {title:"Email",key:'email'},
                {title:"Verified",key:'email_verified_at'},
                {title:"Role",key:'role'},
                {title:"Created",key:'created_at'},
                {title:"Actions",value:'actions'},
            ],
        }
    },
    created() {
        this.getAllUsers();
    },
    methods:{
        dayjs,
        getAllUsers(){
            axios.get('/admin/users')
                .then((resp)=>{
                    this.users = resp.data.users;
                })
        }
    }
}
</script>

<style scoped>

</style>
