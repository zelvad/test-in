<template>
    <div class="links" v-if="displayLinks" :key="componentKey">
        <h3>Список ссылок</h3>
        <div v-for="(link, id) in linksPreviews" class="preview-link" :key="id">
            <a v-bind:href="link.url" class="link-a">
                <div class="alert alert-light preview" role="alert" v-if="link.description">
                    <div class="img" style="width: 160px; height: 100%;" v-if="link.image">
                        <img v-bind:src="link.image" alt="" style="width: 140px; height: 80px;">
                    </div>
                    <div class="info">
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
</template>

<script>
import axios from "axios";

export default {
    name: "PreviewsList",
    data() {
        return {
            linksPreviews: {},
            displayLinks: false,
            componentKey: 0
        };
    },
    methods: {
        forceRerender() {
            this.componentKey += 1
        }
    },
    mounted() {
        this.$root.$on('forceRerender', () => {
            // your code goes here
            this.forceRerender();
        });

        return axios.get('/api/previews/list', {
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
}
</script>

<style scoped>
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
