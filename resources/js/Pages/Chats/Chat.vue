<script setup>
import { provide } from 'vue';
import ChatLayout from '../../Layouts/ChatLayout.vue'
import MessageInput from '@/Components/InputFields/MessageInput.vue'
import ChatHeader from '@/Components/Display/ChatHeader.vue'
import SentToMessage from '@/Components/Display/SentToMessage.vue'
import SentFromMessage from '@/Components/Display/SentFromMessage.vue'
import { ref } from 'vue'

const { chats, chat, messages } = defineProps({
    chats: Array,
    chat: Object,
    messages: Array
})

const feedItems = ref(messages)

provide('chats', chats)
provide('chat', chat)

window.Echo.join(`chat.${chat.uuid}`).listen('.message-sent', (message) => {
    feedItems.value.unshift(message)
})
</script>

<template>
    <ChatLayout>
        <div class="h-full max-w-full flex flex-col">
            <ChatHeader />

            <div class="flex-grow h-full overflow-auto px-2 mt-2">
                <div class="flex flex-col-reverse">
                    <div v-for="item in feedItems">
                        <SentToMessage v-if="item.sent_by.uuid === $page.props.auth.user.uuid" :message="item" />
                        <SentFromMessage v-else :message="item" :show-user-info="true" />
                    </div>
                </div>
            </div>

            <MessageInput />
        </div>
    </ChatLayout>
</template>