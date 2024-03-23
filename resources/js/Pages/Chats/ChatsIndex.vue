<script setup>
import { provide } from 'vue';
import ChatLayout from '../../Layouts/ChatLayout.vue'

const { chats } = defineProps({
    chats: Array
})

provide('chats', chats)
</script>

<template>
    <ChatLayout>
        test
    </ChatLayout>
</template>