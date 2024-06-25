<script setup>
import { useDates } from '@/Composables/useDates'
import {computed, inject, ref} from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {faCircleCheck, faClock, faEye, faReply} from '@fortawesome/free-solid-svg-icons'
import MessageAttachments from '../Chat/MessageAttachments.vue'
import IconButton from "@/Components/Generic/IconButton.vue";

const props = defineProps({
    message: Object
})

const hovering = ref(false)
const replyToMessage = inject('replyToMessage')

const onReply = () => replyToMessage.value = props.message

const { formatTimestamp24Hour } = useDates()
const timestamp = computed(() => formatTimestamp24Hour(new Date(props.message.sent_at)))

const statusIcon = computed(() => {
    let result = faClock

    switch (props.message.status) {
        case 'sent':
            result = faCircleCheck
            break
        case 'seen':
            result = faEye
            break
    }

    return result
})
</script>

<template>
    <div class="flex flex-row-reverse py-1" @mouseover="hovering = true" @mouseleave="hovering = false">
        <div :class="[message.files?.length > 0 ? 'w-1/3' : 'w-3/5']">
            <div class="flex flex-row-reverse">
				<div class="bg-primary/30 rounded-lg p-1 rounded-br-none">
                    <div v-if="message.reply_to" class="border-l-4 border-secondary-text text-sm text-secondary-text pl-1 m-1">
                        <p class="font-medium">{{ message.reply_to.sent_by }}</p>
                        <p>{{ message.reply_to.content }}</p>
                    </div>

                    <MessageAttachments v-if="message.files?.length > 0" :files="message.files" :message-uuid="message.uuid" />

					<div class="flex gap-1 justify-between">
                        <div class="p-1">
                            {{ message.content }}
                        </div>

                        <div class="text-xs text-secondary-text flex flex-col justify-end">
                            <span class="flex items-center gap-1">
                                {{ timestamp }}
                                <FontAwesomeIcon :icon="statusIcon" size="xs" class="mt-0.5" />
                            </span>
                        </div>
                    </div>
				</div>

                <div class="flex items-center justify-end w-44 pr-2">
                    <IconButton
                        v-if="hovering"
                        :on-click="onReply"
                        :icon="faReply"
                        size="sm"
                        variant="subtle"
                        color="base"
                    />
                </div>
			</div>
        </div>
    </div>
</template>
