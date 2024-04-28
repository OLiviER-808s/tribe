<script setup>
import { useDates } from '@/Composables/useDates'
import { computed } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCircleCheck, faClock, faEye } from '@fortawesome/free-solid-svg-icons'
import MessageAttachments from '../Chat/MessageAttachments.vue'
import { useFiles } from '@/Composables/useFiles'

const { message } = defineProps({
    message: Object
})

const { readableFileSize } = useFiles()
const { formatTimestamp24Hour } = useDates()

const timestamp = computed(() => formatTimestamp24Hour(new Date(message.sent_at)))

const statusIcon = computed(() => {
    let result = faClock

    switch (message.status) {
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
    <div class="flex flex-row-reverse py-1">
        <div :class="[message.files?.length > 0 ? 'w-1/3' : 'w-3/5']">
            <div class="flex flex-row-reverse">
				<div class="bg-primary/30 rounded-lg p-1 rounded-br-none">
                    <MessageAttachments :files="message.files" />

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
			</div>
        </div>
    </div>
</template>