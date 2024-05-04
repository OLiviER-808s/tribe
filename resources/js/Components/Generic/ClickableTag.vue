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
    variant: {
        type: String,
        default: 'outline'
    },
    styles: {
        type: String,
        default: ''
    }
})

const classes = computed(() => {
    switch (props.variant) {
		case 'outline':
            return `${props.styles} tag rounded-${props.rounded} text-${props.size} text-${props.color} border-2 border-${props.color} hover:bg-${props.color}/10`
	}
})

const selectedClasses = computed(() => {
    switch (props.variant) {
		case 'outline':
            return `${props.styles} tag rounded-${props.rounded} text-${props.size} bg-${props.color} border-2 border-${props.color} text-black`
	}
})
</script>

<template>
    <button @click="onSelect" :class="selected ? selectedClasses : classes">
        <slot />
    </button>
</template>

<style>
    .tag {
        @apply py-1 px-3 duration-100 text-center;
    }
</style>