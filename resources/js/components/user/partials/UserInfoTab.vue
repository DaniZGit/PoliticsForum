<template>
    <div class="d-flex row p-0 g-3" v-if="user">
        <div class="col-lg-4">
            <div class="border border-primary rounded bg-white mb-4">
                <div class="card-body text-center">
                    <div class="" style="display: inline-block; position: relative; width: 150px;">
                        <input 
                            class="aspect-ratio profile-picture-picker rounded-circle" 
                            id="poster-file" 
                            name="movie_poster" 
                            type="file" 
                            :disabled="loading"
                            @change="previewFiles"
                            accept="image/*"
                        />
                        <img 
                            src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp"
                            alt="" 
                            class="aspect-ratio rounded-circle profile-picture img-fluid"
                            id="profilePicture"
                        >
                    </div>
                    <h5 class="my-3">{{ user.name }}</h5>
                    <div class="md-form pink-textarea active-pink-textarea" ref="description" :contenteditable="descriptionEditable">
                        my description of some sort i really dont know what i should be writting here, but whatever. its something alteast
                    </div>
                    <a 
                        class="text-decoration-none" 
                        href="" 
                        @click.prevent="editDescription()"
                        >
                            {{descriptionEditable ? "Save" : "Edit"}} <i class="bi bi-pencil-square"></i>
                        </a>
                    <div class="d-flex justify-content-center my-2">
                        <button type="button" class="btn btn-primary">Follow</button>
                        <button type="button" class="btn btn-outline-primary ms-1">Message</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-4 border border-primary rounded">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Full Name</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">Johnatan Smith</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">example@example.com</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Phone</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">(097) 234-5678</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Mobile</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">(098) 765-4321</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Address</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">Bay Area, San Francisco, CA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 position-relative">
            <div class="d-flex flex-column scrollable border border-primary rounded">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>

                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>

                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>

                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>

                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
            <!--
            <div class="position-absolute bottom-0 start-50 translate-middle more-button-container m-0 p-2 mb-2">
                <div class="btn d-flex flex-column align-items-center text-center border border-primary rounded py-0 m-0 more-button-content">
                    <div class="h5 p-0 m-0 text-primary fw-bold outline">more</div>
                    <i class="h5 bi bi-caret-down-fill p-0 m-0 text-primary"></i>
                </div>
            </div>
            -->
        </div>
    </div>
    
</template>

<script setup>
    import { ref } from '@vue/reactivity'
    import { onMounted } from '@vue/runtime-core'
    import { useRouter, useRoute } from 'vue-router'
    import { useUserStore } from '../../../stores/userStore'

    const router = useRouter()
    const store = useUserStore()

    // variables
    let user = ref({})
    let descriptionEditable = ref(false)

    // refs
    const description = ref(null)

    onMounted(() => {
        user.value = store.user.data
        console.log("aaaaa")
        console.log(user)
        console.log(store.user.data)
        console.log(user.value.name)
    })

    function editDescription() {
        descriptionEditable.value = !descriptionEditable.value
        if (descriptionEditable.value){
            setTimeout(function() {
                description.value.focus();
            }, 0);
        }
    }

    /*                        */
    /* profile picture upload */
    /*                        */
    function previewFiles(e) {
        const file = e.target.files[0]
        // check if file type is image and if it is, preview the image
        if (file.type.includes('image')) {
            let profilePicture = document.getElementById("profilePicture")
            let reader = new FileReader()
            reader.onload = (event) => {
                profilePicture.src = event.target.result
                this.uploadedFile = file
            }
            reader.readAsDataURL(file)
        }
    }
</script>

<style>
    .profile-picture {
        cursor: pointer;
    }

    .profile-picture-picker{
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1;
        text-indent: -999em;
        outline: none;
        cursor: pointer;
    }

    .profile-picture-picker:hover {
        content: "upload";
        background-color: #121212;
        opacity: 0.5;
        color: white;
    }

    .aspect-ratio {
        aspect-ratio: 1;
        border-radius: 50%;
    }

    .scrollable {
        background-color: #fed9ff;
        height: 700px;
        overflow-x: hidden;
        overflow-y: auto;
        overflow: auto;
        text-align: center;
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    .scrollable::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .scrollable {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    .more-button-container {
        height: 25px;
        top: calc(100% - 50px);
    }

    .more-button-content {
        background: cyan;
    }
</style>