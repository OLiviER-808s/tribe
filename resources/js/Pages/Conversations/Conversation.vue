<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import GlobalSearch from '@/Components/Dropdowns/GlobalSearch.vue'
import Tag from '@/Components/Generic/Tag.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCalendar, faEllipsisVertical, faUser, faUsers } from '@fortawesome/free-solid-svg-icons';
import { useDates } from '@/Composables/useDates';
import { router } from '@inertiajs/vue3';
import Button from '@/Components/Generic/Button.vue';
import IconButton from '@/Components/Generic/IconButton.vue';

const props = defineProps({
    conversation: Object,
    canJoin: Boolean
})

const { formatDate } = useDates()

const join = () => {
    router.post(route('conversation.join', {
        uuid: props.conversation.uuid
    }))
}
</script>

<template>
    <AuthLayout>
        <template #header-right-section>
            <GlobalSearch />
        </template>

        <div class="overflow-auto h-full">
            <section class="flex justify-center overflow-auto h-full">
                <div class="px-1 py-6 w-full max-w-2xl">
                    <div class="flex mb-2">
                        <Tag size="xs">{{ conversation.topic.label }}</Tag>
                    </div>

                    <h2 class="text-2xl font-medium mb-4">{{ conversation.title }}</h2>
                    <p>{{ conversation.description }}</p>

                    <div class="flex flex-col gap-2 text-sm text-secondary-text my-4">
                        <div class="flex items-center gap-4">
                            <span class="w-4 text-center">
                                <FontAwesomeIcon :icon="faUsers" />
                            </span>
                            <span>Members joined: <span class="font-medium">{{ conversation.members }}/{{ conversation.limit }}</span></span>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="w-4 text-center">
                                <FontAwesomeIcon :icon="faUser" />
                            </span>
                            <span>Conversation created by: <span @click="router.visit(route('profile', { username: conversation.created_by }))" class="font-medium cursor-pointer">@{{ conversation.created_by }}</span></span>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="w-4 text-center">
                                <FontAwesomeIcon :icon="faCalendar" />
                            </span>
                            <span>Conversation start date: <span class="font-medium">{{ formatDate(new Date(conversation.created_at)) }}</span></span>
                        </div>
                    </div>

                    <div v-if="conversation.active" class="flex justify-end items-center gap-2">
                        <Button styles="text-sm" @click="join" :disabled="!canJoin">Join</Button>
                        <IconButton :icon="faEllipsisVertical" variant="light" color="base" />
                    </div>
                </div>
            </section>
        </div>
    </AuthLayout>
</template>