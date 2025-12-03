<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6">
                <h4 class="text-h5">Reviews List</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn color="navy" density="compact" variant="outlined">More Actions</v-btn>
            </v-col>
            <v-col cols="12" md="9">
                <v-text-field prepend-inner-icon="mdi-magnify" density="compact" variant="outlined"
                              hide-details placeholder="Search Partners"></v-text-field>
            </v-col>
            <v-col cols="12" md="3">
                <v-btn @click="addDialog = true" color="navy" density="default" block append-icon="mdi-plus">Add Review</v-btn>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                   <v-data-table :items="reviews" :headers="reviewsHeaders" :hide-default-footer="reviews.length < 10">
                       <template v-slot:item.rating="{item}">
                           <v-rating :model-value="item.rating" active-color="orange" density="compact" readonly></v-rating>
                       </template>
                       <template v-slot:item.reviewer_name="{item}">
                           <div>
                               <div>Name: {{item.reviewer_name}}</div>
                               <div>Date: {{item.review_date}}</div>
                           </div>
                       </template>
                       <template v-slot:item.actions="{item}">
                           <div class="d-flex ga-1">
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
                    <v-text-field v-model="defaultItem.reviewer_name" variant="outlined" density="compact" label="Reviewer Name"
                                  placeholder="Alex John"
                                  persistent-placeholder></v-text-field>
                    <v-text-field v-model="defaultItem.title" variant="outlined" density="compact" label="Title"
                                  placeholder="Review Title"
                                  persistent-placeholder></v-text-field>
                    <v-textarea v-model="defaultItem.review_text" variant="outlined" density="compact"
                                label="Review Text" placeholder="Review Text"
                                persistent-placeholder></v-textarea>
                    <v-rating v-model="defaultItem.rating" color="orange" hover></v-rating>
                    <v-date-input v-model="defaultItem.review_date" variant="outlined" density="compact"></v-date-input>
                    <v-select v-model="defaultItem.status" variant="outlined" density="compact" label="Status" placeholder="Status"
                              persistent-placeholder :items="['pending','verified','rejected']" hide-details></v-select>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="addNewReview" :loading="rloading" variant="elevated" density="compact" color="success">Add</v-btn>
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
                    <v-textarea v-model="editedItem.review_text" variant="outlined" density="compact"
                                label="Review Text" placeholder="Review Text"
                                persistent-placeholder></v-textarea>
                    <v-rating v-model="editedItem.rating" color="orange" hover></v-rating>
                    <v-date-input v-model="editedItem.review_date" variant="outlined" density="compact"></v-date-input>
                    <v-select v-model="editedItem.status" variant="outlined" density="compact" label="Status" placeholder="Status"
                              persistent-placeholder :items="['pending','verified','rejected']"></v-select>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="editReview" :loading="rloading" variant="elevated" density="compact" color="success">Update</v-btn>
                    <v-btn @click="editDialog = false" variant="text" density="compact" color="red">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="deleteDialog" max-width="300" persistent>
            <v-card>
                <v-card-text class="text-center">
                    Are you sure to delete the Review {{editedItem.title}}
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="deleteReview" :loading="rloading" variant="elevated" density="compact" color="success">Delete</v-btn>
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
    name:'AdminReviews',
    components:{VDateInput},
    data(){
        return{
            cdn:'https://dsbcdn.s3-accelerate.amazonaws.com/',
            rloading:false,
            addDialog:false,
            editDialog:false,
            deleteDialog:false,
            editedIndex:-1,
            defaultItem:{
                title:'',
                review_text:'',
                rating:5.0,
                user_id:'',
                status:'pending',
                reviewer_name:'Alex John',
                review_date:null,
            },
            editedItem:{
                id:'',
                title:'',
                review_text:'',
                rating:'',
                user_id:'',
                status:'',
                reviewer_name:'',
                review_date:'',
            },
            reviews:[],
            reviewsHeaders:[
                {title:'Title',key:'title'},
                {title:'Review Text',key:'review_text'},
                {title:'Rating',key:'rating'},
                {title:'Name',key:'reviewer_name'},
                {title:'Status',key:'status'},
                {title:'Actions',key:'actions'},
            ]
        }
    },
    created() {
        this.getAllReviews();
    },
    methods:{
        getAllReviews(){
            axios.get('/reviews')
                .then((resp)=>{
                    this.reviews = resp.data.reviews;
                })
        },
        addNewReview(){
            this.rloading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const ndata = {
                name:this.defaultItem.title,
                review_text:this.defaultItem.review_text,
                rating:this.defaultItem.rating,
                user_id:this.defaultItem.user_id,
                status:this.defaultItem.status,
                reviewer_name:this.defaultItem.reviewer_name,
                review_date:dayjs(this.defaultItem.review_date).format('YYYY-MM-DD'),
            }
            axios.post('/review/update',ndata,headers)
                .then((resp)=>{
                    this.addDialog = false;
                    this.getAllReviews();
                    this.defaultItem = {},
                    this.rloading = false;
                })
        },
        editItem(item){
            this.editedIndex = this.reviews.indexOf(item);
            this.editedItem = Object.assign({},item);
            this.editDialog = true;
        },
        editReview(){
            this.rloading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const udata = {
                id:this.editedItem.id,
                name:this.editedItem.title,
                review_text:this.editedItem.review_text,
                rating:this.editedItem.rating,
                user_id:this.editedItem.user_id,
                status:this.editedItem.status,
                reviewer_name:this.editedItem.reviewer_name,
                review_date:dayjs(this.editedItem.review_date).format('YYYY-MM-DD'),
            }
            axios.post('/review/update',udata,headers)
                .then((resp)=>{
                    this.editDialog = false;
                    this.getAllReviews();
                    this.rloading = false;
                })
        },
        deleteItem(item){
            this.editedIndex = this.reviews.indexOf(item);
            this.editedItem = Object.assign({},item);
            this.deleteDialog = true;
        },
        deleteReview(){
            this.rloading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const ddata = {
                mtype:'delete',
                id:this.editedItem.id,
            }
            axios.post('/review/update',ddata,headers)
                .then((resp)=>{
                    this.deleteDialog = false;
                    this.getAllReviews();
                    this.rloading = false;
                })
        },
    }
}
</script>

<style scoped>

</style>
