import Vuex from 'vuex';
import Vue from "vue";
import axios from "axios";

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        token: localStorage.getItem('auth') || '',
        isAuth: false
    },
    mutations: {
        setToken (state, token) {
            localStorage.setItem('auth', token);
            state.token = token;
        },
        clearToken (state) {
            localStorage.removeItem('auth');
            state.token = '';
        }
    }
});
