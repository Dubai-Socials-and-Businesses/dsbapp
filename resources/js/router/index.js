import { createRouter, createWebHistory } from "vue-router";
import AdminDashboard from "@/admin/AdminDashboard.vue";
import AdminUsers from "@/admin/AdminUsers.vue";
import AdminGallery from "@/admin/AdminGallery.vue";
import AdminEvents from "@/admin/AdminEvents.vue";
import AdminReviews from "@/admin/AdminReviews.vue";
import AdminBlogs from "@/admin/AdminBlogs.vue";
import AdminPartners from "@/admin/AdminPartners.vue";
import AdminGalleryAdd from "@/admin/AdminGalleryAdd.vue";
import AdminEventAdd from "@/admin/AdminEventAdd.vue";

const routes = [
    {path:'/sadmin',component:AdminDashboard,name:"AdminDashboard"},
    {path:'/sadmin/users',component:AdminUsers,name:"AdminUsers"},
    {path:'/sadmin/gallery',component:AdminGallery,name:"AdminGallery"},
    {path:'/sadmin/gallery/add',component:AdminGalleryAdd,name:"AdminGalleryAdd"},
    {path:'/sadmin/events',component:AdminEvents,name:"AdminEvents"},
    {path:'/sadmin/event/add',component:AdminEventAdd,name:"AdminEventAdd"},
    {path:'/sadmin/reviews',component:AdminReviews,name:"AdminReviews"},
    {path:'/sadmin/blogs',component:AdminBlogs,name:"AdminBlogs"},
    {path:'/sadmin/partners',component:AdminPartners,name:"AdminPartners"},
    // {path: '/:catchAll(.*)*',redirect:'/login'}
]

const router = createRouter({
    history:createWebHistory(),
    routes
})

export default router;
