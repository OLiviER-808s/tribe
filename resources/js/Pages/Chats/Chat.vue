<script setup>
import { provide } from 'vue';
import ChatLayout from '../../Layouts/ChatLayout.vue'
import MessageInput from '@/Components/InputFields/MessageInput.vue'
import ChatHeader from '@/Components/Display/ChatHeader.vue';

const { chats, chat, messages } = defineProps({
    chats: Array,
    chat: Object,
    messages: Array
})

provide('chats', chats)
provide('chat', chat)

window.Echo.join(`chat.${chat.uuid}`).listen('.message-sent', (e) => {
    console.log(e)
})
</script>

<template>
    <ChatLayout>
        <div class="h-full max-w-full flex flex-col">
            <ChatHeader />

            <MessageInput />
        </div>
    </ChatLayout>
</template>