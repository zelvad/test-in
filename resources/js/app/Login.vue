<template>
    <div class="login">
        <div v-if="loading">
            <span>Loading...</span>
        </div>
        <div v-else class="form-gr">
            <div class="text" style="text-align: center; margin-bottom: 20px">
                <h3>Авторизация</h3>
            </div>
            <div class="alert alert-warning" role="alert" v-if="errorLogin">
                Введен не правильный логин или пароль!
            </div>
            <div class="alert alert-danger" role="alert" v-if="serverError">
                Произошла внутрення ошибка сервера!
            </div>

            <div class="form">
                <form>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" v-model="credentials.email" placeholder="Введите email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" id="password" v-model="credentials.password" placeholder="Введите пароль" required>
                    </div>

                    <button @click.prevent="login" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    name: "Login",
    data () {
        return {
            credentials: {
                email: '',
                password: ''
            },
            loading: true,
            errorLogin: false,
            serverError: false
        };
    },
    mounted () {
        if (this.$store.state.token !== '') {
            return axios.get('/api/user/me', {
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.token
                }
            }).then(() => {
                this.loading = false;
                this.$router.push({
                    name: 'login'
                });
            }).catch(err => function () {
                this.loading = true;
                this.$store.commit('clearToken');
            });
        } else {
            this.loading = false;
        }
    },
    methods: {
        login () {
            axios.post('/api/user/login', this.credentials)
                .then(res => {
                    this.errorLogin = false;
                    this.serverError = false;
                    this.$store.commit('setToken', res.data.access_token);
                    this.$router.push({
                        name: 'home'
                    });
                })
                .catch(err => {
                    if (err.response.status !== 500) {
                        this.errorLogin = true;
                    } else {
                        this.serverError = true;
                    }
                })
        }
    }
}
</script>

<style scoped>
.login {
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.form-gr {
    width: 500px
}
.form {
    width: 100%;
    border: 1px solid #95999c1f;
    padding: 20px;
    border-radius: 5px;
}
</style>
