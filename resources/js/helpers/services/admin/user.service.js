import config from '../../config';
import {authHeader} from '../../auth-header';
import handleResponse from './../handle-response';

export const adminUserService = {
    index,
    create,
    destroy,
    getUserById,
    update
}

function index() {
    return fetch(`${config.apiUrl}/admin/user`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function create(user) {
    return fetch(`${config.apiUrl}/admin/user`, {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(user)
    })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function destroy(id) {
    return fetch(`${config.apiUrl}/admin/user/${id}`, {
        method: 'DELETE',
        headers: authHeader()
    })
        .then(handleResponse);
}

function getUserById(id) {
    return fetch(`${config.apiUrl}/admin/user/${id}/edit`, {
        method: 'GET',
        headers: authHeader()
    })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function update(user) {
    return fetch(`${config.apiUrl}/admin/user/${user.id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(user)
    })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}
