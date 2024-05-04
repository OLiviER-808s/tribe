<script setup>
import { computed } from 'vue'

const props = defineProps({
    selected: Boolean,
    onSelect: Function,
    color: {
        type: String,
        default: 'secondary'
    },
    size: {
        type: String,
        default: 'md'
    },
    rounded: {
        type: String,
        default: 'full'
    },
    styles: {
        type: String,
        default: ''
    }
})

const classes = computed(() => {
    return `${props.styles} tag rounded-${props.rounded} text-${props.size} text-${props.color} border-${props.color} hover:bg-${props.color}/10`
})

const selectedClasses = computed(() => {
    return `${props.styles} tag rounded-${props.rounded} text-${props.size} bg-${props.color} border-${props.color} text-black`
})
</script>

<template>
    <button @click="onSelect" :class="selected ? selectedClasses : classes">
        <slot />
    </button>
</template>

<style>
    .tag {
        @apply py-1 px-3 duration-100 text-center border-2;
    }
</style>