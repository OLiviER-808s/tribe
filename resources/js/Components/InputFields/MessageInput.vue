<script setup>
import Textarea from '../Generic/Textarea.vue'
import IconButton from '../Generic/IconButton.vue'
import {ref, inject, watch} from 'vue'
import {faPaperPlane, faPaperclip, faReply, faXmark} from '@fortawesome/free-solid-svg-icons'
import { useForm, usePage } from '@inertiajs/vue3'
import { v4 as uuidv4 } from 'uuid'
import { useFiles } from '@/Composables/useFiles'
import axios from 'axios'
import {watchDebounced} from "@vueuse/core"
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps({
	chat: Object,
	activeMembers: Array,
	onSend: Function,
	modelValue: Array
})
const emit = defineEmits(['update:modelValue'])

const { formatFiles } = useFiles()
const page = usePage()

const content = ref('')
const fileInput = ref(null)

const typing = ref(false)

const mainContent = inject('mainContent')
const replyToMessage = inject('replyToMessage')

const send = () => {
	if (content.value) {
		const message = content.value
        const replyUuid = replyToMessage.value?.uuid
		const files = mainContent.value?.data?.files ?? []
		const uuid = uuidv4()
		const active_uuids = props.activeMembers.map(user => user.uuid)

		content.value = ''
		mainContent.value = null
        replyToMessage.value = null

		emit('update:modelValue', [
			{
				uuid: uuid,
				content: message,
				status: 'sending',
                type: 'message',
				sent_by: page.props.profile,
				sent_at: new Date().toISOString(),
				files: formatFiles(files)
			},
			...props.modelValue
		])

		props.onSend()

		axios.post(route('chat.send-message', { uuid: props.chat.uuid }), {
            content: message,
            reply_to_uuid: replyUuid,
            files,
            uuid,
            active_uuids
        })
	}
}

const handleKeyPress = (event) => {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault()
        send()
    }
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

const closeMessageReply = () => replyToMessage.value = null

watch(content, () => {
    if (!typing.value) {
        typing.value = true
        axios.post(route('chat.typing', { uuid: props.chat.uuid }), { typing: true })
    }
})

watchDebounced(content, () => {
    typing.value = false
    axios.post(route('chat.typing', { uuid: props.chat.uuid }), { typing: false })
}, { debounce: 1000 })
</script>

<template>
    <div class="p-2 bg-inherit w-full">
		<input name="message-attachments" @change="handleAttachmentUpload" ref="fileInput" type="file" multiple hidden />

        <div v-if="replyToMessage" class="flex items-center gap-2 text-secondary-text pb-2">
            <FontAwesomeIcon :icon="faReply" size="lg" />

            <div class="text-sm">
                <p class="font-medium">{{ replyToMessage.sent_by.name }}</p>
                <p>{{ replyToMessage.content }}</p>
            </div>

            <div class="flex-grow"></div>

            <IconButton
                :icon="faXmark"
                :on-click="closeMessageReply"
                variant="subtle"
                color="base"
            />
        </div>

		<form @submit.prevent="send()" class="flex gap-2">
			<div class="w-full">
				<Textarea
                    variant="outline"
                    placeholder="Message"
                    field-sizing-content
                    :rows="1"
                    :on-key-press="handleKeyPress"
                    v-model="content"
                />
			</div>

            <IconButton :icon="faPaperclip" size="lg" color="base" variant="subtle" @click="handleAttachmentClick" />
			<IconButton :icon="faPaperPlane" size="lg" type="submit" />
		</form>
	</div>
</template>
