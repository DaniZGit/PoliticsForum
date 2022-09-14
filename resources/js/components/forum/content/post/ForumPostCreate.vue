<template>
    <h2>CREATE POST</h2>
    <hr>
    <div class="container d-flex flex-column gap-2">
        <div class="input-group-lg row">
            <label class="form-label p-0 h3" for="form1">Post Title</label>
            <textarea
                class="form-control post-title"
                :class="{'is-invalid': !titleValidation}" 
                id="form1"
                v-model.trim="postTitle"
                ref="textareaTitle"
                rows="1"
            ></textarea>
            <div class="invalid-feedback p-0">
                {{ errors.title }}
            </div>
        </div>

        <div class="input-group-lg row">
            <label class="form-label p-0 h3" for="form2">Post Description</label>
            <!--
            <textarea
                class="form-control post-description"
                :class="{'is-invalid': !descriptionValidation}" 
                id="form2"
                v-model.trim="postDescription"
                ref="textareaDescription"
            ></textarea>
            -->
            <post-editor v-model="postDescription" :editable="true"></post-editor>
            <div class="invalid-feedback p-0">
                {{ errors.description }}
            </div>
        </div>

        <div class="row">
            <div class="col-6 d-flex flex-column m-0 p-0">
                <label class="form-label p-0 h3" for="form1">Post Tags</label>
                <div class="d-flex gap-2" >  
                    <div class="input-group">
                        <select 
                            class="form-control selectpicker" 
                            ref="tagsSelect"
                            v-model="selectedTagId"
                            @change="toggleTag"
                        >
                            <option value="" selected disabled hidden>Select tag from existing tags</option>
                            <option
                                v-for="tag in tags" :key="tag.id"
                                :value="tag.id"
                            >
                                {{ tag.name }}
                            </option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <input 
                            type="text"
                            class="form-control"
                            placeholder="Type a custom tag"
                            @keydown="validateTagInput"
                            ref="addTagInput"
                        >
                        <button class="btn btn-outline-primary" type="button" id="button-addon2" @click="addCustomTag">Add</button>
                    </div>

                </div>

                <div class="d-flex flex-row flex-wrap gap-2 justify-content-start p-0">
                    <div v-for="tag in addedTags.filter((tag) => tag.active)" :key="tag.id" class="d-inline mt-3">
                        <a 
                            href="#" 
                            class="h6 p-2 border border-secondary border-1 rounded-pill bg-white text-dark text-decoration-none"
                            :class="{'active-tag': tag.active}"
                            @click="tag.active = false"
                            v-if="tag.active"
                        >
                            {{ tag.name }} <!--<span class="badge bg-primary rounded-pill" :class="{'active-count': tag.active}">{{ tag.tagCount }}</span>-->
                        </a>
                    </div>
                </div>
                <div class="invalid-feedback p-0 d-block" v-if="!tagsValidation">
                    {{ errors.tags }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 d-flex flex-column m-0 p-0">
                <label class="form-label p-0 h3" for="form1">Category</label>
                <div class="d-flex" >  
                    <div class="input-group">
                        <select 
                            class="form-control selectpicker" 
                            ref="tagsSelect"
                            v-model="selectedCategoryId"
                        >
                            <option value="" selected disabled hidden>Select category</option>
                            <option
                                v-for="category in categories" :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="invalid-feedback p-0 d-block" v-if="!categoryValidation">
                    {{ errors.category }}
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 text-end">
                <button 
                    class="btn btn-primary m-0 col-4" 
                    type="button"
                    @click="createPost"
                    :disabled="!titleValidation || !descriptionValidation || !tagsValidation || !categoryValidation"
                >
                    CREATE POST
                </button>
            </div>
        </div>
    </div>
    
</template>

<script setup>

    import { useUserStore } from '../../../../stores/userStore'
    import { useCategoryStore } from '../../../../stores/categoryStore'
    import axios from 'axios'
    import { computed, inject, onMounted, ref, watch } from '@vue/runtime-core'
    import { useRouter } from 'vue-router'
    // tiptap editor
    import PostEditor from '../../partials/PostEditor.vue'

    let userStore = useUserStore()
    let categoriesStore = useCategoryStore()
    let router = useRouter()

    let postDescription = ref('<p><strong>Please write a description about the title</strong></p><p>Don\'t forget to select:</p><ul><li><p><em>category</em></p></li><li><p><em>tags</em></p></li></ul><p><a target="_blank" rel="noopener noreferrer nofollow" href="http://w3schools.com">w3schools.com</a></p><p>Description needs to include at least <u>50 words</u> !</p>')
    let postTitle = ref('')
    let selectedTagId = ref('')
    let selectedCategoryId = ref('')
    // errors
    const errors = ref({
        'title': "This field can't be empty",
        'description': '',
        'tags': '',
        'category': ''
    })

    // HTML elements
    const textareaTitle = ref(null)
    const textareaDescription = ref(null)
    const addTagInput = ref(null)

    // injects
    const tags = inject('tags')
    const categories = inject('categories')
    
    const addedTags = ref([])

    onMounted(() => {
        if (!userStore.loggedIn()) {
            console.log('not logged in')
            router.push('/forum')
        }

        addedTags.value = JSON.parse(JSON.stringify(tags.value)) // this might be slow...
    })

    // textarea watchers
    // watching for dynamic row extension
    /*
    watch(() => postDescription.value, (newDescription, oldDescription) => {
        textareaDescription.value.style.height = "8px";
        textareaDescription.value.style.height = (textareaDescription.value.scrollHeight)+"px";
    })
    */
    watch(() => postTitle.value, (newTitle, oldtitle) => {
        textareaTitle.value.style.height = "8px";
        textareaTitle.value.style.height = (textareaTitle.value.scrollHeight)+"px";
    })

    // tag functions
    const toggleTag = ((e) => {
        addedTags.value.every((tag) => {
            if(tag.id === selectedTagId.value) 
                tag.active = true
                return true
            }
        )
    })
    const addCustomTag = (() => {
        const tagName = addTagInput.value.value
        addTagInput.value.focus()

        let existingTagIndex = existingTagIndex = addedTags.value.findIndex((tag) => tag.name.toLowerCase() === tagName.toLowerCase())
        if (existingTagIndex !== -1) {
            addedTags.value[existingTagIndex].active = true
            addTagInput.value.value = ""
            return;
        }

        addedTags.value.push({
            id: addedTags.value.at(-1).id + 1,
            name: addTagInput.value.value,
            tagCount: 0,
            active: true
        })
        addTagInput.value.value = ""
    })

    // validation functions
    const titleValidation = computed(() => {
        if (postTitle.value.length > 0)
            return true

        errors.value.title = "Please enter a title"
        return false
    })
    const descriptionValidation = computed(() => {
        if (postDescription.value.length > 0)
            return true

        errors.value.description = "Please enter some description"
        return false
    })
    const tagsValidation = computed(() => {
        const allAddedTags = addedTags.value.filter((tag) => tag.active)
        if (allAddedTags.length > 0)
            return true

        errors.value.tags = "Please add 1 or more tags"
        return false
    })
    const categoryValidation = computed(() => {
        if (selectedCategoryId.value !== '')
            return true

        errors.value.category = "Please choose a category to which this post belongs"
        return false
    })
    const validateTagInput = ((e) => {
        if (e.keyCode === 13) {
            addCustomTag()
        } else {
            if (/^\W$/.test(e.key)) {
                e.preventDefault();
            }
        }
    })

    // post functions
    const createPost = (async () => {
        console.log(postDescription.value)
        // return
        try {
            const res = await axios.post('posts/create', {
                title: postTitle.value,
                description: postDescription.value,
                tags: addedTags.value.filter((tag) => tag.active),
                categoryID: selectedCategoryId.value
            })

            const postID = res.data.post.id
            const category = res.data.category.name

            // add newly created post to the store, so it gets displayed on the categories page instantly
            categoriesStore.addPost(res.data.post)

            // redirect user to his newly created post
            router.push(`/forum/${category}/${postID}`)
        } catch (error) {
            console.log(error)
        }
    })
</script>
    
<style scoped>
    textarea {
        resize: none;
        overflow: hidden;
    }

    .post-title {
        font-size: 1.25rem !important;
        display: block !important;
        overflow-wrap: break-word !important;
    }

    .post-description {
        min-height: 500px !important;
        font-size: 1rem !important;
    }

    .active-tag {
        background-color: #0D6EFD !important;
        color: #fff !important;
        opacity: 1 !important;
        transition: 0.2s !important;
    }
    .active-tag:hover {
        background-color: red !important;
    }
    .active-tag:hover::after {
        content: ' X';
        color: black;
    }

    .active-count {
        background-color: white !important;
        color: #000;
    }
</style>