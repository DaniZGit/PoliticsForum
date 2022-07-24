<template>
    <div class="sticky-top d-flex flex-column gap-4" id="left-sidebar-top">
        <div>
            <h4 class="mb-2">Categories</h4>
            <div id="list-example" class="list-group">
                <router-link :to="'/forum/' + category.name" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-pill border h8" v-for="category in categoryNames" :key="category.name">
                    {{ category.name }} <span class="badge bg-primary rounded-pill">{{ category.postsCount }}</span>
                </router-link>
            </div>
        </div>

        <div v-if="this.$route.name === 'ForumCategory'">
            <div class="d-flex gap-1 align-items-center mb-2">
                <h4 class="m-0 p">Filter</h4>
                <a href="#" class="text-decoration-none">
                    ponastavi <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
            <div class="d-flex flex-row align-items-center gap-1">
                <select class="form-select">
                    <option value="1" selected>Po datumu odgovora</option>
                    <option value="2">Po datumu objave</option>
                    <option value="3">Po naslovnici</option>
                    <option value="4">Po številu ogledov</option>
                </select>
                <a href="#sort" class="d-flex">
                    <i class="bi bi-arrow-down fa-4x" style="font-size: 1.25rem; margin-right: -5px; -webkit-text-stroke: 1px blue;"></i>
                    <i class="bi bi-arrow-up" style="font-size: 1.25rem;"></i>
                </a>
                
            </div>
        </div>

        <div v-if="this.$route.name === 'ForumCategory'">
            <div class="d-flex gap-1 align-items-center mb-2">
                <h4 class="m-0">Tags</h4>
                <a href="#" @click="resetToggles()" class="text-decoration-none">
                    ponastavi <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-start">
                <a 
                    v-for="tag in tags" :key="tag.id"
                    href="#" 
                    class="h6 m-0 p-2 border border-secondary border-1 rounded-pill bg-white text-dark text-decoration-none"
                    :class="{'active-tag': tag.active}"
                    @click="toggleTag(tag)"
                >{{ tag.name }} 
                <span 
                    class="badge bg-primary rounded-pill"
                    :class="{'active-count': tag.active}"
                    >{{ tag.tagCount }}</span></a>
            </div>
        </div>

    </div>
    
</template>

<script>
    export default {
        props: [
            'categoryNames',
            'tags',
        ],

        data() {
            return {
                'activeTags': [],
            }
        },

        created() {
            /*
            categories.forEach(category => {
                let result = await fetch("/api/categories/" + category.name + "/count")
                let c = await result.json()
                this.postCount = c.count
            });
            let result = await fetch("/api/categories/" + this.$route.params.category + "/count")
            let c = await result.json()
            this.postCount = c.count
            */
        },

        methods: {
            toggleTag(tag) {
                tag.active = !tag.active
                /*
                console.log(this.tags)
                let queriedTags = ""
                this.tags.forEach(t => {
                    console.log(t)
                    if(t.active) {
                        queriedTags = queriedTags.concat(t.name + ',')
                    }
                });
                queriedTags = queriedTags.substring(0, queriedTags.length - 1)
                this.$router.replace({ query: { 'tags': queriedTags }})
                */

                this.$emit('tagsToggled', this.tags.filter((tag) => tag.active))
            },

            resetToggles() {
                this.tags.forEach(tag => {
                    tag.active = false
                });

                this.$emit('tagsToggled', this.tags.filter((tag) => tag.active))
            }
        }
    }
</script>

<style scoped>
    .active-tag {
        background-color: #0D6EFD !important;
        color: #fff !important;
    }

    .active-count {
        background-color: white !important;
        color: #000;
    }
</style>