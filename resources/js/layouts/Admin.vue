<template>
    <div v-show="app.layout === 'landing'" class="landing-bg h-100 bg-gradient-primary position-fixed w-100"></div>
    <sidenav v-if="app.showSidenav" />
    <main class="main-content position-relative max-height-vh-100 h-100">
        <navbar v-if="app.showNavbar" />
        <router-view></router-view>
        <app-footer v-show="app.showFooter" />
        <configurator :class="[app.showConfig ? 'show' : '', app.hideConfigButton ? 'd-none' : '']" />
    </main>
</template>
<script>
import Sidenav from "@/components/Sidenav/index.vue";
import Configurator from "@/components/Configurator.vue";
import Navbar from "@/components/Navbars/Navbar.vue";
import AppFooter from "@/components/Footer.vue";
import { mapState } from "vuex";

export default {
    name: "App",
    components: {
        Sidenav,
        Configurator,
        Navbar,
        AppFooter
    },
    computed: {
        ...mapState(['app']),
    }
};
</script>
