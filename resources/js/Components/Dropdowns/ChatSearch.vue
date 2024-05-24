<script setup>
import DropdownMenu from 'v-dropdown-menu'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref } from 'vue'
import { watchDebounced } from '@vueuse/core'
import axios from 'axios'
import { faSearch } from '@fortawesome/free-solid-svg-icons'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    defaultSearchValue: String,
})

const searchValue = ref(props.defaultSearchValue ?? '')
const suggestedChats = ref([])

watchDebounced(searchValue, async () => {
    const response = await axios.get(`/chats?search=${searchValue.value}`)
    suggestedChats.value = response.data?.chats
}, { debounce: 300, maxWait: 500 })

const handleChatSelect = (chat) => router.visit(route('chat.show', { uuid: chat.uuid }))
</script>

<template>
    <DropdownMenu :overlay="false" :dropup="false" class="w-full">
        <template #trigger>
            <Textbox 
            v-model="searchValue" 
            :icon="faSearch"
            placeholder="Search your chats..."
            size="sm"
            />
        </template>
        
        <template #body v-if="searchValue.length > 0">
            <div class="overflow-auto max-h-64 flex flex-col">
                <button @click="handleChatSelect(chat)" v-for="chat in suggestedChats" :key="chat.uuid" class="text-sm py-2 px-6 hover:bg-dropdown-select rounded-md text-left">
                    {{ chat.name }}
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