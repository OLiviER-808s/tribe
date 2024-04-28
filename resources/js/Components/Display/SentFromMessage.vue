<script setup>
import { useDates } from '@/Composables/useDates'
import Avatar from '../Generic/Avatar.vue'
import { computed, inject } from 'vue'
import { useFiles } from '@/Composables/useFiles';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faFile } from '@fortawesome/free-solid-svg-icons';
import MessageAttachments from '../Chat/MessageAttachments.vue';

const { message, showUserInfo } = defineProps({
    message: Object,
    showUserInfo: {
        type: Boolean,
        default: false
    }
})

const inspectInfo = inject('inspectInfo')

const onInspect = () => inspectInfo.value = { type: 'profile', data: message.sent_by }

const { readableFileSize } = useFiles()
const { formatTimestamp24Hour } = useDates()

const timestamp = computed(() => formatTimestamp24Hour(new Date(message.sent_at)))
</script>

<template>
    <div class="flex py-1">
        <div :class="[message.files?.length > 0 ? 'w-1/3' : 'w-3/5']">
            <p v-if="showUserInfo" @click="onInspect" class="text-sm mb-1 font-semibold flex gap-1 text-secondary-text cursor-pointer">
                {{ message.sent_by.name }}
            </p>

            <div class="flex gap-1 items-start">
                <button @click="onInspect" v-if="showUserInfo">
                    <Avatar :src="message.sent_by.photo" styles="min-w-8 w-8" />
                </button>
                <div v-else class="min-w-8 w-8"></div>

				<div class="bg-message rounded-lg p-1" :class="{ 'rounded-tl-none': showUserInfo }">
                    <MessageAttachments :files="message.files" />

                    <div class="flex gap-1 justify-between">
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
    </div>
</template>