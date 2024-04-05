<script setup>
const props = defineProps({
    color: {
        type: String,
        default: 'secondary'
    },
    size: {
        type: String,
        default: 'md'
    },
    styles: {
        type: String,
        default: ''
    }
})
</script>

<template>
    <div :class="`py-1 px-4 rounded-full border-2 duration-100 text-center text-${size} bg-${color}`">
        <slot />
    </div>
</template>
