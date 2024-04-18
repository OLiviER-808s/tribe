<script setup>
import { faComments, faIcons, faMapLocationDot, faPlus } from '@fortawesome/free-solid-svg-icons';
import IconButton from '../Generic/IconButton.vue'
import Avatar from '../Generic/Avatar.vue'

</script>

<template>
    <footer class="flex justify-evenly py-2 bg-card shadow-sm">
        <IconButton
            :icon="faIcons"
            variant="subtle"
            :color="$page.url === '/' ? 'secondary' : 'base'"
            size="xl"
            :href="route('home')"
        />

        <IconButton
            :icon="faComments"
            variant="subtle"
            :color="$page.url.startsWith('/chats') ? 'secondary' : 'base'"
            size="xl"
            :href="route('chats')"
        />

        <IconButton
            :icon="faPlus"
            size="xl"
            variant="light"
            :href="route('conversation.create')"
        />

        <IconButton
            :icon="faMapLocationDot"
            variant="subtle"
            :color="$page.url.startsWith('/map') ? 'secondary' : 'base'"
            size="xl"
            :href="route('chats')"
        />

        <a :href="route('settings.account')" class="flex items-center">
            <Avatar :src="$page.props.profile.photo" styles="w-8" />
        </a>
    </footer>
</template>
