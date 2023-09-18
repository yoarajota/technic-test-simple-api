import { createApp } from "vue";
import "./normalize.css";
import "./style.scss";
import App from "./App.vue";

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import router from "./router";
import Vue3Toasity, { type ToastContainerOptions } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import BootstrapVue from "bootstrap-vue";


// @ts-ignore
createApp(App).use(BootstrapVue)
  .use(router)
  .use(Vue3Toasity, {
    autoClose: 3000,
  } as ToastContainerOptions)
  .mount("#app");
