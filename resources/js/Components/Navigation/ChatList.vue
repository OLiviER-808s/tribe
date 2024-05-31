<script setup>
import { inject, ref, computed } from 'vue'
import ChatTab from './ChatTab.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowLeft, faBoxArchive } from '@fortawesome/free-solid-svg-icons';
import IconButton from '../Generic/IconButton.vue';
import ChatSearch from '../Dropdowns/ChatSearch.vue';

const chats = inject('chats')

const archiveMode = ref(false)
const displayChats = computed(() => chats.filter(chat => archiveMode.value ? chat.archived : !chat.archived))
</script>

<template>
    <div class="flex flex-col h-full p-2">
        <div v-if="archiveMode" class="flex items-center gap-3 mb-4 font-medium">
            <IconButton variant="subtle" color="base" :icon="faArrowLeft" @click="archiveMode = false" /> Archived Chats
        </div>
        <div v-else class="flex items-center gap-2 pb-2">
            <ChatSearch />
        </div>

        <div class="flex-grow h-0 overflow-auto">
            <div v-for="chat in displayChats" :key="chat.uuid">
                <ChatTab :chat="chat" />
            </div>
        </div>

        <button v-if="!archiveMode" @click="archiveMode = true" class="w-full rounded-lg hover:bg-base p-2 text-left flex items-center gap-3">
            <FontAwesomeIcon :icon="faBoxArchive" />
            Archive
        </button>
    </div>
</template>
