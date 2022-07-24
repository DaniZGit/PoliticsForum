<template>
    <!-- Left sidebar -->
    <div class="col-2 border border-top-0 py-3 border-dark border-end-0">
        <div class="sidebar-categories sticky-top">
            <forum-left-sidebar></forum-left-sidebar>
        </div>
    </div>

    <!-- Middle content -->
    <div class="col-8 border border-top-0 py-3 border-dark" id="forumContent">
        <forum-post-content :post="post" v-if="post"></forum-post-content>
    </div>

    <!-- Right sidebar -->
    <div class="col-2 border border-top-0 py-3 border-dark border-start-0">
        <div class="sidebar-categories sticky-top d-flex flex-column" >
            <forum-right-sidebar :post="post" v-if="post"></forum-right-sidebar>
        </div>
    </div>

</template>

<script>
    import ForumPostContent from './content/ForumPostContent.vue'
    import ForumLeftSidebar from './partials/ForumLeftSidebar.vue'
    import ForumRightSidebar from './partials/ForumRightSidebar.vue'

    export default {
        components: {
            ForumPostContent,
            ForumLeftSidebar,
            ForumRightSidebar,
        },

        data() {
            return {
                'post': null,
            }
        },

        async created() {
            console.log(this.$route.params.post)
            let result = await fetch('/api/posts/' + this.$route.params.id)

            if(result.ok) {
                this.post = await result.json()
                console.log(this.post)
            } else {
                console.log("This psot does not exist")
            }

        }
        
    }
</script>