<template>
    <div class="my-4">
        <info-card title="Welcome to Let Learn" description="Learn anything, anytime, anywhere"
            :badge="{ text: 'Moderate', color: 'success' }" />
    </div>
    <div class="mt-5">
        <div class="row">
            <h3>Lesson</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons">
                <router-link :to="`/lesson/${lesson.id}`">
                    <LessonCard :title="lesson.name" :value="lesson.detail_count + ' terms'"
                        :description="lesson.username" />
                </router-link>
            </div>
        </div>
        <div class="row">
            <h3>Course</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="course in courses">
                <router-link :to="`/course/${course.id}`">
                    <LessonCard :title="course.name" :value="course.lesson_count + ' lessons'"
                        :description="course.username" />
                </router-link>
            </div>
        </div>
        <div v-if="classes" class="row">
            <h2>Class</h2>
            <div class="col-lg-4 col-md-6 col-12" v-for="classes in classes">
                <LessonCard :title="classes.name" :value="classes.classes_count + ' courses'" />
            </div>
        </div>
    </div>
    <!-- Floating button -->
    <div class="position-fixed bottom-3 end-2" style="z-index: 11">
        <i class="fa-sharp fa-solid fa-circle-plus" style="font-size: 4rem;" @click="this.addModal.show()"></i>
    </div>
    <div class="modal" id="modal" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content p-md-5">
                <div class="modal-body row">
                    <div class="col-md-3 col-6 p-3">
                        <div class="card" @click="createLesson">
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/5484/5484383.png" class="card-img-top"
                                    alt="...">
                            </div>
                            <div class="card-body p-2 text-center text-black py-4">
                                <h5 class="card-title">Create lesson</h5>
                                <p class="card-text">Create a new lesson from scratch.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 p-3">
                        <div class="card" data-bs-toggle="modal" href="#modalCreateCourse">
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/7688/7688940.png" class="card-img-top"
                                    alt="...">
                            </div>
                            <div class="card-body p-2 text-center text-black py-4">
                                <h5 class="card-title">Create course</h5>
                                <p class="card-text">Create a new course from scratch.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 p-3">
                        <div class="card">
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/10215/10215645.png" class="card-img-top"
                                    alt="...">
                            </div>
                            <div class="card-body p-2 text-center text-black py-4">
                                <h5 class="card-title">Import lesson</h5>
                                <p class="card-text">Import a lesson from other sources.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 p-3">
                        <div class="card">
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/8744/8744051.png" class="card-img-top"
                                    alt="...">
                            </div>
                            <div class="card-body p-2 text-center text-black py-4">
                                <h5 class="card-title">Faqs</h5>
                                <p class="card-text">Learn more about Let Learn Team.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modalCreateCourse" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content p-md-5">
                <div class="modal-body">
                    <div>
                        <label class="form-label mt-2 fs-6">Name</label>
                        <argon-input id="name" type="text" name="name"
                            placeholder="Enter a name. Example:'MAE Chapter 1' " />
                    </div>
                    <div>
                        <label class="form-label mt-2 fs-6">Description</label>
                        <argon-input id="description" type="text" name="description" placeholder="Add a description..." />
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                        <argon-button color="default" variant="gradient" data-bs-toggle="modal" href="#modal">Cancel</argon-button>
                    </div>
                    <div class="col-2">
                        <argon-button color="success" variant="gradient" @click="this.createCourse">Create</argon-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
import InfoCard from "@/components/Cards/InfoCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import { Modal } from "bootstrap";

export default {
    name: "HomePage",
    components: { LessonCard, InfoCard, ArgonButton, ArgonInput },
    data() {
        return {
            lessons: null,
            courses: null,
            classes: null,
            addModal: null
        };
    },
    title() {
        return "Home - " + document.getElementsByTagName("meta")["title"].content;
    },
    beforeMount() {
        this.$store.dispatch("home/getLesson").then(
            lesson => {
                console.log(lesson);
                this.lessons = lesson;
            }
        );
        this.$store.dispatch("home/getCourse").then(
            course => {
                console.log(course);
                this.courses = course;
            }
        );
    },
    mounted() {
        this.addModal = new Modal(document.getElementById("modal"));
    },
    methods: {
        createLesson() {
            // hide modal
            this.addModal.hide();
            // redirect to add lesson page
            this.$router.push({ name: "home.lesson.add" });
        },
        createCourse() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            if (name === '' || description === '') {
                alert('Please fill all fields to create new course');
            } else {
                this.$store.dispatch('course/createCourse', { name: name, description: description}).then(() => {
                    this.$router.push({ name: "home.index"});
                });
            }
        }
    }
};
</script>
<style scoped>
#modal .card:hover {
    border: 3px solid #5e72e4;
}
</style>
