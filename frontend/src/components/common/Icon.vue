<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

defineProps({
    name: {
        type: String,
        required: true,
    },
    size: {
        type: String,
        default: '16px',
    },
    color: {
        type: String,
        default: '#000',
    },
    customClass: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <FontAwesomeIcon :icon="['fas', name]" :class="customClass" :style="{ fontSize: size, color: color }" />
</template>