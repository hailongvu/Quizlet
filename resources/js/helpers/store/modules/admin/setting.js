import { adminSettingService } from "../../../services/admin/setting.service";

const settings = JSON.parse(localStorage.getItem("settings")) || null;
const state = settings ? { status: { index: true }, settings } : { status: {}, settings: [] };

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = { indexing: true };
        },
        indexSuccess(state, settings) {
            state.status = { index: true };
            state.settings = settings;
        },
        indexFailure(state) {
            state.status = {};
            state.settings = [];
        },
        updateRequest(state) {
            state.status = { updating: true };
        },
        updateSuccess(state) {
            state.status = { update: true };
        },
        updateFailure(state) {
            state.status = {};
        }
    },
    actions: {
        index({ commit }) {
            commit("indexRequest");
            adminSettingService.index()
                .then(
                    settings => {
                        commit("updateSuccess");
                    },
                    error => {
                        commit("indexFailure", error);
                    }
                );
        },
        update({ commit }, data) {
            commit("updateRequest");
            return adminSettingService.update(data.key, data.value)
                .then(
                    settings => {
                        commit("indexSuccess", settings);
                        return true;
                    },
                    error => {
                        commit("updateFailure", error);
                        return false;
                    }
                );
        },
        sendNotification({ commit }, data) {
            return adminSettingService.sendNotification(data)
                .then(
                    settings => {
                        return true;
                    },
                    error => {
                        return false;
                    }
                );
        }
    },
    getters: {
        settings: state => {
            return state.settings;
        }
    }
};
