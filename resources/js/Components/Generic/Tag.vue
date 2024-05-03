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
    },
    rounded: {
        type: String,
        default: 'full'
    }
})
</script>

<template>
    <div :class="`py-1 px-4 rounded-${rounded} duration-100 text-center text-black text-${size} bg-${color}`">
        <slot />
    </div>
</template>
