<script setup>
import ChatLayout from '../../Layouts/ChatLayout.vue'

const { chats } = defineProps({
    chats: Array
})
</script>

<template>
    <ChatLayout :chats="chats">
        test
    </ChatLayout>
</template>