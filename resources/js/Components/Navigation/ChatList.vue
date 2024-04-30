<script setup>
import { inject, ref, computed } from 'vue'
import ChatTab from './ChatTab.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowLeft, faBoxArchive } from '@fortawesome/free-solid-svg-icons';
import IconButton from '../Generic/IconButton.vue';

const chats = inject('chats')

const archiveMode = ref(false)
const displayChats = computed(() => chats.filter(chat => archiveMode.value ? chat.archived : !chat.archived))
</script>

<template>
    <div class="flex flex-col h-full p-2">
        <div v-if="archiveMode" class="flex items-center gap-3 mb-4 font-medium">
            <IconButton variant="subtle" color="base" :icon="faArrowLeft" @click="archiveMode = false" /> Archived Chats
        </div>

        <div class="flex-grow h-0 overflow-auto">
            <ChatTab v-for="chat in displayChats" :chat="chat" />

            <button v-if="!archiveMode" @click="archiveMode = true" class="w-full rounded-lg hover:bg-base p-2 text-left flex items-center gap-3">
                <FontAwesomeIcon :icon="faBoxArchive" />
                Archive
            </button>
        </div>
    </div>
</template>