<script setup>
import Textbox from '../Generic/Textbox.vue'
import IconButton from '../Generic/IconButton.vue'
import { ref } from 'vue'
import { faPaperPlane } from '@fortawesome/free-solid-svg-icons'
import { router, usePage } from '@inertiajs/vue3'
import { inject } from 'vue'
import { v4 as uuidv4 } from 'uuid'

const { chat, activeUsers } = defineProps({
	chat: Object,
	activeUsers: Array
})

const content = ref('')

const feedItems= inject('feedItems')
const scrollToBottom = inject('scrollToBottom')
const page = usePage()

const send = async () => {
	if (content.value) {
		const message = content.value
		const uuid = uuidv4()

		content.value = ''

		feedItems.value.unshift({
			uuid: uuid,
			content: message,
			status: 'sending',
			sent_by: page.props.profile,
			sent_at: new Date().toISOString()
		})

		scrollToBottom()

		router.post(route('chat.send-message', { uuid: chat.uuid }), { content: message, uuid: uuid }, {
			preserveScroll: true,
			preserveState: true,
			headers: {
				"X-Socket-Id": window.Echo.socketId(),
			}
		})
	}
}
</script>

<template>
    <form @submit.prevent="send()" class="flex items-center gap-2 w-full p-2 bg-inherit">
		<div class="flex-grow">
			<Textbox variant="outline" placeholder="Message" v-model="content" />
		</div>

		<IconButton :icon="faPaperPlane" size="lg" type="submit" />
	</form>
</template>