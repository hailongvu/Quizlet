let meta = JSON.parse(localStorage.getItem('meta_data'));
const auth = [
    {
        path: '/auth',
        name: 'auth',
        component: () => import('../../layouts/Auth.vue'),
        children: [
            {
                path: 'login',
                name: 'auth.login',
                component: () => import('../../pages/auth/Login.vue'),
            },
            {
                path: 'register',
                name: 'auth.register',
                component: () => import('../../pages/auth/Register.vue'),
            },
            {
                path: 'logout',
                name: 'auth.logout',
                component: () => import('../../pages/auth/Logout.vue'),
            },
            {
                path: 'forgot-password',
                name: 'auth.forgot-password',
                component: () => import('../../pages/auth/ForgotPassword.vue'),
            },
            {
                path: 'reset-password',
                name: 'auth.reset-password',
                component: () => import('../../pages/auth/ResetPassword.vue'),
            },
            {
                path: 'verify',
                name: 'auth.verify',
                component: () => import('../../pages/auth/VerifyEmailNotice.vue'),
            },
            {
                path: 'handle-verify/:id/:hash',
                name: 'auth.verify.token',
                component: () => import('../../pages/auth/VerifyEmail.vue'),
            }
        ],
    },
];
export default auth;
