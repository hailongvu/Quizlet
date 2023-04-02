<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">All User</h5>
                                <p class="mb-0 text-sm">List of all user in the system</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button"
                                            class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#userModal">
                                        + Add User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-0 pb-0 pt-0 card-body">
                        <div class="table-responsive">
                            <DataTable id="user_data" :options="{select: 'single'}" ref="table"
                                       class="table table-flush mx-3">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Role</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Dob</th>
                                    <th>Status</th>
                                    <th>Updated At</th>
                                </tr>
                                </thead>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="userModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mt-lg-10 modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="edit-title" class="modal-title">
                        Add User
                    </h5>
                    <i class="fa-solid fa-option ms-3"></i>
                </div>
                <form @submit.prevent="handleForm" id="add_user">
                    <div class="modal-body">
                        <p>In this form you can create new user</p>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Username ( nullable )</label>
                                <argon-input id="username" name="username" type="text" placeholder="Username"/>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Date of birth</label>
                                <argon-input id="dob" type="date" name="dob" required/>
                            </div>
                            <div class="col-7">
                                <label class="form-label">Email</label>
                                <argon-input id="email" type="email" name="email" placeholder="Email" required/>
                            </div>
                            <div class="col-5">
                                <label class="form-label">Role</label>
                                <select
                                    id="role_ele"
                                    name="role"
                                    class="form-control"
                                >
                                </select>
                            </div>
                            <div class="col-6" v-if="type==='update'">
                                <label class="form-label">Status</label>
                                <select
                                    id="status"
                                    name="status"
                                    class="form-control"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-6" v-if="type==='update'">
                                <label class="form-label">Email verified at</label>
                                <argon-input id="email_verified_at" type="datetime-local" name="email_verified_at"
                                             required/>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Password</label>
                                <argon-input id="password" name="password" type="password" required/>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Password confirmation</label>
                                <argon-input id="password_confirmation" name="password_confirmation" type="password"
                                             required/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-gradient-success btn-sm">
                            Add
                        </button>
                        <button type="button" class="btn bg-gradient-primary btn-sm" id="edit-close"
                                data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="optionModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="edit-title" class="modal-title">
                        Option
                    </h5>
                    <i class="fa-solid fa-option ms-3"></i>
                </div>
                <div class="modal-body">
                    <p>You can select option for this Set</p>
                    <div class="row">
                        <div class="col-6">
                            <button type="button"
                                    class="w-100 mx-1 mb-0 btn bg-gradient-warning btn-sm" @click="this.update">
                                Update
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button"
                                    class="w-100 mx-1 mb-0 btn bg-gradient-danger btn-sm" @click="this.destroy">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-primary btn-sm" id="close-option"
                            data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-bs5';
import 'datatables.net-select';
import {mapActions, mapGetters} from "vuex";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import {Modal} from "bootstrap";
import Choices from "choices.js";

DataTable.use(DataTablesLib);

export default {
    name: "User",
    components: {
        ArgonButton,
        ArgonInput,
        DataTable
    },
    title() {
        return 'List User' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    data() {
        return {
            table: null,
            row: null,
            type: 'add',
            choices_role: null,
        };
    },
    mounted() {
        this.table = this.$refs.table.dt();
        this.handleClickRow();
        this.index().then((response) => {
            this.table.rows.add(response).draw();
        });
        this.role().then((response) => {
            this.choices_role = new Choices('#role_ele', {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true,
            });
            let roles = [];
            response.forEach((role) => {
                roles.push({
                    value: role[0],
                    label: role[1],
                });
            });
            this.choices_role.setChoices(roles, 'value', 'label', true);
            // lesson select default
            this.choices_role.setChoiceByValue(3);
        });
        document.getElementById('userModal').addEventListener('hidden.bs.modal', () => {
            this.type = 'add';
            document.getElementById('username').value = '';
            document.getElementById('email').value = '';
            this.choices_role.setChoiceByValue(3);
            document.getElementById('status') ? document.getElementById('status').value = 'active' : '';
            document.getElementById('email_verified_at') ? document.getElementById('email_verified_at').value = '' : '';
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        });
    },
    methods: {
        ...mapActions({
            index: 'adminUser/index',
            create: 'adminUser/create',
            delete: 'adminUser/destroy',
            getUserById: 'adminUser/getUserById',
            update_user: 'adminUser/update',
            role: 'adminRole/index'
        }),
        ...mapGetters({
            permissions: 'account/permissions',
        }),
        checkPermission(name) {
            return this.permissions().some(permission => permission.name === name);
        },
        handleClickRow() {
            document.getElementById('user_data').addEventListener('click', () => {
                // get id
                this.selected_id = this.table.rows({selected: true}).data().toArray()[0][0];
                // show modal
                let myModal = new Modal(document.getElementById('optionModal'));
                myModal.show();
            });
        },
        handleForm() {
            let username = document.getElementById('username').value;
            let dob = document.getElementById('dob').value;
            let email = document.getElementById('email').value;
            let role = this.choices_role.getValue(true);
            let password = document.getElementById('password').value;
            let password_confirmation = document.getElementById('password_confirmation').value;
            if (this.type === 'add') {
                if (dob === '' || email === '' || role === '' || password === '' || password_confirmation === '') {
                    alert('Please fill all fields');
                } else {
                    this.create({
                        username: username,
                        date_of_birth: dob,
                        email: email,
                        role_id: role,
                        password: password,
                        password_confirmation: password_confirmation,
                    }).then((response) => {
                        this.table.row.add(response).draw();
                    });
                }
            } else {
                let status = document.getElementById('status').value;
                let email_verified_at = document.getElementById('email_verified_at').value;
                if (username === '' || dob === '' || email === '' || role === '' || status === '' || email_verified_at === '') {
                    alert('Please fill all fields');
                } else {
                    this.update_user({
                        id: this.table.rows({selected: true}).data().toArray()[0][0],
                        username: username,
                        date_of_birth: dob,
                        email: email,
                        role_id: role,
                        status: status,
                        email_verified_at: email_verified_at,
                        password: password,
                        password_confirmation: password_confirmation,
                    }).then((response) => {
                        this.table.row({selected: true}).data(response).draw();
                    }).then(() => {
                        document.getElementById('password').setAttribute('required', '');
                        document.getElementById('password_confirmation').setAttribute('required', '');
                        document.getElementById('edit-close').click();
                    });
                }
            }
        },
        destroy() {
            let id = this.table.rows({selected: true}).data().toArray()[0][0];
            this.delete(id).then(() => {
                this.table.row({selected: true}).remove().draw();
                document.getElementById('close-option').click();
            });
        },
        update() {
            this.type = 'update';
            let id = this.table.rows({selected: true}).data().toArray()[0][0];
            document.getElementById('close-option').click();
            // get data
            this.getUserById(id).then((response) => {
                document.getElementById('username').value = response.username;
                document.getElementById('dob').value = response.date_of_birth;
                document.getElementById('email').value = response.email;
                this.choices_role.setChoiceByValue(response.role_id);
                document.getElementById('status').value = response.status;
                document.getElementById('email_verified_at').value = (new Date(response.email_verified_at)).toISOString().slice(0, 16).replace("T", " ");
                document.getElementById('password').removeAttribute('required');
                document.getElementById('password_confirmation').removeAttribute('required');
            });
            // show modal
            let myModal = new Modal(document.getElementById('userModal'));
            myModal.show();
        }
    },
}
</script>
