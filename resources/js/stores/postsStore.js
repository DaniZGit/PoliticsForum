import { defineStore } from 'pinia'
import axios from 'axios'
import { ref } from 'vue'

export const usePostsStore = defineStore("posts", {
    state: () => ({ 
        posts: []
    }),
    actions: {
        async fetchPosts() {
            console.log('fetching posts')
            /*
            try {
                const res = await axios.get('posts', {
                    params: { 
                        includes: ['tags', 'comments', 'replies', 'category']
                    }
                })
                posts.value = res.data
            } catch (error) {
                console.log(error)
            }
            */
        }
    },
    persist: {
        enabled: true
    },
})

