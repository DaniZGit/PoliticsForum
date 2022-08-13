<template>
    <form @submit.prevent="registerUser" class="col-md-4 mx-auto" enctype="multipart/form-data">
        <!-- is-valid & is-invalid bootstrap classes for inputs -->
        <div class="row mb-3">
            <div class="col-md-6 mx-auto text-center">
                <div class="w-50" style="display: inline-block; position: relative;">
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
                        src="https://e7.pngegg.com/pngimages/219/352/png-clipart-nadona-user-profile-computer-icons-avatar-account-blue-heroes-thumbnail.png"
                        alt="" 
                        class="aspect-ratio rounded-circle profile-picture w-100"
                        id="profilePicture"
                    >
                </div>
                
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="validationServer01" class="form-label">Email</label>
                <input 
                    type="text" 
                    class="form-control" 
                    :class="{'is-invalid': !emailValidation}" 
                    id="validationServer01" 
                    name="email"
                    v-model="user.email" 
                    required
                >
                <div class="invalid-feedback">
                    {{ errors.email }}
                </div>
            </div>

            <div class="col-md-6">
                <label for="validationServerUsername" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text" id="inputGroupPrepend3">@</span>
                    <input 
                        type="text" 
                        class="form-control" 
                        :class="{'is-invalid': !usernameValidation}" 
                        id="validationServerUsername" 
                        name="username"
                        v-model="user.username" 
                        required
                    >
                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                        {{ errors.username }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">Password</label>
                <input 
                    type="password" 
                    class="form-control" 
                    :class="{'is-invalid': !passwordValidation}"
                    id="inputPassword4"
                    name="password"
                    v-model="user.password"
                    required
                >
                <div class="invalid-feedback">
                    {{ errors.password }}
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">Password Confirmation</label>
                <input 
                    type="password" 
                    class="form-control"
                    :class="{'is-invalid': !passwordConfirmValidation}"
                    id="inputPassword4"
                    name="password_confirmation"
                    v-model="user.password_confirmation"
                    required
                >
                <div class="invalid-feedback">
                    {{ errors.password_confirmation }}
                </div>
            </div>
        </div>

        <div class="row" v-if="errors.base_error">
            <div class="d-flex justify-content-center">
                <span class="alert alert-danger p-2 text-center d-inline-block" 
                >
                    {{ errors.base_error }}
                </span>
            </div>
        </div>
        
        <div class="row d-flex flex-column align-items-center">
            <button 
                class="btn btn-primary w-50" 
                type="submit"
                :disabled="!(emailValidation && usernameValidation && passwordValidation && passwordConfirmValidation) || loading"
            >Register</button>
            <div class="d-inline text-center">Already an user? <router-link to="/user/login" class="text-decoration-none text-primary">Login here</router-link></div>
        </div>
    </form>

</template>

<script setup>
    import {onMounted, ref, computed, reactive} from 'vue';
    import { useRouter } from 'vue-router'
    import VueCookie from 'vue-cookie'
    import axios from 'axios'
    import { useStore } from 'vuex'

    // variables
    const router = useRouter()
    const cookie = VueCookie
    const store = useStore()

    let loading = ref(false)
    let user = ref({
        'email': '',
        'username': '',
        'password': '',
        'password_confirmation': '',
    })
    let uploadedFile = ref(null)

    // props
    const props = defineProps(['users'])

    let errors = ref({
        'email': '',
        'username': '',
        'password': '',
        'password_confirmation': '',
        'base_error': ''
    })

    onMounted(() => {
        if (store.getters.user.is_authenticated)
            router.push({ name: 'UserDashboard' })
    })

    /*                 */
    /* form validation */
    /*                 */
    const emailValidation = computed(() => {
        // if email already exists
        if( props.users.find((u) => u.email.toLowerCase() === user.value.email.toLowerCase()) ) {
            errors.value.email = "Email is already taken."
            return false
        }

        return true
    })

    const usernameValidation = computed(() => {
        // if username already exists
        if( props.users.find((u) => u.name.toLowerCase() === user.value.username.toLowerCase()) ) {
            errors.value.username = "Username is already taken..."
            return false
        } else if( user.value.username.length < 3 ) {
            errors.value.username = "Username must contain atleast 3 characters."
            return false
        }

        return true
    })

    const passwordValidation = computed(() => {
        if(user.value.password.length < 6) {
            errors.value.password = "Password needs to contain atleast 6 letters."
            return false
        }

        return true
    })

    const passwordConfirmValidation = computed(() => {
        if(user.value.password !== user.value.password_confirmation) {
            errors.value.password_confirmation = "Passwords do not match."
            return false
        }

        return true
    })

    /*                                      */
    /* create user / send request to server */
    /*                                      */
    async function registerUser() {
        loading.value = true

        await axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie');

        try {
            const res = await axios.post('users/register', {
                email: user.value.email,
                username: user.value.username,
                password: user.value.password,
                password_confirmation: user.value.password_confirmation
            })
            loading.value = false
            if (res.status === 200) {
                if(res.data.success) {
                    /*
                    let d = new Date();
                    d.setTime(d.getTime() + 1 * 24 * 60 * 60 * 1000);
                    cookie.set('token', res.data.token, d.toUTCString())
                    */

                    store.dispatch('user', {token: res.data.token, is_authenticated: true, data: res.data.userData})
                    router.push({ name: 'UserDashboard' })
                } else {
                    console.log(res.data.message)
                    errors.value.base_error = res.data.message
                }
                
            } else {
                console.log(res.data)
            }
        } catch (error) {
            if (error.response) {
                loading.value = false
                console.log(error.response.data.errors)
                const responseErrors = error.response.data.errors
                Object.keys(responseErrors).forEach(key => {
                    errors.value.base_error += responseErrors[key][0] + "\n"
                });
            }
            
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

<style scoped>
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
    
</style>