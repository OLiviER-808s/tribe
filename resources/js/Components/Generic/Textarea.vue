<script setup lang="ts">
import { watch } from 'vue';
import { ref } from 'vue'

const props = defineProps({
    name: String,
    disabled: Boolean,
    error: [String, Boolean],
    label: String,
    placeholder: String,
    modelValue: String,
    maxlength: Number
})

const emit = defineEmits(['update:modelValue'])
const internalValue = ref(props.modelValue)

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement
    internalValue.value = target.value
    emit('update:modelValue', internalValue.value)
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

        <textarea
            :class="[{ error: error && typeof error !== 'boolean' }, 'rounded-lg p-2 bg-base outline-none w-full h-24 placeholder:text-secondary-text border-none']"
            :name="name"
            :placeholder="placeholder"
            :value="modelValue"
            :disabled="disabled"
            @input="handleInput"
            :maxlength="maxlength"
        ></textarea>

        <p v-if="error && typeof error !== 'boolean'" class="text-error text-sm">{{ error }}</p>
    </div>
</template>

<style>
.error {
    @apply ring-1 ring-error;
}
</style>
