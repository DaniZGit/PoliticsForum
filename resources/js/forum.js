import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import piniaPluginPersistedState from 'pinia-plugin-persistedstate'
import axios from './axios.js'

import ForumHome from './components/forum/ForumHome.vue'
import ForumHomeContent from './components/forum/content/ForumHomeContent.vue'
import ForumCategoryContent from './components/forum/content/ForumCategoryContent.vue'
import ForumPostContent from './components/forum/content/post/ForumPostContent.vue'
import ForumPostCreate from './components/forum/content/post/ForumPostCreate.vue'

// import 'bootstrap/dist/css/bootstrap.css' 
// ta bootstrap 5.1.3 je drgač kot 5.1.3 cdn link ???
/*
const routes = [
   {
       path: '/forum/',
       name: 'ForumHome',
       component: ForumHome,
   },
   {
        path: '/forum/:category',
        name: 'ForumCategory',
        component: ForumCategory
    },
    {
        path: '/forum/:category/:id',
        name: 'ForumPost',
        component: ForumPost,
    },
]
*/
const routes = [
    {
        path: '/forum',
        component: ForumHome,
        children: [
            { path: '', name: 'ForumHome', component: ForumHomeContent },
            { path: ':category', name: 'ForumCategory', component: ForumCategoryContent, props: true },
            { path: ':category/:post', name: 'ForumPost', component: ForumPostContent },
            { path: 'create', name: 'ForumPostCreate', component: ForumPostCreate },
        ]
    },
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
    scrollBehavior (to, from, savedPosition) {
        // category -> post (scrolls to top on post page)
        // when you go back: category <- post (scrolls to the scroll position that you were at when you pressed on that post on the category page)
        // amazing stuff !
        if (savedPosition) {
            return savedPosition
        } else {
            return { top: 0 }
        }
    },
})

const pinia = createPinia()
pinia.use(piniaPluginPersistedState)

const app = createApp({})
app.use(router)
app.use(pinia)
app.mount('#app')

import 'bootstrap/dist/js/bootstrap.js'