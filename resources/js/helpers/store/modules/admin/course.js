import {adminCourseService} from '../../../services/admin/course.service';
import overlay from '../../../overlay';

let state = {status: {}, courses: []};

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, courses) {
            state.status = {index: true};
            state.courses = courses;
        },
        indexFailure(state) {
            state.status = {};
            state.courses = [];
        },
    },
    actions: {
        index({commit}) {
            adminCourseService.index()
                .then(
                    courses => {
                        commit('indexSuccess', courses);
                    },
                    error => {
                        commit('indexFailure', error);
                    }
                );
        },
        getCourse({commit}, course_id) {
            adminCourseService.getCourse(course_id).then(
                course => {
                    commit('indexSuccess', course);
                },
                error => {
                    commit('indexSuccess', []);
                }
            );
        },
        update({dispatch}, course) {
            return adminCourseService.updateCourse(course)
                .then(
                    course => {
                        return true;
                    },
                    error => {
                        return false;
                    }
                );
        },
        searchLesson({commit}, search) {
            return adminCourseService.searchLesson(search)
                .then(
                    lessons => {
                        return lessons;
                    },
                    error => {
                        return [];
                    }
                );
        },
        addLesson({commit}, lesson) {
            return adminCourseService.addLesson(lesson)
                .then(
                    lessons => {
                        return lessons.data;
                    },
                    error => {
                        return [];
                    }
                );
        },
        removeLesson({commit}, lesson) {
            return adminCourseService.removeLesson(lesson)
                .then(
                    lessons => {
                        return true;
                    },
                    error => {
                        return false;
                    }
                );
        },
        delete({commit}, course_id) {
            return adminCourseService.deleteCourse(course_id)
                .then(
                    course => {
                        return true;
                    },
                    error => {
                        return false;
                    }
                );
        },
        addCourse({commit}, course) {
            return adminCourseService.addCourse(course)
                .then(
                    res => {
                        return res.data;
                    },
                    error => {
                        return false;
                    }
                );
        },
    },
    getters: {
        courses: state => state.courses,
        status: state => state.status
    }
}
