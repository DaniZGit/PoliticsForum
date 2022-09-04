import { defineStore } from 'pinia'
import axios from 'axios'

export const useUserStore = defineStore("user", {
    state: () => ({ 
        users: [],
        user: {
            token: null,
            is_authenticated: false,
            data: null,
        }
    }),
    getters: {
        getUser: (state) => state.user,
    },
    actions: {
        async fetchUsers() {
            try {
                const res = await axios.get('users')
                this.users = res.data
            } catch (error) {
                console.log(error)
            }
        },
        setUser(user) {
            this.user.token = user.token
            this.user.is_authenticated = user.is_authenticated
            this.user.data = user.data
        },
        loggedIn() {
            return this.user.is_authenticated
        }
    },
    persist: {
        enabled: true
    }
})