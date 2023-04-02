import config from '../config';
import { authHeader } from '../auth-header';
import handleResponse from './handle-response';


export const courseService = {
    getLessonByCourseId,
    createCourse,
    deleteCourse,
    getCourseInfo,
    updateCourse,
}

function getLessonByCourseId(course_id) {
    console.log(course_id);
    return fetch(`${config.apiUrl}/course/${course_id}/lesson`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}
//create new course
function createCourse(data) {
    return fetch(`${config.apiUrl}/course`, {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}
//delete course by course id
function deleteCourse(course_id) {
    return fetch(`${config.apiUrl}/course/${course_id}`, {
        method: 'DELETE',
        headers: authHeader()
    }).then(handleResponse);
}
//get course from database by course id
function getCourseInfo(course_id) {
    return fetch(`${config.apiUrl}/course/${course_id}`, {
        method: 'GET',
        headers: authHeader()
    }).then(handleResponse).then(data => {
        return data.data.course;
    });
}
//update course
function updateCourse(data) {
    return fetch(`${config.apiUrl}/course/${data.id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}

