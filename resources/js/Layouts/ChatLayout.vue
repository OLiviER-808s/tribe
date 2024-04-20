<script setup>
import Card from '../Components/Generic/Card.vue'
import AuthLayout from './AuthLayout.vue'
import ChatList from '../Components/Navigation/ChatList.vue'
import { provide, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

provide('headerTitle', 'Chats')

const page = usePage()
const isIndexPage = computed(() => page.url === '/chats')
</script>

<template>
    <AuthLayout :show-mobile-footer="isIndexPage" :show-mobile-header="isIndexPage">
        <template #additional-sidebar>
            <Card styles="w-72 h-full" padding="p-1">
                <ChatList />
            </Card>
        </template>

        <slot />

        <template #right-sidebar>
            <slot name="right-sidebar" />
        </template>
    </AuthLayout>
</template>