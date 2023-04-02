<template>
    <div class="row mt-3">
        <div class="col-md-7 col-12">
            <div class="flashcards-container">
                <section class="card-container">
                    <div class="card d-flex justify-content-center align-items-center p-md-5 p-3"
                        style="min-height: 50vh; max-height: 50vh;" @click="rotateCard">
                        <p id="card-title" class="fs-6" style="white-space: pre-line;"></p>
                    </div>
                </section>
                <!-- add card count display -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button @click="previousCard" class="btn">Previous</button>
                    <div class="card-count w-100 text-center">{{ cardsCount.currentCardIndex + 1 }} / {{
                        cardsCount.totalCards }}</div>
                    <button @click="nextCard" class="btn">Next</button>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-12">
            <div class="row">
                <h2>{{ lesson ? lesson.name : 'loading' }}</h2>
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
                                <router-link :to="{ name: 'home.lesson.edit', params: { id: lesson_id } }">
                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                </router-link>
                                <li><a class="dropdown-item" @click="this.delete">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <router-link :to="{ name: 'learn.flashcard.index', params: { id: lesson_id } }">
                        <div class="card">
                            <div class="card-body p-3 row">
                                <div class="col-8">
                                    <h5 class="card-title mb-0">Flashcard</h5>
                                    <p class="card-text text-sm">Review terms and definitions</p>
                                </div>
                                <div class="col-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/5484/5484383.png" class="img-fluid"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </router-link>
                </div>
                <div class="col-md-6 col-12">
                    <router-link :to="{ name: 'learn.quiz.index', params: { id: lesson_id } }">
                        <div class="card">
                            <div class="card-body p-3 row">
                                <div class="col-8">
                                    <h5 class="card-title mb-0">Learn</h5>
                                    <p class="card-text text-sm">Study with MC, T/F, and other questions</p>
                                </div>
                                <div class="col-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4207/4207247.png" class="img-fluid"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </router-link>
                </div>
                <div class="col-md-6 col-12 pt-3">
                    <div class="card">
                        <div class="card-body p-3 row">
                            <div class="col-8">
                                <h5 class="card-title mb-0">Test</h5>
                                <p class="card-text text-sm">Create a test for this lesson</p>
                            </div>
                            <div class="col-4">
                                <img src="https://cdn-icons-png.flaticon.com/512/3068/3068553.png" class="img-fluid" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 pt-3">
                    <div class="card">
                        <div class="card-body p-3 row">
                            <div class="col-8">
                                <h5 class="card-title mb-0">Report</h5>
                                <p class="card-text text-sm">Report lesson problem to admin</p>
                            </div>
                            <div class="col-4">
                                <img src="https://cdn-icons-png.flaticon.com/512/10209/10209878.png" class="img-fluid"
                                    alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="lesson" class="mt-4">
        <h7>{{ lesson.description }}</h7>
    </div>
    <!-- <h5 class.js="mt-4">Number of terms in this lesson: {{ cardsCount.totalCards }}</h5> -->
    <div class="mt-4" v-if="relearns">
        <h6>Relearn: {{ relearns.length }}</h6>
        <div class="row mt-4">
            <div class="col-12" v-for="relearn in relearns">
                <LessonCard :title="relearn.term" :value="relearn.definition" />
            </div>
        </div>
    </div>
    <div class="mt-4" v-if="notlearns">
        <h6>Relearn: {{ notlearns.length }}</h6>
        <div class="row mt-4">
            <div class="col-12" v-for="notlearn in notlearns">
                <LessonCard :title="notlearn.term" :value="notlearn.definition" />
            </div>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonAvatar from "@/components/Argons/ArgonAvatar.vue";
import { mapActions, mapGetters } from "vuex";

export default {
    name: "lesson",
    props: ['lesson_id'],
    components: {
        LessonCard,
        ArgonButton,
        ArgonAvatar,
    },
    data() {
        return {
            id: this.$route.params.id,
            data: [],
            currentCardIndex: 0,
            currentSide: 'front',
            showSettings: false, // add showSettings data property
            relearns: null,
            notlearns: null,
            lesson_id: this.$route.params.id,
            lesson: null,
            user: JSON.parse(localStorage.getItem('user')),
        };
    },
    title() {
        return "Home - " + document.getElementsByTagName("meta")["title"].content;
    },
    beforeMount() {
        this.$store.dispatch('lesson/getLessonDetailRelearn', this.lesson_id).then(
            relearn => {
                this.relearns = relearn;
            }
        );
        this.$store.dispatch('lesson/getLessonDetailNotLearn', this.lesson_id).then(
            notlearn => {
                this.notlearns = notlearn;
            }
        );
        this.$store.dispatch('lesson/getLessonInfo', this.lesson_id).then(
            lesson => {
                this.lesson = lesson;
            }
        );

    },
    mounted() {
        // get id from params
        this.getLessons(this.id).then(data => {
            this.data = data.detail;
            document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].definition;
        });
        console.log(this.user);
    },
    methods: {
        ...mapActions({
            getLessons: 'learn/getLessons'
        }),
        nextCard() {
            if (this.currentCardIndex < this.data.length - 1) {
                this.currentCardIndex++;
            } else {
                this.currentCardIndex = 0;
            }
            document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].definition;
        },
        previousCard() {
            if (this.currentCardIndex > 0) {
                this.currentCardIndex--;
            } else {
                this.currentCardIndex = this.data.length - 1;
            }
            document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].definition;
        },
        toggleSettings() { // add toggleSettings method
            this.showSettings = !this.showSettings;
        },
        // text to speech
        speak(text) {
            const msg = new SpeechSynthesisUtterance();
            msg.text = text;
            window.speechSynthesis.speak(msg);
        },
        rotateCard(e) {
            let card = e.target.closest('.card');
            if (this.currentSide === 'front') {
                card.classList.add('rotate');
                document.getElementById('card-title').classList.add('card-title-transition');
                document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].term;
                this.currentSide = 'back';
            } else {
                card.classList.remove('rotate');
                document.getElementById('card-title').classList.remove('card-title-transition');
                document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].definition;
                this.currentSide = 'front';
            }
        },
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
                    this.$store.dispatch('lesson/deleteLesson', this.lesson_id);
                    this.$store.push({ name: 'home.index' });
                    // close modal
                    document.getElementById('edit-close').click();
                }
            });
        },
    },
    computed: {
        cardsCount() { // add cardsCount computed property
            return {
                currentCardIndex: this.currentCardIndex,
                totalCards: this.data ? this.data.length : 0
            };
        }
    }
};
</script>
<style scoped>
.dropdown .dropdown-toggle:after {
    content: '\f141' !important;
    font: normal normal normal 14px/1 FontAwesome;
    border: none;
    vertical-align: middle;
}</style>
