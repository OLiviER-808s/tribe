<script setup>
import { inject, computed, ref } from 'vue'
import IconButton from '../Generic/IconButton.vue';
import { faArrowLeft, faArrowRight, faFile, faHeadphones, faVideoCamera, faXmark } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const mainContent = inject('mainContent')

const selectedIdx = ref(0)

const formattedFiles = computed(() => mainContent.value.data?.files?.map(file => {
    const fileType = file.type.split('/')[0]

    return {
        preview: URL.createObjectURL(file),
        type: fileType,
        name: file.name,
        file: file,
    }
}))
</script>

<template>
    <div class="w-full h-full flex flex-col">
        <div class="flex justify-end w-full mb-4">
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
            <div v-for="(file, idx) in formattedFiles" class="relative w-16 h-16">
                <button class="w-full h-full" @click="selectedIdx = idx">
                    <img v-if="file.type === 'image'" :src="file.preview" class="w-full h-full object-cover rounded-md" />
                    <div v-else class="w-full h-full flex items-center justify-center text-secondary-text bg-card rounded-md">
                        <FontAwesomeIcon v-if="file.type === 'audio'" :icon="faHeadphones" size="lg" />
                        <FontAwesomeIcon v-else-if="file.type === 'video'" :icon="faVideoCamera" size="lg" />
                        <FontAwesomeIcon v-else :icon="faFile" size="lg" />
                    </div>
                </button>
            </div>
        </div>
    </div>
</template>