import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router'
import store from './vuex.js'

import ForumHome from './components/forum/ForumHome.vue'
import ForumHomeContent from './components/forum/content/ForumHomeContent.vue'
import ForumCategoryContent from './components/forum/content/ForumCategoryContent.vue'
import ForumPostContent from './components/forum/content/ForumPostContent.vue'

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

const app = createApp({})
app.use(router)
app.use(store)
app.mount('#app')

import 'bootstrap/dist/js/bootstrap.js'