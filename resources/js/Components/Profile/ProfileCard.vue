<script setup>
import Card from '../Generic/Card.vue'
import Avatar from '../Generic/Avatar.vue'

const props = defineProps({
    profile: Object
})
</script>

<template>
    <Card>
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
    </Card>
</template>