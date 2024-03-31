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
    styles: {
        type: String,
        default: ''
    }
})

const textSizeClass = computed(() => `text-${props.size}`)
const borderColorClass = computed(() => `border-${props.color}`)
const bgColorClass = computed(() => `bg-${props.color}`)
const textColorClass = computed(() => `text-${props.color}`)
const hoverBgColorClass = computed(() => `${props.color}/10`)
</script>

<template>
    <button 
        @click="onSelect" 
        :class="`py-1 px-3 rounded-full border-2 duration-100 text-center ${textSizeClass} ${borderColorClass} ${selected ? bgColorClass + ' text-black' : 'bg-transparent ' + textColorClass + ' hover:bg-' + hoverBgColorClass}` + ' ' + styles"
    >
        <slot />
    </button>
</template>
