<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        You are already logged in!
                        Well... can we move on to the <router-link exact-active-class="active" to="/articleList">Articles</router-link>?
                    </div>
                    <div class="card-body">
                        Or, of course, we can just observe this wonderful picture...
                        <img class="align-middle" :src="'/img/chil.jpg'" alt="No chilling :(">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Home"
}
</script>

<style scoped>
    .card-body {
        text-align: center;
    }
</style>
