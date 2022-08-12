<template>

    <div class="post-reply container-fluid p-0 d-flex">
        <!-- ta del je zato da je odgovor zamaknjen od komentarja za širino logota od komentarja -->
        <div class="flex-shrink-0 p-4 border-2 border border-end-0 border-primary rounded-start text-center invisible">
            <img 
                :src="user.profile_picture" 
                alt="..." 
                class="rounded-circle img-fluid justify-content-center mb-1 border w-50"
            >
            <h6 class="m-0 text-dark fw-bold"><a href="#profile" class="text-decoration-none text-dark">{{ user.name }}</a></h6>
        </div>

        <!-- vsebina odgovora -->
        <div class="d-flex flex-column justify-content-between gap-2 border">
            <div class="post-reply container-fluid p-0 d-flex">
            
                <div class="p-4 py-2 d-flex flex-column justify-content-between gap-2"> <!-- border-2 border border-primary rounded-end -->
                    <div class="d-flex flex-column gap-2">
                        <div>
                            <a href="#" class="text-decoration-none d-inline"><i class="bi bi-arrow-return-right"></i> Odgovor od: &#64;{{ user.name }}</a>
                        </div>
                        <div class="h6">
                            {{ reply.description }}
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center gap-2">
                        <a href="#reply" class="text-decoration-none text-dark position-relative h6">
                            <i class="bi bi-arrow-90deg-up " style=" "> </i>
                            odgovori
                        </a>
                        <a href="#like" class="text-decoration-none text-dark h6">
                            <i class="bi bi-hand-thumbs-up"> </i>
                            Like ({{ reply.likes }})
                        </a>
                        <a href="#dislike" class="text-decoration-none text-dark h6">
                            <i class="bi bi-hand-thumbs-down"> </i>
                            Dislike ({{ reply.dislikes }})
                        </a>
                    </div>
                </div>
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
            'reply',
        ],

        async created() {
            console.log("reply")
            console.log(this.comment)
            let result = await fetch('/api/users/' + this.reply.user_id)
            this.user = await result.json()
            console.log(this.user)
        }
    }
</script>