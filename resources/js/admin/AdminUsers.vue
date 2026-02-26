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
                                  items-per-page="25">
                        <template v-slot:item.email_verified_at="{item}">
                            <v-icon v-if="item.email_verified_at" color="green" title="verified">mdi-account-check</v-icon>
                            <v-icon v-else color="red" title="Unverified">mdi-account-question</v-icon>
                        </template>
                        <template v-slot:item.created_at="{item}">
                            {{dayjs(item.created_at).format('D MMM [at] h:mm a')}}
                        </template>
                        <template v-slot:item.actions="{item}">
                            <div class="d-flex ga-1">
                                <v-btn @click="openEmailDialog(item)" icon color="green" density="compact" variant="outlined">
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
        <v-dialog v-model="emailDialog" max-width="500">
            <v-card>
                <v-card-text>
                    <v-text-field label="Subject" v-model="subject" variant="underlined" density="compact"/>
                    <v-text-field label="Title" v-model="title" variant="underlined" density="compact"/>
                    <div>Hello {{editedItem.name}}</div>
                    <v-textarea label="Message" v-model="text" variant="underlined"/>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="sendmEmailtoUser" :loading="sendLoading" color="success" variant="elevated" size="small" append-icon="mdi-send">
                        {{sendLoading ? "Sending.." : "Send"}}
                        Send
                    </v-btn>
                    <v-btn @click="emailDialog = false" color="red" variant="outlined" size="small" append-icon="mdi-close">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script>
import axios from "axios";
import dayjs from "dayjs";

export default {
    name:'AdminUsers',
    data(){
        return{
            sendLoading:false,
            emailDialog:false,
            usearch:'',
            users:[],
            usersHeaders:[
                {title:"Name",key:'name'},
                {title:"Email",key:'email'},
                {title:"Phone",key:'phone'},
                {title:"Verified",key:'email_verified_at'},
                {title:"Role",key:'role'},
                {title:"Created",key:'created_at'},
                {title:"Actions",value:'actions'},
            ],
            editedIndex:-1,
            editedItem:{
                email:"",
                name:"",
            },
            emessage:"",
            subject:"Our Offer",
            title:"Welcome to Event",
            text:"Welcome to our Event",
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
        openEmailDialog(item){
            this.editedIndex = this.users.indexOf(item);
            this.editedItem = Object.assign({},item);
            this.emailDialog = true;
        },
        sendmEmailtoUser(item){
            this.sendLoading = true;
            const udata = {
                subject:this.subject,
                email:this.editedItem.email,
                name:this.editedItem.name,
                title:this.title,
                text:this.text,
            }
            console.log('udata',udata);
            axios.post('/send/memail',udata)
                .then((resp)=>{
                    this.emessage = resp.data.message;
                    this.emailDialog = false;
                    window.Toast.success(resp.data.message);
                })
                .catch((err)=>{
                    window.Toast.error(err)
                })
                .finally(()=>{
                    this.sendLoading = false;
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
