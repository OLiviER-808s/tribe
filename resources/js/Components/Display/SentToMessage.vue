<script setup>
import { useDates } from '@/Composables/useDates'
import { computed } from 'vue'

const { message } = defineProps({
    message: Object
})

const { formatTimestamp24Hour } = useDates()
const timestamp = computed(() => formatTimestamp24Hour(new Date(message.sent_at)))
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
                        {{ timestamp }}
                    </div>
				</div>
			</div>
        </div>
    </div>
</template>