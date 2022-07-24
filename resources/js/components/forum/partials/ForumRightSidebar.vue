<template>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="exampleModalLabel">{{ post.title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ post.description }}
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex flex-column gap-3 text-center sticky-top mb-auto" id="right-sidebar-top">
        <div>
            <h3 class="m-0">
                Naslov
            </h3>
            <p class="h5 m-0 w-100" data-bs-toggle="tooltip" data-bs-placement="left" title="Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo, alias. dolor sit amet consectetur adipisicing elit. Quo, alias">
                {{ post.title }}
            </p>
            
        </div>

        <div>
            <h3 class="m-0">
                Thread Tags
            </h3>
            <div class="d-flex flex-wrap gap-2 justify-content-center p-2">
                <a href="#" 
                    v-for="tag in post.tags" 
                    :key="tag.id" 
                    class="h6 bg-dark align-self-center m-0 p-2 rounded text-white text-decoration-none">{{ tag.name }}</a>
            </div>
        </div>

        <div>
            <h3 class="m-0">
                People Feedback
            </h3>
            <span><i class="bi bi-reply" data-bs-toggle="tooltip" data-bs-placement="bottom" title="replies"></i> {{post.comments.length}}</span>
            <span><i class="bi bi-hand-thumbs-up" data-bs-toggle="tooltip" data-bs-placement="bottom" title="likes"></i> {{post.likes}}</span>
            <span><i class="bi bi-hand-thumbs-down" data-bs-toggle="tooltip" data-bs-placement="bottom" title="dislikes"></i> {{post.dislikes}}</span>
        </div>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Odpri objavo
        </button>
    </div>

    <div class=" text-center">
        <h3 class="m-0">
            Author
        </h3>
        <div class="d-flex flex-column align-items-center">
            <img :src="post.user[0].profile_picture" alt="" class="img-fluid w-25 rounded-circle"><!--https://randomuser.me/api/portraits/women/69.jpg-->
            <a href="#" class="text-decoration-none text-dark fw-bold h4 m-0 mt-1">{{ post.user[0].name }}</a>
            <div class="d-flex flex-wrap gap-2 justify-content-center p-2">
                <a href="#" v-for="tag in post.tags.sort((a, b) => a.name > b.name ? 1 : -1)" :key="tag.id" class="h6 bg-dark align-self-center m-0 p-2 rounded text-white text-decoration-none">{{ tag.name }}</a>
            </div>
        </div>
    </div>


</template>

<script>
    export default {
        props: [
            'post',
        ],
    }

</script>

<style scoped>
    .modalContentScrollabel {
        overflow-y: scroll; 
        height: calc(70%);
    }
</style>