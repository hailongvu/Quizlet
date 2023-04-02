import config from '../config';
import { authHeader } from '../auth-header';
import handleResponse from './handle-response';

export const dashboardService = {
    quotes,
    analytics,
};

function quotes() {
    return fetch('https://quote-garden.onrender.com/api/v3/quotes?limit=3')
        .then(handleResponse)
        .then(quotes => {
            return quotes;
        }
    );
}

function analytics(type) {
    return fetch(`${config.apiUrl}/admin/analytics?type=${type}`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}
