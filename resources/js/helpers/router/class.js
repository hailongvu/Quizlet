const classes = [
    {
        path: '/class',
        redirect: {name: 'error'},
        children: [
            {
                path: ':id',
                name: 'class.js.index',
                component: () => import('../../pages/home/class/Class.vue'),
            },
    ]
    },
];
export default classes;
