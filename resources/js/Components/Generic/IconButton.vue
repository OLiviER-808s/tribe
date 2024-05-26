<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Button from './Button.vue'
import { computed } from 'vue';

const { color, variant, styles, disabled, type, href, onClick, icon, size } = defineProps({
    icon: {
        required: true
    },
    size: {
        type: String,
        default: undefined
    },
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

const { value: btnSize } = computed(() => {
    switch (size) {
        case 'lg':
            return 'w-10 h-10'
        case 'xl':
            return 'w-12 h-12'
		default:
            return 'w-8 h-8'
	}
})
</script>

<template>
    <Button
    :styles="`${styles} ${btnSize} flex justify-center items-center !px-0 !py-0`"
    :href="href"
    :type="type"
    :color="color"
    :on-click="onClick"
    :disabled="disabled"
    :variant="variant">
        <FontAwesomeIcon :size="size" :icon="icon" />
    </Button>
</template>

