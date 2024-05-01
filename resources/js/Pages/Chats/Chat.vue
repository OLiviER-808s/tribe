<script setup>
import { provide, ref, nextTick, onMounted, onUnmounted, watch } from 'vue'
import ChatLayout from '../../Layouts/ChatLayout.vue'
import MessageInput from '@/Components/InputFields/MessageInput.vue'
import ChatHeader from '@/Components/Display/ChatHeader.vue'
import { usePage } from '@inertiajs/vue3'
import ProfileCard from '@/Components/Profile/ProfileCard.vue'
import IconButton from '@/Components/Generic/IconButton.vue'
import { faXmark } from '@fortawesome/free-solid-svg-icons'
import ChatCard from '@/Components/Display/ChatCard.vue'
import MessageFeed from '@/Components/Chat/MessageFeed.vue'
import AttachmentUploadView from '@/Components/Chat/AttachmentUploadView.vue'
import AttachmentInspectView from '@/Components/Chat/AttachmentInspectView.vue'

const props = defineProps({
    chats: Array,
    chat: Object,
    messages: Array,
    actions: Array
})

const page = usePage()

const getInitialFeedItems = () => {
    const combined = [...props.messages.data, ...props.actions]
    return combined.sort((a, b) => new Date(b.sent_at) - new Date(a.sent_at))
}

const scrollToBottom = () => {
    if (feedContainer.value && (feedContainer.value.scrollHeight - feedContainer.value.clientHeight <= feedContainer.value.scrollTop + 50)) {
        nextTick(() => {
            feedContainer.value.scrollTop = feedContainer.value.scrollHeight
        })
    }
}

const feedContainer = ref(null)
const feedItems = ref(getInitialFeedItems())
const activeMembers = ref([])

const inspectInfo = ref(null)
const mainContent = ref(null)

provide('chats', props.chats)
provide('inspectInfo', inspectInfo)
provide('mainContent', mainContent)

window.Echo.join(`chat.${props.chat.uuid}`)
    .here((members) => {
        activeMembers.value = members
    })
    .joining((member) => {
        activeMembers.value.push(member)
    })
    .leaving((member) => {
        const idx = activeMembers.value.findIndex(({ uuid }) => uuid === member.uuid)
        activeMembers.value.splice(idx, 1)
    })
    .listen('.message-sent', (message) => {
        if (message.sent_by.uuid === page.props.profile.uuid) {
            const idx = feedItems.value.findIndex(item => item.uuid === message.uuid)
            feedItems.value[idx].status = 'sent'
        } else {
            feedItems.value.unshift(message)
            scrollToBottom()
        }
    })
    .listen('.user-typing', ({ member_uuid, typing }) => {
        const idx = activeMembers.value.findIndex(({ uuid }) => uuid === member_uuid)
        activeMembers.value[idx].typing = typing
    })
    // .listen('.action-executed', (action) => {
    //     feedItems.value.unshift(action)
    // })

watch(feedContainer, () => {
    if (feedContainer.value) {
        feedContainer.value.scrollTop = feedContainer.value.scrollHeight
    }
}, { immediate: true })

onUnmounted(() => window.Echo.leave(`presence-chat.${props.chat.uuid}`))
</script>

<template>
    <ChatLayout>
        <div class="h-full max-w-full flex flex-col">
            <ChatHeader v-if="!mainContent?.type" :chat="chat" :active-members="activeMembers" />

            <div v-if="mainContent?.type === 'attachment-inspect'" class="flex-grow h-full px-2">
                <AttachmentInspectView :files="messages.data.flatMap(message => message.files.map(file => (({ ...file, message_uuid: message.uuid, status: message.status }))))" :default-selected-file-uuid="mainContent.data.fileUuid" />
            </div>
            <div v-else-if="mainContent?.type === 'attachment-upload'" class="flex-grow h-full px-2">
                <AttachmentUploadView />
            </div>
            <div v-else class="flex-grow h-full overflow-auto px-2 mt-2" ref="feedContainer">
                <MessageFeed :feed-items="feedItems" />
            </div>

            <MessageInput v-if="mainContent?.type !== 'attachment-inspect'" v-model="feedItems" :chat="chat" :active-members="activeMembers" :on-send="scrollToBottom" />
        </div>

        <template #right-sidebar>
            <ProfileCard v-if="inspectInfo?.type === 'profile'" styles="h-full w-80" :with-inerests="true" :profile="inspectInfo.data">
                <template #before>
                    <div class="flex justify-end">
                        <IconButton :icon="faXmark" variant="light" color="base" :on-click="() => inspectInfo = null" />
                    </div>
                </template>
            </ProfileCard>
            
            <ChatCard v-else-if="inspectInfo?.type === 'chat'" styles="h-full w-80" :chat="chat">
                <template #before>
                    <div class="flex justify-end">
                        <IconButton :icon="faXmark" variant="light" color="base" :on-click="() => inspectInfo = null" />
                    </div>
                </template>
            </ChatCard>
        </template>
    </ChatLayout>
</template>