import { courseService } from '../../services/course.service';
import course from './course';

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
        getLessonByCourseId({ commit }, course_id) {
            return courseService.getLessonByCourseId(course_id)
                .then(
                    lesson => {
                        return Promise.resolve(lesson);
                    }
                );
        },
        createCourse({ dispatch }, data) {
            courseService.createCourse(data)
                .then(
                    courses => {
                        dispatch('alert/success', 'Course created', { root: true });
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, { root: true });
                    }
                );
        },
        deleteCourse({ dispatch }, course_id) {
            courseService.deleteCourse(course_id).then(
                courses => {
                    dispatch('alert/success', 'Course deleted', { root: true });
                    dispatch('index');
                },
                error => {
                    dispatch('alert/error', error, { root: true });
                }
            );
        },
        getCourseInfo({ dispatch }, course_id) {
            return courseService.getCourseInfo(course_id)
                .then(
                    course => {
                        return Promise.resolve(course);
                    }
                );
        },
        updateCourse({ dispatch }, data) {
            courseService.updateCourse(data)
                .then(
                    courses => {
                        dispatch('alert/success', 'Course updated', { root: true });
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, { root: true });
                    }
                );
        },
    }
}
