<script setup>
import { provide } from 'vue';
import ChatLayout from '../../Layouts/ChatLayout.vue'
import MessageInput from '@/Components/InputFields/MessageInput.vue'

const { chats, chat, messages } = defineProps({
    chats: Array,
    chat: Object,
    messages: Array
})

provide('chats', chats)
provide('chat', chat)

window.Echo.private(`chat.${chat.uuid}`).subscribed(() => console.log('subed')).listen('.message-sent', (e) => {
    console.log(e)
})
</script>

<template>
    <ChatLayout>
        <div class="h-full max-w-full flex flex-col">
            <MessageInput />
        </div>
    </ChatLayout>
</template>