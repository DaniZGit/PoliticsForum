<template>
    <!-- Navbar -->
    <div class="navbar navbar-expand-md bg-primary navbar-dark fixed-top main-navigation" id="main-navigation">
        <div class="container-fluid d-flex justify-content-between collapse">
            <a href="/forum" class="navbar-brand">Interestingly Forum</a>

            <div class="input-group w-50">
                <input type="search" class="form-control border-primary rounded shadow-none" placeholder="Iskalni niz" /> 
                <button type="button" class="btn m-0 border-primary border-start-0 bg-white rounded shadow-none">POIŠČI</button>
            </div>

            <ul class="navbar-nav d-flex">
                <li class="nav-item">
                    <router-link to="/user/dashboard" class="nav-link text-white" v-if="store.getters.user.is_authenticated">Profile</router-link>
                </li>
                <li class="nav-item" v-if="!store.getters.user.is_authenticated">
                    <router-link to="/user/login" class="nav-link text-white">Login</router-link>
                </li>
                <li v-if="!store.getters.user.is_authenticated">
                    <router-link to="/user/register" class="nav-link">Register</router-link>
                </li>
            </ul>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>

    <section class="container-fluid row m-0 p-0">
        <router-view :users=users></router-view>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center w-100" id="footer">
        <div class="container-fluid position-relative">
            <p class="lead m-0 mt-4">Copyrighz &copy; 2022 Frontend Bootcamp</p>

            <a href="#" class="position-absolute end-0 bottom-0 top-0"><i class="bi bi-arrow-up-circle h1 m-0"></i></a>
        </div>
    </footer>

</template>


<script setup>
    import {onMounted, ref } from 'vue'
    import { useStore } from 'vuex'

    const store = useStore()
    
    /*                 */
    /* fetch users */
    /*                 */
    let users = ref([]);
    onMounted(async () => {
        console.log('users')
        let res = await fetch('/api/users')
        let data = await res.json()
        users.value = data
        console.log(users.value)
    })
</script>