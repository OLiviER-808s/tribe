<script setup>
import Card from '../Generic/Card.vue'
import AvatarGroup from '../Generic/AvatarGroup.vue'
import { computed } from 'vue'
import Avatar from '../Generic/Avatar.vue'
import { usePage } from '@inertiajs/vue3'
import ChatAdminOptions from '@/Components/Dropdowns/ChatAdminOptions.vue'
import Button from '../Generic/Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPencil } from '@fortawesome/free-solid-svg-icons'
import { ModalsContainer, useModal } from 'vue-final-modal'
import ChatEditModal from '@/Components/Modals/ChatEditModal.vue'

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

const editModal = useModal({
    component: ChatEditModal,
    attrs: {
        chat: chat,
        onClose() {
            editModal.close()
        }
    }
})
</script>

<template>
    <Card :styles="styles">
        <slot name="before" />

        <div class="flex justify-center mb-4">
            <div class="flex flex-col items-center gap-2">
                <Avatar v-if="chat.photo" :src="chat.photo" styles="w-20 h-20" />
                <AvatarGroup v-else :avatars="avatars" width="w-12" />
                <h3 class="text-lg font-medium text-center">{{ chat.name }}</h3>
            </div>
        </div>

        <div v-if="isAdmin" class="my-6 flex justify-center text-sm">
            <ModalsContainer />

            <Button variant="outline" :on-click="editModal.open">
                <FontAwesomeIcon :icon="faPencil" /> Edit Chat
            </Button>
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