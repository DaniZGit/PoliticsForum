<template>
    <!-- Navbar -->
    <div class="navbar navbar-expand-md bg-primary navbar-dark fixed-top main-navigation" id="main-navigation">
        <div class="container-fluid d-flex justify-content-between collapse">
            <router-link to="/forum" class="navbar-brand">Interestingly Forum</router-link>

            <div class="input-group w-50">
                <input type="search" class="form-control border-primary rounded shadow-none" placeholder="Iskalni niz" /> 
                <button type="button" class="btn m-0 border-primary border-start-0 bg-white rounded shadow-none">POIŠČI</button>
            </div>

            <ul class="navbar-nav d-flex">
                <li class="nav-item">
                    <a href="/user/dashboard" class="nav-link text-white" v-if="userStore.loggedIn()">Profile</a>
                </li>
                <li class="nav-item" v-if="!userStore.loggedIn()">
                    <a href="/user/login" class="nav-link text-white">Login</a>
                </li>
                <li v-if="!userStore.loggedIn()">
                    <a href="/user/register" class="nav-link">Register</a>
                </li>
            </ul>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>

    <section class="container-fluid row m-0 p-0">
        <!-- Left sidebar -->  
        <div class="col-2 border border-top-0 py-3 border-dark border-end-0">
            <div class="sidebar-categories sticky-top">
                <forum-left-sidebar :categoryNames="categoryNames" :tags="tags" @tagsToggled="tagsToggled" v-if="categories" ref="leftSidebar"></forum-left-sidebar>
            </div>
        </div>

        <!-- Middle content -->
        <div class="col-8 border border-top-0 py-3 border-dark" id="forumContent">
            <router-view @refreshRightSidebar="refreshRightSidebar" :categories="categories" :post="post" :activeTags="activeTags" :key='$route.fullPath' v-if="categories && tags"></router-view>
            <!--<forum-home-content @refreshRightSidebar="refreshRightSidebar"></forum-home-content>-->
        </div>

        <!-- Right sidebar -->
        <div class="col-2 border border-top-0 py-3 border-dark border-start-0">
            <div class="sidebar-categories sticky-top d-flex flex-column" >
                <forum-right-sidebar :post="post" v-if="post"></forum-right-sidebar>
            </div>
        </div>
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
    import ForumHomeContent from './content/ForumHomeContent.vue'
    import ForumLeftSidebar from './partials/ForumLeftSidebar.vue'
    import ForumRightSidebar from './partials/ForumRightSidebar.vue'

    import { mapStores } from 'pinia'
    import { useUserStore } from '../../stores/userStore'
    import { useCategoryStore } from '../../stores/categoryStore'
    import { ref } from '@vue/reactivity'
    import { computed, onMounted, provide } from '@vue/runtime-core'
    import axios from 'axios'

    let userStore = useUserStore()
    let categoriesStore = useCategoryStore()

    let post = ref(null)
    let categories = computed(() => categoriesStore.getCategories())
    let categoryNames = ref(null)
    let tags = ref(null)
    let activeTags = ref([])
    
    const leftSidebar = ref(null)

    onMounted(() => {
        fetchCategories()

        fetchCategoryNames()

        fetchTags()
        
        console.log("authenticated user")
        console.log(userStore.user)
    })

    // fetch functions
    function fetchCategories() {
        categoriesStore.fetchCategories()
    }

    async function fetchCategoryNames() {
       try {
            const res = await axios.get('categories', {
                params: { 
                    includes: ['posts']
                }
            })
            categoryNames.value = res.data
        } catch (error) {
            console.log(error)
        }
    }

    async function fetchTags() {
        try {
            const res = await axios.get('tags')
            tags.value = res.data
        } catch (error) {
            console.log(error)
        }
    }

    // emits
    const refreshRightSidebar = ((emittedPost) => {
        post.value = emittedPost
    })

    const tagsToggled = ((emmittedActiveTags) => {
        console.log(activeTags.value)
        activeTags.value = emmittedActiveTags
    })

    // provides
    provide('tags', tags)
    provide('categories', categories)
</script>