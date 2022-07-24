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

<script>
    import PostCard from '../partials/PostCard.vue'
    import Pagination from '../../../../..//node_modules/vuejs-paginate-next'

    export default {
        components: {
            PostCard,
            Pagination,
        },

        props: [
            'category',
            'activeTags',
        ],

        data() {
            return {
                'currPage': 1,
                'postsPerPage': 5,
                'count': 0,
            }
        },

        computed: {
            getPostsFrom() {
                return (this.currPage-1) * this.postsPerPage
            },

            getPostsTo(categoryName) {
                return this.currPage * this.postsPerPage
            },

        },

        created(){
        },

        methods: {
            refreshRightSidebar(post) {
                this.$emit('refreshRightSidebar', post)
            },

            getPageCount() {
                return Math.ceil( this.category.posts.length / this.postsPerPage )
            },

            setCurrentPage(page) {
                this.currPage = page
            },

        },

        watch: {
            activeTags(newValue, oldValue) {
                let result = fetch('/api/categories/' + this.category.name + '/posts/search')
                console.log(result)
            }
        }
    }
</script>
