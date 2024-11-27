<script setup>
</script>
<template>
    <nav>
        <ul class="flex gap-4">
            <li v-for="(item, key) in items">
                <RouterLink :to="item.path" class="text-gray-100">{{ item.name.split('.')[1] }}</RouterLink>
            </li>
        </ul>
    </nav>
</template>
<script>
export default {
    name: "Nav",
    props: {
        items: Array
    },
}
</script>