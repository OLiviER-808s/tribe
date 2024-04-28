<script setup>
import { inject, ref } from 'vue'
import IconButton from '../Generic/IconButton.vue'
import { faArrowLeft, faArrowRight, faDownload, faFile, faXmark } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { useFiles } from '@/Composables/useFiles'

const props = defineProps({
    files: Array,
    defaultSelectedIdx: {
        type: Number,
        default: 0
    }
})

const { readableFileSize } = useFiles()

const mainContent = inject('mainContent')
const selectedIdx = ref(props.defaultSelectedIdx)

const downloadFile = () => window.open(route('message.download-attachment', {
    messageUuid: props.files[selectedIdx.value].message_uuid,
    fileUuid: props.files[selectedIdx.value].uuid
}))
</script>

<template>
    <div class="w-full h-full flex flex-col">
        <div class="flex items-center w-full mb-4 gap-2">
            <div class="flex items-center gap-4">
                <h3 class="text-lg font-medium">{{ files[selectedIdx].name }}</h3>

                <p class="text-xs text-secondary-text">{{ readableFileSize(files[selectedIdx].size) }}</p>
            </div>

            <div class="flex-grow"></div>

            <IconButton v-if="files[selectedIdx].status === 'sent'" :on-click="downloadFile" variant="subtle" color="base" :icon="faDownload" size="lg" />
            <IconButton variant="subtle" color="base" :icon="faXmark" size="lg" :on-click="() => mainContent = null" />
        </div>

        <div class="flex flex-col w-full h-full flex-grow">
            <div class="w-full h-full flex items-center justify-between gap-4">
                <IconButton :disabled="selectedIdx === 0" variant="subtle" color="base" :icon="faArrowLeft" :on-click="() => selectedIdx -= 1" />

                <div>
                    <div v-if="files[selectedIdx].type === 'image'">
                        <img class="max-h-[60vh]" :src="files[selectedIdx].preview" :alt="files[selectedIdx].name" />
                    </div>
                    <div v-else-if="files[selectedIdx].type === 'video'">
                        <video class="max-h-[60vh]" :src="files[selectedIdx].preview" controls />
                    </div>
                    <div v-else-if="files[selectedIdx].type === 'audio'">
                        <audio :src="files[selectedIdx].preview" controls />
                    </div>
                    <div v-else class="text-center">
                        <FontAwesomeIcon :icon="faFile" size="xl" />
                        <p class="text-lg mt-2">No preview avaliable</p>
                    </div>
                </div>

                <IconButton :disabled="selectedIdx === files.length - 1" variant="subtle" color="base" :icon="faArrowRight" :on-click="() => selectedIdx += 1" />
            </div>
        </div>
    </div>
</template>