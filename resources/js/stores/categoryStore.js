import { defineStore } from 'pinia'
import axios from 'axios'

export const useCategoryStore = defineStore("categories", {
    state: () => ({ 
        categories: []
    }),
    actions: {
        getCategories() {
            return this.categories
        },
        async fetchCategories() {
            try {
                const res = await axios.get('categories', {
                    params: { 
                        includes: ['posts', 'tags', 'comments', 'replies']
                    }
                })
                this.categories = res.data
            } catch (error) {
                console.log(error)
            }
        },
        addPost(post) {
            this.categories.forEach((category) => {
                if(category.id === post.category_id) {
                    if (category.posts.length >= 25) {
                        category.posts.splice(24, 1) // remove last post from the array
                    }

                    category.posts.unshift(post) // add newly created post to the top
                }
            })
        }
    },
    persist: {
        enabled: true
    },
})

