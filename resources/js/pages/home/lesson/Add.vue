<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="mb-0">Add New Lesson</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div>
                                <label class="form-label mt-2 fs-6">Name</label>
                                <argon-input id="name" type="text" name="name"
                                    placeholder="Enter a name. Example:'MAE Chapter 1' " />
                            </div>
                            <div>
                                <label class="form-label mt-2 fs-6">Description</label>
                                <argon-input id="description" type="text" name="description"
                                    placeholder="Add a description..." />
                            </div>
                            <div>
                                <label class="form-label mt-2 fs-6">Password</label>
                                <argon-input id="password" type="text" name="password" placeholder="Add a password..." />
                            </div>
                            <div class="col-lg-12 col-md-12 col-12 mt-4">
                                <argon-button class="fa-solid fa-file-import"></argon-button>
                            </div>
                            <div class="col-12 mt-4">
                                <div v-for="(child, index) in children">
                                    <component :is="child" :title="index + 1 + ''" :key="'setDetail' + index"
                                        @remove="this.removeCard" :data="{}"></component>
                                </div>
                            </div>
                            <div class="col-12">
                                <argon-button color="secondary" variant="gradient" full-width class="my-2 py-5 btn-lg fs-6"
                                    @click="this.addSetCard">Add Card</argon-button>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-5">
                                <argon-button color="success" variant="gradient" full-width class="my-2 btn-lg fs-6"
                                    @click="this.createLesson">Create</argon-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import SetCard from "@/pages/admin/lesson/LessonCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";

export default {
    name: "Add new lesson",
    components: {
        ArgonInput,
        SetCard,
        ArgonButton,
    },
    title() {
        return 'Add new lesson' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    data() {
        return {
            children: [SetCard, SetCard, SetCard],
            count: 3
        }
    },
    methods: {
        addSetCard() {
            this.children.push(SetCard);
            this.count++;
        },
        removeCard(id) {
            this.children.splice(id - 1, 1);
            this.count--;
        },
        createLesson() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            let password = document.getElementById('password').value;
            let setDetail = [];
            for (let i = 1; i <= this.count; i++) {
                let card = document.getElementById('card' + i);
                let term = card.getElementsByClassName('term')[0].value;
                let definition = card.getElementsByClassName('definition')[0].value;
                if (term !== '' && definition !== '') {
                    setDetail.push({ term: term, definition: definition });
                }
            }
            if (name === '' || description === '' || setDetail.length < 3) {
                alert('Please fill all fields and add at least 3 cards');
            } else {
                this.$store.dispatch('lesson/createLesson', { name: name, description: description, password: password, data: setDetail }).then(() => {
                    this.$router.push({ name: 'home.index'});
                });
            }
        }
    },
}
</script>
