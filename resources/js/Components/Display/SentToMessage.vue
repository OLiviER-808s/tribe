<script setup>
import { useDates } from '@/Composables/useDates'
import { computed } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCircleCheck, faClock, faEye } from '@fortawesome/free-solid-svg-icons';

const { message } = defineProps({
    message: Object
})

const { formatTimestamp24Hour } = useDates()
const timestamp = computed(() => formatTimestamp24Hour(new Date(message.sent_at)))

const statusIcon = computed(() => {
    console.log(message.status)
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
        <div class="max-w-md w-auto">
            <div class="flex flex-row-reverse">
				<div class="bg-primary/30 rounded-lg p-1 rounded-br-none flex gap-1">
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
</template>