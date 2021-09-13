<template>
  <div class="register">
      <div v-if="loading">
          <span>Loading...</span>
      </div>
      <div class="form-gr" v-else>
          <div class="text" style="text-align: center; margin-bottom: 20px">
              <h3>Регистрация</h3>
          </div>
          <div class="form-group" v-if="has_error">
              <div class="alert alert-warning" role="alert" v-if="error === 422">
                  Ошибка валидации, проверьте введенные данные!
              </div>
              <div class="alert alert-danger" role="alert" v-else>
                  Произошла внутрення ошибка сервера!
              </div>
          </div>
          <div class="form">
              <form>
                  <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.name }">
                      <label for="email">Имя</label>
                      <input type="email" class="form-control" id="name" v-model="credentials.name" placeholder="Введите имя">
                      <span class="help-block" v-if="has_error && errors.name">{{ errors.name[0] }}</span>
                  </div>
                  <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.email }">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" v-model="credentials.email" placeholder="Введите email">
                      <span class="help-block" v-if="has_error && errors.email">{{ errors.email[0] }}</span>
                  </div>
                  <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.password }">
                      <label for="password">Пароль</label>
                      <input type="password" class="form-control" id="password" v-model="credentials.password" placeholder="Введите пароль">
                      <span class="help-block" v-if="has_error && errors.password">{{ errors.password[0] }}</span>
                  </div>
                  <div class="form-group">
                      <label for="password">Повторение пароля</label>
                      <input type="password" class="form-control" id="password_confirmation" v-model="credentials.password_confirmation" placeholder="Введите пароль">
                  </div>
                  <button @click.prevent="register" class="btn btn-primary">Регистрация</button>
              </form>
          </div>
      </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Register",
    data () {
        return {
            credentials: {
                name: '',
                email: '',
                password: '',
                password_conformation: ''
            },
            has_error: false,
            error: '',
            errors: {},
            success: false,
            loading: true
        };
    },
    methods: {
        register() {
            axios.post('/api/user/register', this.credentials)
                .then(res => {
                    this.$router.push({
                        name: 'login'
                    });
                })
                .catch(err => {
                    this.has_error = true;
                    this.error = err.response.status;
                    this.errors = err.response.data.errors || {};
                    console.log(this.errors)
                })
        }
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
                    name: 'home'
                });
            }).catch(err => function () {
                this.loading = true;
                this.$store.commit('clearToken');
            });
        } else {
            this.loading = false;
        }
    },
}
</script>

<style scoped>
.register {
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
