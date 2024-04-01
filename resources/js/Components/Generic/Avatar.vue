<script setup>
const props = defineProps({
    src: String,
    styles: {
        type: String,
        default: ''
    }
})
</script>

<template>
    <img :src="src" alt="avatar" :class="`rounded-full ${styles}`" />
</template>
