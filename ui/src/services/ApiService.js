import axios from 'axios'

export function login(username, password) {
    const data = new FormData();
    data.append('username', username);
    data.append('password', password);

    return axios.post(window.config.loginUrl, data)
        .then((response) => {
            if (response.data.token) {
                localStorage.setItem('token', response.data.token);
            }
        })
    ;
}

export function logout() {
    localStorage.removeItem('token');
    window.location.reload();
}

export function isLoggedIn() {
    const token = localStorage.getItem('token');

    return (null !== token && undefined !== token);
}

export function search(q) {
    const url = window.config.searchUrl + '?q=' + q;

    const token = localStorage.getItem('token');

    const config = {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    };

    return axios.get(url, config)
        .then((response) => {
            return response.data.customers.items;
        })
        .catch((error) => {
            if (error.response.status === 403) {
                logout();
            }
            else {
                alert(error);
            }
        })
    ;
}
