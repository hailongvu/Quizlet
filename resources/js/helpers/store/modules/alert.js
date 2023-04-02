import Swal from "sweetalert2";

let state = {
    icon: null,
    message: null,
};

const actions = {
    success({ commit }, message) {
        commit('success', message);
    },
    error({ commit }, message) {
        commit('error', message);
    },
    info({ commit }, message) {
        commit('info', message);
    },
    clear({ commit }, message) {
        commit('success', message);
    },
};

const mutations = {
    success(state, message) {
        state.icon = 'success';
        state.message = message;
        alert(state.icon, state.message);
    },
    error(state, message) {
        state.icon = 'error';
        state.message = message;
        alert(state.icon, state.message);
    },
    info(state, message) {
        state.icon = 'info';
        state.message = message;
        alert(state.icon, state.message);
    },
    clear(state) {
        state.icon = null;
        state.message = null;
    },
};

function alert(icon, message) {
    Swal.fire({
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 1200
    }).then(r => {
        // console.log(r);
    });
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
};
