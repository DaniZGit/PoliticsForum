<template>
    <form @submit.prevent="loginUser" class="col-md-4 mx-auto" enctype="multipart/form-data">
        <!-- is-valid & is-invalid bootstrap classes for inputs -->
        <div class="row mb-3">
            <div class="col-md-6 mx-auto text-center">
                <div class="w-50" style="display: inline-block; position: relative;">
                    <img 
                        :src="findProfileByEmail"
                        alt="" 
                        class="aspect-ratio rounded-circle w-100"
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
                :disabled="!(emailValidation && passwordValidation) || loading"
            >Login</button>
            <div class="d-inline text-center">Not yet an user? <router-link to="/user/register" class="text-decoration-none text-primary">Register here</router-link></div>
        </div>
    </form>

</template>

<script setup>
    import {onMounted, ref, computed, reactive} from 'vue'
    import { useRouter } from 'vue-router'
    import VueCookie from 'vue-cookie'
    import axios from 'axios'
    import { useUserStore } from '../../../stores/userStore'
    
    // variables
    const router = useRouter()
    const cookie = VueCookie
    const store = useUserStore()

    let loading = ref(false)
    let defaultProfilePicture = 'https://e7.pngegg.com/pngimages/219/352/png-clipart-nadona-user-profile-computer-icons-avatar-account-blue-heroes-thumbnail.png'
    let user = ref({
        'email': '',
        'username': '',
        'password': '',
        'password_confirm': '',
    })
    let errors = ref({
        'email': '',
        'username': '',
        'password': '',
        'password_confirm': '',
        'base_error': ''
    })

    // props
    const props = defineProps(['users'])

    

    onMounted(() => {
        if (store.loggedIn())
            router.push({ name: 'UserDashboard' })

        console.log(store.user)
    })
    /*                 */
    /* form validation */
    /*                 */
    const emailValidation = computed(() => {
        // if email valid
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(user.value.email)) {
            return true
        } else {
            errors.value.email = "Please enter valid email."
            return false
        }
    })

    const passwordValidation = computed(() => {
        if(user.value.password.length < 6) {
            errors.value.password = "Password needs to contain atleast 6 letters."
            return false
        }

        return true
    })

    const findProfileByEmail = computed(() => {
        // if email already exists
        const existingUser = props.users.find((u) => u.email.toLowerCase() === user.value.email.toLowerCase())
        if( existingUser ) {
            console.log(existingUser)
            return existingUser.profile_picture !== null ? existingUser.profile_picture : defaultProfilePicture
        }

        return defaultProfilePicture
    })

    /*                                      */
    /* create user / send request to server */
    /*                                      */
    async function loginUser() {
        loading.value = true
        errors.value.base_error = ''

        await axios.get('http://127.0.0.1:8000/sanctum/csrf-cookie');

        const res = await axios.post('users/login', {
            email: user.value.email,
            password: user.value.password
        })
        loading.value = false
        if (res.status === 200) {
            console.log(res)
            if(res.data.success) {
                /*
                let d = new Date();
                d.setTime(d.getTime() + 1 * 24 * 60 * 60 * 1000);
                cookie.set('token', res.data.token, d.toUTCString())
                */

                console.log(res.data)
                store.setUser({token: res.data.token, is_authenticated: true, data: res.data.userData})
                //console.log(store.user)
                router.push({ name: 'UserDashboard' })
            } else {
                console.log(res.data.message)
                errors.value.base_error = res.data.message
            }
            
        } else {
            console.log(res.data)
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