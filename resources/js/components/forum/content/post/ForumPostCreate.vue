<template>
    <h1>HELLOO</h1>
</template>

<script setup>
    import { useUserStore } from '../../../../stores/userStore'
    import axios from 'axios'
import { onMounted } from '@vue/runtime-core'
import { useRouter } from 'vue-router'

    let userStore = useUserStore()
    let router = useRouter()

    onMounted(() => {
        console.log(userStore.loggedIn())
        if (!userStore.loggedIn()) {
            console.log('not logged in')
            router.push('/forum')
        }
    })
</script>
    
<style>

</style>