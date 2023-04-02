<template>
    <Navbar :title="title" :showOptions="showOptions" :progress="progress" />
    <main class="main-content main-content-bg mt-5 d-flex justify-content-center align-items-center" >
        <div class="container">
            <router-view @change-progress="updateProgress" @change-title="updateTitle"></router-view>
        </div>
    </main>
    <AppFooter />
</template>

<script>
import Navbar from "@/components/Navbars/LearnNavbar.vue";
export default {
    name: "Learn",
    components: {
        Navbar
    },
    data()  {
        return {
            title: 'Let Learn',
            showOptions: true,
            progress: 0
        }
    },
    methods: {
        updateProgress(data) {
            this.progress = data;
        },
        updateTitle(data) {
            console.log(data);
            this.title = data;
        }
    },
}
</script>

<style scoped>

</style>
