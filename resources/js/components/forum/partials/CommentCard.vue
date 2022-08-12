<template>
    
    <div class="post-comment container-fluid p-0 d-flex border">
        <div class="flex-shrink-0 p-4 text-center align-self-baseline"> <!-- border-2 border border-end-0 border-primary rounded-start --> 
            <img 
                :src="user.profile_picture" 
                alt="..." 
                class="rounded-circle img-fluid justify-content-center mb-1 border w-50"
            >
            <h6 class="m-0 text-dark fw-bold"><a href="#profile" class="text-decoration-none text-dark">{{ user.name }}</a></h6>
        </div>

        <div class="p-4 pt-4 pb-2 d-flex flex-column justify-content-between gap-2">
            <!-- main comment content -->
            <div class="d-flex flex-column gap-2">
                <div class="h5">
                    {{ comment.description }}
                </div>
            </div>
        
            <div class="d-flex justify-content-end align-items-center gap-2">
                <a href="#comment" class="text-decoration-none text-dark position-relative">
                    <i class="bi bi-arrow-90deg-up " style=" "> </i>
                    odgovori
                </a>
                <a href="#like" class="text-decoration-none text-dark">
                    <i class="bi bi-hand-thumbs-up"> </i>
                    Like ({{ comment.likes }})
                </a>
                <a href="#dislike" class="text-decoration-none text-dark">
                    <i class="bi bi-hand-thumbs-down"> </i>
                    Dislike ({{ comment.dislikes }})
                </a>
            </div>
            
        </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                'user': [],
            }
        },

        props: [
            'comment',
        ],

        async created() {
            console.log("comment")
            console.log(this.comment)
            let result = await fetch('/api/users/' + this.comment.user_id)
            this.user = await result.json()
            console.log(this.user)
        }
    }
</script>