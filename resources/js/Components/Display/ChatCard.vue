<script setup>
import Card from '../Generic/Card.vue'
import AvatarGroup from '../Generic/AvatarGroup.vue'
import { computed } from 'vue'
import Avatar from '../Generic/Avatar.vue'
import { usePage } from '@inertiajs/vue3'
import ChatAdminOptions from '@/Components/Dropdowns/ChatAdminOptions.vue'

const { chat } = defineProps({
    chat: Object,
    styles: {
        type: String,
        default: ''
    }
})

const page = usePage()

const avatars = computed(() => chat.members.map(member => member.photo).slice(0,3))
const isAdmin = computed(() => chat.members.find(member => member.user_uuid === page.props.profile.uuid).admin)
</script>

<template>
    <Card :styles="styles">
        <slot name="before" />

        <div class="flex justify-center mb-4">
            <div>
                <AvatarGroup :avatars="avatars" width="w-12 mb-2" />
                <h3 class="text-lg font-medium text-center">{{ chat.name }}</h3>
            </div>
        </div>

        <div>
            <h4 class="font-medium mb-4">Chat Members</h4>

            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-2 text-sm" v-for="member in chat.members">
                    <Avatar :src="member.photo" styles="w-8" />
                    <p>{{ member.name }}</p>

                    <div class="flex-grow" />

                    <div v-if="member.admin" class="p-1 rounded-md border-2 border-secondary text-xs text-secondary font-semibold">
                        ADMIN
                    </div>
                    
                    <ChatAdminOptions 
                    v-if="isAdmin && member.user_uuid !== $page.props.profile.uuid && !member.admin" 
                    :chat="chat" 
                    :member="member" 
                    />
                </div>
            </div>
        </div>

        <slot name="after" />
    </Card>
</template>