import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

export const usePostsStore = defineStore("posts", {
    state: () => ({ 
        posts: []
    }),
    actions: {
        async fetchPosts() {
            try {
                const res = await axios.get('posts/all')
                this.posts = res.data
            } catch (error) {
                console.log(error)
            }
        }
    },
    persist: {
        enabled: true
    },
})

