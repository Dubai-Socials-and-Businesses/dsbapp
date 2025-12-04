<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6">
                <h4 class="text-h5">Packages List</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn color="navy" density="compact" variant="outlined">More Actions</v-btn>
            </v-col>
            <v-col cols="12" md="9">
                <v-text-field prepend-inner-icon="mdi-magnify" density="compact" variant="outlined"
                              hide-details placeholder="Search Partners"></v-text-field>
            </v-col>
            <v-col cols="12" md="3">
                <v-btn @click="addDialog = true" color="navy" density="default" block append-icon="mdi-plus">Add Package</v-btn>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                   <v-data-table :items="packages" :headers="packagesHeaders" :hide-default-footer="packages.length < 10">
                       <template v-slot:item.price="{item}">
                           <v-btn active-color="orange" density="compact" color="info">AED {{item.price}}</v-btn>
                       </template>
                       <template v-slot:item.features="{item}">
                           <v-list v-if="item.features" density="compact" nav>
                               <v-list-item v-for="(feat,idx) in item.features" :key="idx">
                                   {{feat}}
                               </v-list-item>
                           </v-list>
                       </template>
                       <template v-slot:item.actions="{item}">
                           <div class="d-flex ga-1">
                               <v-btn @click="editItem(item)" icon color="primary" density="compact" variant="outlined">
                                   <v-icon size="x-small">mdi-pencil</v-icon>
                               </v-btn>
<!--                               <v-btn @click="deleteItem(item)" icon color="red" density="compact" variant="outlined">-->
<!--                                   <v-icon color="red" size="x-small">mdi-delete</v-icon>-->
<!--                               </v-btn>-->
                           </div>
                       </template>
                   </v-data-table>
                </v-card>
            </v-col>
        </v-row>
<!--        <v-row v-if="packages.length">-->
<!--            <v-col cols="12" md="4" v-for="(pack, index) in packages" :key="index">-->
<!--                <v-card>-->
<!--                    <v-card>-->
<!--                        <v-card-title>{{ pack.title }}</v-card-title>-->
<!--                        <v-card-actions class="justify-center font-weight-bold">-->
<!--                            <span>AED</span>-->
<!--                            {{ pack.price }}-->
<!--                        </v-card-actions>-->
<!--                        <v-card-text >-->
<!--                           <v-list v-if="pack.features">-->
<!--                               <v-list-item v-for="(feature,fdx) in pack.features" :key="fdx">-->
<!--                                   {{feature}}-->
<!--                               </v-list-item>-->
<!--                               <v-btn @click="addNewFeature(index)">add Feature</v-btn>-->
<!--                           </v-list>-->
<!--                        </v-card-text>-->
<!--                    </v-card>-->
<!--                </v-card>-->
<!--            </v-col>-->
<!--        </v-row>-->
        <v-dialog v-model="addDialog" max-width="500" persistent>
            <v-card>
                <v-card-text>
                    <v-text-field v-model="defaultItem.title" variant="outlined" density="compact" label="Package Title"
                                  placeholder="Silver"
                                  persistent-placeholder></v-text-field>
                    <v-number-input prefix="AED" v-model="defaultItem.price" variant="outlined" density="compact" label="Price"
                                  placeholder="AED 499" :precision="2"
                                  persistent-placeholder></v-number-input>
                    <div v-if="defaultItem.features.length" v-for="(feature,fdx) in defaultItem.features" :key="fdx">
                        <v-text-field v-model="defaultItem.features[fdx]" :label="`Feature ${fdx+1}`" variant="outlined"
                                      density="compact" persistent-placeholder placeholder="WhatsApp posting"></v-text-field>
                    </div>
                    <v-select v-model="defaultItem.status" variant="outlined" density="compact" label="Status" placeholder="Status"
                              persistent-placeholder :items="['active','inactive']" hide-details></v-select>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="addNewPackage" :loading="paloading" variant="elevated" density="compact" color="success">Add</v-btn>
                    <v-btn @click="addDialog = false" variant="text" density="compact" color="red">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="editDialog" max-width="500" persistent>
            <v-card>
                <v-card-text>
                    <v-text-field v-model="editedItem.title" variant="outlined" density="compact" label="Title"
                                  placeholder="Review Title"
                                  persistent-placeholder></v-text-field>
                    <v-number-input prefix="AED" v-model="editedItem.price" variant="outlined" density="compact" label="Price"
                                    placeholder="AED 499" :precision="2"
                                    persistent-placeholder></v-number-input>
                    <div v-if="editedItem.features.length" v-for="(feature,fdx) in defaultItem.features" :key="fdx">
                        <v-text-field v-model="editedItem.features[fdx]" :label="`Feature ${fdx+1}`" variant="outlined"
                                      density="compact" persistent-placeholder placeholder="WhatsApp posting"></v-text-field>
                    </div>
                    <v-select v-model="editedItem.status" variant="outlined" density="compact" label="Status" placeholder="Status"
                              persistent-placeholder :items="['active','inactive']" hide-details></v-select>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="editPackage" :loading="paloading" variant="elevated" density="compact" color="success">Update</v-btn>
                    <v-btn @click="editDialog = false" variant="text" density="compact" color="red">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="deleteDialog" max-width="300" persistent>
            <v-card>
                <v-card-text class="text-center">
                    Are you sure to delete the Package {{editedItem.title}}
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="deletePackage" :loading="paloading" variant="elevated" density="compact" color="success">Delete</v-btn>
                    <v-btn @click="deleteDialog = false" variant="text" density="compact" color="red">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script>
import axios from "axios";
import { VDateInput } from "vuetify/labs/components";
import dayjs from "dayjs";

export default {
    name:'AdminPackages',
    components:{VDateInput},
    data(){
        return{
            cdn:'https://dsbcdn.s3-accelerate.amazonaws.com/',
            paloading:false,
            addDialog:false,
            editDialog:false,
            deleteDialog:false,
            editedIndex:-1,
            defaultItem:{
                title:'Silver',
                price:499,
                features:['WhatsApp posting','24 IG story mentions / year'],
                status:'active'
            },
            editedItem:{
                id:'',
                title:'',
                price:0,
                features:[],
                status:''
            },
            packages:[],
            packagesHeaders:[
                {title:'Title',key:'title'},
                {title:'Price',key:'price'},
                {title:'Features',key:'features'},
                {title:'Status',key:'status'},
                {title:'Actions',key:'actions'},
            ]
        }
    },
    created() {
        this.getAllPackages();
    },
    methods:{
        getAllPackages(){
            axios.get('/packages')
                .then((resp)=>{
                    this.packages = resp.data.packages || [];
                })
        },
        addNewPackage(){
            this.paloading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const ndata = {
                title:this.defaultItem.title,
                price:this.defaultItem.price,
                status:this.defaultItem.status,
                features:this.defaultItem.features
            }
            axios.post('/package/update',ndata,headers)
                .then((resp)=>{
                    this.addDialog = false;
                    this.getAllPackages();
                    window.Toast.success(resp.data.message);
                })
                .catch((err)=>{
                    window.Toast.error(err.message);
                })
                .finally(()=>{
                    this.paloading = false;
                })

        },
        editItem(item){
            this.editedIndex = this.packages.indexOf(item);
            this.editedItem = Object.assign({},item);
            this.editDialog = true;
        },
        editPackage(){
            this.paloading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const udata = {
                id:this.editedItem.id,
                title:this.editedItem.title,
                price:this.editedItem.price,
                status:this.editedItem.status,
                features:this.editedItem.features,
            }
            axios.post('/package/update',udata,headers)
                .then((resp)=>{
                    this.editDialog = false;
                    this.getAllPackages();
                    this.paloading = false;
                })
                .finally(()=>{
                    this.paloading = false;
                })
        },
        deleteItem(item){
            this.editedIndex = this.packages.indexOf(item);
            this.editedItem = Object.assign({},item);
            this.deleteDialog = true;
        },
        deletePackage(){
            this.paloading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const ddata = {
                mtype:'delete',
                id:this.editedItem.id,
            }
            axios.post('/package/update',ddata,headers)
                .then((resp)=>{
                    this.deleteDialog = false;
                    this.getAllPackages();
                    this.paloading = false;
                })
                .finally(()=>{
                    this.paloading = false;
                })
        },
    }
}
</script>

<style scoped>

</style>
