<script setup>
import Button from '../Generic/Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCamera, faFileLines, faHeadphones, faPhotoFilm, faLocationDot } from '@fortawesome/free-solid-svg-icons'

const { show } = defineProps({
    show: Boolean
})
</script>

<template>
    <div v-if="show" class="flex items-center justify-center gap-4 flex-wrap mb-4">
        <Button variant="outline" styles="w-24">
            <div>
                <FontAwesomeIcon :icon="faCamera" />
                <p class="text-sm">Camera</p>
            </div>
        </Button>

        <Button variant="outline" color="secondary" styles="w-24">
            <div>
                <FontAwesomeIcon :icon="faPhotoFilm" />
                <p class="text-sm">Gallery</p>
            </div>
        </Button>

        <Button variant="outline" color="accent" styles="w-24">
            <div>
                <FontAwesomeIcon :icon="faHeadphones" />
                <p class="text-sm">Audio</p>
            </div>
        </Button>

        <Button variant="outline" color="success" styles="w-24">
            <div>
                <FontAwesomeIcon :icon="faFileLines" />
                <p class="text-sm">Files</p>
            </div>
        </Button>

        <Button variant="outline" color="error" styles="w-24">
            <div>
                <FontAwesomeIcon :icon="faLocationDot" />
                <p class="text-sm">Location</p>
            </div>
        </Button>
    </div>
</template>