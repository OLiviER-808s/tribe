<script setup>
import Card from '../Components/Generic/Card.vue'
import AuthLayout from './AuthLayout.vue'
import ChatList from '../Components/Navigation/ChatList.vue'
import { provide } from 'vue';

provide('headerTitle', 'Chats')
</script>

<template>
    <AuthLayout>
        <template #additional-sidebar>
            <Card styles="w-72 h-full" padding="p-1">
                <ChatList />
            </Card>
        </template>

        <slot />
    </AuthLayout>
</template>