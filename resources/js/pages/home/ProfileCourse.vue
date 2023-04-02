<template>
    <div class="row">
        <div class="col-md-6 col-12" v-for="course in courses">
            <LessonCard :title="course.name" :value="course.lesson_count + ' lessons'" :description="course.username"/>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
export default {
    name: "MyComponent",
    components: {LessonCard},
    data(){
        return {
            courses: null,
        };
    },
    beforeMount() {
        this.$store.dispatch('home/getCourse').then(
            course => {
                console.log(course);
                this.courses = course;
            }
        );
    }
};
</script>
