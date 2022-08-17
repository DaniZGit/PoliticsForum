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
                    <router-link to="/user/dashboard" class="nav-link text-white" v-if="userStore.isLoggedIn()">Profile</router-link>
                </li>
                <li class="nav-item" v-if="!userStore.isLoggedIn()">
                    <router-link to="/user/login" class="nav-link text-white">Login</router-link>
                </li>
                <li v-if="!userStore.isLoggedIn()">
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
    import {computed, onBeforeUnmount, onMounted, onUnmounted, ref, toRef, toRefs } from 'vue'
    import { useUserStore } from '../../stores/userStore'
    import { usePostsStore } from '../../stores//postsStore'
    // import { storeToRefs } from 'pinia'

    const userStore = useUserStore()
    const postsStore = usePostsStore()
    
    /*                 */
    /* fetch users */
    /*                 */
    let users = computed(() => userStore.users)
    let usersFetchTimer = null
    onMounted(async () => {
        // fetch users at the start
        userStore.fetchUsers()

        // fetch users every 10 seconds (in background)
        usersFetchTimer = setInterval(() => {
            userStore.fetchUsers()
        }, 10000)
    })

    // let postStates = storeToRefs(postsStore)
    let posts = computed(() => postsStore.posts)
    let postsFetchTimer = null
    onMounted(() => {
        // fetch posts at the start
        postsStore.fetchPosts()

        // fetch posts every 10 seconds (in background)
        postsFetchTimer = setInterval(() => {
            postsStore.fetchPosts()
        }, 10000)
    })
    onBeforeUnmount(() => {
        clearInterval(postsFetchTimer)
        clearInterval(usersFetchTimer)
    })
</script>