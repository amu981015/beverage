import { createApp } from "vue";
import Index from "./Index.vue";
import Admin from "./Admin.vue";
import StoreBackground from "./StoreBackground.vue";
import Background from "./Background.vue";
import VueApexCharts from "vue3-apexcharts";
import router from "./router";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "leaflet/dist/leaflet.css";
import "./assets/css/all.min.css";
import "./assets/css/timeline.css";
import "./assets/css/about.css";
import "./assets/css/bootstrap.css";
import "animate.css/animate.min.css";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";

const path = window.location.pathname;

if (path.includes("/background")) {
  createApp(Background).use(VueApexCharts).mount("#app");
} else if (path.includes("/storebackground")) {
  createApp(StoreBackground).use(VueApexCharts).mount("#app");
} else if (path.includes("/admin")) {
  createApp(Admin).mount("#app");
} else {
  createApp(Index).use(router).mount("#app");
}
