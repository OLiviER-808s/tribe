<script setup>
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ref } from 'vue';

const { color, variant, styles, disabled, type, href, onClick } = defineProps({
    color: {
        type: String,
        default: 'primary'
    },
    variant: {
        type: String,
        default: 'filled'
    },
    styles: {
        type: String,
        default: ''
    },
    disabled: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'button'
    },
    href: {
        type: String,
        default: ''
    },
    onClick: {
        type: Function,
        default: (e) => {}
    }
})

const isPressed = ref(false)

const handleClick = (e) => {
    if (href) {
        router.visit(href)
    }
    onClick(e)
}

const btnColor = computed(() => {
    return color === 'base' ? 'secondary-text' : color
})
const classes = computed(() => {
    switch (variant) {
		case 'light':
			return `${styles} btn bg-${btnColor.value}/20 hover:bg-${btnColor.value}/35 text-${btnColor.value}`
		case 'outline':
			return `${styles} btn text-${btnColor.value} border-${btnColor.value} hover:bg-${btnColor.value}/10 border-2`
		case 'subtle':
			return `${styles} btn text-${btnColor.value} hover:bg-${btnColor.value}/10`
		default:
			return `${styles} btn text-black bg-${btnColor.value}`
	}
})
</script>

<template>
    <button 
    @click="handleClick" 
    @mousedown="isPressed = true"
    @mouseup="isPressed = false"
    :class="[classes, { 'pressed': isPressed }, { 'disabled-btn': disabled }]" 
    :disabled="disabled" 
    :type="type">
        <slot />
    </button>
</template>

<style>
	.pressed {
		transform: translateY(2px);
	}

	.btn {
		@apply px-6 py-2 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2;
	}

	.disabled-btn {
		@apply rounded-lg font-semibold bg-base;
	}
</style>