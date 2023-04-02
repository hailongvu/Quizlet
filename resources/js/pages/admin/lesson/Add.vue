<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-0">
                        <h5 class="mb-0">Add New Lesson</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label class="form-label mt-2 fs-6">Name</label>
                                <argon-input id="name" type="text" name="name"
                                             placeholder="Enter a name. Example:'MAE Chapter 1' "/>
                            </div>
                            <div class="col-md-8 col-12">
                                <label class="form-label mt-2 fs-6">Description</label>
                                <argon-input id="description" type="text" name="description"
                                             placeholder="Add a description..."/>
                            </div>
                            <div class="col-12">
                                <div v-for="(child, index) in children">
                                    <component :is="child" :title="index+1 + ''" :key="'setDetail'+index"
                                               @remove="this.removeCard" :data="{}"></component>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="col-12">
                                    <argon-button color="secondary" variant="gradient" full-width
                                                  class="my-2 py-4 btn-lg fs-6" @click="this.addCard">Add Card
                                    </argon-button>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-5">
                                <argon-button color="success" variant="gradient" full-width class="my-2 btn-lg fs-6"
                                              @click="this.createLesson">Create
                                </argon-button>
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
import {markRaw} from "vue";

export default {
    name: "List",
    components: {
        ArgonInput,
        SetCard,
        ArgonButton,
    },
    title() {
        return 'Add Lesson' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    data() {
        return {
            children: markRaw([SetCard, SetCard, SetCard]),
            count: 3
        }
    },
    methods: {
        addCard() {
            // add new card
            this.children = markRaw([...this.children, SetCard]);
            this.count++;
        },
        removeCard(data) {
            this.children = markRaw(this.children.filter((child, index) => index !== data.index - 1));
            this.count--;
        },
        createLesson() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            let detail = [];
            for (let i = 1; i <= this.count; i++) {
                let card = document.getElementById('card' + i);
                let term = card.getElementsByClassName('term')[0].value;
                let definition = card.getElementsByClassName('definition')[0].value;
                if (term !== '' && definition !== '') {
                    detail.push({term: term, definition: definition});
                }
            }
            if (name === '' || description === '' || detail.length < 3) {
                this.$root.$data.snackbar = {
                    color: 'warning',
                    message: 'Please fill in all fields and add at least 3 cards.',
                };
            } else {
                this.$store.dispatch('adminLesson/create', {
                    name: name,
                    description: description,
                    detail: detail
                }).then((res) => {
                    if(res !== 0){
                        this.$root.$data.snackbar = {
                            color: 'success',
                            message: 'Lesson created successfully',
                        };
                        this.$router.push({name: 'admin.lesson.edit', params: {id: res}});
                    }else{
                        this.$root.$data.snackbar = {
                            color: 'danger',
                            message: 'Something went wrong. Please try again.',
                        };
                    }
                });
            }
        }
    },
}
</script>
