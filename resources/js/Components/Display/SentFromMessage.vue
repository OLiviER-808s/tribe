<script setup>
import Avatar from '../Generic/Avatar.vue';

const { message } = defineProps({
    message: Object,
    showUserInfo: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <div class="flex py-1">
        <div class="max-w-md w-auto">
            <p v-if="showUserInfo" class="text-sm mb-1 font-semibold flex gap-1 text-secondary-text cursor-pointer">{{ message.sent_by.name }}</p>

            <div class="flex gap-1 items-start">
                <div class="cursor-pointer">
                    <Avatar :src="message.sent_by.photo" styles="w-8" />
                </div>

				<div class="bg-message rounded-lg p-1 rounded-tl-none flex gap-1">
					<div class="p-1">
						{{ message.content }}
					</div>
	
					<div class="text-xs text-secondary-text flex flex-col justify-end">
                        10:08
                    </div>
				</div>
			</div>
        </div>
    </div>
</template>