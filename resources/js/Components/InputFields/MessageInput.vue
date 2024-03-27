<script setup>
import Textbox from '../Generic/Textbox.vue'
import IconButton from '../Generic/IconButton.vue'
import { ref } from 'vue';
import { faPaperPlane } from '@fortawesome/free-solid-svg-icons';
import { router } from '@inertiajs/vue3';
import { inject } from 'vue';

const content = ref('')
const chat = inject('chat')

const send = () => {
	if (content.value) {
		router.post(route('chat.send-message', { uuid: chat.uuid }), { content: content.value }, {
			preserveScroll: true,
			preserveState: true,
		})
		content.value = ''
	}
}
</script>

<template>
    <div class="flex items-center gap-2 w-full p-2 bg-inherit">
		<div class="flex-grow">
			<Textbox variant="outline" placeholder="Message" v-model="content" />
		</div>

		<IconButton :icon="faPaperPlane" size="lg" :on-click="send" />
	</div>
</template>