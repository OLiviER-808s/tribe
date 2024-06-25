<script setup>
import { useDates } from '@/Composables/useDates'
import Avatar from '../Generic/Avatar.vue'
import {computed, inject, ref} from 'vue'
import MessageAttachments from '../Chat/MessageAttachments.vue';
import IconButton from "@/Components/Generic/IconButton.vue";
import {faReply, faXmark} from "@fortawesome/free-solid-svg-icons";

const props = defineProps({
    message: Object,
    showUserInfo: {
        type: Boolean,
        default: false
    }
})

const hovering = ref(false)

const replyToMessage = inject('replyToMessage')
const inspectInfo = inject('inspectInfo')

const onReply = () => replyToMessage.value = props.message

const onInspect = () => inspectInfo.value = { type: 'profile', data: props.message.sent_by }

const { formatTimestamp24Hour } = useDates()
const timestamp = computed(() => formatTimestamp24Hour(new Date(props.message.sent_at)))
</script>

<template>
    <div class="flex py-1" @mouseover="hovering = true" @mouseleave="hovering = false">
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
                    <div v-if="message.reply_to" class="border-l-4 border-primary text-sm text-secondary-text pl-1 m-1">
                        <p class="font-medium text-primary">{{ message.reply_to.sent_by }}</p>
                        <p>{{ message.reply_to.content }}</p>
                    </div>

                    <MessageAttachments v-if="message.files?.length > 0" :files="message.files" :message-uuid="message.uuid" />

                    <div class="flex gap-1 justify-between">
                        <div class="p-1">
                            {{ message.content }}
                        </div>

                        <div class="text-xs text-secondary-text flex flex-col justify-end">
                            {{ timestamp }}
                        </div>
                    </div>
				</div>

                <div class="flex items-center">
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
