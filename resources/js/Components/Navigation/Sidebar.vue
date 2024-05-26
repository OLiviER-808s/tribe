<script setup>
import { faIcons, faMapLocationDot, faPlus, faComments } from '@fortawesome/free-solid-svg-icons'
import IconButton from '../Generic/IconButton.vue'
import Card from '../Generic/Card.vue'
import Avatar from '../Generic/Avatar.vue'
import { router } from '@inertiajs/vue3'
import Badge from "@/Components/Generic/Badge.vue";
import {inject} from "vue";

const unreadChats = inject('unreadChats')
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

            <button @click="router.visit(route('settings.profile'))">
                <Avatar :src="$page.props.profile.photo" styles="w-10" />
            </button>
        </div>
    </Card>
</template>
