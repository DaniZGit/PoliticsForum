<template>
    <div class="categories-container d-flex flex-column gap-0">
      <!-- Category - Accordion -->
      <div id="category-1" class="accordion" v-if="category">
          <div class="accordion-item position-relative mb-2 border-0 outline-0 inline-0">
              <div class="sticky-top sticky-header-tab">
                    <!-- White background on top of title -->
                    <div class="p-1 bg-white">

                    </div>
                    <router-link to="/forum/" id="arrow-right" class="position-absolute text-white text-decoration-none py-3 start-0 ps-4 h6" style="z-index: 1100;"><i class="bi bi-arrow-left"></i> Nazaj</router-link>

                    <h2 class="accordion-header position-absolute py-2 ps-2 text-white w-100 text-center" id="headingOne">
                        {{ route.params.category }}
                    </h2>

                    <button class="container-fluid border-1 rounded bg-primary text-white text-center py-4" type="button">
                    
                    </button>
              </div>
              
              <div id="posts-1" class="accordion-collapse collapse show" v-if="!category.length">
                  <div class="accordion-body pb-0">
                      <div class="container-fluid px-0 d-flex flex-column gap-2">
                          <!-- YOU NEED TO CLONE THE ARRAY OTHERWISE YOU WILL LOSE THE DATA WITH SPLICE() -- v-for="post in [...category.posts].splice(0,20)" -->
                          <post-card v-for="post in category.posts" :key="post.id"
                                :post="post"
                                :category="category.name"
                                tags="Janša, Golob, Svoboda, Kriza"
                                @mouseenter="refreshRightSidebar(post)"
                            />
                          
          
                      </div>

                        <Paginate
                            :v-model="page"
                            :force-page="page"
                            :page-count="calculatePageCount"
                            :margin-pages="5"
                            :page-range="10"
                            :click-handler="setCurrentPage"
                            :prev-text="'<'"
                            :next-text="'>'"
                            :first-button-text="'<<<'"
                            :last-button-text="'>>>'"
                            :first-last-button="true"
                        >
                        </Paginate>
                      
                      
                  </div> <!-- Accordion body end -->
              </div>
              
          </div>
      </div>

    </div>  
</template>

<script setup>
    import PostCard from '../partials/PostCard.vue'
    import Paginate from '../../../../..//node_modules/vuejs-paginate-next'
    import { ref } from '@vue/reactivity'
    import { computed, onMounted, onUnmounted, watch } from '@vue/runtime-core'
    import { onBeforeRouteLeave, useRoute, useRouter } from 'vue-router'
    import { getCurrentInstance } from "vue";
    import axios from 'axios'

    let category = ref(null)
    let page = ref(1)
    let perPage = ref(25)
    let postsCount = ref(0)
    let tagsChanged = ref(false)

    const route = useRoute()
    const instance = getCurrentInstance()

    const props = defineProps([
        'categories',
        'activeTags'
    ])
    const emit = defineEmits([
        'refreshRightSidebar'
    ])

    onMounted(() => {
        // če je stran refreshed oz. ni prišla iz /forum/
        if(!props.categories){
            fetchCategory()
        } else {
            setupCategory()
        }
    })

    // computed methods

    // methods
    const fetchCategory = (async () => {
        try {
            const res = await axios.get(`categories/${route.params.category}`, {
                params: { 
                    includes: ['posts', 'tags', 'comments', 'replies'],
                    tags: props.activeTags.filter((tag) => tag.active).map((tag) => tag.name),
                    page: page.value,
                    perPage: perPage.value
                }
            })
            category.value = res.data
            postsCount.value = category.value.posts_count
            
            if (page.value > calculatePageCount.value) {
                page.value = calculatePageCount.value
                page.value = calculatePageCount.value
            }
        } catch (error) {
            console.log(error)
        }
    })

    const setupCategory = (() => {
        let categoryName = route.params.category
        // find current category data from propped 'categories'
        category.value = props.categories.find((category) => category.name === categoryName)
        postsCount.value = category.value.posts_count
    })

    const refreshCategory = (() => {

    })

    const refreshRightSidebar = ((post) => {
        emit('refreshRightSidebar', post)
    })

    const setCurrentPage = ((newPage) => {
        page.value = newPage
        //this.fetchCategory()
        
        //if(!this.tagsChanged){
            // refreshPosts()
            tagsChanged.value = false
        //}

        window.scrollTo(0,0);
        fetchCategory()
    })

    const calculatePageCount = computed(() => {
        return Math.ceil( postsCount.value / perPage.value )
    })

    // watchers
    // nisem sure če to dela, neki vem da nrdi....
    /*
    watch(() => route.path, (to, from) => {
        if(route.params.category != category.value.name){
            if(route.name === "ForumCategory") {
                // disable tags if moved from category to another category
                props.activeTags.forEach(t => {
                    t.active = false
                });

                setupCategory()
            }
        }
    })
    */
    
    watch(() => props.activeTags, (newActiveTags, oldActiveTags) => {
        tagsChanged.value = true
        fetchCategory()
    }, { deep: true })

    onUnmounted(() => {
        props.activeTags.forEach((tag) => tag.active = false)
    })




























    /*
    // computed methods
    const getPageParams = computed(() => {
        let cmvTags = ""
        props.activeTags.forEach(t => {
            if(t.active) {
                cmvTags = cmvTags.concat(t.name + ',')
            }
        });

        let queriedTags = `?page=${currPage.value}&size=${postsPerPage.value}`
        if(cmvTags.length > 0){
            cmvTags = cmvTags.substring(0, cmvTags.length - 1)
            queriedTags = queriedTags.concat("&tags=")
            queriedTags = queriedTags.concat(cmvTags)
        }

        return queriedTags
    })

    const fetchCategoryCount = (async () => {
        // tega nerabm sm pretty sure, sam preverm length od posts ki so bli fetched
        let result = await fetch("/api/categories/" + route.params.category + "/count")
        console.log("fetchCategoryCount")
        let c = await result.json()
        postCount.value = c.count
        pageCount.value = getPageCount()
    })

    const refreshPosts = (async () =>  {
        let url = `/api/categories/` + route.params.category + getPageParams.value
        console.log("refreshPosts")
        let result = await fetch(url)
        category.value = await result.json()
    })
    

    
    const refreshPage = (async () => {
        // refresh posts count
        let result = await fetch("/api/categories/" + route.params.category + "/count" + getPageParams.value)
        console.log("refreshPage")
        let c = await result.json()
        postCount.value = c.count
        pageCount.value = getPageCount()
        if(pageCount.value <= currPage.value){
            currPage.value = pageCount.value
        }
        // refresh shows posts
        refreshPosts()
    })
    */
</script>
