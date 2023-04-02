import config from '../../config';
import {authHeader} from '../../auth-header';
import handleResponse from './../handle-response';

export const adminSettingService = {
    index,
    meta,
    update,
    sendNotification
}

function index() {
    return fetch(`${config.apiUrl}/admin/settings`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            localStorage.setItem('settings', JSON.stringify(data.data));
            return data.data;
        });
}

function update(key, value) {
    if (key === 'logo' || key === 'favicon') {
        let formData = new FormData();
        formData.append('value', value);
        let headers = authHeader();
        delete headers['Content-Type'];
        return fetch(`${config.apiUrl}/admin/settings/${key}`, {
            method: 'POST',
            headers: headers,
            body: formData
        })
            .then(handleResponse)
            .then(data => {
                return data;
            });
    } else {
        return fetch(`${config.apiUrl}/admin/settings/${key}`, {
            method: 'POST',
            headers: authHeader(),
            body: JSON.stringify({value: value})
        })
            .then(handleResponse)
            .then(data => {
                return data;
            });
    }
}

function meta() {
    return fetch(`${config.apiUrl}/meta`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data;
        });
}

function sendNotification(data) {
    let requestOption = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json; charset=utf-8',
            'Authorization': 'Basic Nzg1NGQxZTktMDVmMy00YTQwLTk4NmQtOWRmYWE2MjFhOTg2'
        },
        body: JSON.stringify({
            app_id: "1a3c6ac3-1e41-4338-bb97-99fb99ae5140",
            contents: {"en": data.message},
            headings: {"en": data.title},
            included_segments: [data.audience],
            url: data.launchUrl ?? null,
        })
    }

    return fetch(`https://onesignal.com/api/v1/notifications`, requestOption)
        .then(handleResponse)
}
