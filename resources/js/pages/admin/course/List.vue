<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">All Courses</h5>
                                <p class="mb-0 text-sm">List of all Courses in the system</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="this.modal_add.show()">
                                       + Add Course
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-0 pb-0 pt-0 card-body">
                        <div class="table-responsive">
                            <DataTable id="folder_data" :options="{select: 'single'}" ref="course_table"
                                       class="table table-flush mx-3">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Username</th>
                                    <th>Public</th>
                                    <th>Created At</th>
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
    <div id="addCourseModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalTitle" class="modal-title">
                        Add Course
                    </h5>
                    <i class="fa-solid fa-folder-plus ms-3"></i>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-5">
                            <label class="form-label mt-2">Name</label>
                            <argon-input id="add_name" name="name" class="mb-2"
                                         placeholder="Enter name..." required/>
                        </div>
                        <div class="col-7">
                            <label class="form-label mt-2">Description</label>
                            <argon-input id="add_description" name="description" class="mb-2"
                                         placeholder="Enter description..." required/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary btn-sm"
                            data-bs-dismiss="modal" id="close_model">
                        Close
                    </button>
                    <button type="button" class="btn bg-gradient-success btn-sm" id="btn-submit" @click="add">
                        Add
                    </button>
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
                        <div class="col-4">
                            <button type="button" class="w-100 btn btn-sm btn-outline-secondary">
                                View
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="w-100 btn btn-sm btn-outline-warning" @click="this.edit">
                                Update
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="w-100 btn btn-sm btn-outline-danger" @click="this.delete">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
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
    name: "Folder List",
    components: {
        ArgonButton,
        ArgonSwitch,
        ArgonTextarea,
        ArgonInput,
        DataTable
    },
    title() {
        return 'List Course' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    data() {
        return {
            table: null,
            folder: [],
            password_option: false,
            selected_id: null,
            modal_add: null,
            modal_option: null,
        };
    },
    beforeMount() {
        this.$store.dispatch('adminCourse/index');
    },
    mounted() {
        this.$nextTick(() => {
            this.init();
        });
    },
    methods: {
        init(){
            this.table = this.$refs.course_table.dt();
            this.modal_add = new Modal(document.getElementById('addCourseModal'));
            this.modal_option = new Modal(document.getElementById('optionModal'));
            let getCourses = setInterval(() => {
                this.lessons = this.$store.getters['adminCourse/courses'];
                if (this.lessons && this.lessons.length > 0) {
                    this.table.clear();
                    this.table.rows.add(this.lessons);
                    this.table.draw();
                    clearInterval(getCourses);
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
        edit(){
            this.modal_option.hide();
            this.$router.push({name: 'admin.course.edit', params: {id: this.selected_id}});
        },
        delete(){
            this.$swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$store.dispatch('adminCourse/delete', this.selected_id).then((res) => {
                        if(res){
                            this.$swal('Deleted!', 'Your file has been deleted.', 'success');
                            this.table.row({selected: true}).remove().draw(false);
                            this.modal_option.hide();
                        }else{
                            this.$swal('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        },
        add(){
            let data = {
                name: document.getElementById('add_name').value,
                description: document.getElementById('add_description').value,
            };
            this.$store.dispatch('adminCourse/addCourse', data).then((res) => {
                if(res){
                    this.$swal('Success!', 'Course has been added.', 'success');
                    this.modal_add.hide();
                    this.table.row.add(res).draw(false);
                }else{
                    this.$swal('Error!', 'Something went wrong.', 'error');
                }
            });
        },
    },
}
</script>
