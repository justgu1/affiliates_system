<script setup>
const isAdmin = localStorage.getItem('user_isAdmin')
</script>
<template>
    <template v-if="isAdmin">
        isAdmin
        
    </template>
    <template v-else>
        notAdmin
    </template>
</template>