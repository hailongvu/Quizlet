<template>
    <main class="main-content mt-0">
        <div class="page-header min-vh-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-7 mx-auto text-center">
                        <span style="font-size: 3vw;" class="text-danger"><i class="fa-light fa-circle-exclamation"></i></span>
                        <h1 class="display-1 text-bolder text-primary">Error 404</h1>
                        <h2>Erm. Page not found</h2>
                        <p class="lead mb-0">
                            We suggest you to go to the homepage while we solve this issue.
                        </p>
                        <router-link :to="{ name: 'home' }"><argon-button color="dark" variant="gradient" class="mt-4">Go to Homepage</argon-button></router-link>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import ArgonButton from "@/components/Argons/ArgonButton.vue";

export default {
    name: "Error404",
    components: {
        ArgonButton,
    },
    created() {
        document.getElementsByTagName("body")[0].classList.remove("bg-gray-100")
    },
};
</script>
