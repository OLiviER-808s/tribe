<script setup>
const props = defineProps({
    content: [String, Number, null],
    color: {
        type: String,
        default: 'primary'
    },
    size: {
        type: String,
        default: 'xs'
    },
    styles: String
})
</script>

<template>
    <div :class="`text-${size} text-black font-bold bg-${color} rounded-xl px-1 ${styles}`">
        {{ content }}
    </div>
</template>