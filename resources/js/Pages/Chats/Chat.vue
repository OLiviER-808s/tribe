<script setup>
import { provide, ref, nextTick, onMounted } from 'vue'
import ChatLayout from '../../Layouts/ChatLayout.vue'
import MessageInput from '@/Components/InputFields/MessageInput.vue'
import ChatHeader from '@/Components/Display/ChatHeader.vue'
import SentToMessage from '@/Components/Display/SentToMessage.vue'
import SentFromMessage from '@/Components/Display/SentFromMessage.vue'
import Timestamp from '@/Components/Display/Timestamp.vue'
import { useDates } from '@/Composables/useDates'
import { usePage } from '@inertiajs/vue3'

const { chats, chat, messages, actions } = defineProps({
    chats: Array,
    chat: Object,
    messages: Array,
    actions: Array
})

const { differentDay } = useDates()
const page = usePage()

const showTimestamp = (item1, item2) => {
    return item2 ? differentDay(new Date(item1.sent_at), new Date(item2.sent_at)) : false
}

const getInitialFeedItems = () => {
    const combined = [...messages, ...actions]
    return combined.sort((a, b) => new Date(b.sent_at) - new Date(a.sent_at))
}

const scrollToBottom = () => {
    if (feedContainer.value.scrollHeight - feedContainer.value.clientHeight <= feedContainer.value.scrollTop + 50) {
        nextTick(() => {
            feedContainer.value.scrollTop = feedContainer.value.scrollHeight
        })
    }
}

const feedContainer = ref(null)
const feedItems = ref(getInitialFeedItems())

provide('chats', chats)
provide('chat', chat)
provide('feedItems', feedItems)
provide('scrollToBottom', scrollToBottom)

window.Echo.join(`chat.${chat.uuid}`).listen('.message-sent', (message) => {
    if (message.sent_by.uuid === page.props.profile.uuid) {
        const idx = feedItems.value.findIndex(item => item.uuid === message.uuid)
        feedItems.value[idx].status = 'sent'
    } else {
        feedItems.value.unshift(message)
        scrollToBottom()
    }
}).listen('.action-executed', (action) => {
    feedItems.value.unshift(action)
})

onMounted(() => feedContainer.value.scrollTop = feedContainer.value.scrollHeight)
</script>

<template>
    <ChatLayout>
        <div class="h-full max-w-full flex flex-col">
            <ChatHeader />

            <div class="flex-grow h-full overflow-auto px-2 mt-2" ref="feedContainer">
                <div class="flex flex-col-reverse">
                    <div v-for="(item, idx) in feedItems" :key="item.uuid">
                        <Timestamp :show="showTimestamp(item, feedItems[idx + 1])" :timestamp="item.sent_at" />
                        <p v-if="item.text" class="py-2 text-center text-sm text-secondary-text font-medium">{{ item.text }}</p>
                        <SentToMessage v-else-if="item.sent_by.uuid === $page.props.profile.uuid" :message="item" />
                        <SentFromMessage v-else :message="item" :show-user-info="true" />
                    </div>
                </div>
            </div>

            <MessageInput />
        </div>
    </ChatLayout>
</template>