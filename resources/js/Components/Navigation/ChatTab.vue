<script setup>
import { computed } from 'vue'
import AvatarGroup from '../Generic/AvatarGroup.vue'
import { useDates } from '@/Composables/useDates'
import Badge from '../Generic/Badge.vue';
import Avatar from '../Generic/Avatar.vue';

const { chat } = defineProps({
    chat: Object
})

const { formatTimestamp24Hour, formatDate, differentDay } = useDates()

const avatars = computed(() => chat.members.map(member => member.photo).slice(0,3))

const timestamp = computed(() => {
	if (!chat.latestMessage) return ''

	const messageSentAt = new Date(chat.latestMessage.sent_at)
	const now = new Date()

	return differentDay(messageSentAt, now) ? formatDate(messageSentAt) : formatTimestamp24Hour(messageSentAt)
})
</script>

<template>
    <a :href="route('chat.show', { uuid: chat.uuid })" class="w-full rounded-lg hover:bg-base p-2 text-left flex items-center gap-2">
		<Avatar v-if="chat.photo" :src="chat.photo" styles="w-8 h-8" />
		<AvatarGroup v-else :avatars="avatars" width="w-8" />

		<div class="flex-1 overflow-hidden">
			<div class="flex justify-between items-center gap-2">
				<h4 class="whitespace-nowrap flex-1 overflow-hidden w-4/5" :class="{ 'font-semibold': chat.unreadMessages > 0 }">
					{{ chat.name }}
				</h4>

				<p class="text-xs text-secondary-text">{{ timestamp }}</p>
			</div>

			<div v-if="chat.latestMessage" class="flex items-center justify-between gap-1">
				<p class="text-xs whitespace-nowrap w-4/5 overflow-hidden" :class="{ 'font-semibold': chat.unreadMessages > 0 }">
					{{ chat.latestMessage.content }}
				</p>

				<Badge v-if="chat.unreadMessages > 0" :content="chat.unreadMessages" />
			</div>
		</div>
	</a>
</template>