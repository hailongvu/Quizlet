import {adminRoleService} from '../../../services/admin/role.service';
import overlay from '../../../overlay';

const state = {
    status: {},
    roles: []
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, roles) {
            state.status = {index: true};
            state.roles = roles;
        },
        indexFailure(state) {
            state.status = {};
            state.roles = [];
        }
    },
    actions: {
        index({commit}) {
            commit('indexRequest');
            return adminRoleService.index()
                .then(
                    roles => {
                        commit('indexSuccess', roles);
                        return Promise.resolve(roles);
                    },
                    error => {
                        commit('indexFailure', error);
                        return [];
                    }
                );
        },
        getRoleUsers({commit}, id) {
            overlay();
            commit('indexRequest');
            return adminRoleService.getRoleUsers(id)
                .then(
                    roles => {
                        commit('indexSuccess', roles);
                        overlay();
                        return Promise.resolve(roles);
                    },
                    error => {
                        commit('indexFailure', error);
                        overlay();
                        return [];
                    }
                );
        },
        getRoleInfo({commit}, id) {
            overlay();
            commit('indexRequest');
            return adminRoleService.getRoleInfo(id)
                .then(
                    roles => {
                        commit('indexSuccess', roles);
                        overlay();
                        return Promise.resolve(roles);
                    },
                    error => {
                        commit('indexFailure', error);
                        overlay();
                        return [];
                    }
                );
        },
        getAllPermission({commit}) {
            commit('indexRequest');
            return adminRoleService.getAllPermission()
                .then(
                    roles => {
                        commit('indexSuccess', roles);
                        return Promise.resolve(roles);
                    },
                    error => {
                        commit('indexFailure', error);
                        return [];
                    }
                );
        },
        addRole({commit}, role) {
            overlay();
            commit('indexRequest');
            return adminRoleService.addRole(role)
                .then(
                    roles => {
                        commit('indexSuccess', roles);
                        overlay();
                        return Promise.resolve(roles);
                    },
                    error => {
                        commit('indexFailure', error);
                        overlay();
                        return [];
                    }
                );
        },
        updateRole({commit}, role) {
            overlay();
            commit('indexRequest');
            return adminRoleService.updateRole(role)
                .then(
                    roles => {
                        commit('indexSuccess', roles);
                        overlay();
                        return Promise.resolve(roles);
                    },
                    error => {
                        commit('indexFailure', error);
                        overlay();
                        return [];
                    }
                );
        },

        deleteRole({commit}, id) {
            overlay();
            return adminRoleService.deleteRole(id)
                .then(
                    roles => {
                        overlay();
                        return true;
                    },
                    error => {
                        overlay();
                        return false;
                    }
                );
        },
        searchUsers({commit}, query) {
            commit('indexRequest');
            return adminRoleService.searchUser(query)
                .then(
                    roles => {
                        commit('indexSuccess', roles);
                        return Promise.resolve(roles);
                    },
                    error => {
                        commit('indexFailure', error);
                        return [];
                    }
                );
        },
        assignUser({commit}, data) {
            overlay();
            commit('indexRequest');
            return adminRoleService.assignUser(data)
                .then(
                    roles => {
                        overlay();
                        return 'ok';
                    },
                    error => {
                        overlay();
                        return false;
                    }
                );
        },
        unassignUser({commit}, data) {
            overlay();
            commit('indexRequest');
            return adminRoleService.unassignUser(data)
                .then(
                    roles => {
                        overlay();
                        return 'ok';
                    },
                    error => {
                        overlay();
                        return false;
                    }
                );
        },

    }
}
