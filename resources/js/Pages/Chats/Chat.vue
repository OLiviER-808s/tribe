<script setup>
import { provide } from 'vue';
import ChatLayout from '../../Layouts/ChatLayout.vue'
import MessageInput from '@/Components/InputFields/MessageInput.vue'
import ChatHeader from '@/Components/Display/ChatHeader.vue'
import SentToMessage from '@/Components/Display/SentToMessage.vue'
import SentFromMessage from '@/Components/Display/SentFromMessage.vue'
import { ref } from 'vue'
import Timestamp from '@/Components/Display/Timestamp.vue';
import { useDates } from '@/Composables/useDates';

const { chats, chat, messages, actions } = defineProps({
    chats: Array,
    chat: Object,
    messages: Array,
    actions: Array
})

const { differentDay } = useDates()

const showTimestamp = (item1, item2) => {
    return item2 ? differentDay(new Date(item1.sent_at), new Date(item2.sent_at)) : false
}

const getInitialFeedItems = () => {
    const combined = [...messages, ...actions]
    return combined.sort((a, b) => new Date(b.sent_at) - new Date(a.sent_at))
}

const feedItems = ref(getInitialFeedItems())

provide('chats', chats)
provide('chat', chat)
provide('feedItems', feedItems)

window.Echo.join(`chat.${chat.uuid}`).listen('.message-sent', (message) => {
    console.log('test')
    feedItems.value.unshift(message)
})
</script>

<template>
    <ChatLayout>
        <div class="h-full max-w-full flex flex-col">
            <ChatHeader />

            <div class="flex-grow h-full overflow-auto px-2 mt-2">
                <div class="flex flex-col-reverse">
                    <div v-for="(item, idx) in feedItems" :key="item.uuid">
                        <Timestamp :show="showTimestamp(item, feedItems[idx + 1])" :timestamp="item.sent_at" />
                        <p v-if="item.text" class="py-2 text-center text-sm text-secondary-text font-medium">{{ item.text }}</p>
                        <SentToMessage v-else-if="item.sent_by.uuid === $page.props.auth.user.uuid" :message="item" />
                        <SentFromMessage v-else :message="item" :show-user-info="true" />
                    </div>
                </div>
            </div>

            <MessageInput />
        </div>
    </ChatLayout>
</template>