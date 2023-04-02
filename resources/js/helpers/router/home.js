const home = [
    {
        path: '/',
        name: 'home',
        component: () => import('../../layouts/Home.vue'),
        redirect: { name: 'home.index' },
        children: [
            {
                path: '',
                name: 'home.index',
                component: () => import('../../pages/home/Index.vue'),
            },
            {
                path: 'lesson',
                children: [
                    {
                        path: ':id',
                        name: 'home.lesson.index',
                        component: () => import('../../pages/home/lesson/Lesson.vue'),
                    },
                    {
                        path: ':id/edit',
                        name: 'home.lesson.edit',
                        component: () => import('../../pages/home/lesson/Edit.vue'),
                    },
                    {
                        path: 'add',
                        name: 'home.lesson.add',
                        component: () => import('../../pages/home/lesson/Add.vue'),
                    },
                ],
            },
            {
                path: 'course',
                children: [
                    {
                        path: ':id',
                        name: 'home.course.index',
                        component: () => import('../../pages/home/course/Course.vue'),
                    },
                    {
                        path: ':id/edit',
                        name: 'home.course.edit',
                        component: () => import('../../pages/home/course/Edit.vue'),
                    },
                ],
            },
            {
                path: 'photoshop',
                name: 'home.photoshop',
                component: () => import('../../pages/home/Photoshop.vue'),
            },
            {
                path: 'profile',
                name: 'home.profile',
                component: () => import('../../pages/home/Profile.vue'),
                children: [
                    {
                        path: 'lessons',
                        name: 'home.profile.lessons',
                        component: () => import('../../pages/home/ProfileLesson.vue'),
                    },
                    {
                        path: 'courses',
                        name: 'home.profile.courses',
                        component: () => import('../../pages/home/ProfileCourse.vue'),
                    },
                    {
                        path: 'classes',
                        name: 'home.profile.class.js',
                        component: () => import('../../pages/home/ProfileClass.vue'),
                    }
                ],
            },
            {
                path: 'setting',
                name: 'home.setting',
                component: () => import('../../pages/home/Setting.vue'),
            },
            {
                path: 'forum',
                name: 'home.forum',
                component: () => import('../../pages/home/Forum.vue'),
            },
            {
                path: 'forum_detail',
                name: 'home.forumdetail',
                component: () => import('../../pages/home/ForumDetail.vue'),
            },
        ],
    },
];
export default home;
