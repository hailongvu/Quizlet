<template>
    <div id="overlay">
        <span class="loader"></span>
    </div>
    <router-view></router-view>
    <div class="position-fixed top-1 end-1">
        <argon-snackbar
            v-if="snackbar"
            title="Notification"
            date="Just now"
            :description="snackbar.message"
            :icon="{ component: 'ni ni-notification-70', color: 'danger' }"
            :color="snackbar.color"
            :close-handler="closeSnackbar"
        />
    </div>
</template>
<script>
import {mapActions, mapState} from "vuex";
import ArgonSnackbar from "@/components/Argons/ArgonSnackbar.vue";

export default {
    name: 'main app',
    components: {
        ArgonSnackbar,
    },
    data() {
        return {
            snackbar: "",
        };
    },
    beforeMount() {
        this.index();
        // abc 123
    },
    mounted() {
        if (this.meta) {
            // lesson meta title
            this.meta.name ? document.querySelector('meta[name="title"]').setAttribute('content', this.meta.name) : '';
            // lesson meta description
            this.meta.description ? document.querySelector('meta[name="description"]').setAttribute('content', this.meta.description) : '';
            // lesson meta keywords
            this.meta.keywords ? document.querySelector('meta[name="keywords"]').setAttribute('content', this.meta.keywords) : '';
            // add html code to head
            this.meta.header ? document.querySelector('head').insertAdjacentHTML('beforeend', this.meta.header) : '';
        }
    },
    created() {
    },
    title() {
        return this.meta.name;
    },
    methods: {
        ...mapActions('meta', ['index']),
        closeSnackbar() {
            this.snackbar = "";
        },
    },
    computed: {
        ...mapState({
            meta: state => state.meta.meta,
        }),
    },
};
</script>

<style scoped>
.notification {
    position: relative;
    z-index: 99999;
    top: 10px;
    right: 10px;
}

#overlay {
    position: fixed;
    top: 0;
    z-index: 99999;
    width: 100%;
    height: 100%;
    display: none;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, 1);
}

.loader, .loader:before, .loader:after {
    border-radius: 50%;
    width: 2.5em;
    height: 2.5em;
    animation-fill-mode: both;
    animation: bblFadInOut 1.8s infinite ease-in-out;
}

.loader {
    color: #ffffff;
    font-size: 7px;
    position: relative;
    text-indent: -9999em;
    transform: translateZ(0);
    animation-delay: -0.16s;
}

.loader:before,
.loader:after {
    content: '';
    position: absolute;
    top: 0;
}

.loader:before {
    left: -3.5em;
    animation-delay: -0.32s;
}

.loader:after {
    left: 3.5em;
}

@keyframes bblFadInOut {
    0%, 80%, 100% {
        box-shadow: 0 2.5em 0 -1.3em
    }
    40% {
        box-shadow: 0 2.5em 0 0
    }
}
</style>
