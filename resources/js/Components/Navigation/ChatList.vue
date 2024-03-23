<script setup>
import { inject } from 'vue';
import ChatTab from './ChatTab.vue'

const chats = inject('chats')
</script>

<template>
    <div class="flex flex-col h-full p-2">
        <div class="flex-grow h-0 overflow-auto">
            <ChatTab v-for="chat in chats" :chat="chat" />
        </div>
    </div>
</template>