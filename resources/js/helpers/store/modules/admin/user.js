import {adminUserService} from '../../../services/admin/user.service';
import overlay from '../../../overlay';

const state = {
    status: {},
    users: []
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, users) {
            state.status = {index: true};
            state.users = users;
        },
        indexFailure(state) {
            state.status = {};
            state.users = [];
        }
    },
    actions: {
        index({commit}) {
            commit('indexRequest');
            return adminUserService.index()
                .then(
                    users => {
                        commit('indexSuccess', users);
                        return Promise.resolve(users);
                    },
                    error => {
                        commit('indexFailure', error);
                        return [];
                    }
                );
        },
        create({dispatch}, user) {
            overlay();
            return adminUserService.create(user)
                .then(
                    user => {
                        overlay();
                        dispatch('alert/success', 'User created', {root: true});
                        return Promise.resolve(user);
                    },
                    error => {
                        overlay();
                        dispatch('alert/error', error, {root: true});
                        return [];
                    }
                );
        },
        destroy({dispatch}, id) {
            overlay();
            adminUserService.destroy(id)
                .then(
                    user => {
                        overlay();
                        dispatch('alert/success', 'User deleted', {root: true});
                    },
                    error => {
                        overlay();
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        getUserById({dispatch}, id) {
            overlay();
            return adminUserService.getUserById(id)
                .then(
                    data => {
                        overlay();
                        return Promise.resolve(data);
                    },
                    error => {
                        overlay();
                        dispatch('alert/error', error, {root: true});
                        return [];
                    }
                );
        },
        update({dispatch}, user) {
            overlay();
            return adminUserService.update(user)
                .then(
                    user => {
                        overlay();
                        dispatch('alert/success', 'User updated', {root: true});
                        return Promise.resolve(user);
                    },
                    error => {
                        overlay();
                        dispatch('alert/error', error, {root: true});
                        return [];
                    }
                );
        }
    }
}
