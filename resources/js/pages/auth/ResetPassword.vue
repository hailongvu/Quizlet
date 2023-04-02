<template>
    <div class="card-header text-center pt-4 pb-1">
        <h4 class="font-weight-bolder mb-1">Reset password</h4>
        <p class="mb-0">
            Please enter your new password. You will be redirected to the login page.
        </p>
    </div>
    <div class="card-body">
        <form role="form" @submit.prevent="handleSubmit">
            <input type="hidden" name="token" :value="`${this.$route.query.token}`">
            <argon-input
                id="email"
                type="email"
                name="email"
                :value="`${this.$route.query.email}`"
                placeholder="Email"
                aria-label="Email"
                disabled
            />
            <argon-input
                id="password"
                type="password"
                name="password"
                :value="`${this.password}`"
                placeholder="Password"
                aria-label="Password"
                :error="!!this.pass_err"
            />
            <argon-input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                :value="`${this.password_confirmation}`"
                placeholder="Password confirmation"
                aria-label="Password confirmation"
                :error="!!this.pass_err"
            />
            <p v-if="this.pass_err" class="text-danger">{{ this.pass_err }}</p>
            <div class="text-center">
                <argon-button color="dark" variant="gradient" class="my-4 mb-2" full-width>Reset</argon-button>
            </div>
        </form>
    </div>
</template>
<script lang="js">
import {mapActions, mapState} from "vuex";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";

export default {
    name: "Auth Forgot Password",
    data() {
        return {
            email: '',
            token: '',
            password: '',
            password_confirmation: '',
            pass_err: '',
        }
    },
    computed: {
        ...mapState(['account', 'app']),
    },
    components: {
        ArgonInput,
        ArgonButton,
    },
    created() {
        this.$store.state.app.hideConfigButton = true;
        this.logout();
    },
    beforeUnmount() {
        this.$store.state.app.hideConfigButton = false;
    },
    methods: {
        ...mapActions({
            logout: "account/logout",
            error: "alert/error",
            reset: "account/resetPassword"
        }),
        handleSubmit(e) {
            // get form data
            this.email = e.target.email.value;
            this.token = e.target.token.value;
            this.password = e.target.password.value;
            this.password_confirmation = e.target.password_confirmation.value;
            // check null value
            if (this.email && this.password && this.password_confirmation) {
                // check password confirmation
                if (this.password === this.password_confirmation) {
                    // reset password
                    this.reset({
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                        token: this.token
                    });
                } else {
                    // lesson password error
                    this.pass_err = "Password confirmation doesn't match";
                }
            } else {
                // lesson error
                this.error("Please fill all the fields");
            }
        }
    },
};
</script>
