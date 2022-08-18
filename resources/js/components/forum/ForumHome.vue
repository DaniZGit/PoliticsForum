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
                    <a href="/user/dashboard" class="nav-link text-white" v-if="userStore.isLoggedIn()">Profile</a>
                </li>
                <li class="nav-item" v-if="!userStore.isLoggedIn()">
                    <a href="/user/login" class="nav-link text-white">Login</a>
                </li>
                <li v-if="!userStore.isLoggedIn()">
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
                <forum-left-sidebar :categoryNames="categoryNames" :tags="tags" @tagsToggled="tagsToggled" v-if="categories"></forum-left-sidebar>
            </div>
        </div>

        <!-- Middle content -->
        <div class="col-8 border border-top-0 py-3 border-dark" id="forumContent">
            <router-view @refreshRightSidebar="refreshRightSidebar" :categories="categories" :post="post" :activeTags="activeTags" :key='$route.fullPath'></router-view>
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
<!--
<script>
    import ForumHomeContent from './content/ForumHomeContent.vue'
    import ForumLeftSidebar from './partials/ForumLeftSidebar.vue'
    import ForumRightSidebar from './partials/ForumRightSidebar.vue'

    import { mapStores } from 'pinia'
    import { useUserStore } from '../../stores/userStore'

    export default {
        components: {
            ForumHomeContent,
            ForumLeftSidebar,
            ForumRightSidebar,
        },

        data() {
            return {
                "post": null,
                "categories": null,
                "categoryNames": null,
                "tags": null,
                "activeTags": [],
                "store": useUserStore()
            }
        },

        created() {
            this.fetchCategories()

            this.fetchCategoryNames()

            this.fetchTags()

            console.log("authenticated user")
            console.log(useUserStore())
            console.log(useUserStore().user)
        },

        methods: {
            async fetchCategories() {
                let result = await fetch("/api/categories/posts/details")
                this.categories = await result.json()
                console.log(this.categories)
            },

            async fetchCategoryNames() {
                let result = await fetch("/api/categories/count")
                this.categoryNames = await result.json()
            },

            async fetchTags() {
                let result = await fetch("/api/tags/")
                this.tags = await result.json()
                this.tags.sort((a,b) => {
                if(a.name > b.name) {
                        return 1;
                    } else {
                        return -1;
                    }
                })
                console.log(this.tags)
            },

            refreshRightSidebar(post) {
                this.post = post
                console.log(this.post)
            },

            tagsToggled(activeTags) {
                console.log(activeTags)
                this.activeTags = activeTags
            }
        },

        computed: {
            ...mapStores(useUserStore)
        }
    }
</script>
-->
<script setup>
    import ForumHomeContent from './content/ForumHomeContent.vue'
    import ForumLeftSidebar from './partials/ForumLeftSidebar.vue'
    import ForumRightSidebar from './partials/ForumRightSidebar.vue'

    import { mapStores } from 'pinia'
    import { useUserStore } from '../../stores/userStore'
    import { ref } from '@vue/reactivity'
    import { onMounted } from '@vue/runtime-core'

    let post = ref(null)
    let categories = ref(null)
    let categoryNames = ref(null)
    let tags = ref(null)
    let activeTags = ref([])
    let userStore = useUserStore()

    onMounted(() => {
        fetchCategories()

        fetchCategoryNames()

        fetchTags()

        console.log("authenticated user")
        console.log(userStore)
        console.log(userStore.user)
    })

    // fetch functions
    async function fetchCategories() {
        let result = await fetch("/api/categories/posts/details")
        categories.value = await result.json()
        console.log(categories.value)
    }

    async function fetchCategoryNames() {
        let result = await fetch("/api/categories/count")
        categoryNames.value = await result.json()
    }

    async function fetchTags() {
        let result = await fetch("/api/tags/")
        tags.value = await result.json()
        tags.value.sort((a,b) => {
        if(a.name > b.name) {
                return 1
            } else {
                return -1
            }
        })
        console.log(tags)
    }

    // emits
    const refreshRightSidebar = ((emittedPost) => {
        post.value = emittedPost
    })

    const tagsToggled = ((emmittedActiveTags) => {
        console.log(activeTags.value)
        activeTags.value = emmittedActiveTags
    })
</script>