<script setup>
import { faArrowLeft, faEllipsisVertical, faPhone, faVideoCamera } from '@fortawesome/free-solid-svg-icons';
import IconButton from '../Generic/IconButton.vue'
import { computed, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Badge from '../Generic/Badge.vue';

const props = defineProps({
	chat: Object,
	activeMembers: Array
})

const page = usePage()

const authMember = computed(() => props.chat.members.find(member => member.user_uuid === page.props.profile.uuid))
const otherMembers = computed(() => props.activeMembers.filter(member => member.uuid !== authMember.value.uuid))
const typingMembers = ref([])

watch(props.activeMembers, () => {
	typingMembers.value = otherMembers.value.filter(member => member.typing)
}, { deep: true })
</script>

<template>
    <div class="p-2 flex items-center gap-2">
		<div class="flex items-center">
			<IconButton variant="subtle" color="base" :icon="faArrowLeft" size="lg" href="/chats" />
		</div>
		
		<div class="font-medium">
			<h2>{{ chat.name }}</h2>

			<div class="text-xs font-medium text-secondary-text flex items-center gap-1" v-if="otherMembers.length > 0">
				<Badge color="success" styles="mt-0.5" />
				<p v-if="typingMembers.length > 0">
					{{ typingMembers[0].name }} 
					<span v-if="typingMembers.length > 1">+ {{ typingMembers.length - 1 }} are typing...</span>
					<span v-else> is typing...</span>
				</p>
				<p v-else>
					{{ otherMembers[0].name }} 
					<span v-if="otherMembers.length > 1">+ {{ otherMembers.length - 1 }} are online</span>
					<span v-else> is online</span>
				</p>
			</div>
		</div>

		<div class="flex-grow"></div>

		<IconButton variant="subtle" color="base" :icon="faVideoCamera" size="lg" />
		<IconButton variant="subtle" color="base" :icon="faPhone" size="lg" />
		<IconButton variant="subtle" color="base" :icon="faEllipsisVertical" size="lg" />
	</div>
</template>