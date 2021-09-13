<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav-bar></nav-bar>
                </div>
                <div class="col-lg-12">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import NavBar from "./component/NavBar";
import axios from "axios";
export default {
    name: "App",
    components: {
        NavBar
    }
}
</script>

<style scoped>

</style>
