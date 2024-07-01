<script setup lang="ts">
import {computed, PropType, watch} from 'vue';
import { ref } from 'vue'

const props = defineProps({
    name: String,
    disabled: Boolean,
    error: [String, Boolean],
    success: [String, Boolean],
    label: String,
    placeholder: String,
    modelValue: String,
    maxlength: Number,
    styles: String,
    variant: {
        type: String,
        default: 'filled',
    },
    rows: {
        type: Number,
        default: 4
    },
    fieldSizingContent: {
        type: Boolean,
        default: false
    },
    onInput: Function as PropType<(e: Event) => void>,
    onFocus: Function as PropType<() => void>,
    onBlur: Function as PropType<() => void>,
    onKeyPress: Function as PropType<(any) => void>
})
const emit = defineEmits(['update:modelValue'])

const internalValue = ref(props.modelValue)
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
        <div class="flex items-center justify-between">
            <label :for="name" class="font-medium">{{ label }}</label>

            <p v-if="maxlength" class="text-xs text-secondary-text mr-1">{{ modelValue?.length ?? 0 }} / {{ maxlength }}</p>
        </div>

        <div
            :class="[
                'rounded-lg flex duration-300 max-h-48 overflow-auto',
                variantStyles,
                styles,
                {
                    error: error,
                    success: success,
                    selected: focused,
                }
            ]"
        >
            <textarea
                class="rounded-lg p-2 flex-grow bg-transparent outline-none max-w-full placeholder:text-secondary-text border-none"
                :class="{ 'field-sizing-content': fieldSizingContent }"
                :name="name"
                :placeholder="placeholder"
                :value="modelValue"
                :disabled="disabled"
                @input="handleInput"
                @focus="handleFocus"
                @blur="handleBlur"
                @keydown="onKeyPress"
                :maxlength="maxlength"
                :rows="rows"
            ></textarea>
        </div>

        <p v-if="error && typeof error !== 'boolean'" class="text-error text-sm">{{ error }}</p>
        <p v-else-if="success && typeof error !== 'boolean'" class="text-success text-sm">{{ success }}</p>
    </div>
</template>

<style>
.field-sizing-content {
    field-sizing: content;
}

.selected {
    @apply border-none ring-1 ring-primary;
}
.error {
    @apply ring-1 ring-error;
}
.success {
    @apply border-none ring-1 ring-success;
}
</style>
