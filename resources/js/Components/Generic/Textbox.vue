<script setup lang="ts">
import { ref, computed, defineProps, PropType, watch } from 'vue'
import { IconDefinition } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps({
    name: String,
    defaultValue: String,
    type: {
        type: String,
        default: 'text',
    },
    disabled: Boolean,
    error: [String, Boolean],
    success: [String, Boolean],
    variant: {
        type: String,
        default: 'filled',
    },
    styles: String,
    label: String,
    icon: Object as PropType<IconDefinition | null>,
    placeholder: String,
    onInput: Function as PropType<(e: Event) => void>,
    onFocus: Function as PropType<() => void>,
    onBlur: Function as PropType<() => void>,
    modelValue: String
})

const emit = defineEmits(['update:modelValue'])
const internalValue = ref(props.defaultValue)

const focused = ref(false)

const variantStyles = computed(() => props.variant === 'filled' ? 'bg-base' : 'bg-transparent border border-secondary-text')

const handleFocus = () => {
    focused.value = true
    if (props.onFocus) props.onFocus()
}

const handleBlur = () => {
    focused.value = false
    if (props.onBlur) props.onBlur()
}

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement
    internalValue.value = target.value
    emit('update:modelValue', internalValue.value)

    if (props.onInput) props.onInput(event)
}

watch(() => props.modelValue, (newVal) => {
    internalValue.value = newVal
})
</script>

<template>
    <div>
        <label :for="name" class="font-medium">{{ label }}</label>

        <div
            :class="[
                'rounded-lg flex h-10 duration-300',
                variantStyles,
                styles,
                {
                    error: error,
                    success: success,
                    selected: focused
                }
            ]"
        >
            <div v-if="icon" class="flex justify-center items-center w-10 rounded-l-md">
                <FontAwesomeIcon :icon="icon" class="text-secondary-text" />
            </div>

            <input
                :name="name"
                :type="type"
                :placeholder="placeholder"
                :value="modelValue"
                :disabled="disabled"
                @input="handleInput"
                @focus="handleFocus"
                @blur="handleBlur"
                :class="{ 'pl-0': icon }"
                class="flex-grow w-full p-2 border-none outline-none rounded-lg bg-transparent placeholder:text-secondary-text"
            />

            <slot name="right-section" />
        </div>

        <p v-if="error && typeof error !== 'boolean'" class="text-error text-sm">{{ error }}</p>

        <p v-if="success && typeof error !== 'boolean'" class="text-success text-sm">{{ success }}</p>
    </div>
</template>

<style>
.selected {
    @apply border-none ring-1 ring-primary;
}

.error {
    @apply border-none ring-1 ring-error;
}

.success {
    @apply border-none ring-1 ring-success;
}
</style>
