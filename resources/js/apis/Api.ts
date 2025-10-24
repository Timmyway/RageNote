import axios from "axios";

const Api = axios.create({
    baseURL: window.apiUrl,
    withCredentials: true,
    headers: { Accept: 'application/json' }
});

// Api.defaults.withCredentials = true;
// Api.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export {Api, axios };
