<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card mb-3">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Course Info</h5>
                                <p class="mb-0 text-sm">In this section you can update course infomation</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button class="mb-0 btn btn-outline-success btn-sm" @click="this.update">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" v-if="this.data">
                            <div class="col-md-4 col-12">
                                <label class="form-label mt-2 fs-6">Name</label>
                                <argon-input id="name" type="text" name="name"
                                             placeholder="Enter a name, like:'MAE Chapter 1'"
                                             :value="this.data.name" @input="input"/>
                            </div>
                            <div class="col-md-8 col-12">
                                <label class="form-label mt-2 fs-6">Description</label>
                                <argon-input id="description" type="text" name="description"
                                             placeholder="Add a description..." :value="this.data.description"
                                             @input="input"/>
                            </div>
                            <div class="col-md-4 col-6">
                                <label class="form-label mt-2 fs-6">Status</label>
                                <argon-switch id="status" name="status" class="mb-3"
                                              :checked="this.data.status === 'active'" @input="input">
                                    Active
                                </argon-switch>
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label mt-2 fs-6">Public</label>
                                <argon-switch id="is_public" name="is_public" class="mb-3"
                                              :checked="this.data.is_public" @input="input">
                                    Public
                                </argon-switch>
                            </div>
                            <div v-if="!this.data.is_public" class="col-md-6 col-12">
                                <label class="form-label mt-2 fs-6">Password (option)</label>
                                <argon-input id="password" type="password" name="name"
                                             placeholder="Enter passord for this set"
                                             :value="this.data.password" @input="input"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Lesson in this course</h5>
                                <p class="mb-0 text-sm">In this section you can manage lesson of this course</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button v-if="selected_id.length > 0"
                                            class="mb-0 me-3 btn btn-outline-danger btn-sm"
                                            @click="this.removeLesson">-&nbsp;
                                        Remove
                                    </button>
                                    <button class="mb-0 btn btn-outline-success btn-sm"
                                            @click="()=>{
                                                this.find_lesson.clearStore();
                                                this.modal_find_lesson.show();
                                            }">
                                        +&nbsp;
                                        Add Lesson
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <DataTable id="lesson_data" :options="{select: 'multi'}" ref="lesson_table"
                                       class="table table-flush">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Username</th>
                                    <th>Public</th>
                                    <th>Updated At</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modalFindLesson" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="ModalLabel" class="modal-title">
                        Find lesson
                    </h5>
                    <i class="fa-regular fa-cloud-arrow-up ms-3"></i>
                </div>
                <div class="modal-body">
                    <label class="form-label mt-2">Find lesson by name, description</label>
                    <select
                        id="keyword"
                        name="keyword"
                        class="form-control"
                        multiple
                    >
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-success btn-sm" id="btnSubmit" @click="addLesson">
                        Add
                    </button>
                    <button type="button" class="btn bg-gradient-secondary btn-sm"
                            data-bs-dismiss="modal" id="closeImport">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonSwitch from "@/components/Argons/ArgonSwitch.vue";
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-bs5';
import 'datatables.net-select';
import {Modal} from "bootstrap";
import Choices from "choices.js";

DataTable.use(DataTablesLib);
export default {
    name: "edit",
    components: {
        ArgonInput,
        ArgonButton,
        ArgonSwitch,
        DataTable
    },
    data() {
        return {
            id: this.$route.params.id,
            data: null,
            lesson_table: null,
            selected_id: [],
            modal_find_lesson: null,
            find_lesson: null,
            unsubscribe: null
        }
    },
    beforeMount() {
        this.$store.dispatch('adminCourse/getCourse', this.id);
    },
    mounted() {
        this.$nextTick(() => {
            this.init();
        });
    },
    beforeUnmount() {
        this.lesson_table.destroy();
        // stop the subscription
        this.unsubscribe();
    },
    methods: {
        init() {
            this.lesson_table = this.$refs.lesson_table.dt();
            this.modal_find_lesson = new Modal(document.getElementById('modalFindLesson'));
            this.find_lesson = new Choices('#keyword', {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                allowHTML: true,
                addItems: true,
                searchResultLimit: 10,
            });
            this.unsubscribe = this.$store.subscribe((mutation, state) => {
                if (mutation.type === 'adminCourse/indexSuccess') {
                    this.data = state.adminCourse.courses;
                    let lessons = this.data.lessons.map(item => {
                        return [
                            item.id,
                            item.name,
                            item.description,
                            item.user.username,
                            item.is_public ? 'Yes' : 'No',
                            new Date(item.updated_at).toLocaleString(),
                            item.status
                        ]
                    });
                    this.lesson_table.rows.add(lessons).draw();
                }
            });
            this.lesson_table.on('select', (e, dt, type, indexes) => {
                if (type === 'row') {
                    let id = this.lesson_table.rows(indexes).data()[0][0];
                    this.selected_id.push(id);
                }
            });
            this.lesson_table.on('deselect', (e, dt, type, indexes) => {
                if (type === 'row') {
                    let id = this.lesson_table.rows(indexes).data()[0][0];
                    this.selected_id = this.selected_id.filter(item => item !== id);
                }
            });
            this.find_lesson.passedElement.element.addEventListener('search', this.searchLesson);
        },
        publicChange() {
            this.data.is_public = !this.data.is_public;
        },
        input(e) {
            if (e.target.type === 'checkbox') {
                if (e.target.name === "status") this.data[e.target.name] = e.target.checked ? 'active' : 'inactive';
                else this.data[e.target.name] = e.target.checked;
            } else {
                this.data[e.target.name] = e.target.value;
            }
        },
        update() {
            this.$root.$data.snackbar = {
                color: 'warning',
                message: 'Updating course...',
            };
            this.$store.dispatch('adminCourse/update', this.data).then((res) => {
                if (res) {
                    this.$root.$data.snackbar = {
                        color: 'success',
                        message: 'Update course success!',
                    };
                } else {
                    this.$root.$data.snackbar = {
                        color: 'danger',
                        message: 'Update course failed!',
                    };
                }
                setTimeout(() => {
                    this.$root.$data.snackbar = null;
                }, 2000);
            });
        },
        searchLesson(e) {
            clearTimeout(this.find_lesson.timer);
            this.find_lesson.timer = setTimeout(() => {
                this.$store.dispatch('adminCourse/searchLesson', e.detail.value).then((res) => {
                    if (res) {
                        this.find_lesson.setChoices(res.map(item => {
                            return {
                                value: item[0],
                                label: item[1] + ' - ' + item[2],
                                selected: false,
                                disabled: false,
                            }
                        }), 'value', 'label', true);
                    }
                });
            }, 500);
        },
        addLesson(){
            let ids = this.find_lesson.getValue(true);
            if(ids.length > 0){
                this.$root.$data.snackbar = {
                    color: 'warning',
                    message: 'Adding lesson...',
                };
                this.$store.dispatch('adminCourse/addLesson', {type: 'add_lesson', course_id: this.id, lesson_ids: ids}).then((res) => {
                    if (res.length > 0) {
                        this.$root.$data.snackbar = {
                            color: 'success',
                            message: 'Add lesson success!',
                        };
                        let lessons = res.map(item => {
                            return [
                                item[0],
                                item[1],
                                item[2],
                                item[3],
                                item[4] ? 'Yes' : 'No',
                                new Date(item[5]).toLocaleString(),
                                item[6]
                            ]
                        });
                        this.lesson_table.rows().remove().draw();
                        // add new data
                        this.lesson_table.rows.add(lessons).draw();
                        this.modal_find_lesson.hide();
                    } else {
                        this.$root.$data.snackbar = {
                            color: 'danger',
                            message: 'Add lesson failed!',
                        };
                    }
                    setTimeout(() => {
                        this.$root.$data.snackbar = null;
                    }, 3000);
                });
            }else{
                this.$root.$data.snackbar = {
                    color: 'danger',
                    message: 'Please select lesson!',
                };
                setTimeout(() => {
                    this.$root.$data.snackbar = null;
                }, 3000);
            }
        },
        removeLesson(){
            if(this.selected_id.length > 0){
                this.$root.$data.snackbar = {
                    color: 'warning',
                    message: 'Removing lesson...',
                };
                this.$store.dispatch('adminCourse/removeLesson', {type: 'remove_lesson', course_id: this.id, lesson_ids: this.selected_id}).then((res) => {
                    if (res) {
                        this.$root.$data.snackbar = {
                            color: 'success',
                            message: 'Remove lesson success!',
                        };
                        // remove selected row
                        this.lesson_table.rows('.selected').remove().draw();
                    } else {
                        this.$root.$data.snackbar = {
                            color: 'danger',
                            message: 'Remove lesson failed!',
                        };
                    }
                    setTimeout(() => {
                        this.$root.$data.snackbar = null;
                    }, 2000);
                });
            }else{
                this.$root.$data.snackbar = {
                    color: 'danger',
                    message: 'Please select lesson!',
                };
                setTimeout(() => {
                    this.$root.$data.snackbar = null;
                }, 3000);
            }
        }
    }
}
</script>
