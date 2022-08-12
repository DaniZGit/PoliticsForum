<template>
    <router-view :users="users"></router-view>
</template>

<script setup>
    import {onMounted, ref } from 'vue'
    
    /*                 */
    /* fetch users */
    /*                 */
    let users = ref([]);
    onMounted(async () => {
        console.log('users')
        let res = await fetch('/api/users')
        let data = await res.json()
        users.value = data
        console.log(users.value)
    })
</script>