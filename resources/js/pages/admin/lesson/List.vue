<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">All Lesson</h5>
                                <p class="mb-0 text-sm">List of all lesson in the system</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <router-link :to="{ name: 'admin.lesson.add' }"
                                                 class="mb-0 btn bg-gradient-success btn-sm">+&nbsp;
                                        New Lesson
                                    </router-link>
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="this.modal_import.show()">
                                        Import
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-0 pb-0 pt-0 card-body">
                        <div class="table-responsive">
                            <DataTable id="setdata" :options="{select: 'single'}" ref="table"
                                       class="table table-flush mx-3">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Username</th>
                                    <th>Is Public</th>
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
    <div id="optionModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mt-lg-10">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row justify-content-between">
                        <div class="col-6">
                            <span class="text-start fs-6 fw-bold float-start">Choose an option</span>
                        </div>
                        <div class="col-6">
                            <span class="text-end fs-6 fw-bold float-end" data-bs-dismiss="modal"><i
                                class="fa-regular fa-circle-xmark"></i></span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3 col-6">
                            <button type="button" class="w-100 btn btn-sm btn-outline-secondary">
                                View
                            </button>
                        </div>
                        <div class="col-md-3 col-6">
                            <button type="button" class="w-100 btn btn-sm btn-outline-warning" @click="this.edit">
                                Update
                            </button>
                        </div>
                        <div class="col-md-3 col-6">
                            <button type="button" class="w-100 btn btn-sm btn-outline-danger" @click="this.delete">
                                Delete
                            </button>
                        </div>
                        <div class="col-md-3 col-6">
                            <button type="button" class="w-100 btn btn-sm btn-outline-success" @click="this.export">
                                Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="importModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="ModalLabel" class="modal-title">
                        Import Set
                    </h5>
                    <i class="fa-regular fa-cloud-arrow-up ms-3"></i>
                </div>
                <form @submit.prevent="handleImport">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-5">
                                <label class="form-label mt-2">Name</label>
                                <argon-input id="name" name="name" class="mb-2"
                                             placeholder="Enter name..." required/>
                            </div>
                            <div class="col-7">
                                <label class="form-label mt-2">Description</label>
                                <argon-input id="description" name="description" class="mb-2"
                                             placeholder="Enter description..." required/>
                            </div>
                        </div>
                        <argon-switch id="importText" name="switchImport" class="mb-3" @change="this.switchImport">
                            Import from text
                        </argon-switch>
                        <div v-if="this.importType === 'file'">
                            <p>Import set from csv, xls, xlsx file ( <a
                                href="https://s3-hcm-r1.longvan.net/19403879-letlearn/template/lesson_template.xlsx"
                                target="_blank">Template</a> )</p>
                            <input type="file" placeholder="Browse file..." class="mb-3 form-control"
                                   accept=".csv, .xls, .xlsx" name="file"/>
                        </div>
                        <div v-if="this.importType === 'text'">
                            <p>Import set from text</p>
                            <argon-textarea id="raw_data" name="raw_data" class="mb-2" rows="10"
                                            placeholder="Paste text here..."/>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label mt-2">Between Term and Definition</label>
                                    <argon-input id="betweenTermDefinition" name="term_separator" class="mb-2"
                                                 placeholder="Enter text here..." value="-----"/>
                                </div>
                                <div class="col-6">
                                    <label class="form-label mt-2">Between Card</label>
                                    <argon-input id="betweenTermDefinition" name="detail_separator" class="mb-2"
                                                 placeholder="Enter text here..." value="~~~~~"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary btn-sm"
                                data-bs-dismiss="modal" id="closeImport">
                            Close
                        </button>
                        <button type="submit" class="btn bg-gradient-success btn-sm">
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-bs5';
import 'datatables.net-select';
import {mapActions} from "vuex";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonSwitch from "@/components/Argons/ArgonSwitch.vue";
import ArgonTextarea from "@/components/Argons/ArgonTextarea.vue";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import {Modal} from "bootstrap";

DataTable.use(DataTablesLib);

export default {
    name: "List",
    components: {
        ArgonButton,
        ArgonSwitch,
        ArgonTextarea,
        ArgonInput,
        DataTable
    },
    title() {
        return 'List Lesson' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    data() {
        return {
            table: null,
            lessons: [],
            importType: 'file',
            selected_id: null,
            modal_option: null,
            modal_import: null,
        };
    },
    beforeCreate() {
        this.$store.dispatch('adminLesson/index');
    },
    mounted() {
        this.table = this.$refs.table.dt();
        this.modal_option = new Modal(document.getElementById('optionModal'));
        this.modal_import = new Modal(document.getElementById('importModal'));
        // load data to table
        let delay = setInterval(() => {
            this.lessons = this.$store.getters['adminLesson/lessons'];
            if (this.lessons && this.lessons.length > 0) {
                this.table.clear();
                this.table.rows.add(this.lessons);
                this.table.draw();
                clearInterval(delay);
            }
        }, 100);
        this.table.on('select', (e, dt, type, indexes) => {
            if (type === 'row') {
                this.selected_id = this.table.rows(indexes).data()[0][0];
                this.modal_option.show();
            }
        });
        this.table.on('deselect', (e, dt, type, indexes) => {
            if (type === 'row') {
                this.selected_id = null;
            }
        });
    },
    beforeUnmount() {
        this.table.destroy(true);
        // this.modal_option.dispose();
        // this.modal_import.dispose();
    },
    methods: {
        switchImport() {
            if (this.importType === 'file') {
                this.importType = 'text';
            } else {
                this.importType = 'file';
            }
        },
        handleImport(e) {
            let new_lesson_id = 0;
            let formData = new FormData();
            formData.append('name', e.target.name.value);
            formData.append('description', e.target.description.value);
            if (this.importType === 'file') {
                formData.append('type', this.importType);
                formData.append('file', e.target.file.files[0]);
                new_lesson_id = this.$store.dispatch('adminLesson/importLesson', formData);
            } else if (this.importType === 'text') {
                formData.append('type', this.importType);
                formData.append('raw_data', e.target.raw_data.value);
                formData.append('term_separator', e.target.term_separator.value);
                formData.append('detail_separator', e.target.detail_separator.value);
                new_lesson_id = this.$store.dispatch('adminLesson/importLesson', formData);
            }
            if (new_lesson_id !== 0) {
                new_lesson_id.then((res) => {
                    if (res !== 0) {
                        this.modal_import.hide();
                        this.$root.$data.snackbar = {
                            color: 'success',
                            message: 'Imported successfully',
                        };
                        this.$router.push({name: 'admin.lesson.edit', params: {id: res}});
                    } else {
                        this.$root.$data.snackbar = {
                            color: 'danger',
                            message: 'Imported failed',
                        };
                        setTimeout(() => {
                            this.$root.$data.snackbar = null;
                        }, 2000);
                    }
                });
            } else {
                this.$root.$data.snackbar = {
                    color: 'danger',
                    message: 'Imported failed',
                };
                setTimeout(() => {
                    this.$root.$data.snackbar = null;
                }, 2000);
            }

        },
        edit() {
            this.modal_option.hide();
            this.$router.push({name: 'admin.lesson.edit', params: {id: this.selected_id}});
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
                    let id = this.selected_id;
                    this.$root.$data.snackbar = {
                        color: 'warning',
                        message: 'Deleting...',
                    };
                    this.$store.dispatch('adminLesson/deleteLesson', id).then((res) => {
                        if (res) {
                            this.table.rows({selected: true}).remove().draw();
                            this.$root.$data.snackbar = {
                                color: 'success',
                                message: 'Deleted successfully',
                            };
                        } else {
                            this.$root.$data.snackbar = {
                                color: 'danger',
                                message: 'Something went wrong',
                            };
                        }
                        this.modal_option.hide();
                        setTimeout(() => {
                            this.$root.$data.snackbar = null;
                        }, 2000);
                    });
                }
            });
        },
        export() {
            this.$root.$data.snackbar = {
                color: 'warning',
                message: 'Exporting...',
            };
            this.$store.dispatch('adminLesson/exportLesson', this.selected_id).then((res) => {
                console.log(res);
                if (res) {
                    this.$root.$data.snackbar = {
                        color: 'success',
                        message: 'Downloading file...',
                    };
                } else {
                    this.$root.$data.snackbar = {
                        color: 'danger',
                        message: 'Something went wrong',
                    };
                }
                setTimeout(() => {
                    this.$root.$data.snackbar = null;
                }, 2000);
            });
        },
    },
}
</script>
