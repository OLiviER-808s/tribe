<script setup>
import { provide } from 'vue';
import ChatLayout from '../../Layouts/ChatLayout.vue'
import ChatList from '@/Components/Navigation/ChatList.vue';
import { useIsHandheld } from '@/Composables/useIsHandheld';

const { chats } = defineProps({
    chats: Array
})
provide('chats', chats)

const { isHandheld } = useIsHandheld()
</script>

<template>
    <ChatLayout>
        <ChatList v-if="isHandheld" />
    </ChatLayout>
</template>