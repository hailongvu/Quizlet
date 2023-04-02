<template>
    <div class="card-header bg-transparent pb-0">
        <h5 class="text-dark text-center mt-2 mb-3">Register with</h5>
        <div class="btn-wrapper text-center">
            <a href="javascript:;" class="btn btn-neutral btn-icon btn-sm mb-0 me-1">
                <img class="w-30" src="https://s3-hcm-r1.longvan.net/19403879-letlearn/images/github.svg" />
                Github
            </a>
            <a href="javascript:;" class="btn btn-neutral btn-icon btn-sm mb-0">
                <img class="w-30" src="https://s3-hcm-r1.longvan.net/19403879-letlearn/images/google.svg" />
                Google
            </a>
        </div>
        <div class="position-relative text-center py-3">
            <p class="text-sm font-weight-bold mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                or
            </p>
        </div>
    </div>
    <div class="card-body px-lg-5 pt-0">
        <form role="form" class="text-start" @submit.prevent="handleSubmit">
            <argon-input id="email" name="email" type="email" placeholder="Email" aria-label="Email" />
            <argon-input id="dob" name="date_of_birth" type="date" placeholder="Date of birth"
                aria-label="Date of birth" />
            <argon-input id="password" name="password" type="password" placeholder="Password" aria-label="Password" />
            <argon-input id="password_confirmation" name="password_confirmation" type="password"
                placeholder="Password confirmation" aria-label="Password confirmation" />
            <argon-checkbox id="flexCheckDefault" name="flexCheckDefault" checked>
                <label class="form-check-label" for="flexCheckDefault">
                    I agree the
                    <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
                </label>
            </argon-checkbox>
            <div class="text-center">
                <argon-button color="dark" variant="gradient" full-width class="my-4 mb-2">Sign up</argon-button>
            </div>
            <p class="text-sm mt-3 mb-0">
                Already have an account?
                <router-link :to="{ name: 'auth.login' }" class="text-dark font-weight-bolder">
                    Sign in
                </router-link>
            </p>
        </form>
    </div>
</template>
<script lang="js">
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import ArgonCheckbox from "@/components/Argons/ArgonCheckbox.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import { mapMutations, mapActions, mapState } from "vuex";

export default {
    name: "SignupBasic",
    components: {
        ArgonInput,
        ArgonCheckbox,
        ArgonButton,
    },
    data() {
        return {
            email: '',
            date_of_birth: '',
            password: '',
            password_confirmation: '',
            submitted: false,
        }
    },
    computed: {
        ...mapState(['account', 'app'])
    },
    created() {
        this.$store.state.hideConfigButton = true;
        this.toggleDefaultLayout();
        this.logout();
    },
    beforeUnmount() {
        this.$store.state.hideConfigButton = false;
        this.toggleDefaultLayout();
    },
    methods: {
        ...mapMutations(["toggleDefaultLayout"]),
        ...mapActions('account', ['register', 'logout']),
        handleSubmit(e) {
            this.submitted = true;
            this.email = e.target.email.value;
            this.date_of_birth = e.target.date_of_birth.value;
            this.password = e.target.password.value;
            this.password_confirmation = e.target.password_confirmation.value;

            if(this.email && this.date_of_birth && this.password && this.password_confirmation) {
                if (this.password != this.password_confirmation) {
                    this.$swal({
                        title: 'Oops!',
                        text: 'Password confirmation does not match!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    this.submitted = false;
                }else{
                    let data = {
                        email: this.email,
                        date_of_birth: this.date_of_birth,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                    };
                    this.register(data);
                }
            }else {
                this.$swal({
                    title: 'Oops!',
                    text: 'Please fill in all fields!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                this.submitted = false;
            }
        }
    },
};
</script>
