<script setup>
import DropdownMenu from 'v-dropdown-menu'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref } from 'vue'
import { watchDebounced } from '@vueuse/core'
import axios from 'axios'
import { faSearch } from '@fortawesome/free-solid-svg-icons'

const props = defineProps({
    defaultSearchValue: String,
    filteredOutTopics: Array
})
const emit = defineEmits(['onTopicSelect'])

const searchValue = ref(props.defaultSearchValue ?? '')
const suggestedTopics = ref([])

watchDebounced(searchValue, async () => {
    const response = await axios.get(`/topics?search=${searchValue.value}`)
    suggestedTopics.value = response.data?.topics.filter(topic => !props.filteredOutTopics.find(({ uuid }) => topic.uuid === uuid))
}, { debounce: 300, maxWait: 500 })

const handleTopicSelect = (topic) => {
    emit('onTopicSelect', topic)
}
</script>

<template>
    <DropdownMenu :overlay="false" :dropup="false">
        <template #trigger>
            <Textbox 
            v-model="searchValue" 
            :icon="faSearch"
            placeholder="Search for topics..."
            />
        </template>
        
        <template #body v-if="searchValue.length > 0">
            <div class="overflow-auto max-h-64 flex flex-col">
                <button @click="handleTopicSelect(topic)" v-for="topic in suggestedTopics" :key="topic.uuid" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md text-left">
                    {{ topic.label }}
                </button>
            </div>
        </template>
    </DropdownMenu>
</template>

<style>
.v-dropdown-menu__container {
    @apply rounded-md bg-dropdown text-dropdown-text !border-none;
}
</style>