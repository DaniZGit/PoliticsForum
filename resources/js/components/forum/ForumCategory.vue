<template>
    <!-- Left sidebar -->
    <div class="col-2 border border-top-0 py-3 border-dark border-end-0">
        <div class="sidebar-categories sticky-top">
            <forum-left-sidebar></forum-left-sidebar>
        </div>
    </div>

    <!-- Middle content -->
    <div class="col-8 border border-top-0 py-3 border-dark" id="forumContent">
        <forum-category-content @refreshRightSidebar="refreshRightSidebar"></forum-category-content>
    </div>

    <!-- Right sidebar -->
    <div class="col-2 border border-top-0 py-3 border-dark border-start-0">
        <div class="sidebar-categories sticky-top d-flex flex-column" >
            <forum-right-sidebar :post="post" v-if="post"></forum-right-sidebar>
        </div>
    </div>

</template>

<script>
    import ForumCategoryContent from './content/ForumCategoryContent.vue'
    import ForumLeftSidebar from './partials/ForumLeftSidebar.vue'
    import ForumRightSidebar from './partials/ForumRightSidebar.vue'

    export default {
        components: {
            ForumCategoryContent,
            ForumLeftSidebar,
            ForumRightSidebar,
        },

        data() {
            return {
                "post": null,
            }
        },

        methods: {
            refreshRightSidebar(post) {
                this.post = post
                console.log(this.post)
            }
        }
    }
</script>