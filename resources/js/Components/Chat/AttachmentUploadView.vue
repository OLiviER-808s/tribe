<script setup>
import { inject, computed } from 'vue'

const mainContent = inject('mainContent')

const formattedFiles = computed(() => mainContent.value.data.files.map(file => {
    const fileType = file.type.split('/')[0]

    return {
        preview: URL.createObjectURL(file),
        type: fileType,
        file: file,
    }
}))
</script>

<template>
    <div>
        <div v-for="file in formattedFiles">
            <div v-if="file.type === 'image'">
                <img :src="file.preview" />
            </div>
            <div v-else-if="file.type === 'video'">
                <video controls :src="file.preview" />
            </div>
            <div v-else-if="file.type === 'audio'">
                <audio controls :src="file.preview" />
            </div>
            <div v-else>
                <div>no preview</div>
            </div>
        </div>
    </div>
</template>