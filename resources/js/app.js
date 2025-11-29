/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import App from "./App.vue";
import vuetify from "./vuetify.js";
import router from "./router";
import AOS from 'aos';
import "aos/dist/aos.css"

const app = createApp({App});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

app.use(router);
app.use(vuetify)
app.mount('#app');

AOS.init({
    duration: 800, // animation duration in ms
});
