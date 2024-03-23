<script setup>
import { faIcons, faMapLocationDot, faPlus, faComments } from '@fortawesome/free-solid-svg-icons'
import IconButton from '../Generic/IconButton.vue'
import Card from '../Generic/Card.vue'
</script>

<template>
    <Card styles="h-full">
        <div class="flex flex-col items-center gap-3 h-full">
            <IconButton
            :icon="faIcons"
            variant="subtle"
            color="base"
            size="xl"
            href="/"
            />

            <IconButton
            :icon="faComments"
            variant="subtle"
            color="base"
            size="xl"
            href="/"
            />

            <IconButton
            :icon="faMapLocationDot"
            variant="subtle"
            color="base"
            size="xl"
            href="/"
            />

            <IconButton 
            :icon="faPlus" 
            size="xl" 
            variant="light" 
            href="/conversation/create" 
            />

            <div class="flex-grow" />

            <!-- <a href="/settings/account">
                <Avatar src={$profile?.photo} styles="w-10" />
            </a> -->
        </div>
    </Card>
</template>