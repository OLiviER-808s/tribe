<script setup>
import { faArrowLeft, faPhone, faVideoCamera } from '@fortawesome/free-solid-svg-icons'
import IconButton from '../Generic/IconButton.vue'
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Badge from '../Generic/Badge.vue'
import ChatOptions from '@/Components/Dropdowns/ChatOptions.vue'
import { useIsHandheld } from '@/Composables/useIsHandheld'

const props = defineProps({
	chat: Object,
	activeMembers: Array
})

const page = usePage()
const { isHandheld } = useIsHandheld()

const otherMembers = ref([])
const typingMembers = ref([])

watch(() => props.activeMembers, (newMembers, oldMembers) => {
    const authMember = props.chat.members.find(member => member.user_uuid === page.props.profile.uuid);
    
    otherMembers.value = newMembers.filter(member => member.uuid !== authMember.uuid);
    typingMembers.value = otherMembers.value.filter(member => member.typing);
}, { deep: true, immediate: true })
</script>

<template>
    <div class="p-2 flex items-center gap-2">
		<div class="flex items-center" v-if="isHandheld">
			<IconButton variant="subtle" color="base" :icon="faArrowLeft" size="lg" :href="route('chats')" />
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
		<ChatOptions :chat="chat" />
	</div>
</template>