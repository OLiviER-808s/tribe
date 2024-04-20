<script setup>
import { router } from '@inertiajs/vue3';
import Button from '../Generic/Button.vue';
import Card from '../Generic/Card.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faEllipsisVertical, faUsers } from '@fortawesome/free-solid-svg-icons';
import IconButton from '../Generic/IconButton.vue';
import Tag from '../Generic/Tag.vue';

const { conversation } = defineProps({
    conversation: Object
})

const join = () => {
    router.post(route('conversation.join', {
        uuid: conversation.uuid
    }))
}


</script>

<template>
    <Card>
        <h1 class="font-bold text-xl mb-1">{{ conversation.title }}</h1>
        <p class="mb-3 whitespace-pre-line">{{ conversation.description }}</p>

        <div class="flex items-center justify-between mb-6">
            <Tag size="sm">{{ conversation.category }}</Tag>

            <!-- TODO: make this a link to profile page -->
            <p class="text-sm text-secondary-text">by <span class="font-semibold">@{{ conversation.created_by }}</span></p>
        </div>

        <div class="flex gap-2">
            <p class="text-sm text-secondary-text flex items-center gap-2">
                <FontAwesomeIcon :icon="faUsers" class="mt-1" /> <span>{{ conversation.members }}/{{ conversation.limit }}</span>
            </p>

            <div class="flex-grow"></div>

            <Button styles="text-sm" @click="join">Join</Button>
            <IconButton :icon="faEllipsisVertical" variant="light" color="base" />
        </div>
    </Card>
</template>