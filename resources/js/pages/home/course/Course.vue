<template>
    <div class="row">
        <h2>{{ course ? course.name : 'loading' }}</h2>
        <div class="col-12 mb-3 row align-items-center justify-content-center">
            <div class="col-2">
                <argon-avatar
                    img="https://gcavocats.ca/wp-content/uploads/2018/09/man-avatar-icon-flat-vector-19152370-1.jpg"
                    alt="Avatar" size="md" circular />
            </div>
            <div class="col-6">
                <h6 class="m-0">{{ this.user.email }}</h6>
                <p class="m-0">{{ this.user.username }} | {{ this.user.email_verified_at ? 'verified' : 'unverified'
                }}</p>
            </div>
            <div class="col-4 p-0">
                <div class="dropdown float-end">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    </button>
                    <ul class="dropdown-menu">
                        <router-link :to="{ name: 'home.course.edit', params: { id: course_id } }">
                            <li><a class="dropdown-item" href="#">Edit</a></li>
                        </router-link>
                        <li><a class="dropdown-item" @click="this.delete">Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div>
        <!-- show description -->
        <p class="m-0">{{ course ? course.description : 'loading' }}</p>
    </div>
    <div class="row mt-4">
        <div class="col-md-6 col-12 mt-4" v-for="lesson in lessons">
            <router-link :to="`/l/${lesson.id}`">
                <LessonCard :title="lesson.name" :value="lesson.detail_count + ' terms'" :description="lesson.username" />
            </router-link>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonAvatar from "@/components/Argons/ArgonAvatar.vue";
import { mapActions } from "vuex";
export default {
    name: "course",
    props: ['course_id'],
    components: {
        LessonCard,
        ArgonButton,
        ArgonAvatar,
    },
    data() {
        return {
            lessons: null,
            course_id: this.$route.params.id,
            course: null,
            user: JSON.parse(localStorage.getItem('user')),
        }
    },
    title() {
        return "Home - " + document.getElementsByTagName("meta")["title"].content;
    },
    beforeMount() {
        this.$store.dispatch('course/getLessonByCourseId', this.course_id).then(
            lesson => {
                this.lessons = lesson;
            }
        );
        this.$store.dispatch('course/getCourseInfo', this.course_id).then(
            course => {
                this.course = course;
            }
        );
    },
    methods: {
        ...mapActions({
            getLesson: "course/getLessonByCourseId",
        }),
        delete() {
            this.$swal({
                icon: "question",
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$store.dispatch('course/deleteCourse', this.course_id);
                    this.$store.push({ name: 'home.index' });
                    // close modal
                    document.getElementById('edit-close').click();
                }
            });
        }
    },
};
</script>

<style scoped>
.dropdown .dropdown-toggle:after {
    content: '\f141' !important;
    font: normal normal normal 14px/1 FontAwesome;
    border: none;
    vertical-align: middle;
}
</style>
