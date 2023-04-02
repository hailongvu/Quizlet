<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Add School</h5>
                                <p class="mb-0 text-sm">Add new school</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="row" @submit.prevent="this.handleSubmit" ref="form">
                            <label class="form-label mt-2 fs-6">School Information</label>
                            <div class="col-md-8 col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label mt-2">Name</label>
                                        <argon-input id="name" type="text" name="name"
                                                     placeholder="School name, like 'FPT University'" required
                                                     @change="changeInput"/>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mt-2">Website</label>
                                        <argon-input id="website" type="text" name="website"
                                                     placeholder="School website, like 'https://daihoc.fpt.edu.vn'"
                                                     required @change="changeInput"/>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label mt-2">Email domain (split by ',')</label>
                                        <argon-input id="email" type="text" name="email"
                                                     placeholder="School email domain, like 'fpt.edu.vn'"
                                                     required @change="changeInput"/>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <label class="form-label mt-2">Phone</label>
                                        <argon-input id="phone" type="text" name="phone" placeholder="02473001866"
                                                     required @change="changeInput"/>
                                    </div>
                                    <div class="col-md-8 col-6">
                                        <label class="form-label mt-2">Address</label>
                                        <argon-input id="address" type="text" name="address"
                                                     placeholder="School address" required @change="changeInput"/>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mt-2">Country</label>
                                        <select
                                            id="select_country"
                                            name="country"
                                            class="form-control"
                                        >
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mt-2">State</label>
                                        <select
                                            id="select_state"
                                            name="state"
                                            class="form-control"
                                        >
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mt-2">City</label>
                                        <select
                                            id="select_city"
                                            name="city"
                                            class="form-control"
                                        >
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label mt-2">Zip</label>
                                        <argon-input id="zip" type="number" name="zip" placeholder="Zip code" required
                                                     @change="changeInput"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label mt-2">Logo</label>
                                        <argon-input :value="this.school.logo" id="logo" type="text" name="logo"
                                                     placeholder="Logo" @change="this.changeLogo" required/>
                                        <img :src="this.school.logo" class="img-fluid rounded"
                                             alt="">
                                    </div>
                                </div>
                            </div>
                            <label class="form-label mt-3 fs-6">Manager Information</label>
                            <div class="col-md-6 col-12">
                                <label class="form-label mt-2">Email</label>
                                <argon-input id="manager.email" type="email" name="manager.email"
                                             placeholder="Edu email, like 'admin@school.edu.vn'"
                                             @change="this.changeInput" required/>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label mt-2">Department/position</label>
                                <argon-input id="manager.department" type="text" name="manager.department"
                                             placeholder="Department/position" @change="this.changeInput" required/>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label mt-2">Date of birth</label>
                                <argon-input id="manager.dob" type="date" name="manager.dob"
                                             @change="this.changeInput"/>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label mt-2">Password</label>
                                <argon-input id="manager.password" type="password" name="manager.password"
                                             @change="this.changeInput"/>
                            </div>
                            <div class="col-12">
                                <argon-button type="submit" class="mt-2 w-100 btn-lg" color="success">Submit
                                </argon-button>
                            </div>
                        </form>
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
import {mapGetters} from "vuex";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import Choices from "choices.js";
import {country_data} from '@/helpers/country_data.js';

DataTable.use(DataTablesLib);

export default {
    name: "Add School",
    components: {
        ArgonButton,
        ArgonInput,
        DataTable
    },
    title() {
        return 'List Schools' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    data() {
        return {
            country: null,
            state: null,
            city: null,
            school: {
                name: '',
                website: '',
                email: '',
                phone: '',
                address: '',
                state: '',
                city: '',
                country: '',
                zip: '',
                logo: 'https://cdn.haitrieu.com/wp-content/uploads/2021/10/Logo-Dai-hoc-FPT.png',
                manager: {
                    email: '',
                    department: '',
                    dob: '',
                    password: '',
                }
            },
            select_country: null,
            select_city: null,
            select_state: null,
        };
    },
    beforeMount() {
    },
    mounted() {
        this.$nextTick(() => {
            this.init();
        });
    },
    beforeUnmount() {
        this.select_country.destroy();
        this.select_city.destroy();
        this.select_state.destroy();
    },
    methods: {
        ...mapGetters({
            permissions: 'account/permissions',
        }),
        checkPermission(name) {
            return this.permissions().some(permission => permission.name === name);
        },
        init() {
            this.country = country_data.getCountry();
            this.select_country = new Choices('#select_country', {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true,
            });
            this.select_city = new Choices('#select_city', {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true,
            });
            this.select_state = new Choices('#select_state', {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true,
            });
            this.select_country.setChoices(this.country, 'isoCode', 'name', true);
            this.select_country.passedElement.element.addEventListener('choice', (event) => {
                this.select_state.clearStore();
                this.select_city.clearStore();
                this.changeCountry(event.detail.choice.value);
            });
            this.select_state.passedElement.element.addEventListener('choice', (event) => {
                this.select_city.clearStore();
                this.changeState(event.detail.choice.value);
            });
            this.select_city.passedElement.element.addEventListener('choice', (event) => {
                this.changeCity(event.detail.choice.value);
            });
        },
        changeCountry(isoCode) {
            this.school.country = isoCode;
            this.state = country_data.getState(isoCode);
            this.select_state.setChoices(this.state, 'isoCode', 'name', true);
            this.school.state = '';
            this.school.city = '';
            this.state = [];
            this.city = [];
        },
        changeState(isoCode) {
            this.school.state = isoCode;
            this.city = country_data.getCity(this.school.country, isoCode);
            this.select_city.setChoices(this.city, 'name', 'name', true);
            this.school.city = '';
            this.city = [];
        },
        changeCity(name) {
            this.school.city = name;
        },
        changeLogo(event) {
            this.school.logo = event.target.value;
        },
        changeInput(event) {
            if (event.target.name.includes('manager')) {
                this.school.manager[event.target.name.replace('manager.', '')] = event.target.value;
            } else {
                this.school[event.target.name] = event.target.value;
            }
        },
        handleSubmit() {
            // check school data is valid
            let check = true;
            let error_message = '';
            for (let key in this.school) {
                if (key === 'state' || key === 'city') {
                    if (this.school.country !== '') {
                        let state_count = this.state ? this.state.length : 0;
                        if (key === 'state' && this.school.state === '' && state_count > 0) {
                            check = false;
                            error_message += key + ', ';
                        } else if (key === 'phone') {
                            let phone = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/g;
                            if (!phone.test(this.school[key])) {
                                check = false;
                                error_message += key + ' must be a valid phone number, ';
                            }
                        } else {
                            let city_count = this.city ? this.city.length : 0;
                            if (key === 'city' && this.school.city === '' && city_count > 0) {
                                check = false;
                                error_message += key + ', ';
                            }
                        }
                    }
                } else if (key === 'manager') {
                    for (let manager_key in this.school.manager) {
                        if (manager_key === 'email') {
                            let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.+-]+\.edu.?([\w-]{2,4})?$/g;
                            if (!regex.test(this.school.manager[manager_key])) {
                                check = false;
                                error_message += manager_key + ' must be an .edu email, ';
                            }
                        } else {
                            if (!this.school.manager[manager_key]) {
                                check = false;
                                error_message += manager_key + ', ';
                            }
                        }
                    }
                } else if (!this.school[key]) {
                    check = false;
                    error_message += key + ', ';
                }
            }
            if (check) {
                this.$store.dispatch('adminSchool/addSchool', this.school).then(() => {
                    this.$swal({
                        title: 'Success!',
                        text: 'School has been added!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        this.$router.push({name: 'admin.schools.list'});
                    });
                }).catch((error) => {
                    this.$swal({
                        title: 'Error!',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            } else {
                this.$swal({
                    title: 'Error!',
                    text: 'Please check: ' + error_message + '!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
    },
}
</script>
