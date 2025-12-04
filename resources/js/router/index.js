import { createRouter, createWebHistory } from "vue-router";
import store from '../store';

import AdminDashboard from "@/admin/AdminDashboard.vue";
import AdminUsers from "@/admin/AdminUsers.vue";
import AdminGallery from "@/admin/AdminGallery.vue";
import AdminGalleryAdd from "@/admin/AdminGalleryAdd.vue";
import AdminGalleryEdit from "@/admin/AdminGalleryEdit.vue";
import AdminEvents from "@/admin/AdminEvents.vue";
import AdminEventAdd from "@/admin/AdminEventAdd.vue";
import AdminEventEdit from "@/admin/AdminEventEdit.vue";
import AdminReviews from "@/admin/AdminReviews.vue";
import AdminBlogs from "@/admin/AdminBlogs.vue";
import AdminBlogAdd from "@/admin/AdminBlogAdd.vue";
import AdminBlogEdit from "@/admin/AdminBlogEdit.vue";
import AdminPartners from "@/admin/AdminPartners.vue";
import AdminPackages from "@/admin/AdminPackages.vue";




const routes = [
    {path:'/sadmin/dashboard',component:AdminDashboard,name:"AdminDashboard",meta: { requiresAuth: true }},
    {path:'/sadmin/users',component:AdminUsers,name:"AdminUsers",meta: { requiresAuth: true }},
    {path:'/sadmin/gallery',component:AdminGallery,name:"AdminGallery",meta: { requiresAuth: true }},
    {path:'/sadmin/gallery/add',component:AdminGalleryAdd,name:"AdminGalleryAdd",meta: { requiresAuth: true }},
    {path:'/sadmin/gallery/edit/:gallery_id',component:AdminGalleryEdit,name:"AdminGalleryEdit",props:true,meta: { requiresAuth: true }},
    {path:'/sadmin/events',component:AdminEvents,name:"AdminEvents",meta: { requiresAuth: true }},
    {path:'/sadmin/event/add',component:AdminEventAdd,name:"AdminEventAdd",meta: { requiresAuth: true }},
    {path:'/sadmin/event/edit/:event_id',component:AdminEventEdit,name:"AdminEventEdit",props:true,meta: { requiresAuth: true }},
    {path:'/sadmin/reviews',component:AdminReviews,name:"AdminReviews",meta: { requiresAuth: true }},
    {path:'/sadmin/blogs',component:AdminBlogs,name:"AdminBlogs",meta: { requiresAuth: true }},
    {path:'/sadmin/blog/add',component:AdminBlogAdd,name:"AdminBlogAdd",meta: { requiresAuth: true }},
    {path:'/sadmin/blog/edit/:blog_id',component:AdminBlogEdit,name:"AdminBlogEdit",props:true,meta: { requiresAuth: true }},
    {path:'/sadmin/partners',component:AdminPartners,name:"AdminPartners",meta: { requiresAuth: true }},
    {path:'/sadmin/packages',component:AdminPackages,name:"AdminPackages",meta: { requiresAuth: true }},
    // {path: '/:catchAll(.*)*',redirect:'/login'}
]

const router = createRouter({
    history:createWebHistory(),
    routes
})

router.beforeEach(async (to,from,next)=>{
    if(to.meta.requiresAuth){
        const isAuth = await store.dispatch("checkAuth")
        if(!isAuth){
            window.location.href = "/login"
            return
        }
    }
    next()
})

export default router;
