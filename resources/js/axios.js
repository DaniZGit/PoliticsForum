import axios from 'axios'
import { useUserStore } from './stores/userStore'

axios.defaults.baseURL = 'http://127.0.0.1:8000/api/'
axios.defaults.withCredentials = true;

// pred vsakim requestom pošle authorization header z tokenom
axios.interceptors.request.use(function (config) {
    const store = useUserStore()
    const token = store.user.token;
    config.headers.Authorization = `Bearer ${token}`;

    return config;
});
