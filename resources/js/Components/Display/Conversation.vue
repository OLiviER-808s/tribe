<script setup>
import Card from '../Generic/Card.vue'

const { conversation } = defineProps({
    conversation: Object
})
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
    </Card>
</template>