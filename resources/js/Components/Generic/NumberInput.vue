<script setup>
import { ref, computed, watch, defineProps } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

const props = defineProps({
    label: String,
    icon: Object,
    placeholder: String,
    styles: String,
    min: {
        type: Number,
        default: 0,
    },
    max: {
        type: Number,
        default: 10000,
    },
    modelValue: Number
})

const emit = defineEmits(['update:modelValue'])

const handleInput = (event) => {
    emit('update:modelValue', event.target.value)
}


const isError = computed(() => {
    if (props.modelValue > props.max) {
        return `Value must be ${props.max} or less.`
    } else if (props.modelValue < props.min) {
        return `Value must be ${props.min} or more.`
    } else if (props.modelValue === null) {
        return 'Field is required.'
    }
    return false
})
</script>

<template>
    <div>
        <h4 class="font-medium">{{ label }}</h4>

        <div :class="['rounded-lg flex bg-base h-10 items-center', styles, { error: isError }]">
            <div v-if="icon" class="text-text-secondary flex justify-center items-center w-10 rounded-l-md">
                <FontAwesomeIcon :icon="icon" size="1x" class="text-secondary-text" />
            </div>

            <input
                @input="handleInput"
                :value="modelValue"
                :placeholder="placeholder"
                type="number"
                :min="min"
                :max="max"
                class="flex-grow w-full p-2 border-none outline-none rounded-lg placeholder:text-secondary-text bg-transparent appearance-none"
            />
        </div>

        <p v-if="isError" class="text-error text-sm">{{ isError }}</p>
        <p v-else-if="error && typeof error === 'string'" class="text-error text-sm">{{ error }}</p>
    </div>
</template>

<style>
.error {
    @apply ring-1 ring-error;
}
</style>
