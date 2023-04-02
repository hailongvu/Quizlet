import config from '../config';
import handleResponse from './handle-response';
import {authHeader} from '../auth-header';

export const userService = {
    login,
    logout,
    register,
    forgotPassword,
    resetPassword,
    verifyEmail,
    resendVerificationEmail
};

function login(email, password, rememberMe) {
    const requestOptions = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({email, password, rememberMe})
    };
    return fetch(`${config.apiUrl}/auth/login`, requestOptions)
        .then(handleResponse)
        .then(user => {
            // Login successful if status is success
            if (user.status === 'success') {
                // Store user details and bearer token in local storage to keep user logged in between page refreshes
                localStorage.setItem('user', JSON.stringify(user.data.user));
                localStorage.setItem('token', user.data.access_token);
                localStorage.setItem('permissions', JSON.stringify(user.data.permissions));
            }
            return user;
        });
}

function logout() {
    localStorage.clear();
}

function register(user) {
    const requestOptions = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(user)
    };

    return fetch(`${config.apiUrl}/auth/register`, requestOptions)
        .then(handleResponse)
        .then(user => {
            // login after successful registration
            if (user.status === 'success') {
                // Store user details and bearer token in local storage to keep user logged in between page refreshes
                localStorage.setItem('user', JSON.stringify(user.data.user));
                localStorage.setItem('token', JSON.stringify(user.data.access_token));
                localStorage.setItem('permissions', JSON.stringify(user.data.permissions));
            }

            return user;
        });
}

function forgotPassword(email) {
    const requestOptions = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({email: email})
    };

    return fetch(`${config.apiUrl}/auth/forgot-password`, requestOptions)
        .then(handleResponse)
        .then(response => {
                return response;
            }
        );
}

function resetPassword(email, password, password_confirmation, token) {
    const requestOptions = {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            email: email,
            password: password,
            password_confirmation: password_confirmation,
            token: token
        })
    };

    return fetch(`${config.apiUrl}/auth/reset-password`, requestOptions)
        .then(handleResponse)
        .then(response => {
                return response;
            }
        );
}

function verifyEmail(id, hash, expires, signature) {

    let url = `${config.apiUrl}/auth/email/handle-verify/${id}/${hash}?expires=${expires}&signature=${signature}`;
    return fetch(url, {method: 'POST', headers: authHeader()})
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function resendVerificationEmail() {
    return fetch(`${config.apiUrl}/auth/email/resend`, {method: 'POST', headers: authHeader()})
        .then(handleResponse)
        .then(response => {
                return response;
            }
        );
}
