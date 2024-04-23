<script setup>
import Textbox from '../Generic/Textbox.vue'
import IconButton from '../Generic/IconButton.vue'
import { ref } from 'vue'
import { faPaperPlane, faPaperclip } from '@fortawesome/free-solid-svg-icons'
import { router, usePage } from '@inertiajs/vue3'
import { inject } from 'vue'
import { v4 as uuidv4 } from 'uuid'
import MessageAttachmentSelect from '../Chat/MessageAttachmentSelect.vue'

const { chat, activeMembers } = defineProps({
	chat: Object,
	activeMembers: Array,
})

const attachmentDrawOpen = ref(false)

const content = ref('')
const typing = ref(false)

const feedItems= inject('feedItems')
const scrollToBottom = inject('scrollToBottom')
const page = usePage()

let debounceTimer = null

const send = async () => {
	if (content.value) {
		const message = content.value
		const uuid = uuidv4()
		const active_uuids = activeMembers.map(user => user.uuid)

		content.value = ''

		feedItems.value.unshift({
			uuid: uuid,
			content: message,
			status: 'sending',
			sent_by: page.props.profile,
			sent_at: new Date().toISOString()
		})

		scrollToBottom()

		router.post(route('chat.send-message', { uuid: chat.uuid }), 
		{
			content: message,
			uuid,
			active_uuids
		}, {
			preserveScroll: true,
			preserveState: true,
		})
	}
}

const startTyping = () => {
	clearTimeout(debounceTimer)

	if (!typing.value) {
		typing.value = true
		router.post(route('chat.typing', { uuid: chat.uuid }), { typing: true })
	}

	debounceTimer = setTimeout(() => {
		typing.value = false
		router.post(route('chat.typing', { uuid: chat.uuid }), { typing: false })
	}, 1000)
}
</script>

<template>
    <div class="p-2 bg-inherit w-full">
		<MessageAttachmentSelect :show="attachmentDrawOpen" />

		<form @submit.prevent="send()" class="flex items-center gap-2">
			<div class="flex-grow">
				<Textbox variant="outline" placeholder="Message" v-model="content" :on-input="startTyping">
					<template #right-section>
						<div class="mr-1 flex items-center justify-center">
							<IconButton :icon="faPaperclip" color="base" variant="subtle" @click="attachmentDrawOpen = !attachmentDrawOpen" />
						</div>
					</template>
				</Textbox>
			</div>

			<IconButton :icon="faPaperPlane" size="lg" type="submit" />
		</form>
	</div>
</template>