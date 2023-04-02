import './bootstrap';
import {createApp} from "vue";
import OneSignalVuePlugin from '@onesignal/onesignal-vue3';
import VueTilt from "vue-tilt.js";
import VueSweetalert2 from "vue-sweetalert2";
import VueGtag from "vue-gtag";
import App from "./App.vue";
import store from "./helpers/store/index.js";
import router from "./helpers/router/index.js";
import "../css/app.css";
import ArgonDashboard from "./argon-dashboard";
import {pageTitle} from 'vue-page-title';

const appInstance = createApp(App);
appInstance.use(OneSignalVuePlugin, {
    appId: '1a3c6ac3-1e41-4338-bb97-99fb99ae5140',
})
appInstance.use(store);
appInstance.use(router);
appInstance.use(VueTilt);
appInstance.use(VueSweetalert2);
appInstance.use(ArgonDashboard);
appInstance.use(VueGtag, {
    config: {id: "G-8T9RFMHSRP"},
});
appInstance.use(
    pageTitle({
        mixin: true,
    })
);
appInstance.mount("#app");
