<template>
    <div class="categories-container d-flex flex-column gap-0 pt-2 position-relative scrollspy-example" data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" tabindex="0">
        <!-- Category - Accordion -->
        <div v-for="category in categories" :key="category.id" :id="category.id" class="accordion">
            <category-card @refreshRightSidebar="refreshRightSidebar" :category="category" :activeTags="activeTags"></category-card>
        </div>

    </div>

</template>

<script>
    import CategoryCard from '../partials/CategoryCard.vue'

    export default {
        components: {
            CategoryCard,
        },

        props: [
            'categories',
            'activeTags',
        ],

        data() {
            return {
                'categoriesData': []
            }
        },

        created(){
            console.log(this.currPage)
        },

        methods: {
            /*
            refreshRightSidebar(post) {
                this.$emit('refreshRightSidebar', post)
            },
            */
        }
    }
</script>

<script setup>
    const emits = defineEmits(['refreshRightSidebar'])

    function refreshRightSidebar(post) {
        console.log("click2")
        emits('refreshRightSidebar', post)
    }
    
</script>
