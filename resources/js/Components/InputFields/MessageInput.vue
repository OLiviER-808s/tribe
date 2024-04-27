<script setup>
import Textbox from '../Generic/Textbox.vue'
import IconButton from '../Generic/IconButton.vue'
import { ref, inject } from 'vue'
import { faPaperPlane, faPaperclip } from '@fortawesome/free-solid-svg-icons'
import { router, usePage } from '@inertiajs/vue3'
import { v4 as uuidv4 } from 'uuid'

const props = defineProps({
	chat: Object,
	activeMembers: Array,
	onSend: Function,
	modelValue: Array
})
const emit = defineEmits(['update:modelValue'])

const page = usePage()

const content = ref('')
const fileInput = ref(null)

const typing = ref(false)

const mainContent = inject('mainContent')

let debounceTimer = null

const send = async () => {
	if (content.value) {
		const message = content.value
		const uuid = uuidv4()
		const active_uuids = props.activeMembers.map(user => user.uuid)

		content.value = ''

		emit('update:modelValue', [
			{
				uuid: uuid,
				content: message,
				status: 'sending',
				sent_by: page.props.profile,
				sent_at: new Date().toISOString()
			},
			...props.modelValue
		])

		props.onSend()

		router.post(route('chat.send-message', { uuid: props.chat.uuid }), 
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
		router.post(route('chat.typing', { uuid: props.chat.uuid }), { typing: true })
	}

	debounceTimer = setTimeout(() => {
		typing.value = false
		router.post(route('chat.typing', { uuid: props.chat.uuid }), { typing: false })
	}, 1000)
}

const handleAttachmentUpload = (event) => {
	const files = Array.from(event.target.files)

	if (files.length > 0) {
		mainContent.value = { type: 'attachment-upload', data: { files: files } }
	}
}

const handleAttachmentClick = () => {
	fileInput.value.value = ''
    fileInput.value.click()
}
</script>

<template>
    <div class="p-2 bg-inherit w-full">
		<input name="message-attchments" @change="handleAttachmentUpload" ref="fileInput" type="file" multiple hidden />

		<form @submit.prevent="send()" class="flex items-center gap-2">
			<div class="flex-grow">
				<Textbox variant="outline" placeholder="Message" v-model="content" :on-input="startTyping">
					<template #right-section>
						<div class="mr-1 flex items-center justify-center">
							<IconButton :icon="faPaperclip" color="base" variant="subtle" @click="handleAttachmentClick" />
						</div>
					</template>
				</Textbox>
			</div>

			<IconButton :icon="faPaperPlane" size="lg" type="submit" />
		</form>
	</div>
</template>