<script setup>
import Card from '../Components/Generic/Card.vue'
import AuthLayout from './AuthLayout.vue'
import ChatList from '../Components/Navigation/ChatList.vue'
</script>

<template>
    <AuthLayout title="Chats">
        <template #additional-sidebar>
            <Card styles="w-72 h-full" padding="p-2">
                <ChatList />
            </Card>
        </template>

        <slot />
    </AuthLayout>
</template>