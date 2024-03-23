<script setup lang="ts">
import { ref, computed, defineProps, PropType } from 'vue'
import { IconDefinition } from '@fortawesome/free-solid-svg-icons'

const { name, defaultValue, type, disabled, error, success, variant, styles, label, icon, placeholder, onInput } = defineProps({
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
})

const emit = defineEmits(['update:modelValue'])
const internalValue = ref(defaultValue)

const variantStyles = computed(() =>
    variant === 'filled' ? 'bg-base' : 'bg-transparent border border-secondary-text'
)

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement
    internalValue.value = target.value
    emit('update:modelValue', internalValue.value)

    if (onInput) {
        onInput(event)
    }
}
</script>

<template>
    <div>
        <label :for="name" class="font-medium">{{ label }}</label>

        <div
            :class="[
                'rounded-lg flex h-10',
                variantStyles,
                styles,
                {
                    error: error && typeof error !== 'boolean',
                    success: success && typeof error !== 'boolean'
                }
            ]"
        >
            <div v-if="icon" class="flex justify-center items-center w-10 rounded-l-md">
                <Fa :icon="icon" size="1.2x" class="text-secondary-text" />
            </div>

            <input
                :name="name"
                :type="type"
                :placeholder="placeholder"
                v-model="internalValue"
                :disabled="disabled"
                @input="handleInput"
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
.error {
    @apply border-none ring-1 ring-error;
}

.success {
    @apply border-none ring-1 ring-success;
}
</style>
