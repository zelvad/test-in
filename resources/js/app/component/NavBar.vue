<template>
    <div class="nav-bar">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <router-link :to="{ name: 'home' }" class="nav-link">Главная</router-link>
            </li>
            <li v-if="!this.$store.state.token" class="nav-item">
                <router-link :to="{ name: 'login' }" class="nav-link">Авторизация</router-link>
            </li>
            <li v-if="!this.$store.state.token" class="nav-item">
                <router-link :to="{ name: 'register' }" class="nav-link">Регистрация</router-link>
            </li>
            <li v-if="this.$store.state.token" class="nav-item">
                <button @click.prevent="logout" class="nav-link button-logout">Выйти</button>
            </li>
        </ul>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "NavBar",
    data () {
        return {
            credentials: {
                email: '',
                password: ''
            },
            isAuth: false
        };
    },
    methods: {
        logout () {
            this.$router.push({
                name: 'login'
            });
            this.$store.commit('clearToken')
        }
    },
    mounted () {
        if (this.$store.state.token !== '') {
            return axios.get('/api/user/me', {
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.token
                }
            }).then(() => {
                this.isAuth = true;
            }).catch(() => {
                this.isAuth = false;
                this.$store.commit('clearToken');

            });
        } else {
            this.loading = false;
        }
    },
}
</script>

<style scoped>
    .nav-bar {
        display: flex;
        justify-content: flex-end;
        padding: 1.5rem 1rem;
        border-radius: 5px;
        margin: 20px 0 0 0;
        height: 50px;
    }
    .navbar-nav {
        list-style-type: none;
    }
    .nav-item {
        margin: 0 10px;
    }
    .button-logout {
        background: unset;
        border: unset;
        text-decoration: none;
    }
    .nav-bar .nav-item a {
        color: #000;
    }
</style>
