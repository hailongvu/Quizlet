import {userService} from '../../services';
import router from "../../router";

const user = JSON.parse(localStorage.getItem('user'));
const permissions = JSON.parse(localStorage.getItem('permissions'));
const token = localStorage.getItem('token');
const state = user
    ? {status: {loggedIn: true}, user, token, permissions}
    : {status: {}, user: null, permissions: null, token: null};

export default {
    namespaced: true,
    state: state,
    mutations: {
        loginRequest(state) {
            state.status = {loggingIn: true};
        },
        loginSuccess(state, data) {
            state.status = {loggedIn: true};
            state.user = data.user;
            state.token = data.access_token;
            state.permissions = data.permissions;
        },
        loginFailure(state) {
            state.status = {};
            state.user = null;
            state.token = null;
            state.permissions = null;
        },
        logout(state) {
            state.status = {};
            state.user = null;
            state.token = null;
            state.permissions = null;
        },
        registerRequest(state) {
            state.status = {registering: true};
        },
        registerSuccess(state, user) {
            state.status = {};
            state.user = user;
        },
        registerFailure(state, error) {
            state.status = {};
        },
    },
    actions: {
        login({dispatch, commit}, {email, password, rememberMe}) {
            commit('loginRequest');
            userService.login(email, password, rememberMe)
                .then(
                    user => {
                        commit('loginSuccess', {
                            user: user.data.user,
                            access_token: user.data.access_token,
                            permissions: user.data.permissions
                        });
                        dispatch('alert/success', user.message, {root: true});
                        // check if the user is admin
                        if (user.data.permissions.some(permission => permission.name === 'admin.dashboard')) {
                            setTimeout(() => {
                                router.push({name: 'admin'})
                            }, 1000);
                        }else if (user.data.permissions.some(permission => permission.name === 'manager.dashboard')) {
                            setTimeout(() => {
                                router.push({name: 'manage'})
                            }, 1000);
                        } else {
                            setTimeout(() => {
                                router.push({name: 'home'})
                            }, 1000);
                        }
                    },
                    error => {
                        commit('loginFailure', error);
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        logout({commit}) {
            userService.logout();
            commit('logout');
        },
        register({dispatch, commit}, user) {
            commit('registerRequest', user);
            userService.register(user)
                .then(
                    user => {
                        commit('registerSuccess', user);
                        dispatch('alert/success', 'Registration successful', {root: true});
                        setTimeout(() => {
                            router.push({name: 'home'})
                        }, 1000)
                    },
                    error => {
                        commit('registerFailure', error);
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        forgotPassword({dispatch, commit}, email) {
            userService.forgotPassword(email)
                .then(
                    user => {
                        dispatch('alert/success', user.message, {root: true});
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        resetPassword({dispatch, commit}, {email, password, password_confirmation, token}) {
            userService.resetPassword(email, password, password_confirmation, token)
                .then(
                    user => {
                        dispatch('alert/success', user.message, {root: true});
                        setTimeout(() => {
                            router.push({name: 'auth.login'})
                        }, 1000)
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        verifyEmail({dispatch, commit}, {id, hash, expires, signature}) {
            userService.verifyEmail(id, hash, expires, signature)
                .then(
                    user => {
                        dispatch('alert/success', user.message, {root: true});
                        setTimeout(() => {
                            router.push({name: 'auth.login'})
                        }, 1000)
                    },
                    error => {
                        dispatch('alert/error', error, {root: true}).then(() => {
                            dispatch('alert/error', "You must login to verify email", {root: true}).then(() => {
                                setTimeout(() => {
                                    router.push({name: 'auth.login'})
                                }, 1000)
                            });
                        });
                    }
                );
        },
        resendVerificationEmail({dispatch, commit}) {
            userService.resendVerificationEmail()
                .then(
                    user => {
                        dispatch('alert/success', user.message, {root: true});
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        }
    },
    getters: {
        isAdmin: state => {
            return state.permissions.some(permission => permission.name === 'admin.dashboard');
        },
        getUser: state => {
            return state.user;
        },
        isEmailVerified: state => {
            return state.user.email_verified_at;
        },
        permissions: state => {
            return state.permissions;
        }
    }
};
