import './bootstrap';
import { createApp } from 'vue';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import App from './App.vue';

createApp(App).use(Toast).mount("#app");