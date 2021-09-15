import axios from "axios";
import {store} from "./store";

function checkAuth() {
    return axios.get('/api/user/me', {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('auth')
        }
    });
}

export const authTestD = function () {
    var test;

    checkAuth().then(res => {test = res})
    checkAuth().catch(res => {test = res})

    console.log(test)

    return test;
};
