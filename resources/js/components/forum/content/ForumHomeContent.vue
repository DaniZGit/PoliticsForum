<template>
    <div class="categories-container d-flex flex-column gap-0 pt-2 position-relative scrollspy-example" data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" tabindex="0">
        <!-- Category - Accordion -->
        <div v-for="category in props.categories" :key="category.id" :id="category.id" class="accordion">
            <category-card @refreshRightSidebar="refreshRightSidebar" :category="category" :activeTags="props.activeTags"></category-card>
        </div>

    </div>

</template>

<script setup>
    import { onMounted, ref } from '@vue/runtime-core'
    import CategoryCard from '../partials/CategoryCard.vue'

    let categoriesData = ref([])

    const props = defineProps([
        'categories',
        'activeTags'
    ])
    const emits = defineEmits(['refreshRightSidebar'])

    onMounted(() => {
    })

    function refreshRightSidebar(post) {
        console.log("click2")
        emits('refreshRightSidebar', post)
    }

</script>
