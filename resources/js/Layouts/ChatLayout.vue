<script setup>
import Card from '../Components/Generic/Card.vue'
import AuthLayout from './AuthLayout.vue'
import ChatList from '../Components/Navigation/ChatList.vue'
import { provide, computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const showChatSidebar = ref(true)

const page = usePage()
const isIndexPage = computed(() => page.url === '/chats')

provide('headerTitle', 'Chats')
provide('showChatSidebar', showChatSidebar)
</script>

<template>
    <AuthLayout :show-mobile-footer="isIndexPage" :show-mobile-header="isIndexPage">
        <template #additional-sidebar v-if="showChatSidebar">
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