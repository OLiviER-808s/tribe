<script setup>
import { faArrowLeft, faEllipsisVertical, faPhone, faVideoCamera } from '@fortawesome/free-solid-svg-icons';
import IconButton from '../Generic/IconButton.vue'

const { chat } = defineProps({
	chat: Object
})
</script>

<template>
    <div class="p-2 flex items-center gap-2">
		<div class="flex items-center">
			<IconButton variant="subtle" color="base" :icon="faArrowLeft" size="lg" href="/chats" />
		</div>
		<h2>{{ chat.name }}</h2>

		<div class="flex-grow"></div>

		<IconButton variant="subtle" color="base" :icon="faVideoCamera" size="lg" />
		<IconButton variant="subtle" color="base" :icon="faPhone" size="lg" />
		<IconButton variant="subtle" color="base" :icon="faEllipsisVertical" size="lg" />
	</div>
</template>