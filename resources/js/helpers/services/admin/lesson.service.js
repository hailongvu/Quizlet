import config from '../../config';
import {authHeader} from '../../auth-header';
import handleResponse from './../handle-response';

export const adminLessonService = {
    index,
    create,
    importLesson,
    deleteLesson,
    exportLesson,
    getLesson,
    updateLesson
}

function index() {
    return fetch(`${config.apiUrl}/admin/lesson`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function create(data) {
    data.type = 'general';
    return fetch(`${config.apiUrl}/admin/lesson`, {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}

function importLesson(data) {
    let headers = authHeader();
    delete headers['Content-Type'];
    return fetch(`${config.apiUrl}/admin/lesson`, {
        method: 'POST',
        headers: headers,
        body: data
    }).then(handleResponse);
}

function deleteLesson(id) {
    return fetch(`${config.apiUrl}/admin/lesson/${id}`, {
        method: 'DELETE',
        headers: authHeader()
    }).then(handleResponse);
}

function exportLesson(id) {
    let headers = authHeader();
    delete headers['Content-Type'];
    delete headers['Accept'];
    headers['Accept'] = 'application/csv';
    headers['Content-Type'] = 'application/csv';
    return fetch(`${config.apiUrl}/admin/lesson/${id}`, {
        method: 'GET',
        headers: headers,
    }).then((res)=>{
        if(res.status === 200){
            // download attachment file
            let filename = '';
            let disposition = res.headers.get('Content-Disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
                let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                let matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) {
                    filename = matches[1].replace(/['"]/g, '');
                }
                return res.blob().then(blob => {
                        if (window.navigator.msSaveOrOpenBlob) {
                            window.navigator.msSaveBlob(blob, filename);
                        } else {
                            let elem = window.document.createElement('a');
                            elem.href = window.URL.createObjectURL(blob);
                            elem.download = filename;
                            document.body.appendChild(elem);
                            elem.click();
                            document.body.removeChild(elem);
                        }
                        return true;
                    }
                );
            }
            return false;
        }else{
            return false;
        }
    });
}

function getLesson(id) {
    return fetch(`${config.apiUrl}/admin/lesson/${id}/edit`, {
        method: 'GET',
        headers: authHeader()
    }).then(handleResponse).then(data => {
        return data.data;
    });
}

function updateLesson(data) {
    return fetch(`${config.apiUrl}/admin/lesson/${data.id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}

