<template>
    <div class="wrapper col-2 m-0 p-0">
        <!-- Sidebar -->
        <div class="sticky-top py-3">
            <nav id="sidebar">
                <ul class="list-unstyled components">
                    <li 
                        v-for="(tab, key) in tabs" :key="tab"
                        class="p-2"
                        :class="[tab.active ? 'activeTab' : '']" 
                        @click="tabClickHandler(key)">
                        <a class="text-decoration-none">{{ key }}</a>
                    </li>
                </ul>
            </nav>
        </div>
        

    </div>
    <div class="col-10">
        <user-info-tab v-if="tabs['PROFILE'].active"></user-info-tab>
        <user-posts-tab v-if="tabs['POSTS'].active"></user-posts-tab>
        <user-comments-tab v-if="tabs['COMMENTS'].active"></user-comments-tab>
    </div>
</template>

<script setup>
    import { onMounted, ref } from "vue";
    import { useRouter, useRoute } from 'vue-router'
    import VueCookie from 'vue-cookie'
    import { useStore } from 'vuex'
    import axios from 'axios'

    // components
    import UserInfoTab from './partials/UserInfoTab.vue'
    import UserPostsTab from './partials/UserPostsTab.vue'
    import UserCommentsTab from './partials/UserCommentsTab.vue'
    
    // variables
    const cookie = VueCookie
    const router = useRouter()
    const route = useRoute()
    const store = useStore()

    let currentActiveTab = 'PROFILE'
    let tabs = ref({
        'PROFILE': {
            component: UserInfoTab,
            active: true
        },
        'POSTS': {
            component: UserPostsTab,
            active: false
        },
        'COMMENTS': {
            component: UserCommentsTab,
            active: false
        },
    })

    onMounted(async () => {
        console.log(store.getters.user)
        if (!store.getters.user.is_authenticated)
            router.push({ name: 'UserLogin' })
        else {
            // window.axios.Headers['Authorization'] = `Bearer ${cookie.get('token')}`
            const res = await axios.get('/api/user', {
                headers: { Authorization: `Bearer ${store.getters.user.token}` }
            })
            if (res.status === 200) {
                if(res.errors) {
                    console.log(res.errors)
                } else {
                    console.log(res.data)
                }
                
            } else {
                console.log(res.data)
            }
        }
    })

    function tabClickHandler(tabKey) {
        console.log(tabs.value)
        tabs.value[currentActiveTab].active = false
        tabs.value[tabKey].active = true
        currentActiveTab = tabKey
    }

    async function logoutUser() {
        // window.axios.defaults.headers.common['Authorization'] = `Bearer ${cookie.get('token')}`
        await axios.get("sanctum/csrf-cookie");

        const res = await axios.get('/api/users/logout', {
            // headers: { Authorization: `Bearer ${cookie.get('token')}` }
            headers: { Authorization: `Bearer ${store.getters.user.token}` }
        })
        
        if (res.status === 200) {
            if(res.errors) {
                console.log(res.errors)
            } else {
                //cookie.delete('token')
                store.dispatch('user', {token: null, is_authenticated: false, data: null})
                router.push({ name: 'UserLogin' })
            }
            
        } else {
            console.log(res.data)
        }
    }
</script>

<style scoped>
    #sidebar ul {
        border-top: 1px solid #0d6efd;
        border-bottom: 1px solid #0d6efd;
        text-align: center;
    }

    #sidebar li:nth-child(even) {
        border-top: 1px solid #0d6efd;
        border-bottom: 1px solid #0d6efd;
    }

    #sidebar li:hover {
        cursor: pointer;
        background: #0d6efd;
        
    }
    #sidebar li:hover a{
        color: white;
        
    }

    .activeTab {
        background: #0d6efd;
        font-weight: bolder;
    }

    .activeTab a {
        color: white;
    }

    .sticky-top {
        top: 5vh;
    }

    .main-content {

    }

</style>