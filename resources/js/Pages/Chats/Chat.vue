<script setup>
import { provide, ref, nextTick, onUnmounted, watch, computed } from 'vue'
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
import { useInfiniteScroll } from '@vueuse/core'

const props = defineProps({
    chats: Array,
    chat: Object,
    messages: Object,
})

const page = usePage()

const scrollToBottom = () => {
    if (feedContainer.value && (feedContainer.value.scrollHeight - feedContainer.value.clientHeight <= feedContainer.value.scrollTop + 50)) {
        nextTick(() => {
            feedContainer.value.scrollTop = feedContainer.value.scrollHeight
        })
    }
}

const messagesState = ref(props.messages.data)
const loading = ref(false)

const feedContainer = ref(null)
const activeMembers = ref([])

const inspectInfo = ref(null)
const mainContent = ref(null)

const allFiles = computed(() => messagesState.value.flatMap(message => message.files.map(file => (({ ...file, message_uuid: message.uuid, status: message.status })))))

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
            const idx = messagesState.value.findIndex(item => item.uuid === message.uuid)
            messagesState.value[idx].status = 'sent'
        } else {
            messagesState.value.unshift(message)
            scrollToBottom()
        }
    })
    .listen('.user-typing', ({ member_uuid, typing }) => {
        const idx = activeMembers.value.findIndex(({ uuid }) => uuid === member_uuid)
        activeMembers.value[idx].typing = typing
    })

watch(feedContainer, () => {
    if (feedContainer.value) {
        feedContainer.value.scrollTop = feedContainer.value.scrollHeight
    }
}, { immediate: true })

useInfiniteScroll(feedContainer, async () => {
    if (loading.value || !props.messages.meta.next_cursor) return

    loading.value = true
    const response = await axios.get(`${props.messages.meta.path}?cursor=${props.messages.meta.next_cursor}`)

    messagesState.value = [...messagesState.value, ...response.data.data]
    props.messages.meta = response.data.meta
    loading.value = false
}, { distance: 200, direction: 'top' })

onUnmounted(() => window.Echo.leave(`presence-chat.${props.chat.uuid}`))
</script>

<template>
    <ChatLayout>
        <div class="h-full max-w-full flex flex-col">
            <ChatHeader v-if="!mainContent?.type" :chat="chat" :active-members="activeMembers" />

            <div v-if="mainContent?.type === 'attachment-inspect'" class="flex-grow h-full px-2">
                <AttachmentInspectView :files="allFiles" :default-selected-file-uuid="mainContent.data.fileUuid" />
            </div>
            <div v-else-if="mainContent?.type === 'attachment-upload'" class="flex-grow h-full px-2">
                <AttachmentUploadView />
            </div>
            <div v-else class="flex-grow h-full overflow-auto px-2 mt-2" ref="feedContainer">
                <MessageFeed :messages="messagesState" />
            </div>

            <MessageInput v-if="mainContent?.type !== 'attachment-inspect'" v-model="messagesState" :chat="chat" :active-members="activeMembers" :on-send="scrollToBottom" />
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