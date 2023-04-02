import {adminLessonService} from '../../../services/admin/lesson.service';
import overlay from '../../../overlay';

let state = {
    lessons: [],
    status: {}
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, lessons) {
            state.status = {index: true};
            state.lessons = lessons;
        },
        indexFailure(state) {
            state.status = {};
            state.lessons = [];
        }
    },
    actions: {
        index({commit}) {
            commit('indexRequest');
            adminLessonService.index()
                .then(
                    lessons => {
                        commit('indexSuccess', lessons);
                    },
                    error => {
                        commit('indexFailure', error);
                    }
                );
        },
        create({dispatch}, data) {
            return adminLessonService.create(data)
                .then(
                    response => {
                        return response.id;
                    },
                    error => {
                        return 0;
                    }
                );
        },
        importLesson({dispatch}, data) {
            return adminLessonService.importLesson(data)
                .then(
                    res => {
                        return res.id;
                    },
                    error => {
                        return 0;
                    }
                );
        },
        deleteLesson({dispatch}, id) {
            return adminLessonService.deleteLesson(id)
                .then(
                    response => {
                        return true;
                    },
                    error => {
                        return false;
                    }
                );
        },
        exportLesson({dispatch}, id) {
            return adminLessonService.exportLesson(id);
        },
        getLesson({dispatch}, id) {
            return adminLessonService.getLesson(id)
                .then(
                    sets => {
                        return sets;
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                        // return back to previous page
                        window.history.back();
                    }
                );
        },
        updateLesson({dispatch}, data) {
            return adminLessonService.updateLesson(data)
                .then(
                    response => {
                        dispatch('index');
                        return true;
                    },
                    error => {
                        return false;
                    }
                );
        }
    },
    getters: {
        lessons: state => {
            return state.lessons;
        }
    }
}
