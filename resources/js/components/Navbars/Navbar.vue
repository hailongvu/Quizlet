<template>
    <nav id="navbarBlur" :class="`${!app.isNavFixed
    ? 'navbar navbar-main navbar-expand-lg px-0 mx-4 border-radius-xl shadow-none'
    : `navbar navbar-main navbar-expand-lg px-0 mx-4 border-radius-xl shadow-none position-sticky ${app.darkMode ? 'bg-default' : 'bg-white'} left-auto top-2 z-index-sticky`
    }`" v-bind="$attrs" data-scroll="true">
        <div class="px-3 py-1 container-fluid">
            <breadcrumbs :current-page="currentRouteName" :current-directory="currentDirectory"/>
            <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none">
                <a href="#" class="p-0 nav-link text-body" @click.prevent="navbarMinimize">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line" :class="
                            app.isNavFixed && !app.darkMode ? ' opacity-8 bg-dark' : 'bg-white'
                        "></i>
                        <i class="sidenav-toggler-line" :class="
                            app.isNavFixed && !app.darkMode ? ' opacity-8 bg-dark' : 'bg-white'
                        "></i>
                        <i class="sidenav-toggler-line" :class="
                            app.isNavFixed && !app.darkMode ? ' opacity-8 bg-dark' : 'bg-white'
                        "></i>
                    </div>
                </a>
            </div>
            <div id="navbar" class="mt-2 collapse navbar-collapse mt-sm-0 me-md-0 me-sm-4">
                <div class="pe-md-3 d-flex align-items-center ms-md-auto">
<!--                    <div class="input-group rounded-pill">-->
<!--                        <span class="input-group-text text-body">-->
<!--                            <i class="fas fa-search" aria-hidden="true"></i>-->
<!--                        </span>-->
<!--                        <input type="text" class="form-control" :placeholder="'Type here to search...'"/>-->
<!--                    </div>-->
                </div>
                <ul class="navbar-nav justify-content-end">
                    <li class="nav-item d-flex align-items-center">
                        <span class="px-0 nav-link font-weight-bold" :class="
                            app.isNavFixed && !app.darkMode ? ' opacity-8 text-dark' : 'text-white'
                        " target="_blank">
                            <i class="fa fa-user me-sm-1"></i>
                            <span v-if="this.getUser" class="d-sm-inline d-none">{{ this.getUser.email }}</span>
                        </span>
                    </li>
                    <li class="nav-item d-flex ps-3 align-items-center">
                        <span class="px-0 nav-link font-weight-bold" :class="
                            app.isNavFixed && !app.darkMode ? ' opacity-8 text-dark' : 'text-white'
                        " target="_blank">
                            <i class="fa-solid fa-power-off me-sm-1"></i>
                            <router-link :to="{ name: 'auth.logout' }" class="d-sm-inline d-none">Logout</router-link>
                        </span>
                    </li>
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a id="iconNavbarSidenav" href="#" class="p-0 nav-link text-white"
                           @click.prevent="navbarMinimize">
                            <div class="sidenav-toggler-inner">
                                <i :class="`sidenav-toggler-line ${app.isNavFixed && !app.darkMode ? ' opacity-8 bg-dark' : 'bg-white'
                                }`"></i>
                                <i :class="`sidenav-toggler-line ${app.isNavFixed && !app.darkMode ? ' opacity-8 bg-dark' : 'bg-white'
                                }`"></i>
                                <i :class="`sidenav-toggler-line ${app.isNavFixed && !app.darkMode ? ' opacity-8 bg-dark' : 'bg-white'
                                }`"></i>
                            </div>
                        </a>
                    </li>
                    <li class="px-3 nav-item d-flex align-items-center">
                        <a class="p-0 nav-link" @click="toggleConfigurator">
                            <i :class="`cursor-pointer fa fa-cog fixed-plugin-button-nav ${!app.isNavFixed || app.darkMode ? 'text-white' : 'opacity-8 text-dark'
                            }`"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown d-flex align-items-center pe-2">
                        <a id="dropdownMenuButton" href="#" :class="`p-0 nav-link ${app.isNavFixed && !app.darkMode ? ' opacity-8 text-dark' : 'text-white'
                        } ${showMenu ? 'show' : ''}`" data-bs-toggle="dropdown" aria-expanded="false"
                           @click="showMenu = !showMenu">
                            <i class="cursor-pointer fa fa-bell"></i>
                        </a>
                        <ul class="px-2 py-2 dropdown-menu dropdown-menu-end me-sm-n4" :class="showMenu ? 'show' : ''"
                            aria-labelledby="dropdownMenuButton">
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="py-1 d-flex">
                                        <div class="my-auto">
                                            <img src="https://cdn-icons-png.flaticon.com/512/9717/9717185.png"
                                                 class="avatar avatar-sm me-3" alt="user image"/>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-1 text-sm font-weight-normal">
                                                <span class="font-weight-bold">New message</span> from
                                                Laur
                                            </h6>
                                            <p class="mb-0 text-xs text-secondary">
                                                <i class="fa fa-clock me-1"></i>
                                                13 minutes ago
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="py-1 d-flex">
                                        <div class="my-auto">
                                            <img src="https://cdn-icons-png.flaticon.com/512/9717/9717185.png"
                                                 class="avatar avatar-sm bg-gradient-dark me-3" alt="logo spotify"/>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-1 text-sm font-weight-normal">
                                                <span class="font-weight-bold">New album</span> by
                                                Travis Scott
                                            </h6>
                                            <p class="mb-0 text-xs text-secondary">
                                                <i class="fa fa-clock me-1"></i>
                                                1 day
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
import Breadcrumbs from "../Breadcrumbs.vue";
import {mapState, mapMutations, mapActions, mapGetters} from "vuex";

export default {
    name: "Navbar",
    components: {
        Breadcrumbs
    },
    data() {
        return {
            showMenu: false,
        };
    },
    computed: {
        ...mapState(['app']),
        ...mapGetters('account', ['getUser']),
        currentRouteName() {
            let name = this.$route.name.split(".")[1];
            return name.charAt(0).toUpperCase() + name.slice(1);
        },
        currentDirectory() {
            let dir = this.$route.path.split("/")[1];
            return dir.charAt(0).toUpperCase() + dir.slice(1);
        }
    },
    beforeUpdate() {
        this.toggleNavigationOnMobile();
    },
    methods: {
        ...mapMutations(["navbarMinimize", "toggleConfigurator"]),
        ...mapActions(["toggleSidebarColor"]),
        toggleNavigationOnMobile() {
            if (window.innerWidth < 1200) {
                this.navbarMinimize();
            }
        },
    }
};
</script>
