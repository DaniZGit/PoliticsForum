<template>
    <div class="accordion-item position-relative border-0 mb-2 outline-0 inline-0" v-if="category">
        <div>
            <h2 class="accordion-header position-absolute py-2 ps-2" id="headingOne">
                <router-link :to="'/forum/' + category.name.toLowerCase()" clasS="text-white text-decoration-none">{{ category.name }}</router-link>
            </h2>
            <router-link :to="'/forum/' + category.name.toLowerCase()" id="arrow-right" class="position-absolute text-white text-decoration-none py-3 end-0 pe-4 h6">Prebrskaj več <i class="bi bi-arrow-right"></i></router-link>
            
            <button class="container-fluid rounded bg-primary text-white text-start py-4 border-1" type="button" data-bs-toggle="collapse" :data-bs-target="'#posts-' + category.id" >
            </button>
        </div>
        
        <div :id="'posts-' + category.id" class="accordion-collapse collapse show">
            <div class="accordion-body pb-0">
                <div class="container-fluid px-0 d-flex flex-column gap-2">
                    <post-card 
                        v-for="post in category.posts.slice(getPostsFrom, getPostsTo)" :key="post.id"
                        :post="post"
                        :category="category.name"
                        tags="Janša, Golob, Svoboda, Kriza"
                        @mouseenter="refreshRightSidebar(post)"
                    />
    
                </div>

                <Pagination
                    :page-count="getPageCount()"
                    :margin-pages="3"
                    :page-range="10"
                    :click-handler="setCurrentPage"
                    :prev-text="'<'"
                    :next-text="'>'"
                    :first-button-text="'<<<'"
                    :last-button-text="'>>>'"
                    :first-last-button="true"

                    
                >
                </Pagination>
                
                
                
            </div> <!-- Accordion body end -->
        </div>
        
    </div>

</template>

<script setup>
    import PostCard from '../partials/PostCard.vue'
    import Pagination from '../../../../..//node_modules/vuejs-paginate-next'
    import { ref } from '@vue/reactivity'
    import { computed, watch } from '@vue/runtime-core'

    const props = defineProps([
        'category',
        'activeTags'
    ])
    const emit = defineEmits(['refreshRightSidebar'])

    let currPage = ref(1)
    let postsPerPage = ref(5)
    let count = ref(5)

    const getPostsFrom = computed(() => {
        return (currPage.value-1) * postsPerPage.value
    })
    const getPostsTo = computed(() => {
        return currPage.value * postsPerPage.value
    })

    function refreshRightSidebar(post) {
        emit('refreshRightSidebar', post)
    }
    const getPageCount = (() => {
        return Math.ceil( props.category.posts.length / postsPerPage.value )
    })

    const setCurrentPage = ((page) => {
        currPage.value = page
    })

    watch(props.activeTags, (newActiveTags, oldActiveTags) => {
        let result = fetch('/api/categories/' + props.category.name + '/posts/search')
        console.log(result)
    })
</script>
