const admin = [
    {
        path: '/admin',
        name: 'admin',
        redirect: {name: 'admin.dashboard'},
        component: () => import('../../layouts/Admin.vue'),
        children: [
            {
                path: 'dashboard',
                name: 'admin.dashboard',
                component: () => import('../../pages/admin/Dashboard.vue'),
            },
            {
                path: 'setting',
                name: 'admin.setting',
                component: () => import('../../pages/admin/setting/Setting.vue'),
            },
            {
                path: 'notification',
                name: 'admin.notification',
                component: () => import('../../pages/admin/notification/Index.vue'),
            },
            {
                path: 'users',
                name: 'admin.users',
                component: () => import('../../pages/admin/users/Index.vue'),
            },
            {
                path: 'roles',
                name: 'admin.roles',
                component: () => import('../../pages/admin/roles/Index.vue'),
            },
            {
                path: 'schools',
                name: 'admin.schools',
                redirect: {name: 'admin.schools.list'},
                children: [
                    {
                        path: '',
                        name: 'admin.schools.list',
                        component: () => import('../../pages/admin/school/Index.vue'),
                    },
                    {
                        path: 'add',
                        name: 'admin.schools.add',
                        component: () => import('../../pages/admin/school/Add.vue'),
                    },
                    {
                        path: 'edit/:id',
                        name: 'admin.schools.edit',
                        component: () => import('../../pages/admin/school/edit/Index.vue'),
                    },
                    {
                        path: 'request',
                        name: 'admin.schools.request',
                        component: () => import('../../pages/admin/school/Index.vue'),
                    },
                ]
            },
            {
                path: 'lessons',
                name: 'admin.lesson',
                redirect: {name: 'admin.lesson.list'},
                children: [
                    {
                        path: '',
                        name: 'admin.lesson.list',
                        component: () => import('../../pages/admin/lesson/List.vue'),
                    },
                    {
                        path: 'add',
                        name: 'admin.lesson.add',
                        component: () => import('../../pages/admin/lesson/Add.vue'),
                    },
                    {
                        path: 'edit/:id',
                        name: 'admin.lesson.edit',
                        component: () => import('../../pages/admin/lesson/Edit.vue'),
                    }
                ]
            },
            {
                path: 'course',
                name: 'admin.course',
                redirect: {name: 'admin.course.list'},
                children: [
                    {
                        path: '',
                        name: 'admin.course.list',
                        component: () => import('../../pages/admin/course/List.vue'),
                    },
                    {
                        path: 'edit/:id',
                        name: 'admin.course.edit',
                        component: () => import('../../pages/admin/course/Edit.vue'),
                    }
                ]
            }
        ],
    },
];
export default admin;
