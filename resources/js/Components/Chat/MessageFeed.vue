<script setup>
import { useDates } from '@/Composables/useDates'
import Timestamp from '../Display/Timestamp.vue';
import ChatAction from '../Display/ChatAction.vue';
import SentToMessage from '../Display/SentToMessage.vue';
import SentFromMessage from '../Display/SentFromMessage.vue';

const props = defineProps({
    messages: Array
})

const { differentDay } = useDates()

const showTimestamp = (message1, message2) => {
    return message2 ? differentDay(new Date(message1.sent_at), new Date(message2.sent_at)) : false
}
</script>

<template>
    <div class="flex flex-col-reverse">
        <div v-for="(message, idx) in messages" :key="message.uuid">
            <Timestamp :show="showTimestamp(message, messages[idx + 1])" :timestamp="message.sent_at" />

            <ChatAction v-if="message.type === 'action'" :action="message" />

            <SentToMessage v-else-if="message.sent_by.uuid === $page.props.profile.uuid" :message="message" />

            <SentFromMessage v-else :message="message" :show-user-info="idx < messages.length - 1 ? messages[idx + 1].sent_by?.uuid !== message.sent_by?.uuid : false" />
        </div>
    </div>
</template>