<script setup>
import { useDates } from '@/Composables/useDates'
import Timestamp from '../Display/Timestamp.vue';
import ChatAction from '../Display/ChatAction.vue';
import SentToMessage from '../Display/SentToMessage.vue';
import SentFromMessage from '../Display/SentFromMessage.vue';

const props = defineProps({
    feedItems: Array
})

const { differentDay } = useDates()

const showTimestamp = (item1, item2) => {
    return item2 ? differentDay(new Date(item1.sent_at), new Date(item2.sent_at)) : false
}
</script>

<template>
    <div class="flex flex-col-reverse">
        <div v-for="(item, idx) in feedItems" :key="item.uuid">
            <Timestamp :show="showTimestamp(item, feedItems[idx + 1])" :timestamp="item.sent_at" />

            <ChatAction v-if="item.text" :action="item" />

            <SentToMessage v-else-if="item.sent_by.uuid === $page.props.profile.uuid" :message="item" />

            <SentFromMessage v-else :message="item" :show-user-info="idx < feedItems.length - 1 ? feedItems[idx + 1].sent_by?.uuid !== item.sent_by?.uuid : false" />
        </div>
    </div>
</template>