<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" md="6">
                <h4 class="text-h5">Blogs List</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn color="navy" density="compact" variant="outlined">More Actions</v-btn>
            </v-col>
            <v-col cols="12" md="9">
                <v-text-field prepend-inner-icon="mdi-magnify" density="compact" variant="outlined"
                              hide-details placeholder="Search Blogs"></v-text-field>
            </v-col>
            <v-col cols="12" md="3">
                <v-btn :to="{name:'AdminBlogAdd'}" color="navy" density="default" block append-icon="mdi-plus">Add Blog</v-btn>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                   <v-data-table :items="blogs" :headers="blogsHeaders" :hide-default-footer="blogs.length < 20">
                       <template v-slot:item.image="{item}">
                           <v-img class="my-1" v-if="item.image" :src="cdn+item.image" max-width="170" max-height="52" contain></v-img>
                       </template>
                       <template v-slot:item.status="{item}">
                           <v-btn v-if="item.status === 'active'" color="green" size="x-small">Active</v-btn>
                           <v-btn v-else color="red" size="x-small">Inactive</v-btn>
                       </template>
                       <template v-slot:item.actions="{item}">
                           <v-btn :to="{name:'AdminBlogEdit',params:{blog_id:item.id}}" color="navy" variant="outlined" density="compact">Edit</v-btn>
                       </template>
                   </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";
export default {
    name:'AdminBlogs',
    data(){
        return{
            cdn:'https://dsbcdn.s3-accelerate.amazonaws.com/',
            blogs:[],
            blogsHeaders:[
                {title:'Title',key:'title'},
                {title:'Image',key:'image'},
                {title:'Excerpt',key:'excerpt'},
                {title:'Status',key:'status'},
                {title:'Actions',key:'actions'},
            ]
        }
    },
    created() {
        this.getAllBlogs();
    },
    methods:{
        getAllBlogs(){
            axios.get('/blogs')
                .then((resp)=>{
                    this.blogs = resp.data.blogs || [];
                })
        }
    }
}
</script>

<style scoped>

</style>
