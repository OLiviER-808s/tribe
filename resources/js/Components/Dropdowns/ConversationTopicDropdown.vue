<script setup>
import DropdownMenu from 'v-dropdown-menu'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref, watch } from 'vue'
import { watchDebounced } from '@vueuse/core'
import axios from 'axios'

const props = defineProps({
    modelValue: String,
    error: [String, Boolean],
})
const emit = defineEmits(['update:modelValue'])

const searchValue = ref(props.modelValue)
const suggestedTopics = ref([])

watchDebounced(searchValue, async () => {
    const response = await axios.get(`/topics?search=${searchValue.value}`)
    suggestedTopics.value = response.data?.topics
}, { debounce: 300, maxWait: 500 })

watch(searchValue, () => {
    const topic = suggestedTopics.value.find(topic => topic.label.toLowerCase() === searchValue.value.toLowerCase())

    if (topic) {
        emit('update:modelValue', topic.uuid)
        suggestedTopics.value = []
    } else {
        emit('update:modelValue', '')
    }
})
</script>

<template>
    <DropdownMenu :overlay="false" :dropup="false" withDropdownCloser>
        <template #trigger>
            <Textbox
            v-model="searchValue"
            label="Topic"
            placeholder="Pick a topic..."
            :error="error"
            />
        </template>

        <template #body>
            <div class="overflow-auto max-h-64 flex flex-col">
                <button @click="searchValue = topic.label" v-for="topic in suggestedTopics" :key="topic.uuid" dropdown-closer class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md text-left">
                    {{ topic.label }}
                </button>
            </div>
        </template>
    </DropdownMenu>
</template>

<style>
.v-dropdown-menu__container {
    @apply !w-full rounded-md bg-dropdown text-dropdown-text !border-none;
}
</style>
