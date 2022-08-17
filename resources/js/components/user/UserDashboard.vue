<template>
    <div class="row p-0 m-0 w-100" v-if="store.isLoggedIn()">
        <div class="wrapper col-2 m-0 py-0 ps-3">
            <!-- Sidebar -->
            <div class="sticky-top py-3">
                <nav id="sidebar">
                    <ul class="list-unstyled components border border-primary rounded">
                        <li 
                            v-for="(tab, key) in tabs" :key="tab"
                            class="p-2 tab"
                            :class="[tab.active ? 'activeTab' : '']" 
                            @click="tabClickHandler(key)">
                            <a class="text-decoration-none">{{ key }}</a>
                        </li>
                        <li class="p-2">
                            <button 
                                class="btn btn-danger"
                                @click="logoutUser"
                                >
                                Logout</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        
        <div class="col-10 py-3 px-3 gx-3">
            <user-info-tab v-if="tabs['PROFILE'].active"></user-info-tab>
            <user-posts-tab v-if="tabs['POSTS'].active"></user-posts-tab>
            <user-comments-tab v-if="tabs['COMMENTS'].active"></user-comments-tab>
            <user-messages-tab v-if="tabs['MESSAGES'].active"></user-messages-tab>
        </div>
    </div>
    
</template>

<script setup>
    import { onMounted, ref } from "vue";
    import { useRouter, useRoute } from 'vue-router'
    import VueCookie from 'vue-cookie'
    import { useUserStore } from '../../stores/userStore'
    import axios from 'axios'

    // components
    import UserInfoTab from './partials/UserInfoTab.vue'
    import UserPostsTab from './partials/UserPostsTab.vue'
    import UserCommentsTab from './partials/UserCommentsTab.vue'
    import UserMessagesTab from './partials/UserMessagesTab.vue'
    
    // variables
    const cookie = VueCookie
    const router = useRouter()
    const route = useRoute()
    const store = useUserStore()
    console.log(store.isLoggedIn())
    console.log(store.user.is_authenticated)

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
        'MESSAGES': {
            component: UserMessagesTab,
            active: false
        }
    })

    onMounted(async () => {
        console.log("hellooo")
        
        if (!store.isLoggedIn())
            router.push({ name: 'UserLogin' })
        else {
            // window.axios.Headers['Authorization'] = `Bearer ${cookie.get('token')}`
            try {
                const res = await axios.get('user', {
                    headers: { Authorization: `Bearer ${store.user.token}` }
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
            } catch (error) {
                console.log(error)
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
        const res = await axios.post('users/logout')
        
        if (res.status === 200) {
            if(res.errors) {
                console.log(res.errors)
            } else {
                //cookie.delete('token')
                store.setUser({token: null, is_authenticated: false, data: null})
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

    .tab:nth-child(even) {
        border-top: 1px solid #0d6efd;
        border-bottom: 1px solid #0d6efd;
    }

    .tab:hover {
        cursor: pointer;
        background: #0d6efd;
        
    }
    .tab:hover a{
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

</style>