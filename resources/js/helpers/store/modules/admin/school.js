import {adminSchoolService} from '../../../services/admin/school.service';
import overlay from '../../../overlay';

const state = {
    status: {},
    schools: [],
}

export default {
    namespaced: true,
    state,
    actions: {
        getAllSchools({commit}) {
            return adminSchoolService.getAllSchools()
                .then(
                    schools => {
                        commit('getSchools', schools);
                        return Promise.resolve(schools);
                    },
                    error => {
                        commit('getSchools', null);
                        return Promise.reject(error);
                    }
                );
        },
        addSchool({commit}, data) {
            overlay();
            return adminSchoolService.addSchool(data)
                .then(
                    school => {
                        commit('getSchools', school);
                        overlay();
                        return Promise.resolve(school);
                    },
                    error => {
                        commit('getSchools', null);
                        overlay();
                        return Promise.reject(error);
                    }
                );
        },
        getSchool({commit}, id) {
            return adminSchoolService.getSchool(id)
                .then(
                    school => {
                        commit('getSchools', school);
                        return Promise.resolve(school);
                    },
                    error => {
                        commit('getSchools', null);
                        return Promise.reject(error);
                    }
                );
        },
        update({commit}, data) {
            overlay();
            return adminSchoolService.update(data)
                .then(
                    school => {
                        commit('getSchools', school);
                        overlay();
                        return Promise.resolve(school);
                    },
                    error => {
                        commit('getSchools', null);
                        overlay();
                        return Promise.reject(error);
                    }
                );
        },
        searchUser({commit}, data) {
            return adminSchoolService.searchUser(data)
                .then(
                    users => {
                        commit('getSchools', users);
                        return Promise.resolve(users);
                    },
                    error => {
                        commit('getSchools', null);
                        return Promise.reject(error);
                    }
                );
        },
        addManager({commit}, data) {
            overlay();
            return adminSchoolService.addManager(data)
                .then(
                    school => {
                        commit('getSchools', school);
                        overlay();
                        return Promise.resolve(school);
                    },
                    error => {
                        commit('getSchools', null);
                        overlay();
                        return Promise.reject(error);
                    }
                );
        },
        getManager({commit}, id) {
            return adminSchoolService.getManager(id)
                .then(
                    manager => {
                        commit('getSchools', manager);
                        return Promise.resolve(manager);
                    },
                    error => {
                        commit('getSchools', null);
                        return Promise.reject(error);
                    }
                );
        },
        removeManager({commit}, id) {
            console.log(id);
            overlay();
            return adminSchoolService.removeManager(id)
                .then(
                    school => {
                        commit('getSchools', school);
                        overlay();
                        return Promise.resolve(school);
                    },
                    error => {
                        commit('getSchools', null);
                        overlay();
                        return Promise.reject(error);
                    }
                );
        },
        addClass({commit}, data) {
            overlay();
            return adminSchoolService.addClass(data)
                .then(
                    school => {
                        commit('getSchools', school);
                        overlay();
                        return Promise.resolve(school);
                    },
                    error => {
                        commit('getSchools', null);
                        overlay();
                        return Promise.reject(error);
                    }
                );
        },
        getClasses({commit}, id) {
            return adminSchoolService.getClass(id)
                .then(
                    schoolClass => {
                        commit('getSchools', schoolClass);
                        return Promise.resolve(schoolClass);
                    },
                    error => {
                        commit('getSchools', null);
                        return Promise.reject(error);
                    }
                );
        },
        removeClass({commit}, data){
            overlay();
            return adminSchoolService.removeClass(data)
                .then(
                    school => {
                        commit('getSchools', school);
                        overlay();
                        return Promise.resolve(school);
                    },
                    error => {
                        commit('getSchools', null);
                        overlay();
                        return Promise.reject(error);
                    }
                );
        },
    },
    mutations: {
        getSchools(state, schools) {
            state.schools = schools;
        },
    }
}
