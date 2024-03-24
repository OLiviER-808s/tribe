<script setup>
import { provide } from 'vue';
import ChatLayout from '../../Layouts/ChatLayout.vue'

const { chats, chat, messages } = defineProps({
    chats: Array,
    chat: Object,
    messages: Array
})
provide('chats', chats)
</script>

<template>
    <ChatLayout>
        <div>
            test 2
        </div>
    </ChatLayout>
</template>