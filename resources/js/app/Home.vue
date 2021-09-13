<template>
    <div>
        <div class="generate-preview">
            <div class="text">
                <h4>Введите ссылку чтобы увидеть ее</h4>
            </div>
            <div class="errors" v-if="createPreviewError">
                <div class="alert alert-danger" role="alert">
                    {{ createErrors.message }}
                </div>
            </div>
            <div class="form" style="margin: 20px 0">
                <form class="form-inline" >
                    <input v-model="credentials.url" style="width: 400px" class="form-control form-control-sm ml-6 w-75" type="text" placeholder="Введите ссылку">
                    <i @click.prevent="createPreview" style="margin-left: 10px; cursor: pointer" class="fas fa-search" aria-hidden="true"></i>
                </form>
            </div>
        </div>
        <div v-if="displayLinks">
            <div class="links" v-if="displayLinks" :key="componentKey">
                <h3>Список ссылок</h3>
                <div v-for="(link, id) in linksPreviews" class="preview-link" :key="id">
                    <a v-bind:href="link.url" class="link-a">
                        <div class="alert alert-light preview" role="alert" v-if="link.description">
                            <div class="img" style="width: 160px; height: 100%;" v-if="link.image">
                                <img v-bind:src="link.image" alt="" style="width: 140px; height: 80px;">
                            </div>
                            <div class="info" style="margin: 0 20px;">
                                <div class="link"><a>{{ link.url }}</a></div>
                                <div class="author"><a><b>{{ link.author }}</b></a></div>
                                <div class="title"><b>{{ link.title }}</b></div>
                                <div class="description">{{ link.description }}</div>
                            </div>
                        </div>
                        <div class="alert alert-light preview" role="alert" v-else>
                            <div class="info">
                                <div class="link"><a>{{ link.url }}</a></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PreviewsList from "./component/PreviewsList";
import axios from "axios";
import GeneratePreview from "./component/GeneratePreview";

export default {
    name: "Home",
    components: {
        GeneratePreview,
        PreviewsList
    },
    data () {
        return {
            credentials: {
                url: ''
            },
            linksPreviews: {},
            displayLinks: false,
            componentKey: 0,
            isAuth: false,
            createPreviewError: false,
            createErrors: {}
        };
    },
    methods: {
        createPreview() {
            return axios.post('/api/previews/create', this.credentials, {
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.token
                }
            }).then((res) => {
                if (!this.$store.state.token) {
                    console.log(1)
                    this.linksPreviews = [
                        res.data
                    ];
                    this.displayLinks = true;
                    console.log(this.linksPreviews)
                } else {
                    this.forceRerender()
                }
            }).catch(err => {
                this.createPreviewError = true;
                this.createErrors = err.response.data;
                console.log(this.createErrors)
            });
        },
        forceRerender() {
            axios.get('/api/previews/list', {
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.token
                }
            }).then((res) => {
                this.linksPreviews = res.data;
                this.displayLinks = true;
            }).catch(err => function () {
                this.displayLinks = false;
                if (err.response.data.status_code === 500) {
                    this.$router.push({
                        name: 'login'
                    })
                }
            })
            this.componentKey += 1
        }
    },
    mounted () {
        if (this.$store.state.token !== '') {
            axios.get('/api/previews/list', {
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.token
                }
            }).then((res) => {
                this.linksPreviews = res.data;
                this.displayLinks = true;
            }).catch(err => function () {
                this.displayLinks = false;
                console.log(err);
            });
        }

        if (this.$store.state.token !== '') {
            axios.get('/api/user/me', {
                headers: {
                    'Authorization': 'Bearer ' + this.$store.state.token
                }
            }).then(() => {
                this.isAuth = true;
            }).catch(err => function () {
                this.isAuth = false;
                this.$store.commit('clearToken');
                /*https://getbootstrap.com/docs/4.3/components/dropdowns/*/
            });
        } else {
            this.loading = false;
        }
    },
}
</script>

<style scoped>
    .generate-preview {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .links {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .preview {
        display: flex;
        align-items: center;
        border: 1px solid #95999c1f;
    }
    .preview-link {
        color: #000000;
        text-decoration: none;
        width: 700px;
        margin: 20px 0 0 0;
    }
    .link-a {
        color: #000000;
        text-decoration: none;
        width: 700px;
        margin: 20px 0 0 0;
    }
</style>
