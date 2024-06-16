<script setup>
import {computed, ref} from "vue"

const props = defineProps({
    value: String,
    label: String,
    name: String,
    modelValue: String
})
const emit = defineEmits(['update:modelValue'])

const hovering = ref(false)
const checked = computed(() => props.modelValue === props.value)

const select = () => emit('update:modelValue', props.value)
</script>

<template>
    <label class="flex items-center gap-3 relative cursor-pointer" @mouseenter="hovering = true" @mouseleave="hovering = false">
        <input class="absolute opacity-0 cursor-pointer" type="radio" :name="name" :value="value" :checked="checked"  @change="select()">

        <span class="rounded-full border-2 border-secondary flex items-center justify-center h-4 w-4">
            <span class="rounded-full h-2 w-2" :class="{'bg-secondary': hovering || checked}"></span>
        </span>

        <span>{{ label }}</span>
    </label>
</template>

<style>

</style>
