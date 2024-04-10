<script setup>
import { computed } from 'vue'
import AvatarGroup from '../Generic/AvatarGroup.vue'

const { chat } = defineProps({
    chat: Object
})

const avatars = computed(() => chat.members.map(member => member.photo).slice(0,3))
</script>

<template>
    <a :href="route('chat.show', { uuid: chat.uuid })" class="block w-full rounded-lg hover:bg-base p-2 text-left flex items-center gap-2">
		<AvatarGroup :avatars="avatars" width="w-8" />

		<div class="flex-1 overflow-hidden">
			<div class="flex justify-between items-center gap-2">
				<h4 class="whitespace-nowrap flex-1 overflow-hidden w-4/5">
					{{ chat.name }}
				</h4>
			</div>
		</div>
	</a>
</template>