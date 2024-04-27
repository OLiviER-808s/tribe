<script setup>
import { inject, computed, ref } from 'vue'
import IconButton from '../Generic/IconButton.vue';
import { faArrowLeft, faArrowRight, faXmark } from '@fortawesome/free-solid-svg-icons';

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
    <div class="w-full h-full">
        <div class="flex justify-end w-full mb-4">
            <IconButton variant="subtle" color="base" :icon="faXmark" size="lg" @click="mainContent = null" />
        </div>

        <div class="flex flex-col w-full h-full">
            <div class="w-full flex items-center justify-between gap-4">
                <IconButton :disabled="selectedIdx === 0" variant="subtle" color="base" :icon="faArrowLeft" @click="selectedIdx -= 1" />

                <div>
                    <div v-if="formattedFiles[selectedIdx].type === 'image'">
                        <img class="max-h-[70vh]" :src="formattedFiles[selectedIdx].preview" :alt="formattedFiles[selectedIdx].name" />
                    </div>
                    <div v-if="formattedFiles[selectedIdx].type === 'video'">
                        <video class="max-h-[70vh]" :src="formattedFiles[selectedIdx].preview" controls />
                    </div>
                    <div v-if="formattedFiles[selectedIdx].type === 'audio'">
                        <audio :src="formattedFiles[selectedIdx].preview" controls />
                    </div>
                </div>

                <IconButton :disabled="selectedIdx === formattedFiles.length - 1" variant="subtle" color="base" :icon="faArrowRight" @click="selectedIdx += 1" />
            </div>
        </div>
    </div>
</template>