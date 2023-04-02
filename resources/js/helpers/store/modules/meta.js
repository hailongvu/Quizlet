import {adminSettingService} from "../../services/admin/setting.service";

const meta = JSON.parse(localStorage.getItem('meta_data'));

const state = meta ? {status: {index: true}, meta: meta} : {status: {}, meta: []};

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, meta) {
            state.status = {index: true};
            state.meta = meta;
        },
        indexFailure(state) {
            state.status = {};
            state.meta = [];
        }
    },
    actions: {
        index({dispatch,commit}) {
            commit('indexRequest');
            adminSettingService.meta()
                .then(
                    meta => {
                        // save meta data to local storage
                        localStorage.setItem('meta_data', JSON.stringify(meta.data));
                        commit('indexSuccess', meta.data);
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                        commit('indexFailure', error);
                    }
                );
        }
    },
    getters: {
        meta: state => {
            return state.meta;
        }
    }
}
