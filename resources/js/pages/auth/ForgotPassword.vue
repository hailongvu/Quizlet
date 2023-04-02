<template>
    <div class="card-header text-center pt-4 pb-1">
        <h4 class="font-weight-bolder mb-1">Forgot password</h4>
        <p class="mb-0">
            Please enter your email address and we will send you a link to reset your password.<br>If you do not receive an email, please check your <strong>spam</strong> folder.
        </p>
    </div>
    <div class="card-body">
        <form role="form" @submit.prevent="handleSubmit">
            <argon-input
                id="email"
                type="email"
                name="email"
                placeholder="Email"
                aria-label="Email"
            />
            <div class="text-center">
                <argon-button color="dark" variant="gradient" class="my-4 mb-2" full-width>Send</argon-button>
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
    name: "Auth Forgot Password",
    data() {
        return {
            email: '',
        }
    },
    computed: {
        ...mapState(['account', 'app'])
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
        ...mapActions('account', ['logout', "forgotPassword"]),
        handleSubmit(e) {
            this.email = e.target.email.value;
            if (this.email) {
                this.forgotPassword(this.email);
            } else {
                this.$swal({
                    title: 'Oops!',
                    text: 'Please enter your email address!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
    },
};
</script>
