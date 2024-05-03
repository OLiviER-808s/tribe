<script setup>
import { faIcons, faMapLocationDot, faPlus, faComments } from '@fortawesome/free-solid-svg-icons'
import IconButton from '../Generic/IconButton.vue'
import Card from '../Generic/Card.vue'
import Avatar from '../Generic/Avatar.vue'
</script>

<template>
    <Card styles="h-full">
        <div class="flex flex-col items-center gap-3 h-full">
            <IconButton
            :icon="faIcons"
            variant="subtle"
            :color="$page.url.startsWith('/discover') ? 'secondary' : 'base'"
            size="xl"
            :href="route('discover')"
            />

            <IconButton
            :icon="faComments"
            variant="subtle"
            :color="$page.url.startsWith('/chats') ? 'secondary' : 'base'"
            size="xl"
            :href="route('chats')"
            />

            <IconButton
            :icon="faMapLocationDot"
            variant="subtle"
            :color="$page.url.startsWith('/map') ? 'secondary' : 'base'"
            size="xl"
            :href="route('chats')"
            />

            <IconButton 
            :icon="faPlus" 
            size="xl" 
            variant="light" 
            :href="route('conversation.create')"
            />

            <div class="flex-grow" />

            <a :href="route('settings.profile')">
                <Avatar :src="$page.props.profile.photo" styles="w-10" />
            </a>
        </div>
    </Card>
</template>