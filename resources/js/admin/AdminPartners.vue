<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6">
                <h4 class="text-h5">Partners List</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
               <v-btn color="navy" density="compact" variant="outlined">More Actions</v-btn>
            </v-col>
            <v-col cols="12" md="9">
                <v-text-field prepend-inner-icon="mdi-magnify" density="compact" variant="outlined"
                              hide-details placeholder="Search Partners"></v-text-field>
            </v-col>
            <v-col cols="12" md="3">
                <v-btn @click="addDialog = true" color="navy" density="default" block append-icon="mdi-plus">Add Partner</v-btn>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                    <v-data-table :items="partners" :headers="partnersHeaders" density="comfortable"
                                  :hide-default-footer="partners.length < 20">
                        <template v-slot:item.image="{item}">
                            <v-img :src="cdn+item.image" max-width="48" max-height="48" class="my-1"></v-img>
                        </template>
                        <template v-slot:item.status="{item}">
                            <v-icon v-if="item.status === 'active'" color="green" title="active">mdi-account-check</v-icon>
                            <v-icon v-else color="red" title="Inactive">mdi-account-question</v-icon>
                        </template>
                        <template v-slot:item.created_at="{item}">
                            {{dayjs(item.created_at).format('D MMM [at] h:mm a')}}
                        </template>
                        <template v-slot:item.actions="{item}">
                            <div class="d-flex ga-1">
                                <v-btn icon color="green" density="compact" variant="outlined">
                                    <v-icon size="x-small">mdi-email-edit</v-icon>
                                </v-btn>
                                <v-btn @click="editItem(item)" icon color="primary" density="compact" variant="outlined">
                                    <v-icon size="x-small">mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn @click="deleteItem(item)" icon color="red" density="compact" variant="outlined">
                                    <v-icon color="red" size="x-small">mdi-delete</v-icon>
                                </v-btn>
                            </div>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="addDialog" max-width="500" persistent>
            <v-card>
                <v-card-text>
                    <v-text-field v-model="defaultItem.name" variant="outlined" density="compact" label="Name" placeholder="Partner Name"
                                  persistent-placeholder></v-text-field>
                    <v-file-input v-model="defaultItem.image" accept="image/*" label="Partner Logo/Image" density="compact" variant="underlined"></v-file-input>
                    <v-textarea v-model="defaultItem.description" variant="outlined" density="compact" label="Description" placeholder="Partner Detail"
                                  persistent-placeholder></v-textarea>
                    <v-select v-model="defaultItem.package" variant="outlined" density="compact" label="Package" placeholder="Choose Package"
                                persistent-placeholder :items="['Bronze','Silver','Gold','Platinum']"></v-select>
                    <v-select v-model="defaultItem.status" variant="outlined" density="compact" label="Status" placeholder="Status"
                              persistent-placeholder :items="['active','inactive']"></v-select>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="addNewPartner" :loading="ploading" variant="elevated" density="compact" color="success">Add</v-btn>
                    <v-btn @click="addDialog = false" variant="text" density="compact" color="red">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="editDialog" max-width="500" persistent>
            <v-card>
                <v-card-text>
                    <v-text-field v-model="editedItem.name" variant="outlined" density="compact" label="Name" placeholder="Partner Name"
                                  persistent-placeholder></v-text-field>
                    <v-img v-if="editedItem.previewImage" :src="editedItem.previewImage" max-height="48" max-width="48"></v-img>
                    <v-file-input @change="updatePreviewImage(editedItem.image)"
                                  v-model="editedItem.image" accept="image/*" label="Partner Logo/Image"
                                  density="compact" variant="underlined"></v-file-input>
                    <v-textarea v-model="editedItem.description" variant="outlined" density="compact" label="Description" placeholder="Partner Detail"
                                persistent-placeholder></v-textarea>
                    <v-select v-model="editedItem.package" variant="outlined" density="compact" label="Package" placeholder="Choose Package"
                              persistent-placeholder :items="['Bronze','Silver','Gold','Platinum']"></v-select>
                    <v-select v-model="editedItem.status" variant="outlined" density="compact" label="Status" placeholder="Status"
                              persistent-placeholder :items="['active','inactive']"></v-select>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="editPartner" :loading="ploading" variant="elevated" density="compact" color="success">Update</v-btn>
                    <v-btn @click="editDialog = false" variant="text" density="compact" color="red">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="deleteDialog" max-width="300" persistent>
            <v-card>
                <v-card-text class="text-center">
                    Are you sure to delete the partner {{editedItem.name}}
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="deletePartner" :loading="ploading" variant="elevated" density="compact" color="success">Delete</v-btn>
                    <v-btn @click="deleteDialog = false" variant="text" density="compact" color="red">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script>
import axios from "axios";
import dayjs from "dayjs";

export default {
    name:'AdminPartners',
    data(){
        return{
            cdn:'https://dsbcdn.s3-accelerate.amazonaws.com/',
            ploading:false,
            addDialog:false,
            editDialog:false,
            deleteDialog:false,
            editedIndex:-1,
            defaultItem:{
                name:'',
                image:null,
                description:'',
                package:'Bronze',
                status:'active',
            },
            editedItem:{
                id:'',
                name:'',
                image:null,
                previewImage:'',
                description:'',
                package:'',
                status:'',
            },
            partners:[],
            partnersHeaders:[
                {title:"Name",key:'name'},
                {title:"Image",key:'image'},
                {title:"Description",key:'description'},
                {title:"Package",key:'package'},
                {title:"Status",key:'status'},
                {title:"Created",key:'created_at'},
                {title:"Actions",value:'actions'},
            ],
        }
    },
    watch:{
        'editedItem.image'(newVal){
            this.updatePreviewImage(newVal)
        }
    },
    created() {
        this.getAllPartners();
    },
    methods:{
        dayjs,
        getAllPartners(){
            axios.get('/partners')
                .then((resp)=>{
                    this.partners = resp.data.partners;
                })
        },
        addNewPartner(){
            this.ploading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const ndata = {
                name:this.defaultItem.name,
                image:this.defaultItem.image,
                description:this.defaultItem.description,
                package:this.defaultItem.package,
                status:this.defaultItem.status,
            }
            axios.post('/partner/update',ndata,headers)
                .then((resp)=>{
                    this.addDialog = false;
                    this.getAllPartners();
                    this.defaultItem = {},
                    this.ploading = false;
                })
        },
        editItem(item){
            this.editedIndex = this.partners.indexOf(item);
            this.editedItem = Object.assign({},item);
            this.editDialog = true;
        },
        editPartner(){
            this.ploading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const udata = {
                id:this.editedItem.id,
                name:this.editedItem.name,
                image:this.editedItem.image,
                description:this.editedItem.description,
                package:this.editedItem.package,
                status:this.editedItem.status,
            }
            axios.post('/partner/update',udata,headers)
                .then((resp)=>{
                    this.editDialog = false;
                    this.getAllPartners();
                    this.ploading = false;
                })
        },
        deleteItem(item){
            this.editedIndex = this.partners.indexOf(item);
            this.editedItem = Object.assign({},item);
            this.deleteDialog = true;
        },
        deletePartner(){
            this.ploading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const ddata = {
                mtype:'delete',
                id:this.editedItem.id,
            }
            axios.post('/partner/update',ddata,headers)
                .then((resp)=>{
                    this.deleteDialog = false;
                    this.getAllPartners();
                    this.ploading = false;
                })
        },
        updatePreviewImage(file){
            if(file instanceof File){
                this.editedItem.image = file;
                this.editedItem.previewImage = URL.createObjectURL(file);
            } else {
                this.editedItem.image = null;
            }
        }
    }
}
</script>

<style scoped>

</style>
