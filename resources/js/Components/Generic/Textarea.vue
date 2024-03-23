<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps({
    name: String,
    value: String,
    disabled: Boolean,
    error: [String, Boolean],
    label: String,
    placeholder: String,
})

const emit = defineEmits(['update:modelValue'])
const internalValue = ref(props.value)

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement
    internalValue.value = target.value
    emit('update:modelValue', internalValue.value)
}
</script>

<template>
    <div>
        <label :for="name" class="font-medium">{{ label }}</label>

        <textarea
            :class="[{ error: error && typeof error !== 'boolean' }, 'rounded-lg p-2 bg-base outline-none w-full h-24 placeholder:text-secondary-text border-none']"
            :name="name"
            :placeholder="placeholder"
            v-model="internalValue"
            :disabled="disabled"
            @input="handleInput"
        ></textarea>

        <p v-if="error && typeof error !== 'boolean'" class="text-error text-sm">{{ error }}</p>
    </div>
</template>

<style>
.error {
    @apply ring-1 ring-error;
}
</style>
