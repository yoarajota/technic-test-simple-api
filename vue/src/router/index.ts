import { createRouter, createWebHashHistory } from "vue-router";
import Home from "../components/Home.vue";
import Index from "../components/Index.vue";
import Form from "../components/Form.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/list-:register(.*)*", component: Index },
  { path: "/form-:register(.*)*", component: Form },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
