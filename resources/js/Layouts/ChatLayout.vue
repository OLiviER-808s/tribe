<script setup>
import Card from '../Components/Generic/Card.vue'
import AuthLayout from './AuthLayout.vue'
import ChatList from '../Components/Navigation/ChatList.vue'

const { chats } = defineProps({
    chats: Array
})
</script>

<template>
    <AuthLayout title="Chats">
        <template #additional-sidebar>
            <Card styles="p-0 w-72 h-full">
                <ChatList :chats="chats" />
            </Card>
        </template>

        <template>
            <slot />
        </template>
    </AuthLayout>
</template>