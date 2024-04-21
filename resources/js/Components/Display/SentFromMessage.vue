<script setup>
import { useDates } from '@/Composables/useDates'
import Avatar from '../Generic/Avatar.vue'
import { computed } from 'vue'

const { message, showUserInfo } = defineProps({
    message: Object,
    showUserInfo: {
        type: Boolean,
        default: false
    }
})
const emit = defineEmits(['selectUser'])

const onSelect = () => {
    emit('selectUser', { type: 'profile', data: message.sent_by })
}

const { formatTimestamp24Hour } = useDates()
const timestamp = computed(() => formatTimestamp24Hour(new Date(message.sent_at)))
</script>

<template>
    <div class="flex py-1">
        <div class="max-w-md w-auto">
            <p v-if="showUserInfo" @click="onSelect" class="text-sm mb-1 font-semibold flex gap-1 text-secondary-text cursor-pointer">
                {{ message.sent_by.name }}
            </p>

            <div class="flex gap-1 items-start">
                <button @click="onSelect" v-if="showUserInfo">
                    <Avatar :src="message.sent_by.photo" styles="w-8" />
                </button>
                <div v-else class="w-8"></div>

				<div class="bg-message rounded-lg p-1 flex gap-1" :class="{ 'rounded-tl-none': showUserInfo }">
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