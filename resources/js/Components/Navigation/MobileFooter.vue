<script setup>
import { faComments, faIcons, faMapLocationDot, faPlus } from '@fortawesome/free-solid-svg-icons';
import IconButton from '../Generic/IconButton.vue'
import Avatar from '../Generic/Avatar.vue'
import Badge from "@/Components/Generic/Badge.vue"
import {inject} from "vue";

const unreadChats = inject('unreadChats')
</script>

<template>
    <footer class="flex justify-evenly py-2 bg-card shadow-sm">
        <IconButton
            :icon="faIcons"
            variant="subtle"
            :color="$page.url.startsWith('/discover') ? 'secondary' : 'base'"
            size="xl"
            :href="route('discover')"
        />

        <div class="relative">
            <div v-if="unreadChats?.length > 0" class="absolute top-0 right-0">
                <Badge :content="unreadChats.length" />
            </div>

            <IconButton
                :icon="faComments"
                variant="subtle"
                :color="$page.url.startsWith('/chats') ? 'secondary' : 'base'"
                size="xl"
                :href="route('chats')"
            />
        </div>

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

        <a :href="route('settings')" class="flex items-center">
            <Avatar :src="$page.props.profile.photo" styles="w-8" />
        </a>
    </footer>
</template>
