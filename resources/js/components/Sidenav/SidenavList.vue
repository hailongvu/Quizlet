<template>
    <div id="sidenav-collapse-main" class="collapse navbar-collapse w-auto h-auto h-100 overflow-hidden">
        <ul class="navbar-nav">
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.dashboard' })" collapse-ref="" nav-text="Dashboards"
                    :class="getRoute() === 'dashboard' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-solid fa-chart-line text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item" v-if="this.checkPermission('admin.setting')">
                <sidenav-collapse @click="$router.push({ name: 'admin.setting' })" collapse-ref="" nav-text="Settings"
                                  :class="getRoute() === 'setting' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-gear text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.notification' })" collapse-ref="" nav-text="Notification"
                                  :class="getRoute() === 'notification' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-bell text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="mt-3 nav-item">
                <h6 class="text-xs ps-4 text-uppercase font-weight-bolder opacity-6 ms-2">
                    Users
                </h6>
            </li>
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.users' })" collapse-ref="" nav-text="Users"
                                  :class="getRoute() === 'users' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-user text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.roles' })" collapse-ref="" nav-text="Roles"
                                  :class="getRoute() === 'roles' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-users text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="mt-3 nav-item">
                <h6 class="text-xs ps-4 text-uppercase font-weight-bolder opacity-6 ms-2">
                    Learning
                </h6>
            </li>
            <li class="nav-item">
                <sidenav-collapse collapse-ref="" nav-text="School"
                                  :class="getRoute() === 'schools' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-school text-primary text-sm opacity-10"></i>
                    </template>
                    <template #list>
                        <ul class="nav ms-4">
                            <!-- nav links -->
                            <sidenav-item :to="{ name: 'admin.schools.list' }" mini-icon="L" text="School List" />
                            <sidenav-item :to="{ name: 'admin.schools.add' }" mini-icon="A" text="Add School" />
                            <sidenav-item :to="{ name: 'admin.schools.request' }" mini-icon="R" text="School Request" />
                        </ul>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.lesson.list' })" collapse-ref="" nav-text="Lessons"
                                  :class="getRoute() === 'set' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-book text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.course.list' })" collapse-ref="" nav-text="Courses"
                                  :class="getRoute() === 'folder' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-folder text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
        </ul>
    </div>
</template>
<script>
import SidenavCollapse from "@/components/Sidenav/SidenavCollapse.vue";
import SidenavItem from "@/components/Sidenav/SidenavItem.vue";
import SidenavCard from "@/components/Sidenav/SidenavCard.vue";
import {mapGetters} from "vuex";

export default {
    name: "SidenavList",
    components: {
        SidenavCollapse,
        SidenavItem,
        SidenavCard,
    },
    methods: {
        getRoute() {
            const routeArr = this.$route.path.split("/");
            return routeArr[2];
        },
        ...mapGetters({
            permissions:'account/permissions'
        }),
        checkPermission(name){
            return this.permissions().some(permission => permission.name === name);
        }
    },
};
</script>
