<script setup>
import { faFile } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { useFiles } from '@/Composables/useFiles'

const props = defineProps({
    files: Array
})

const { readableFileSize } = useFiles()
</script>

<template>
    <div v-if="files.length > 0" class="flex flex-col mb-1 gap-2">
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