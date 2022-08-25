<template>

    <div v-if="postData">
        <!-- Title -->
        <div class="sticky-top sticky-header-tab mb-4">
            <div class="p-1 bg-white">

            </div>
            <a @click="this.$router.back()" id="arrow-right" class="position-absolute text-white text-decoration-none py-3 start-0 ps-4 h6" style="z-index: 1000;"><i class="bi bi-arrow-left"></i> Nazaj</a>

            <h2 class="accordion-header position-absolute py-2 ps-2 text-white w-100 text-center" id="headingOne">
                {{ this.$route.params.category }}
            </h2>

            <button class="container-fluid border-1 rounded bg-primary text-white text-center py-4" type="button">
            
            </button>
        </div>
        
        <!-- Main content -->
        <div class="d-flex flex-column gap-4">
            <!-- Main Post -->
            <div class="container-fluid p-0 d-flex flex-row">
                <div class="flex-shrink-0 p-4 border-2 border border-end-0 border-primary rounded-start text-center align-self-baseline">
                    <img 
                        :src="postData.user.profile_picture" 
                        alt="..." 
                        class="rounded-circle img-fluid justify-content-center mb-1 border"
                    >
                    <h6 class="m-0 text-dark fw-bold"><a href="#profile" class="text-decoration-none text-dark">{{ postData.user.name }}</a></h6>
                </div>

                <div class=" border-2 border border-primary rounded-end">
                    <h3 class="p-3 d-inline-block fw-bold">
                        {{ postData.title }}
                    </h3>

                    <div class="h5 p-3">
                        {{ postData.description }}
                    </div>

                    <div class="d-flex flex-wrap gap-2 border-top p-3">
                        <a href="#" 
                            v-for="tag in postData.tags" 
                            :key="tag.id" class="h6 bg-dark align-self-center m-0 p-2 rounded text-white text-decoration-none">{{ tag.name }}</a>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                <div v-for="comment in postData.comments.sort((a, b) => a.likes < b.likes ? 1 : -1)" :key="comment.id">
                    <comment-card  :comment="comment"></comment-card>
                    <reply-card v-for="reply in comment.replies.sort((a, b) => a.likes < b.likes ? 1 : -1)" :key="reply.id" :reply="reply"></reply-card>
                </div>
                
            </div>
            
        </div>
    </div>
    
</template>

<script setup>
    import { ref } from '@vue/reactivity'
    import { onMounted } from '@vue/runtime-core'
    import { useRoute } from 'vue-router'
    import CommentCard from '../partials/CommentCard.vue'
    import ReplyCard from '../partials/ReplyCard.vue'

    let postData = ref(null)
    let props = defineProps(['post'])
    const route = useRoute()

    const emit = defineEmits(['refreshRightSidebar'])

    onMounted(async () => {
        if(!props.post) {
            let result = await fetch('/api/posts/' + route.params.post)
            postData.value = await result.json()
            
            emit("refreshRightSidebar", postData.value)
        } else {
            postData.value = props.post
        }
    })
</script>
