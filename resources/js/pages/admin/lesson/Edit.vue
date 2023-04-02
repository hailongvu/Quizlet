<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-0">
                        <h5 class="mb-0">Update lesson</h5>
                        <p class="mb-0 text-sm">Update lesson and data</p>
                    </div>
                    <div class="card-body">
                        <div class="row" v-if="this.data">
                            <div class="col-md-4 col-12">
                                <label class="form-label mt-2 fs-6">Name</label>
                                <argon-input id="name" type="text" name="name"
                                             placeholder="Enter a name, like:'MAE Chapter 1'"
                                             :value="this.data.lesson.name"/>
                            </div>
                            <div class="col-md-8 col-12">
                                <label class="form-label mt-2 fs-6">Description</label>
                                <argon-input id="description" type="text" name="description"
                                             placeholder="Add a description..." :value="this.data.lesson.description"/>
                            </div>
                            <div class="col-4">
                                <label class="form-label mt-2 fs-6">Status</label>
                                <argon-switch id="status" name="status" class="mb-3"
                                              :checked="this.data.lesson.status === 'active'">
                                    Active
                                </argon-switch>
                            </div>
                            <div class="col-2">
                                <label class="form-label mt-2 fs-6">Public</label>
                                <argon-switch id="is_public" name="is_public" class="mb-3"
                                              :checked="this.data.lesson.is_public" @change="publicChange">
                                    Public
                                </argon-switch>
                            </div>
                            <div v-if="password_option" class="col-6">
                                <label class="form-label mt-2 fs-6">Password (option)</label>
                                <argon-input id="password" type="password" name="name"
                                             placeholder="Enter passord for this set"
                                             :value="this.data.lesson.password"/>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="d-lg-flex">
                                    <div>
                                        <h6 class="mb-0">Detail</h6>
                                        <p class="mb-0 text-sm">Detail in this lesson</p>
                                    </div>
                                    <div class="my-auto mt-4 ms-auto mt-lg-0">
                                        <div class="my-auto ms-auto">
                                            <button type="button"
                                                    class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                                    @click="this.switch">
                                                Switch all term and definition
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-for="(child, index) in children">
                                    <component :is="child" :title="index+1 + ''" :key="'setDetail'+index"
                                               @remove="this.removeCard"
                                               :data="{
                                                    id: data.detail[index]?data.detail[index].id:0,
                                                   term: data.detail[index]?data.detail[index].term:'',
                                                   definition: data.detail[index]?data.detail[index].definition:'' }"
                                    ></component>
                                </div>
                            </div>
                            <div class="col-12">
                                <argon-button color="secondary" variant="gradient" full-width
                                              class="my-2 py-4 btn-lg fs-6" @click="this.addCard">Add Card
                                </argon-button>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-5">
                                <argon-button color="success" variant="gradient" full-width class="my-2 btn-lg fs-6"
                                              @click="this.updateSet">Update
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
import ArgonSwitch from "@/components/Argons/ArgonSwitch.vue";
import {markRaw} from "vue";

export default {
    name: "List",
    components: {
        ArgonInput,
        SetCard,
        ArgonButton,
        ArgonSwitch
    },
    data() {
        return {
            children: markRaw([]),
            count: 0,
            data: null,
            password_option: false,
            lesson_id: this.$route.params.id,
            delete_id: []
        }
    },
    title() {
        let name = this.data ? this.data.lesson.name : 'Lesson';
        return 'Edit ' + name + '  - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    beforeMount() {
        this.$store.dispatch('adminLesson/getLesson', this.lesson_id).then((res) => {
            this.data = res;
            this.password_option = !this.data.lesson.is_public;
            for (let i = 0; i < this.data.detail.length; i++) {
                this.children.push(SetCard);
                this.count++;
            }
        });
    },
    methods: {
        addCard() {
            // add new card
            this.children = markRaw([...this.children, SetCard]);
            this.count++;
        },
        removeCard(data) {
            this.data.detail = this.data.detail.filter((child, index) => index !== data.index - 1);
            this.children = markRaw(this.children.filter((child, index) => index !== data.index - 1));
            this.count--;
            if (data.id !== 0) {
                this.delete_id.push(data.id);
            }
        },
        publicChange() {
            this.password_option = !this.password_option;
        },
        updateSet() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            let status = document.getElementById('status').checked ? 'active' : 'inactive';
            let is_public = document.getElementById('is_public').checked ? 1 : 0;
            let password = document.getElementById('password') ? document.getElementById('password').value : '';
            let lessonDetail = [];
            for (let i = 1; i <= this.count; i++) {
                let card = document.getElementById('card' + i);
                let term = card.getElementsByClassName('term')[0].value;
                let definition = card.getElementsByClassName('definition')[0].value;
                let id = card.getElementsByClassName('id')[0].value;
                if (term !== '' && definition !== '') {
                    lessonDetail.push({
                        id: id,
                        term: term,
                        definition: definition
                    });
                }
            }
            if (name === '' || description === '' || lessonDetail.length < 3) {
                alert('Please fill all fields and add at least 3 cards');
            } else {
                this.$root.$data.snackbar = {
                    color: 'warning',
                    message: 'Updating lesson...',
                };
                this.$store.dispatch('adminLesson/updateLesson', {
                    id: this.lesson_id,
                    name: name,
                    description: description,
                    status: status,
                    is_public: is_public,
                    password: password,
                    data: {
                        lessonDetail: lessonDetail,
                        delete_id: this.delete_id
                    }
                }).then((res) => {
                    if (res) {
                        this.$root.$data.snackbar = {
                            color: 'success',
                            message: 'Update lesson successfully!',
                        };
                    } else {
                        this.$root.$data.snackbar = {
                            color: 'danger',
                            message: 'Update lesson failed!',
                        };
                    }
                    setTimeout(() => {
                        this.$root.$data.snackbar = null;
                    }, 3000);
                });
            }
        },
        switch(){
            // switch term and definition
            for (let i = 1; i <= this.count; i++) {
                let card = document.getElementById('card' + i);
                let term = card.getElementsByClassName('term')[0].value;
                let definition = card.getElementsByClassName('definition')[0].value;
                card.getElementsByClassName('term')[0].value = definition;
                card.getElementsByClassName('definition')[0].value = term;
            }
        }
    },
}
</script>
