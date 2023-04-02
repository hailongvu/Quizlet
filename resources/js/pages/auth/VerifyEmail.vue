<template>
</template>

<script>
import { mapActions } from "vuex";
export default {
    name: "VerifyEmail",
    data() {
        return {
            id:this.$route.params.id,
            hash:this.$route.params.hash,
            expires:this.$route.query.expires,
            signature:this.$route.query.signature,
        };
    },
    methods: {
        ...mapActions({
            verify: "account/verifyEmail"
        }),
        handleVerifyEmail(e) {
            this.verify({
                id: this.id,
                hash: this.hash,
                expires: this.expires,
                signature: this.signature,
            });
        }
    },
    created() {
        this.handleVerifyEmail();
    }
}
</script>

<style scoped>

</style>
