<script setup>
import { ref } from 'vue'
import { faCamera, faPencil, faXmark } from '@fortawesome/free-solid-svg-icons'
import TribeButton from './Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { DEFAULT_PROFILE_PIC } from '../../lib/constants'
import Cropper from 'cropperjs'
import 'cropperjs/dist/cropper.css'
import { watch } from 'vue'

const props = defineProps({
    defaultSrc: {
        type: String,
        default: DEFAULT_PROFILE_PIC
    }
})

const cropping = ref(false)
const hovering = ref(false)

const cropImageUrl = ref(null)
const imageElement = ref(null)
const cropper = ref(null)

const src = ref(props.defaultSrc)

watch(imageElement, () => {
    if (imageElement.value) {
        cropper.value = new Cropper(imageElement.value, {
            aspectRatio: 1 / 1,
            zoomable: false,
            scalable: false,
            viewMode: 3,
        })
    }
})

const handleClick = () => {
    const target = document.getElementById('avatar-input')
    target.click()
}

const handleFileChange = (event) => {
    const selectedFile = event.target.files[0]

    if (selectedFile && selectedFile.type.includes('image')) {
        const reader = new FileReader()

        reader.onloadend = () => {
            cropImageUrl.value = reader.result
        }
        reader.readAsDataURL(selectedFile)

        cropping.value = true
    }
}

const completeCrop = () => {
    const canvas = cropper.value.getCroppedCanvas()

    canvas.toBlob(blob => {
        const reader = new FileReader()

        reader.addEventListener('loadend', () => {
            src.value = reader.result
        })

        reader.readAsDataURL(blob)
    })

    closeCrop()
}

const closeCrop = () => {
    cropping.value = false
    hovering.value = false
    cropImageUrl.value = null
}
</script>

<template>
    <div v-if="cropping && cropImageUrl">
        <div>
            <img id="crop-image" class="w-full max-w-full block" :src="cropImageUrl" alt="cropper" ref="imageElement" />
        </div>
        <div class="mt-2 flex justify-between gap-2 items-center">
            <TribeButton :styles="'w-full'" color="base" @click="closeCrop">Cancel</TribeButton>
            <TribeButton :styles="'w-full'" @click="completeCrop">Confirm</TribeButton>
        </div>
    </div>
    <div v-else class="flex items-center gap-2">
        <button
            type="button"
            @mouseover="hovering = true"
            @mouseleave="hovering = false"
            @click="handleClick"
            class="relative cursor-pointer"
        >
            <img :src="src" alt="avatar" class="rounded-full w-16" />
            <div v-if="hovering"
                class="absolute bg-black text-white bg-opacity-70 rounded-full w-full h-full top-0 flex justify-center items-center"
            >
                <FontAwesomeIcon :icon="faCamera" size="lg" />
            </div>
        </button>
        <div class="flex flex-col justify-between">
            <h4 class="text-lg">{{ $page.props.auth.user.name }}</h4>
            <div class="flex items-center gap-2">
                <TribeButton @click="handleClick" variant="light" color="secondary" styles="text-xs" padding="py-2 px-4">
                    <FontAwesomeIcon :icon="faPencil" /> Edit Photo
                </TribeButton>
                <TribeButton v-if="src !== DEFAULT_PROFILE_PIC" @click="src = DEFAULT_PROFILE_PIC" variant="light" color="error" styles="text-xs" padding="py-2 px-4">
                    <FontAwesomeIcon :icon="faXmark" /> Remove Photo
                </TribeButton>
            </div>
        </div>
        <input :name="$page.props.auth.user.name" @change="handleFileChange" id="avatar-input" type="file" hidden />
    </div>
</template>
