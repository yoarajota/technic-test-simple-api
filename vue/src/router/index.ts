import { createRouter, createWebHashHistory } from "vue-router";
import Home from "../components/Home.vue";
import Index from "../components/Index.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/temp-:register(.*)*", component: Index },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
