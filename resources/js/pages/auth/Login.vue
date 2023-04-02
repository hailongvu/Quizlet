<template>
    <div class="card-header bg-transparent">
        <h5 class="text-dark text-center mt-2 mb-3">Sign in</h5>
        <div class="btn-wrapper text-center">
            <a href="javascript:;" class="btn btn-neutral btn-icon btn-sm mb-0 me-1">
                <img class="w-30" src="/storage/images/logos/github.svg" />
                Github
            </a>
            <a href="javascript:;" class="btn btn-neutral btn-icon btn-sm mb-0">
                <img class="w-30" src="/storage/images/logos/google.svg" />
                Google
            </a>
        </div>
    </div>
    <div class="card-body px-lg-5 pt-0">
        <div class="text-center text-muted mb-4">
            <small>Or sign in with credentials</small>
        </div>
        <form role="form" class="text-start" @submit.prevent="handleSubmit">
            <div class="mb-3">
                <argon-input id="email" type="text" name="email" placeholder="Email" aria-label="Email"
                             isRequired />
            </div>
            <div class="mb-3">
                <argon-input id="password" type="password" name="password" placeholder="Password" aria-label="Password"
                             isRequired />
            </div>
            <argon-switch id="rememberMe" name="rememberMe">
                Remember me
            </argon-switch>
            <div class="text-center">
                <argon-button color="success" variant="gradient" full-width class="my-4 mb-2">Sign in</argon-button>
            </div>
            <div class="text-center">
                <div class="text-center text-muted my-1">
                    <small><strong>Forgot your password?</strong></small>
                </div>
            </div>
            <div class="mb-2 position-relative text-center">
                <p class="text-sm font-weight-bold mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                    or
                </p>
            </div>
            <div class="text-center">
                <router-link :to="{ name: 'auth.register' }" class="text-secondary">
                    <argon-button color="dark" variant="gradient" full-width class="mt-2 mb-4">Sign up</argon-button>
                </router-link>
            </div>
        </form>
    </div>
</template>
<script lang="js">
import { mapMutations, mapActions, mapState } from "vuex";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import ArgonSwitch from "@/components/Argons/ArgonSwitch.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";

export default {
    name: "Auth Login",
    data() {
        return {
            email: "",
            password: "",
            rememberMe: false,
            submitted: false
        };
    },
    title() {
        return "Login - " + document.querySelector("meta[name=\"title\"]").getAttribute("content");
    },
    components: {
        ArgonInput,
        ArgonSwitch,
        ArgonButton
    },
    created() {
        this.logout();
    },
    methods: {
        ...mapActions("account", ["login", "logout"]),
        handleSubmit(e) {
            this.submitted = true;
            this.email = e.target.email.value;
            this.password = e.target.password.value;
            this.rememberMe = e.target.rememberMe.checked;
            if (this.email && this.password) {
                this.login({
                    email: this.email,
                    password: this.password,
                    rememberMe: this.rememberMe
                });
            } else {
                this.$swal({
                    title: "Oops!",
                    text: "Please enter your email and password!",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                this.submitted = false;
            }
        }
    }
};
</script>
