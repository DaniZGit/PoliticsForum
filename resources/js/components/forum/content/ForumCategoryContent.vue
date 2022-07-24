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
                        {{ this.$route.params.category }}
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

                        <Pagination
                            v-model="currPage"
                            :page-count="pageCount"
                            :margin-pages="5"
                            :page-range="10"
                            :click-handler="setCurrentPage"
                            :prev-text="'<'"
                            :next-text="'>'"
                            :first-button-text="'<<<'"
                            :last-button-text="'>>>'"
                            :first-last-button="true"
                            v-if="getPageCount"
                        >
                        </Pagination>
                      
                      
                  </div> <!-- Accordion body end -->
              </div>
              
          </div>
      </div>

    </div>  
</template>

<script>
    import PostCard from '../partials/PostCard.vue'
    import Pagination from '../../../../..//node_modules/vuejs-paginate-next'

    export default {
        components: {
            PostCard,
            Pagination
        },

        data() {
            return{
                'category': null,
                'currPage': 1,
                'postsPerPage': 25,
                'postCount': 0,
                'pageCount': 0,
                'tagsChanged': false,
            }
        },

        props: [
            'categories',
            'activeTags',
        ],

        created() {
            // če je stran refreshed oz. ni prišla iz /forum/
            if(!this.categories){
                this.fetchPosts()
            } else {
                this.setCategory()
            }

            this.fetchCategoryCount()
            //this.refreshPage()
        },

        computed: {
            getPageParams() {
                let cmvTags = ""
                this.activeTags.forEach(t => {
                    if(t.active) {
                        cmvTags = cmvTags.concat(t.name + ',')
                    }
                });

                let queriedTags = `?page=${this.currPage}&size=${this.postsPerPage}`
                if(cmvTags.length > 0){
                    cmvTags = cmvTags.substring(0, cmvTags.length - 1)
                    queriedTags = queriedTags.concat("&tags=")
                    queriedTags = queriedTags.concat(cmvTags)
                }

                return queriedTags
            },
        },

        methods: {
            async fetchPosts() {
                //let url = `/api/categories/${this.$route.params.category}` + this.getPageParams()
                let url = `/api/categories/${this.$route.params.category}?page=${this.currPage}&size=${this.postsPerPage}`
                console.log("fetchPosts")
                let result = await fetch(url)
                this.category = await result.json()
            },

            async fetchCategoryCount() {
                let result = await fetch("/api/categories/" + this.$route.params.category + "/count")
                console.log("fetchCategoryCount")
                let c = await result.json()
                this.postCount = c.count
                this.pageCount = this.getPageCount()
            },

            setCategory() {
                let categoryName = this.$route.params.category

                this.category = (this.categories.filter((cat, ind) => cat.name === categoryName))
                if(this.category.length > 0) {
                    this.category = this.category[0]
                }
                //this.fetchCategoryCount()
                this.refreshPage()
                //this.currPage = 1
                console.log("do we set")
            },

            refreshRightSidebar(post) {
                this.$emit('refreshRightSidebar', post)
            },

            setCurrentPage(page) {
                this.currPage = page
                //this.fetchPosts()
                
                //if(!this.tagsChanged){
                    this.refreshPosts()
                    this.tagsChanged = false
                //}

                window.scrollTo(0,0);

            },

            getPageCount() {
                return Math.ceil( this.postCount / this.postsPerPage )
            },

            async refreshPosts() {
                let url = `/api/categories/` + this.$route.params.category + this.getPageParams
                console.log("refreshPosts")
                let result = await fetch(url)
                this.category = await result.json()
            },

            async refreshPage() {
                // refresh posts count
                let result = await fetch("/api/categories/" + this.$route.params.category + "/count" + this.getPageParams)
                console.log("refreshPage")
                let c = await result.json()
                this.postCount = c.count
                this.pageCount = this.getPageCount()
                if(this.pageCount <= this.currPage){
                    this.currPage = this.pageCount
                }
                // refresh shows posts
                this.refreshPosts()
            }
            
        },

        watch: {
            $route (to, from){
                if(this.$route.params.category != this.category.name){
                    if(from.name === "ForumCategory") {
                        // disable tags
                        this.activeTags.forEach(t => {
                            t.active = false
                        });
                    }

                    this.setCategory()
                }
            },

            async activeTags(newValue, oldValue) {
                this.tagsChanged = true
                this.refreshPage()
                //this.refreshPosts()
            },
        }
    }
</script>