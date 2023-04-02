<template>
    <div id="info" class="card mt-4">
        <div class="card-header">
            <div class="d-lg-flex">
                <div>
                    <h5 class="mb-0">Basic school information</h5>
                    <p class="mb-0 text-sm">Basic information of a {{ this.school.name }}</p>
                </div>
                <div class="my-auto mt-4 ms-auto mt-lg-0">
                    <div class="my-auto ms-auto">
                        <button type="button"
                                class="mx-1 mb-0 btn btn-outline-success btn-sm" @click="updateInfo">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label mt-2">Name</label>
                    <argon-input id="name" type="text" name="name"
                                 placeholder="School name, like 'FPT University'" :value="this.school.name" required
                                 @change="changeInput" />
                </div>
                <div class="col-md-6">
                    <label class="form-label mt-2">Website</label>
                    <argon-input id="website" type="text" name="website"
                                 placeholder="School website, like 'https://daihoc.fpt.edu.vn'"
                                 :value="this.school.website"
                                 required @change="changeInput" />
                </div>
                <div class="col-12">
                    <label class="form-label mt-2">Email domain (split by ',')</label>
                    <argon-input id="email" type="text" name="email"
                                 placeholder="School email domain, like 'fpt.edu.vn'" :value="this.school.email"
                                 required @change="changeInput" />
                </div>
                <div class="col-md-4 col-6">
                    <label class="form-label mt-2">Phone</label>
                    <argon-input id="phone" type="text" name="phone" placeholder="02473001866"
                                 :value="this.school.phone"
                                 required @change="changeInput" />
                </div>
                <div class="col-md-8 col-6">
                    <label class="form-label mt-2">Address</label>
                    <argon-input id="address" type="text" name="address"
                                 placeholder="School address" :value="this.school.address" required
                                 @change="changeInput" />
                </div>
                <div class="col-md-6">
                    <label class="form-label mt-2">Country</label>
                    <select
                        id="select_country"
                        name="country"
                        class="form-control"
                    >
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label mt-2">State</label>
                    <select
                        id="select_state"
                        name="state"
                        class="form-control"
                    >
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label mt-2">City</label>
                    <select
                        id="select_city"
                        name="city"
                        class="form-control"
                    >
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mt-2">Zip</label>
                    <argon-input id="zip" type="number" name="zip" placeholder="Zip code" :value="this.school.zip"
                                 required
                                 @change="changeInput" />
                </div>
                <div class="col-md-3">
                    <label class="form-label mt-2">Status</label>
                    <select id="status" name="status" class="form-control" :value="this.school.status"
                            @change="changeInput" required>
                        <option value="inactive">Inactive</option>
                        <option value="active">Active</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label mt-2">Logo</label>
                    <argon-input id="logo" type="text" name="logo"
                                 placeholder="Logo of this school" :value="this.school.logo" required
                                 @change="changeInput" />
                    <div class="w-100 text-center">
                        <img class="img-fluid" id="img-logo" :src="this.school.logo" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="staff" class="card mt-4">
        <div class="card-header">
            <div class="d-lg-flex">
                <div>
                    <h5 class="mb-0">Staff</h5>
                    <p class="mb-0 text-sm">List all manager in {{ this.school.name }}</p>
                </div>
                <div class="my-auto mt-4 ms-auto mt-lg-0">
                    <div class="my-auto ms-auto">
                        <button type="button"
                                class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                @click="showForm('manager')">
                            Assign Manager
                        </button>
                        <button type="button"
                                class="mx-1 mb-0 btn btn-outline-danger btn-sm" @click="removeManager">
                            Unassign Manager
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <DataTable id="manager_table" :options="{select: 'single'}" ref="manager_table"
                           class="table table-flush mx-3">
                    <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                    </tr>
                    </thead>
                </DataTable>
            </div>
        </div>
    </div>
    <div id="class" class="card mt-4">
        <div class="card-header">
            <div class="d-lg-flex">
                <div>
                    <h5 class="mb-0">Class</h5>
                    <p class="mb-0 text-sm">List all class in {{ this.school.name }}</p>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <DataTable id="class_table" :options="{select: 'single'}" ref="class_table"
                           class="table table-flush mx-3">
                    <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Start date</th>
                        <th>End date</th>
                    </tr>
                    </thead>
                </DataTable>
            </div>
        </div>
    </div>
    <div id="student" class="card mt-4">
        <div class="card-header">
            <div class="d-lg-flex">
                <div>
                    <h5 class="mb-0">Student</h5>
                    <p class="mb-0 text-sm">List all student in {{ this.school.name }}</p>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <DataTable id="student_table" :options="{select: 'single'}" ref="student_table"
                           class="table table-flush mx-3">
                    <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                    </tr>
                    </thead>
                </DataTable>
            </div>
        </div>
    </div>
    <div id="teacher" class="card mt-4">
        <div class="card-header">
            <div class="d-lg-flex">
                <div>
                    <h5 class="mb-0">Teacher</h5>
                    <p class="mb-0 text-sm">List all teacher in {{ this.school.name }}</p>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <DataTable id="teacher_table" :options="{select: 'single'}" ref="teacher_table"
                           class="table table-flush mx-3">
                    <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                    </tr>
                    </thead>
                </DataTable>
            </div>
        </div>
    </div>
    <div id="modalUser" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="ModalLabel" class="modal-title">
                        Find User
                    </h5>
                    <i class="fa-regular fa-cloud-arrow-up ms-3"></i>
                </div>
                <div class="modal-body">
                    <label class="form-label mt-2">Find user in school</label>
                    <select
                        id="keyword"
                        name="keyword"
                        class="form-control"
                        multiple
                    >
                    </select>
                    <label class="form-label mt-1">Position of selected user</label>
                    <argon-input v-if="this.form_type === 'manager'" id="position" type="text" name="position"
                                 placeholder="Position" required />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-success btn-sm" id="btnSubmit" disabled>
                        Submit
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
import Choices from "choices.js";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import { mapActions, mapGetters } from "vuex";
import { country_data } from "@/helpers/country_data.js";
import DataTable from "datatables.net-vue3";
import DataTablesLib from "datatables.net-bs5";
import "datatables.net-select";
import { Modal } from "bootstrap";

DataTable.use(DataTablesLib);
export default {
    name: "Content",
    components: {
        ArgonInput,
        DataTable
    },
    data() {
        return {
            editor: ClassicEditor,
            country: null,
            state: null,
            city: null,
            school: {
                id: "",
                name: "",
                website: "",
                email: "",
                phone: "",
                address: "",
                state: "",
                city: "",
                country: "",
                zip: "",
                logo: "",
                status: ""
            },
            select_country: null,
            select_city: null,
            select_state: null,
            select_user: null,
            manager_table: null,
            class_table: null,
            modal_search: null,
            form_type: "manager",
            selected_manager: null
        };
    },
    mounted() {
        this.$nextTick(() => {
            this.init();
        });
    },
    beforeUnmount() {
        this.select_country.destroy();
        this.select_state.destroy();
        this.select_city.destroy();
        this.select_user.destroy();
    },
    methods: {
        ...mapActions("adminSchool", ["getSchool", "getManager"]),
        ...mapGetters({
            permissions: "account/permissions"
        }),
        checkPermission(name) {
            return this.permissions().some(permission => permission.name === name);
        },
        changeInput(e) {
            this.school[e.target.name] = e.target.value;
        },
        changeCountry(isoCode) {
            this.school.country = isoCode;
            this.state = country_data.getState(isoCode);
            this.select_state.setChoices(this.state, "isoCode", "name", true);
            this.school.state = "";
            this.school.city = "";
            this.state = [];
            this.city = [];
        },
        changeState(isoCode) {
            this.school.state = isoCode;
            this.city = country_data.getCity(this.school.country, isoCode);
            this.select_city.setChoices(this.city, "name", "name", true);
            this.school.city = "";
            this.city = [];
        },
        changeCity(name) {
            this.school.city = name;
        },
        init() {
            this.country = country_data.getCountry();
            this.select_country = new Choices("#select_country", {
                searchEnabled: true,
                itemSelectText: "",
                shouldSort: false,
                allowHTML: true
            });
            this.select_state = new Choices("#select_state", {
                searchEnabled: true,
                itemSelectText: "",
                shouldSort: false,
                allowHTML: true
            });
            this.select_city = new Choices("#select_city", {
                searchEnabled: true,
                itemSelectText: "",
                shouldSort: false,
                allowHTML: true
            });
            this.select_user = new Choices("#keyword", {
                searchEnabled: true,
                itemSelectText: "",
                shouldSort: false,
                allowHTML: true
            });
            this.select_country.setChoices(this.country, "isoCode", "name", true);
            let school_id = this.$route.params.id;
            this.getSchool(school_id).then((res) => {
                this.school = res.school;
                this.setChoice();
                this.setManager(res.managers);
                this.setClass(res.classes);
            });
            this.setEventListener();
            this.modal_search = new Modal(document.getElementById("modalUser"));
        },
        setEventListener() {
            this.select_country.passedElement.element.addEventListener("choice", (event) => {
                this.select_state.clearStore();
                this.select_city.clearStore();
                this.changeCountry(event.detail.choice.value);
            });
            this.select_state.passedElement.element.addEventListener("choice", (event) => {
                this.select_city.clearStore();
                this.changeState(event.detail.choice.value);
            });
            this.select_city.passedElement.element.addEventListener("choice", (event) => {
                this.changeCity(event.detail.choice.value);
            });
            this.select_user.passedElement.element.addEventListener("search", this.searchUsers);
        },
        setChoice() {
            this.select_country.setChoiceByValue(this.school.country);
            this.state = country_data.getState(this.school.country);
            this.select_state.setChoices(this.state, "isoCode", "name", true);
            this.select_state.setChoiceByValue(this.school.state);
            this.city = country_data.getCity(this.school.country, this.school.state);
            this.select_city.setChoices(this.city, "name", "name", true);
            this.select_city.setChoiceByValue(this.school.city);
        },
        setManager(manager) {
            // lesson data for manager table
            this.manager_table = this.$refs.manager_table.dt();
            this.manager_table.clear();
            this.manager_table.rows.add(manager).draw();
            // handle event for manager table
            this.manager_table.on("select", (e, dt, type, indexes) => {
                if (type === "row") {
                    this.selected_manager = this.manager_table.rows(indexes).data().toArray()[0];
                }
            });
            this.manager_table.on("deselect", (e, dt, type, indexes) => {
                if (type === "row") {
                    this.selected_manager = null;
                }
            });
        },
        setClass(classes) {
            console.log(classes);
            this.class_table = this.$refs.class_table.dt();
            this.class_table.clear();
            this.class_table.rows.add(classes).draw();
            // handle event for class.js table
            this.class_table.on("select", (e, dt, type, indexes) => {
                if (type === "row") {
                    this.selected_class = this.class_table.rows(indexes).data().toArray()[0];
                }
            });
            this.class_table.on("deselect", (e, dt, type, indexes) => {
                if (type === "row") {
                    this.selected_class = null;
                }
            });
        },
        updateInfo() {
            let check = true;
            for (let schoolKey in this.school) {
                if (schoolKey === "state" || schoolKey === "city") {
                    if (this.state && this.state.length > 0 && this.school["state"] === "") {
                        check = false;
                        break;
                    } else if (this.city && this.city.length && this.school["city"] === "") {
                        check = false;
                        break;
                    }
                } else {
                    if (this.school[schoolKey] === "") {
                        check = false;
                        break;
                    }
                }
            }
            if (check) {
                // update school information
                this.$store.dispatch("adminSchool/update", { type: "school", school: this.school }).then((res) => {
                    if (res.status_code === 200) {
                        this.$swal({
                            title: "Success!",
                            text: "School information updated successfully",
                            icon: "success",
                            confirmButtonText: "Ok"
                        });
                    } else {
                        this.$swal({
                            title: "Error!",
                            text: "Something went wrong",
                            icon: "error",
                            confirmButtonText: "Ok"
                        });
                    }
                });
            } else {
                this.$swal({
                    title: "Error!",
                    text: "Please fill all fields",
                    icon: "error",
                    confirmButtonText: "Ok"
                });
            }
        },
        searchUsers(e) {
            // remove all selected options
            this.select_user.removeActiveItems();
            // remove old data
            this.select_user.clearStore();
            // wait user done typing
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                // get keyword
                let keyword = e.detail.value;
                // search user
                this.$store.dispatch("adminSchool/searchUser", {
                    keyword: keyword,
                    schoolId: this.school.id
                }).then((res) => {
                    if (res.status_code === 200) {
                        this.select_user.setChoices(res.data, "id", "email", true);
                    }
                });
            }, 500);
        },
        showForm(type) {
            this.form_type = type;
            document.getElementById("btnSubmit").disabled = false;
            // clear old data
            this.select_user.clearStore();
            // remove all selected options
            this.select_user.removeActiveItems();
            // clear event listener for btn submit
            document.getElementById("btnSubmit").removeEventListener("click", this.submitForm);
            // add event listener for btn submit
            document.getElementById("btnSubmit").addEventListener("click", this.submitForm);
            this.modal_search.show();
        },
        submitForm() {
            if (this.form_type === "manager") {
                this.addManager();
            }
        },
        addManager() {
            let manager = this.select_user.getValue(true);
            let position = document.getElementById("position").value;
            if (manager.length > 0 && position !== "") {
                this.$store.dispatch("adminSchool/addManager", {
                    user_id: manager,
                    schoolId: this.school.id,
                    position: position
                }).then((res) => {
                    if (res.status_code === 200) {
                        this.$swal({
                            title: "Success!",
                            text: "Manager added successfully",
                            icon: "success",
                            confirmButtonText: "Ok"
                        });
                        this.getManager(this.school.id).then((res) => {
                            this.setManager(res.data);
                        });
                    } else {
                        this.$swal({
                            title: "Error!",
                            text: "Something went wrong",
                            icon: "error",
                            confirmButtonText: "Ok"
                        });
                    }
                });
            } else {
                this.$swal({
                    title: "Error!",
                    text: "Please select a manager",
                    icon: "error",
                    confirmButtonText: "Ok"
                });
            }
        },
        removeManager() {
            if (this.selected_manager) {
                // unassign manager
                this.$store.dispatch("adminSchool/removeManager", {
                    user_id: this.selected_manager[0],
                    schoolId: this.school.id
                }).then((res) => {
                    if (res.status_code === 200) {
                        this.$swal({
                            title: "Success!",
                            text: "Manager removed successfully",
                            icon: "success",
                            confirmButtonText: "Ok"
                        });
                    } else {
                        this.$swal({
                            title: "Error!",
                            text: "Something went wrong",
                            icon: "error",
                            confirmButtonText: "Ok"
                        });
                    }
                });
            } else {
                this.$swal({
                    title: "Error!",
                    text: "Please select a manager",
                    icon: "error",
                    confirmButtonText: "Ok"
                });
            }
        },
        getClasses(schoolId) {
            return this.$store.dispatch("adminSchool/getClasses", schoolId);
        }
    }
};
</script>
<style scoped>
#img-logo {
    width: 40vw;
    height: auto;
    object-fit: cover;
    object-position: center;
}

@media (max-width: 768px) {
    #img-logo {
        width: 100%;
    }
}
</style>
