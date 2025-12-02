/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// import './bootstrap';
import { createApp } from 'vue';
import App from "./App.vue";
import vuetify from "./vuetify.js";
import router from "./router";
import store from "./store";
import Toast, {useToast} from "vue-toastification";
import "vue-toastification/dist/index.css";

const options = {
    position: 'bottom-right',
    timeout: 2000,
    closeOnClick: true,
    pauseOnHover: true,
};

const app = createApp(App);

app.use(Toast,options)
app.config.globalProperties.$toast = useToast();
window.Toast = useToast();
app.use(router);
app.use(vuetify);
app.use(store);
app.mount('#app');
