<template>
    <button :type="type" @click="$emit('action-click', $event)"
        class="featuredBtn w-full p-3 mb-3 text-gray-900 font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
        {{ text }}
    </button>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: 'button',
        },
        text: String,
    }
}
</script>