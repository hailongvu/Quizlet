import config from '../config';
import { authHeader } from '../auth-header';
import handleResponse from './handle-response';

export const lessonService = {
    showLessonDetailRelearn,
    showLessonDetailNotLearn,
    getLessonInfo,
    createLesson,
    updateLesson,
    deleteLesson,
    getLessonInfoToEdit
}
//show lesson detail of relearn
function showLessonDetailRelearn(lesson_id) {
    console.log(lesson_id);
    let user = JSON.parse(localStorage.getItem('user'));
    let user_id = user.id;
    return fetch(`${config.apiUrl}/user/${user_id}?type=detail_split&lesson_id=${lesson_id}`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data.relearn;
        }
        );
}

//show lesson detail of not learn
function showLessonDetailNotLearn(lesson_id) {
    console.log(lesson_id);
    let user = JSON.parse(localStorage.getItem('user'));
    let user_id = user.id;
    return fetch(`${config.apiUrl}/user/${user_id}?type=detail_split&lesson_id=${lesson_id}`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data.notLearn;
        }
        );
}

//show lesson info
function getLessonInfo(lesson_id) {
    console.log(lesson_id);
    return fetch(`${config.apiUrl}/lesson/${lesson_id}`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data.lesson;
        });
}
//show lesson info to edit
function getLessonInfoToEdit(lesson_id) {
    console.log(lesson_id);
    return fetch(`${config.apiUrl}/lesson/${lesson_id}`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}
//create new lesson
function createLesson(data) {
    data.type = 'general';
    return fetch(`${config.apiUrl}/lesson`, {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}
//update lesson
function updateLesson(data) {
    return fetch(`${config.apiUrl}/lesson/${data.id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}
//delete lesson by lesson id
function deleteLesson(lesson_id) {
    return fetch(`${config.apiUrl}/lesson/${lesson_id}`, {
        method: 'DELETE',
        headers: authHeader()
    }).then(handleResponse);
}
