<template>
    <div class="card-header text-center pt-4 pb-1">
        <h4 class="font-weight-bolder mb-1">Verify Your Email Address</h4>
        <p class="mb-0">
            Before proceeding, please check your email for a verification link.
            If you did not receive the email, click the button below to request another.
        </p>
    </div>
    <div class="card-body pt-0">
        <div class="text-center">
            <argon-button color="dark" variant="gradient" class="my-4 mb-2" full-width @click="handleResendEmail">Resend</argon-button>
        </div>
    </div>
</template>
<script lang="js">
import {mapActions} from "vuex";
import ArgonButton from "@/components/Argons/ArgonButton.vue";

export default {
    name: "Auth Verify Email",
    components: {
        ArgonButton,
    },
    methods: {
        ...mapActions({
            resend: "account/resendVerificationEmail"
        }),
        handleResendEmail(e) {
            this.resend();
        }
    },
};
</script>
