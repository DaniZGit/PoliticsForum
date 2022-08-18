<template>
    <div class="card ">
        <div class="card-body row m-0">
            <div class="col-1 text-start text-center align-self-baseline">
                <a :href="'/forum/' + props.category + '/' + props.post.id" class="text-decoration-none">
                    <img 
                        :src="props.post.user[0].profile_picture"
                        alt="..." 
                        class="rounded-circle img-fluid w-75 justify-content-center mb-1 border"
                    >
                    <h6 class="m-0 text-dark fw-bold">{{ props.post.user[0].name }}</h6>
                </a>
            </div>
            <div class="col-9 align-self-center align-self-stretch">
                <router-link :to="{ name: 'ForumPost', params: {'category': props.category, 'post': props.post.id} }" class="text-decoration-none">
                    <h4 class="card-title bold text-dark">{{ post.title }}</h4>
                </router-link>
                <div class="d-flex flex-wrap gap-2 align-content-center">
                    <a 
                        v-for="tag in props.post.tags.sort((a, b) => a.name > b.name ? 1 : -1)"
                        :key="tag.id"
                        href="#" 
                        class="h6 bg-dark align-self-center m-0 p-2 rounded text-white text-decoration-none">
                            {{ tag.name }}
                    </a>

                </div>
            </div>
            <div class="col-2 d-flex flex-column justify-content-between text-end">
                <div class="align-self-end">
                    <h6 class="mb-3">
                        <span class="text-primary">Objavljeno:</span><br/>
                        12. 07. 2022
                    </h6>
                </div>
                <div class="align-self-end">
                    <h6 class="m-0">
                        <span class="text-primary">Zadnji odgovor:</span><br/>
                        <a href="#profile" class="text-dark fw-bold text-decoration-none">Jamaicon</a>, pred 36 min
                    </h6>
                </div>
                
            </div>
        </div>
    </div>
</template>
<!--
<script>
    export default {
        props: [
            'post',
            'category',
            'tags'
        ],
    }
</script>
-->

<script setup>
    const props = defineProps([
        'post',
        'category',
        'tags'
    ])

</script>
