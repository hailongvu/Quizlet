const manage = [
    {
        path: '/manage',
        name: 'manage',
        component: () => import('../../layouts/Manage.vue'),
        redirect: { name: 'manage.index' },
        children: [
            {
                path: '',
                name: 'manage.index',
                component: () => import('../../pages/manage/Index.vue'),
            }
        ]
    }
]

export default manage;
