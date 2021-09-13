<template>
    <div class="generate-preview">
        <div class="text">
            <h4>Введите ссылку чтобы увидеть ее</h4>
        </div>
        <div class="form" style="margin: 20px 0">
            <form class="form-inline">
                <input v-model="credentials.url" style="width: 400px" class="form-control form-control-sm ml-6 w-75" type="text" placeholder="Введите ссылку">
                <i @click.prevent="createPreview" style="margin-left: 10px; cursor: pointer" class="fas fa-search" aria-hidden="true"></i>
            </form>
        </div>
    </div>
</template>

<script>
import PreviewList from './PreviewsList'
import axios from "axios";
export default {
    name: "GeneratePreview",
    components: {
        PreviewList
    },
    data () {
        return {
            credentials: {
                url: ''
            }
        };
    },
    methods: {
        createPreview() {
            return axios.post('/api/previews/create', this.credentials, {
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.token
                }
            }).then(() => {
                this.$router.push({
                    name: login
                });
            }).catch(err => function () {
                this.loading = true;
                this.$store.commit('clearToken');
            });
        }
    }
}
</script>

<style scoped>
    .generate-preview {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
</style>
