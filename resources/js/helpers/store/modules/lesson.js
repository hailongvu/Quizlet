import { lessonService } from '../../services/lesson.service';
import lesson from './lesson';

let state = { status: {}, user: null };
export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = { indexing: true };
        },
        indexSuccess(state, folders) {
            state.status = { index: true };
            state.folders = folders;
        },
        indexFailure(state) {
            state.status = {};
            state.folders = [];
        }
    },
    actions: {
        getLessonDetailRelearn({ commit }, lesson_id) {
            return lessonService.showLessonDetailRelearn(lesson_id)
                .then(
                    relearn => {
                        return Promise.resolve(relearn);
                    }
                );
        },
        getLessonDetailNotLearn({ commit }, lesson_id) {
            return lessonService.showLessonDetailNotLearn(lesson_id)
                .then(
                    notlearn => {
                        return Promise.resolve(notlearn);
                    }
                );
        },
        getLessonInfo({ commit }, lesson_id) {
            return lessonService.getLessonInfo(lesson_id)
                .then(
                    lesson => {
                        return Promise.resolve(lesson);
                    }
                );
        },
        createLesson({ dispatch }, data) {
            lessonService.createLesson(data)
                .then(
                    lessons => {
                        dispatch('alert/success', 'Lesson created', { root: true });
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, { root: true });
                    }
                );
        },
        getLessonInfo({ dispatch }, lesson_id) {
            return lessonService.getLessonInfo(lesson_id)
                .then(
                    detail => {
                        return detail;
                    },
                    error => {
                        dispatch('alert/error', error, { root: true });
                        // return back to previous page
                        window.history.back();
                    }
                );
        },
        getLessonInfoToEdit({ dispatch }, lesson_id) {
            return lessonService.getLessonInfoToEdit(lesson_id)
                .then(
                    data => {
                        return data;
                    },
                    error => {
                        dispatch('alert/error', error, { root: true });
                        // return back to previous page
                        window.history.back();
                    }
                );
        },
        updateLesson({ dispatch }, data) {
            return lessonService.updateLesson(data)
                .then(
                    response => {
                        return true;
                    },
                    error => {
                        return false;
                    }
                );
        },
        deleteLesson({ dispatch }, lesson_id) {
            lessonService.deleteLesson(lesson_id)
                .then(
                    lessons => {
                        dispatch('alert/success', 'Lesson deleted', { root: true });
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, { root: true });
                    }
                );
        },
    }
}
