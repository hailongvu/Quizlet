<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">All School</h5>
                                <p class="mb-0 text-sm">List of all school in the system</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <router-link v-if="this.select_id"
                                            class="mx-1 mb-0 btn btn-outline-info w-100" :to=" { name: 'admin.schools.edit', params: { id: select_id } }">
                                        Update
                                    </router-link>
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
                                    <th>Name</th>
                                    <th>Phone number</th>
                                    <th>Email domain</th>
                                    <th>Website</th>
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
</template>

<script>
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-bs5';
import 'datatables.net-select';
import {mapGetters} from "vuex";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
DataTable.use(DataTablesLib);

export default {
    name: "User",
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
            table: null,
            select_id: null,
        };
    },
    beforeMount() {
    },
    mounted() {
        this.table = this.$refs.table.dt();
        this.table.on('select', (e, dt, type, indexes) => {
            if (type === 'row') {
                this.select_id = this.table.rows(indexes).data()[0][0];
            }
        });
        this.table.on('deselect', (e, dt, type, indexes) => {
            if (type === 'row') {
                this.select_id = null;
            }
        });
        this.$store.dispatch('adminSchool/getAllSchools').then(
            schools => {
                this.table.clear();
                this.table.rows.add(schools);
                this.table.draw();
            }
        );
    },
    methods: {
        ...mapGetters({
            permissions: 'account/permissions',
        }),
        checkPermission(name) {
            return this.permissions().some(permission => permission.name === name);
        },
    },
}
</script>
