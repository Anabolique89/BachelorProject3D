import { createApp } from "vue";
import "./style.css";
import store from "./store";
import router from "./router";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import App from "./App.vue";

// Toast configuration
const toastOptions = {
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false,
    transition: "Vue-Toastification__bounce",
    maxToasts: 3,
    newestOnTop: true,
};

createApp(App).use(store).use(router).use(Toast, toastOptions).mount("#app");
