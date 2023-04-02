<template>
    <div class="row">
        <div class="col-md-6 col-12" v-for="lesson in lessons">
            <LessonCard :title="lesson.name" :value="lesson.detail_count + ' terms'" :description="lesson.username"/>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";

export default {
    name: "MyComponent",
    components: {LessonCard},
    data() {
        return {
            lessons: null,
        };
    },
    beforeMount() {
        this.$store.dispatch('home/getLesson').then(
            lesson => {
                console.log(lesson);
                this.lessons = lesson;
            }
        );
    }
};
</script>
