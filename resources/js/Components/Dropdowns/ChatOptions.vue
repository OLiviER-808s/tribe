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
const archiveChat = () => router.patch(route(chat.archived ? 'chat.unarchive' : 'chat.archive', { uuid: chat.uuid }))
const viewChat = () => inspectInfo.value = { type: 'chat' }

const inspectInfo = inject('inspectInfo')
</script>

<template>
    <DropdownMenu :overlay="false" :dropup="false" direction="right" withDropdownCloser>
        <template #trigger>
            <IconButton variant="subtle" color="base" :icon="faEllipsisVertical" size="lg" />
        </template>

        <template #body>
            <div class="max-w-64">
                <div dropdown-closer @click="viewChat()" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md cursor-pointer">
                    View
                </div>
                <div dropdown-closer @click="archiveChat()" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md cursor-pointer">
                    {{ chat.archived ? 'Unarchive' : 'Archive' }}
                </div>
                <div dropdown-closer @click="leaveChat()" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md cursor-pointer">
                    Leave
                </div>
            </div>
        </template>
    </DropdownMenu>
</template>

<style scoped>
.v-dropdown-menu__container {
    @apply rounded-md bg-dropdown text-dropdown-text !border-none;
}
</style>