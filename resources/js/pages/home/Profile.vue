<template>
    <div class="card">
        <div class="card-header me-5 pb-0">
                <div class="row">
                    <div class="col-md-2 p-3">
                        <img
                            :src="this.user.avatar" class="img-fluid rounded-circle w-100" alt="...">
                    </div>
                    <div class="col-md-10">
                        <div class="card-body">
                            <p class="card-text mb-1">Username: <span class="card-text fs-5 text-dark">{{ this.user.username }}</span></p>
                            <p class="card-text mb-1">Email: {{ this.user.email }}</p>
                            <p class="card-text">Dob: {{ this.user.date_of_birth }}</p>
                        </div>
                    </div>
                </div>
        </div>
        <div class="card-body pt-1">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <router-link :to="{ name: 'home.profile.lessons'}" class="nav-link"
                                 :class="{ active: $route.name === 'home.profile.lessons' }" style="color: black"
                                 aria-current="page">Lesson
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name: 'home.profile.courses'}" class="nav-link"
                                 :class="{ active: $route.name === 'home.profile.courses' }" style="color: black"
                                 aria-current="page">Course
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name: 'home.profile.class.js'}" class="nav-link"
                                 :class="{ active: $route.name === 'home.profile.class.js' }" style="color: black"
                                 aria-current="page">Class
                    </router-link>
                </li>
            </ul>
            <div class="row mt-4">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<style scoped>
.nav-item .active {
    border: none;
    border-bottom: 4px solid #5e72e4;
}

.nav-item:hover {
    border: none;
    border-bottom: 4px solid #5e72e4;
}
</style>

<script>
import MD5 from "crypto-js/md5";
export default {
    name: "Profile",
    data() {
        return {
            user: null,
            lessons: null,
            courses: null,
            classes: null
        };
    },
    beforeMount() {
        this.user = JSON.parse(localStorage.getItem('user'));
        this.user.avatar = 'http://www.gravatar.com/avatar/' + MD5(this.user.email).toString();
    },
    created() {
        this.$router.push({name: 'home.profile.lessons'});
    },
};
</script>
