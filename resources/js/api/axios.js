import axios from 'axios';
import store from '../store'

const baseURL = '/api/';


const instance = axios.create();

instance.defaults.timeout = 30000; // 所有接口30s超时
instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// 所有请求头设置 CSRF Token
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    instance.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// 请求统一处理
instance.interceptors.request.use(async config => {
    if (config.url && config.url.charAt(0) === '/') {
        config.url = `${baseURL}${config.url}`;
    }

    if (store.state.userInfo.token) {
        // let each request carry token
        // ['X-Token'] is a custom headers key
        // please modify it according to the actual situation
        config.headers['Authorization'] = 'Bearer ' + store.state.userInfo.token
    }
    return config;
}, error => Promise.reject(error));

// 对返回的内容做统一处理
instance.interceptors.response.use(response => {
    // const res = response.data
    // if (res.code == 1) {
    //     console.log(response.data);
    //     return response.data;
    // }
    return response.data;
}, error => {
    if (error) {
        console.log(JSON.stringify(error));
    } else {
        console.log('出了点问题，暂时加载不出来，请稍后再来吧');
    }
    return Promise.reject(error);
});

export default instance;
