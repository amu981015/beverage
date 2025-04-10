import { createRouter, createWebHistory } from "vue-router";
import Navbar from "../components/Navbar.vue";
import Home from "../components/Home.vue";
import Menu from "../components/Menu.vue";
import Faq from "../components/Faq.vue";
import About from "../components/About.vue";
import StoreMap from "../components/StoreMap.vue";
import Register from "../components/Register.vue";
import Login from "../components/Login.vue";
import Footer from "../components/Footer.vue";

const routes = [
  { path: "/", components: { default: Home, navbar: Navbar, footer: Footer } },
  {
    path: "/menu",
    components: { default: Menu, navbar: Navbar, footer: Footer },
  },
  {
    path: "/faq",
    components: { default: Faq, navbar: Navbar, footer: Footer },
  },
  {
    path: "/about",
    components: { default: About, navbar: Navbar, footer: Footer },
  },
  {
    path: "/storemap",
    components: { default: StoreMap, navbar: Navbar, footer: Footer },
  },
  {
    path: "/register",
    components: { default: Register, navbar: Navbar, footer: Footer },
  },
  {
    path: "/login",
    components: { default: Login, navbar: Navbar, footer: Footer },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
