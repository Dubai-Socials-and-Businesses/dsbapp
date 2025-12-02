<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="6" class="d-flex ga-3 align-center">
                <v-icon @click="this.$router.push({name:'AdminBlogs'})">mdi-arrow-left</v-icon>
                <h4 class="text-h5">Add Blog</h4>
            </v-col>
            <v-col cols="12" md="6" class="text-end">
                <v-btn @click="addBlog" :loading="bloading" :disabled="bloading" color="green" density="compact"
                       variant="elevated">Save</v-btn>
            </v-col>
            <v-col cols="12" md="8">
                <v-card class="border-sm">
                    <v-card-title>Details</v-card-title>
                    <v-card-text>
                        <v-text-field v-model="blog.title" variant="underlined" label="Blog Title" placeholder="Write Awesome news about the Dubai"
                                      persistent-placeholder counter="100" persistent-counter></v-text-field>
                        <v-textarea v-model="blog.excerpt" variant="underlined" label="Short Description" placeholder="Write short about the Blog"
                                      persistent-placeholder counter="200" rows="2" persistent-counter></v-textarea>
                        <v-textarea v-model="blog.description" variant="underlined" label="Short Description" placeholder="Write short about the Blog"
                                    persistent-placeholder counter auto-grow persistent-counter></v-textarea>
                        <v-combobox prepend-inner-icon="mdi-tag" label="Blog Tags" class="mt-2"
                                    variant="outlined" density="comfortable"
                                    v-model="blog.tags" chips closable-chips multiple></v-combobox>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card class="border-sm">
                    <v-card-title>Status</v-card-title>
                    <v-card-text>
                        <v-checkbox-btn v-model="blog.status" value="active" label="Active" color="green"></v-checkbox-btn>
                        <v-checkbox-btn v-model="blog.status" value="inactive" label="Inactive" color="red"></v-checkbox-btn>
                    </v-card-text>
                    <v-card-text>
                        <v-file-upload v-model="blog.image" accept="image/*" density="comfortable" title="Main Image" clearable></v-file-upload>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import axios from "axios";
import { VFileUpload } from 'vuetify/labs/VFileUpload'
export default {
    name:'AdminBlogAdd',
    components:{VFileUpload},
    data(){
        return{
            bloading:false,
            blog:{
                title:'',
                excerpt:'',
                description:'',
                tags:[],
                status:'active',
                image:null,
            },
        }
    },
    methods:{
        addBlog(){
            this.bloading = true;
            const headers = {headers: {'Content-Type': 'multipart/form-data'}}
            const nblog = this.blog;
            axios.post('/blog/add',nblog,headers)
                .then((resp)=>{
                    window.Toast.success(resp.data.message);
                    this.$router.push({name:'AdminBlogEdit',params:{blog_id:resp.data.blog.id}})
                })
                .catch((err)=>{
                    window.Toast.error(err.message);
                })
                .finally(()=>{
                    this.bloading = false
                })
        }
    }
}
</script>

<style scoped>

</style>
