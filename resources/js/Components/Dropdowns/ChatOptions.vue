<script setup>
import { faEllipsisVertical } from '@fortawesome/free-solid-svg-icons'
import DropdownMenu from 'v-dropdown-menu'
import IconButton from '../Generic/IconButton.vue'
import { router } from '@inertiajs/vue3'
import { inject } from 'vue'

const { chat } = defineProps({
	chat: Object
})

const leaveChat = () => router.delete(route('chat.leave', { uuid: chat.uuid }))
const viewChat = () => inspectInfo.value = { type: 'chat' }

const inspectInfo = inject('inspectInfo')
</script>

<template>
    <DropdownMenu :overlay="false" :dropup="false" direction="right">
        <template #trigger>
            <IconButton variant="subtle" color="base" :icon="faEllipsisVertical" size="lg" />
        </template>

        <template #body>
            <div>
                <div @click="viewChat()" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md cursor-pointer">
                    View chat
                </div>
                <div class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md cursor-pointer">
                    Archive chat
                </div>
                <div @click="leaveChat()" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md cursor-pointer">
                    Leave chat
                </div>
            </div>
        </template>
    </DropdownMenu>
</template>

<style>
.v-dropdown-menu__container {
    @apply rounded-md bg-dropdown text-dropdown-text !border-none;
}
</style>