<script setup>
import { faFile, faHeadphones, faVideo } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { useFiles } from '@/Composables/useFiles'

const props = defineProps({
    files: Array
})

const { readableFileSize } = useFiles()
</script>

<template>
    <div v-if="files.length > 3" class="grid grid-cols-2 mb-1 gap-1">
        <button v-for="(file, idx) in files.slice(0, 4)" class="xl:w-32 xl:h-32 lg:w-28 lg:h-28 w-24 h-24">
            <img v-if="file.type === 'image'" :src="file.preview" class="xl:w-32 xl:h-32 lg:w-28 lg:h-28 w-24 h-24 rounded-sm object-cover" />
            <div v-else class="rounded-sm bg-card flex justify-center items-center text-base-text h-full w-full">
                <h4 v-if="files.length > 4 && idx === 3" class="text-xl font-semibold">+{{ files.length - 3 }}</h4>
                <FontAwesomeIcon v-else-if="file.type === 'audio'" :icon="faHeadphones" size="xl" />
                <FontAwesomeIcon v-else-if="file.type === 'video'" :icon="faVideo" size="xl" />
                <FontAwesomeIcon v-else :icon="faFile" size="xl" />
            </div>
        </button>
    </div>
    <div v-else-if="files.length > 0" class="flex flex-col mb-1 gap-2">
        <div v-for="file in files">
            <img v-if="file.type === 'image'" :src="file.preview" class="rounded-sm" />
            <audio v-else-if="file.type === 'audio'" :src="file.preview" controls />
            <video v-else-if="file.type === 'video'" :src="file.preview" controls class="rounded-sm" />
            <div v-else class="flex items-center gap-2 min-w-64 px-2">
                <FontAwesomeIcon :icon="faFile" size="lg" />
                <div>
                    <p class="font-medium">{{ file.name }}</p>
                    <p class="text-xs text-secondary-text">{{ readableFileSize(file.size) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>