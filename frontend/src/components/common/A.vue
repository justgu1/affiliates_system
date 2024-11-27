<script setup>

</script>

<template>
    <a :href="href">
        {{ name }}
    </a>
</template>

<script>
export default {
    props: {
        name: String,
        href: String
    }
}
</script>