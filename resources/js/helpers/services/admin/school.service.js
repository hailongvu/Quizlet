import config from '../../config';
import {authHeader} from '../../auth-header';
import handleResponse from './../handle-response';

export const adminSchoolService = {
    getAllSchools,
    addSchool,
    getSchool,
    update,
    searchUser,
    addManager,
    getManager,
    removeManager,
    addClass,
    getClass,
    removeClass
};

function getAllSchools() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`${config.apiUrl}/admin/school`, requestOptions).then(handleResponse).then(response => {
        return response.data;
    });
}

function addSchool(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`${config.apiUrl}/admin/school`, requestOptions).then(handleResponse);
}

function getSchool(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`${config.apiUrl}/admin/school/${id}?type=all`, requestOptions).then(handleResponse).then(response => {
        return response.data;
    });
}


function update(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data.school)
    };
    return fetch(`${config.apiUrl}/admin/school/${data.school.id}?type=${data.type}`, requestOptions).then(handleResponse);
}

function searchUser(data) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`${config.apiUrl}/admin/school/${data.schoolId}?type=search_user&keyword=${data.keyword}`, requestOptions).then(handleResponse).then(response => {
        return response;
    });
}

function addManager(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`${config.apiUrl}/admin/school/${data.schoolId}?type=add_manager`, requestOptions).then(handleResponse);
}
function getManager(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`${config.apiUrl}/admin/school/${id}?type=managers`, requestOptions).then(handleResponse).then(response => {
        return response;
    });
}

function removeManager(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`${config.apiUrl}/admin/school/${data.schoolId}?type=remove_manager`, requestOptions).then(handleResponse);
}

function addClass(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`${config.apiUrl}/admin/school/${data.schoolId}?type=add_class`, requestOptions).then(handleResponse);
}

function getClass(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`${config.apiUrl}/admin/school/${id}?type=classes`, requestOptions).then(handleResponse).then(response => {
        return response;
    });
}

function removeClass(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`${config.apiUrl}/admin/school/${data.schoolId}?type=remove_class`, requestOptions).then(handleResponse);
}
