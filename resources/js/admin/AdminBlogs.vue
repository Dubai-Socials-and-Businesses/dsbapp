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
                <p-btn>my Button</p-btn>
                <DatePicker/>
            </v-col>
            <v-col cols="12" md="12">
                <v-card>
                   <v-data-table :items="blogs" :headers="blogsHeaders" :hide-default-footer="blogs.length < 20"></v-data-table>
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
            axios.get('/admin/blogs')
                .then((resp)=>{
                    this.blogs = resp.data.blogs;
                })
        }
    }
}
</script>

<style scoped>

</style>
