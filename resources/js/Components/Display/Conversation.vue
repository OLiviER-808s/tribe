<script setup>
import { router } from '@inertiajs/vue3';
import Button from '../Generic/Button.vue';
import Card from '../Generic/Card.vue'

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

        <div class="flex items-center justify-between text-sm mb-6">
            <!-- TODO: make this a link to profile page -->
            <p>by <span class="font-semibold">@{{ conversation.created_by }}</span></p>

            <p>{{ conversation.members }}/{{ conversation.limit }}</p>
        </div>

        <div class="flex justify-end">
            <Button styles="text-sm" @click="join">Join</Button>
        </div>
    </Card>
</template>