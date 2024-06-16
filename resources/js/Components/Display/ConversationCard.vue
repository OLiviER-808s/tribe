<script setup>
import { router } from '@inertiajs/vue3';
import Button from '../Generic/Button.vue';
import Card from '../Generic/Card.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faEllipsisVertical, faUsers } from '@fortawesome/free-solid-svg-icons';
import IconButton from '../Generic/IconButton.vue';
import Tag from '../Generic/Tag.vue';
import ConversationOptions from "@/Components/Dropdowns/ConversationOptions.vue";

const { conversation } = defineProps({
    conversation: Object
})

const inspect = () => router.visit(route('conversation', { uuid: conversation.uuid }))
const inspectUser = () => router.visit(route('profile', { username: conversation.created_by }))

const join = () => {
    router.post(route('conversation.join', {
        uuid: conversation.uuid
    }))
}
</script>

<template>
    <Card>
        <h1 class="font-bold text-xl mb-1 cursor-pointer" @click="inspect">{{ conversation.title }}</h1>
        <p class="mb-3 whitespace-pre-line cursor-pointer" @click="inspect">{{ conversation.description }}</p>

        <div class="flex items-center justify-between mb-6">
            <Tag size="sm">{{ conversation.topic.label }}</Tag>

            <!-- TODO: make this a link to profile page -->
            <p class="text-sm text-secondary-text">by <span class="font-semibold cursor-pointer" @click="inspectUser">@{{ conversation.created_by }}</span></p>
        </div>

        <div class="flex gap-2">
            <p class="text-sm text-secondary-text flex items-center gap-2">
                <FontAwesomeIcon :icon="faUsers" class="mt-1" /> <span>{{ conversation.members }}/{{ conversation.limit }}</span>
            </p>

            <div class="flex-grow"></div>

            <Button styles="text-sm" @click="join">Join</Button>
            <ConversationOptions :conversation="conversation" />
        </div>
    </Card>
</template>
