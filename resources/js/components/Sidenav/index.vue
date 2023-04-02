<template>
    <div v-show="app.layout === 'default'" class="min-height-300 position-absolute w-100"
         :class="`${app.darkMode ? 'bg-trasnparent' : 'bg-gradient-primary'}`"/>

    <aside id="sidenav-main"
           class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 ms-4 overflow-hidden"
           :class="` ${app.sidebarType} fixed-start ms-3 ${app.layout === 'landing' ? 'bg-transparent shadow-none' : ''}`">
        <div class="sidenav-header">
            <i id="iconSidenav"
               class="top-0 p-3 cursor-pointer fas fa-times text-secondary opacity-5 position-absolute end-0 d-none d-xl-none"
               aria-hidden="true"></i>
            <router-link class="m-0 navbar-brand" to="/">
                <img :src="
    app.layout === 'landing' || app.darkMode || app.sidebarType === 'bg-default'
        ? logoWhite
        : logo
                " class="navbar-brand-img h-100" alt="main_logo"/>
                <span class="ms-2 font-weight-bold">Let Learn</span>
            </router-link>
        </div>
        <hr class="mt-0 horizontal dark"/>
        <sidenav-list/>
    </aside>
</template>
<script>
import {mapState} from "vuex";
import SidenavList from "./SidenavList.vue";

export default {
    name: "Index",
    components: {
        SidenavList,
    },
    data() {
        return {
            logo: '/logo.png',
            logoWhite: '/logo.png',
            image: '',
        };
    },
    computed: {
        ...mapState(['app'])
    },
    beforeUnmount() {
        this.$store.state.app.image;
    }
};
</script>
