<script setup>
import Card from '../Generic/Card.vue'
import Avatar from '../Generic/Avatar.vue'
import Tag from '../Generic/Tag.vue';

const props = defineProps({
    profile: Object,
    styles: {
        type: String,
        default: ''
    },
    withInerests: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <Card :styles="styles">
        <slot name="before" />

        <div class="flex gap-2 mb-2">
            <Avatar :src="profile.photo" styles="w-16" />

            <div>
                <h3 class="text-lg font-medium">{{ profile.name }}</h3>
                <p class="text-xs font-medium text-secondary-text">@{{ profile.username }}</p>
            </div>
        </div>

        <div class="mb-2 whitespace-pre-line">
            {{ profile.bio }}
        </div>

        <div v-if="withInerests" class="flex gap-2 flex-wrap mt-4">
            <div v-for="topic in profile.interests" :key="topic.uuid">
                <Tag size="xs">{{ topic.label }}</Tag>
            </div>
        </div>

        <slot name="after" />
    </Card>
</template>