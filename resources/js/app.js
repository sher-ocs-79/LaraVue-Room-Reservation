import { createApp } from "vue";

require("./bootstrap");
import App from "./App.vue";
import axios from "axios";
import router from "./router";
import moment from "moment";
import VCalendar from "v-calendar";

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.config.globalProperties.$moment = moment;
app.use(router).use(VCalendar, {});
app.mount("#app");
