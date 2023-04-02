<template>
    <div class="container-fluid top-0 position-sticky z-index-sticky">
        <div class="row">
            <div class="col-12">
                <navbar is-blur="blur border-radius-lg my-3 py-2 start-0 end-0 shadow m-0" btn-background="bg-gradient-success"
                        :dark-mode="true" />
            </div>
        </div>
    </div>
    <main class="main-content main-content-bg mt-0" >
        <div class="container">
            <router-view></router-view>
        </div>
    </main>
    <AppFooter />
</template>

<script lang="js">
import Navbar from "@/components/Navbars/HomeNavbar.vue";
import AppFooter from "@/components/Footer.vue";
export default {
    name: "Home",
    components: {
        Navbar,
        AppFooter,
    },
};
</script>
