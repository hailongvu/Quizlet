<template>
    <div class="card" id="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-subtitle text-muted">{{ cardsCount.currentCardIndex + 1 }} /
                        {{ cardsCount.totalCards }}</h6>
                </div>
                <div @click="speak">
                    <i class="fa-solid fa-volume fs-4"></i>
                </div>
            </div>
        </div>
        <div class="card-body" style="height: 50vh; max-height: 70vh;" @click="rotateCard">
            <div class="h-100 d-flex justify-content-center align-items-center">
                <p class="card-text fs-4" id="text"></p>
            </div>
        </div>
        <h5 class="card-footer"></h5>
    </div>
    <div class="mt-3 mx-1 row justify-content-between">
        <button class="btn btn-outline-warning col-sm-3 col-5" @click="previousCard">Previous</button>
        <button class="btn btn-outline-success col-sm-3 col-5" @click="nextCard">Next</button>
    </div>
</template>


<script>
import { mapActions, mapGetters } from "vuex";

export default {
    name: "Flashcards",
    data() {
        return {
            id: this.$route.params.id,
            data: [],
            currentCardIndex: 0,
            currentSide: "front",
            showSettings: false // add showSettings data property
        };
    },
    mounted() {
        // get id from params
        this.getLessons(this.id).then(data => {
            this.data = data.detail;
            this.updateTitle(data.lesson.name);
            document.getElementById("text").innerHTML = this.data[this.currentCardIndex].definition;
        });
    },
    methods: {
        ...mapActions({
            getLessons: "learn/getLessons"
        }),
        updateTitle(title) {
            this.$emit("change-title", title);
        },
        nextCard() {
            console.log(this.currentCardIndex);
            if (this.currentCardIndex < this.data.length-1) {
                this.currentCardIndex++;
                this.$emit("change-progress", Math.round((this.currentCardIndex / this.data.length) * 100));
            } else {
                this.currentCardIndex = 0;
                this.$emit("change-progress", 0);
            }
            document.getElementById("text").innerHTML = this.data[this.currentCardIndex].definition;
        },
        previousCard() {
            if (this.currentCardIndex > 0) {
                this.currentCardIndex--;
                this.$emit("change-progress", Math.round((this.currentCardIndex / this.data.length) * 100));
            } else {
                this.currentCardIndex = this.data.length - 1;
            }
            document.getElementById("text").innerHTML = this.data[this.currentCardIndex].definition;
        },
        toggleSettings() { // add toggleSettings method
            this.showSettings = !this.showSettings;
        },
        // text to speech
        speak() {
            const msg = new SpeechSynthesisUtterance();
            msg.text = document.getElementById("text").innerHTML;
            window.speechSynthesis.speak(msg);
        },
        rotateCard(e) {
            let card = document.getElementById("card");
            if (this.currentSide === "front") {
                card.classList.add("rotate");
                document.getElementById("text").innerHTML = this.data[this.currentCardIndex].term;
                this.currentSide = "back";
            } else {
                card.classList.remove("rotate");
                document.getElementById("text").innerHTML = this.data[this.currentCardIndex].definition;
                this.currentSide = "front";
            }
        }
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
.card{
    transition: all 0.3s;
    transform-style: preserve-3d;
}
.rotate {
    transform: rotateY(360deg);
}
</style>
