<script setup>
import DropdownMenu from 'v-dropdown-menu'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref } from 'vue'
import { watchDebounced } from '@vueuse/core'
import axios from 'axios'
import { faSearch } from '@fortawesome/free-solid-svg-icons'
import {router} from "@inertiajs/vue3";

const props = defineProps({
    defaultSearchValue: String,
    filteredOutTopics: Array,
    withAdd: {
        type: Boolean,
        default: true
    }
})
const emit = defineEmits(['onTopicSelect'])

const searchValue = ref(props.defaultSearchValue ?? '')
const suggestedTopics = ref([])

const error = ref('')

watchDebounced(searchValue, async () => {
    const response = await axios.get(`/topics?search=${searchValue.value}`)
    suggestedTopics.value = response.data?.topics.filter(topic => !props.filteredOutTopics.find(({ uuid }) => topic.uuid === uuid))
}, { debounce: 300, maxWait: 500 })

const handleTopicSelect = (topic) => {
    emit('onTopicSelect', topic)
}

const addNewTopic = async () => {
    try {
        const response  = await axios.post(route('topic.store'), {
            label: searchValue.value
        })

        emit('onTopicSelect', response.data.topic)
    } catch (errors) {
        error.value = errors.response?.data?.message
    }
}
</script>

<template>
    <DropdownMenu :overlay="false" :dropup="false" withDropdownCloser class="w-full">
        <template #trigger>
            <Textbox
                v-model="searchValue"
                :icon="faSearch"
                :error="error"
                placeholder="Search for topics..."
            />
        </template>

        <template #body v-if="searchValue.length > 0">
            <div class="overflow-auto max-h-64 flex flex-col">
                <button v-for="topic in suggestedTopics" :key="topic.uuid" type="button" @click="handleTopicSelect(topic)" dropdown-closer class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md text-left">
                    {{ topic.label }}
                </button>
                <button v-if="withAdd" @click="addNewTopic" dropdown-closer type="button" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md text-left">
                    Add Interest
                </button>
            </div>
        </template>
    </DropdownMenu>
</template>

<style>
.v-dropdown-menu__container {
    @apply w-full rounded-md bg-dropdown text-dropdown-text !border-none;
}
</style>
