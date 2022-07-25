import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router'
import UserHome from './components/user/UserHome.vue'
import UserLogin from './components/user/authentication/UserLogin.vue'
import UserRegister from './components/user/authentication/UserRegister.vue'
import UserDashboard from './components/user/UserDashboard.vue'

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
        path: '/user',
        component: UserHome,
        children: [
            { path: '', alias: 'login', name: 'UserLogin', component: UserLogin },
            { path: 'register', name: 'UserRegister', component: UserRegister },
            { path: 'dashboard', name: 'UserDashboard', component: UserDashboard },
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
app.mount('#app')

import 'bootstrap/dist/js/bootstrap.js'