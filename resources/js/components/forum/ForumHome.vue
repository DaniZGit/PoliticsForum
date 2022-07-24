<template>
    <!-- Left sidebar -->
    <div class="col-2 border border-top-0 py-3 border-dark border-end-0">
        <div class="sidebar-categories sticky-top">
            <forum-left-sidebar :categoryNames="categoryNames" :tags="tags" @tagsToggled="tagsToggled" v-if="categories"></forum-left-sidebar>
        </div>
    </div>

    <!-- Middle content -->
    <div class="col-8 border border-top-0 py-3 border-dark" id="forumContent">
        <router-view @refreshRightSidebar="refreshRightSidebar" :categories="categories" :post="post" :activeTags="activeTags" :key='$route.fullPath'></router-view>
        <!--<forum-home-content @refreshRightSidebar="refreshRightSidebar"></forum-home-content>-->
    </div>

    <!-- Right sidebar -->
    <div class="col-2 border border-top-0 py-3 border-dark border-start-0">
        <div class="sidebar-categories sticky-top d-flex flex-column" >
            <forum-right-sidebar :post="post" v-if="post"></forum-right-sidebar>
        </div>
    </div>

</template>

<script>
    import ForumHomeContent from './content/ForumHomeContent.vue'
    import ForumLeftSidebar from './partials/ForumLeftSidebar.vue'
    import ForumRightSidebar from './partials/ForumRightSidebar.vue'

    export default {
        components: {
            ForumHomeContent,
            ForumLeftSidebar,
            ForumRightSidebar,
        },

        data() {
            return {
                "post": null,
                "categories": null,
                "categoryNames": null,
                "tags": null,
                "activeTags": []
            }
        },

        created() {
            this.fetchCategories()

            this.fetchCategoryNames()

            this.fetchTags()
        },

        methods: {
            async fetchCategories() {
                let result = await fetch("/api/categories/posts/details")
                this.categories = await result.json()
                console.log(this.categories)
            },

            async fetchCategoryNames() {
                let result = await fetch("/api/categories/count")
                this.categoryNames = await result.json()
            },

            async fetchTags() {
                let result = await fetch("/api/tags/")
                this.tags = await result.json()
                this.tags.sort((a,b) => {
                if(a.name > b.name) {
                        return 1;
                    } else {
                        return -1;
                    }
                })
                console.log(this.tags)
            },

            refreshRightSidebar(post) {
                this.post = post
                console.log(this.post)
            },

            tagsToggled(activeTags) {
                console.log(activeTags)
                this.activeTags = activeTags
            }
        }
    }
</script>