<template>
    <div class="card-header p-3 pb-0">
        <h4 class="font-weight-bolder text-center">Logging Out</h4>
    </div>
    <div class="card-body pt-2">
        <p class="text-center m-0"> Please wait while we log you out. </p>
    </div>
</template>
<script lang="js">
import { mapActions} from "vuex";
export default {
    name: "Auth Logout",
    created() {
        this.logout().then(() => {
            this.$router.push({ name: 'auth.login' });
        });
    },
    methods: {
        ...mapActions('account', ['logout']),
    },
};
</script>
