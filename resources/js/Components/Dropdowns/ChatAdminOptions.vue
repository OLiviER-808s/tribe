<script setup>
import { faEllipsisVertical } from '@fortawesome/free-solid-svg-icons'
import DropdownMenu from 'v-dropdown-menu'
import IconButton from '../Generic/IconButton.vue'
import { router } from '@inertiajs/vue3';

const { chat, member } = defineProps({
	chat: Object,
    member: Object
})

const removeMember = () => router.delete(route('chat.remove-member', {
    chatUuid: chat.uuid,
    memberUuid: member.uuid
}))
</script>

<template>
    <DropdownMenu :overlay="false" :dropup="false" direction="right">
        <template #trigger>
            <IconButton variant="subtle" color="base" :icon="faEllipsisVertical" size="md" />
        </template>

        <template #body>
            <div>
                <div class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md cursor-pointer">
                    Make admin
                </div>
                <div @click="removeMember()" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md cursor-pointer">
                    Remove from chat
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