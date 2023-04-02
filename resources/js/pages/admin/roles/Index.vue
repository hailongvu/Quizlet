<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-lg-3">
                <div class="card position-sticky top-1 mt-4">
                    <button type="button"
                            class="mx-3 mb-0 mt-3 btn btn-outline-success" @click="showFormAddRole">
                        + New Role
                    </button>
                    <hr class="mb-1">
                    <ul class="nav flex-column bg-white border-radius-lg p-2">
                        <li v-for="role in roles"
                            :class="'nav-item d-flex justify-content-between align-items-center px-3 py-1 role' + [ getRoute() === role[1] ? ' active ' : '']"
                            :id="'role-'+role[0]">
                            <a class="text-body p-0" :href="'#' + role[1]" @click="getRoleUser(role[0])">
                                <span class="fs-6 fw-bold">{{ role[1] }}</span>
                                <p class="fs-6 m-0">{{ role[2] }}</p>
                            </a>
                            <i class="fa-regular fa-gear fs-5"
                               @click="showFormEditRole(role[0])"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9 mt-lg-0 mt-4">
                <div id="basic-info" class="card mt-4">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Role Users</h5>
                                <p class="mb-0 text-sm">List all user assigned to this role</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div v-if="this.current_role" class="my-auto ms-auto">
                                    <button type="button"
                                            class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="this.user_modal.show()">
                                        + Assign Users
                                    </button>
                                    <button type="button"
                                            class="mx-1 mb-0 btn btn-outline-danger btn-sm" @click="this.unassignUser">
                                        Unassign Users
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-0 pb-0 pt-0 card-body">
                        <div class="table-responsive">
                            <DataTable id="user_data" :options="{select: true}" ref="table"
                                       class="table table-flush mx-3">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
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
    <div id="roleModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="role-title" class="modal-title">
                        Option
                    </h5>
                    <i class="fa-solid fa-option ms-3"></i>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label mt-2">Name</label>
                            <argon-input id="name" type="text" name="name" placeholder="Role name" />
                        </div>
                        <div class="col-12">
                            <label class="form-label mt-2">Description</label>
                            <argon-input id="description" type="text" name="description"
                                         placeholder="Role description" />
                        </div>
                        <div class="col-12">
                            <label class="form-label mt-2">Permission</label>
                            <select
                                class="form-control"
                                data-trigger
                                name="permission"
                                id="permission"
                                multiple
                            >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-success btn-sm" id="role-submit">
                        Submit
                    </button>
                    <button v-if="this.mode === 'edit' && checkPermission('admin.roles.delete')" type="button"
                            class="btn bg-gradient-danger btn-sm"
                            id="role-delete" @click="deleteRole">
                        Delete
                    </button>
                    <button type="button" class="btn bg-gradient-primary btn-sm" id="close-option"
                            data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="roleUserModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="role-title" class="modal-title">
                        Find User
                    </h5>
                    <i class="fa-regular fa-magnifying-glass ms-3"></i>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label mt-2">Find by username or email</label>
                            <select
                                class="form-control"
                                data-trigger
                                name="search_user"
                                id="search_user"
                            >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-success btn-sm" id="role-assign" @click="assignUser">
                        Add
                    </button>
                    <button type="button" class="btn bg-gradient-primary btn-sm" id="close-assign"
                            data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import DataTable from "datatables.net-vue3";
import DataTablesLib from "datatables.net-bs5";
import "datatables.net-select";
import { mapActions, mapGetters } from "vuex";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import { Modal } from "bootstrap";
import Choices from "choices.js";

DataTable.use(DataTablesLib);

export default {
    name: "Roles Index",
    components: {
        DataTable,
        ArgonButton,
        ArgonInput
    },
    title() {
        return "Roles Settings" + " - " + document.querySelector("meta[name=\"title\"]").getAttribute("content");
    },
    data() {
        return {
            roles: null,
            table: null,
            role_modal: null,
            user_modal: null,
            permission_select: null,
            mode: "create",
            id: null,
            current_role: null,
            search_user: null
        };
    },
    methods: {
        getRoute() {
            return window.location.hash.replace("#", "");
        },
        ...mapActions({
            getRole: "adminRole/index",
            getRoleUsers: "adminRole/getRoleUsers",
            getRoleInfo: "adminRole/getRoleInfo",
            getAllPermissions: "adminRole/getAllPermission",
            searchUsers: "adminRole/searchUsers"
        }),
        ...mapGetters({
            permissions: "account/permissions"
        }),
        checkPermission(name) {
            return this.permissions().some(permission => permission.name === name);
        },
        getRoleUser(id) {
            this.current_role = id;
            this.getRoleUsers(id).then((response) => {
                this.table.clear();
                this.table.rows.add(response);
                this.table.draw();
            });
        },
        showFormEditRole(id) {
            this.mode = "edit";
            document.getElementById("role-title").innerHTML = "Edit Role";
            document.getElementById("role-submit").innerHTML = "Update";
            // remove all selected option
            this.permission_select.removeActiveItems();
            this.role_modal.show();
            this.getRoleInfo(id).then((response) => {
                document.getElementById("name").value = response.role[1];
                document.getElementById("description").value = response.role[2];
                response.permissions.forEach((permission) => {
                    this.permission_select.setChoiceByValue(permission[0]);
                });
            });
            this.id = id;
        },
        showFormAddRole() {
            this.mode = "create";
            document.getElementById("role-title").innerHTML = "Add Role";
            document.getElementById("name").value = "";
            document.getElementById("description").value = "";
            document.getElementById("role-submit").innerHTML = "Submit";
            // remove all selected option
            this.permission_select.removeActiveItems();
            this.role_modal.show();
        },
        handleRoleSubmit() {
            if (this.mode === "create") {
                this.addRole();
            } else if (this.mode === "edit") {
                this.updateRole();
            }
        },
        addRole() {
            let name = document.getElementById("name").value;
            let description = document.getElementById("description").value;
            let permissions = this.permission_select.getValue(true);
            let data = {
                name: name,
                description: description,
                permissions: permissions
            };
            if (name === "" || description === "" || permissions.length === 0) {
                alert("Please fill all fields and select at least one permission");
            } else {
                this.$store.dispatch("adminRole/addRole", data).then((response) => {
                    this.role_modal.hide();
                    this.roles.push(response);
                });
            }
        },
        updateRole() {
            let name = document.getElementById("name").value;
            let description = document.getElementById("description").value;
            let permissions = this.permission_select.getValue(true);
            let data = {
                name: name,
                description: description,
                permissions: permissions
            };
            if (this.id === 1) {
                alert("You can not edit this role");
            } else if (name === "" || description === "" || permissions.length === 0) {
                alert("Please fill all fields and select at least one permission");
            } else {
                this.$store.dispatch("adminRole/updateRole", { id: this.id, data: data }).then((response) => {
                    this.role_modal.hide();
                    this.roles.forEach((role, index) => {
                        if (role[0] === this.id) {
                            this.roles[index] = response;
                        }
                    });
                });
            }
        },
        deleteRole() {
            if (this.id <= 6) {
                alert("You can not delete this role");
            } else {
                this.$swal({
                    icon: "question",
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$store.dispatch("adminRole/deleteRole", this.id).then((response) => {
                            if (response === true) {
                                this.roles.forEach((role, index) => {
                                    if (role[0] === this.id) {
                                        this.roles.splice(index, 1);
                                    }
                                });
                                this.$swal({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "Role has been deleted.",
                                    timer: 600
                                });
                            } else {
                                this.$swal({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Something went wrong!",
                                    timer: 600
                                });
                            }
                        }).then(() => {
                            this.role_modal.hide();
                        });
                    }
                });
            }
        },
        searchUser(e) {
            // remove all selected option
            this.search_user.removeActiveItems();
            this.search_user.setChoices([], "value", "label", true);
            // wait for user to finish typing
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                let keyword = e.detail.value;
                if (keyword.length > 3) {
                    this.search_user.setChoices(() => {
                        return this.searchUsers(keyword).then((response) => {
                            return response;
                        });
                    }, "value", "label", true);
                }
            }, 600);
        },
        assignUser() {
            // get selected user
            let user = this.search_user.getValue(true);
            // get selected role
            let role = this.current_role;
            if (!user || role === null) {
                alert("Please select user and role");
            } else {
                this.$store.dispatch("adminRole/assignUser", { userId: user, roleId: role }).then((response) => {
                    if (response === "ok") {
                        this.$swal({
                            icon: "success",
                            title: "Success!",
                            text: "User has been assigned to role.",
                            showConfirmButton: false,
                            timer: 600
                        }).then(() => {
                            this.user_modal.hide();
                        }).then(() => {
                            this.getRoleUser(role);
                        });

                    } else {
                        this.$swal({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            showConfirmButton: false,
                            timer: 600
                        });
                    }
                });
            }
        },
        unassignUser() {
            // get select user
            let selected = this.table.rows({ selected: true }).data();
            if (selected.length === 0) {
                alert("Please select at least one user");
            } else {
                let users = [];
                selected.each(function(value, index) {
                    users.push(value[0]);
                });
                // unassign user
                this.$store.dispatch("adminRole/unassignUser", {
                    users: users,
                    role: this.current_role
                }).then((response) => {
                    if (response === "ok") {
                        this.$swal({
                            icon: "success",
                            title: "Success!",
                            text: "User has been unassigned from role.",
                            showConfirmButton: false,
                            timer: 600
                        }).then(() => {
                            this.user_modal.hide();
                        }).then(() => {
                            this.getRoleUser(this.current_role);
                        });
                    } else {
                        this.$swal({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            showConfirmButton: false,
                            timer: 600
                        });
                    }
                });
            }
        }
    },
    beforeMount() {
        this.getRole().then((response) => {
            this.roles = response;
        });
    },
    mounted() {
        this.table = this.$refs.table.dt();
        this.role_modal = new Modal(document.getElementById("roleModal"), {
            keyboard: true,
            backdrop: "static"
        });
        this.user_modal = new Modal(document.getElementById("roleUserModal"), {
            keyboard: true,
            backdrop: "static"
        });
        this.permission_select = new Choices("#permission", {
            removeItemButton: true,
            searchResultLimit: 5,
            searchFields: ["label", "value"],
            shouldSort: false,
            itemSelectText: "",
            allowHTML: true
        });
        this.permission_select.setChoices(() => {
            return this.getAllPermissions().then((response) => {
                return response;
            });
        }, "value", "label", true);
        document.getElementById("role-submit").addEventListener("click", this.handleRoleSubmit);
        this.search_user = new Choices("#search_user", {
            removeItemButton: true,
            searchResultLimit: 5,
            searchFields: ["label", "value"],
            shouldSort: false,
            itemSelectText: "",
            allowHTML: true
        });
        document.getElementById("search_user").addEventListener("search", this.searchUser);
    }
};
</script>

<style scoped>
.nav .nav-item:hover {
    background-color: #f3f3f3;
    border-radius: 0.5rem;
}

.nav .nav-item.active {
    background-color: #d2dbe4;
    color: #344767;
    border-radius: 0.5rem;
}

.choices__inner {
    max-height: 20vh;
    overflow-y: auto;
}
</style>
