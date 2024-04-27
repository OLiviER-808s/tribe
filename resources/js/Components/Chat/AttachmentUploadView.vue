<script setup>
import { inject, computed, ref } from 'vue'
import IconButton from '../Generic/IconButton.vue'
import { faArrowLeft, faArrowRight, faFile, faHeadphones, faVideoCamera, faXmark } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { useFiles } from '@/Composables/useFiles'
import { useIsHandheld } from '@/Composables/useIsHandheld'

const { readableFileSize, formatFiles } = useFiles()
const { isHandheld } = useIsHandheld()

const mainContent = inject('mainContent')

const selectedIdx = ref(0)
const hoveredIdx = ref(null)

const removeFile = (idx) => {
    if (mainContent.value.data.files.length === 1) {
        mainContent.value = null
    }

    mainContent.value.data.files = mainContent.value.data.files.filter((file, i) => i !== idx)
}

const formattedFiles = computed(() => formatFiles(mainContent.value.data.files))
</script>

<template>
    <div class="w-full h-full flex flex-col">
        <div class="flex items-center justify-between w-full mb-4">
            <div class="flex items-center gap-4">
                <h3 class="text-lg font-medium">{{ formattedFiles[selectedIdx].name }}</h3>

                <p class="text-xs text-secondary-text">{{ readableFileSize(formattedFiles[selectedIdx].size) }}</p>
            </div>

            <IconButton variant="subtle" color="base" :icon="faXmark" size="lg" @click="mainContent = null" />
        </div>

        <div class="flex flex-col w-full h-full flex-grow">
            <div class="w-full h-full flex items-center justify-between gap-4">
                <IconButton :disabled="selectedIdx === 0" variant="subtle" color="base" :icon="faArrowLeft" @click="selectedIdx -= 1" />

                <div>
                    <div v-if="formattedFiles[selectedIdx].type === 'image'">
                        <img class="max-h-[50vh]" :src="formattedFiles[selectedIdx].preview" :alt="formattedFiles[selectedIdx].name" />
                    </div>
                    <div v-else-if="formattedFiles[selectedIdx].type === 'video'">
                        <video class="max-h-[50vh]" :src="formattedFiles[selectedIdx].preview" controls />
                    </div>
                    <div v-else-if="formattedFiles[selectedIdx].type === 'audio'">
                        <audio :src="formattedFiles[selectedIdx].preview" controls />
                    </div>
                    <div v-else class="text-center">
                        <FontAwesomeIcon :icon="faFile" size="xl" />
                        <p class="text-lg mt-2">No preview avaliable</p>
                    </div>
                </div>

                <IconButton :disabled="selectedIdx === formattedFiles.length - 1" variant="subtle" color="base" :icon="faArrowRight" @click="selectedIdx += 1" />
            </div>
        </div>

        <div class="flex justify-center gap-2 w-full p-4">
            <div v-for="(file, idx) in formattedFiles" class="relative w-16 h-16 border-secondary rounded-md" :class="{ 'border-2': selectedIdx === idx }" @mouseenter="hoveredIdx = idx" @mouseleave="hoveredIdx = null">
                <button class="w-full h-full" @click="selectedIdx = idx">
                    <img v-if="file.type === 'image'" :src="file.preview" class="w-full h-full object-cover rounded-md" />
                    <div v-else class="w-full h-full flex items-center justify-center text-secondary-text bg-card rounded-md">
                        <FontAwesomeIcon v-if="file.type === 'audio'" :icon="faHeadphones" size="lg" />
                        <FontAwesomeIcon v-else-if="file.type === 'video'" :icon="faVideoCamera" size="lg" />
                        <FontAwesomeIcon v-else :icon="faFile" size="lg" />
                    </div>
                </button>

                <button @click="removeFile(idx)" v-if="(hoveredIdx === idx) || isHandheld" class="absolute cursor-pointer top-0 right-0 text-secondary-text bg-card/80 rounded-full w-5 h-5 flex justify-center items-center">
                    <FontAwesomeIcon :icon="faXmark" size="xs" />
                </button>
            </div>
        </div>
    </div>
</template>