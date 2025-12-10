<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6">
                <h4 class="text-h5">Users List</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn variant="outlined" class="text-none" color="grey-darken-4" density="compact"
                       @click="exportToCSV">
                    Export Users
                </v-btn>
            </v-col>
            <v-col cols="12" md="9">
                <v-text-field v-model="usearch" prepend-inner-icon="mdi-magnify" density="compact" variant="outlined"
                              hide-details placeholder="Search Users"></v-text-field>
            </v-col>
            <v-col cols="12" md="3">
                <v-btn color="navy" density="default" block append-icon="mdi-plus">Add User</v-btn>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                    <v-data-table :items="users" :headers="usersHeaders" density="comfortable" :search="usearch"
                                  :hide-default-footer="users.length < 10">
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
            usearch:'',
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
            axios.get('/users')
                .then((resp)=>{
                    this.users = resp.data.users;
                })
        },
        exportToCSV() {
            const headers = ['Name', 'Email', 'Role','Phone'];
            const rows = this.users.map((user) => {
                return [
                    `${user.name}`,
                    user.email || '',
                    `${user?.role || 'user'}`,
                    `${user?.phone || ' '}`
                ];
            });
            const csvContent = [headers, ...rows].map(e => e.join(",")).join("\n");
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.setAttribute("download", "users.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}
</script>

<style scoped>

</style>
