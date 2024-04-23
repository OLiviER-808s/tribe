<script setup>
import { VueFinalModal } from 'vue-final-modal'
import Card from '../Generic/Card.vue'
import { useForm } from '@inertiajs/vue3'
import Button from '@/Components/Generic/Button.vue'
import Textbox from '../Generic/Textbox.vue'
import { ref, computed, watch } from 'vue'
import AvatarGroup from '../Generic/AvatarGroup.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPencil } from '@fortawesome/free-solid-svg-icons'
import Cropper from 'cropperjs'
import 'cropperjs/dist/cropper.css'
import Avatar from '../Generic/Avatar.vue'

const { chat } = defineProps({
    chat: Object
})
const emit = defineEmits(['close'])

const avatars = computed(() => chat.members.map(member => member.photo).slice(0,3))

const form = useForm({
    _method: 'PUT',
    name: chat.name,
    photo: chat.photo ?? null,
})
const errors = ref({})

const src = ref('')
const cropping = ref(false)
const cropImageUrl = ref(null)
const imageElement = ref(null)
const cropper = ref(null)

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

const handlePhotoEditClick = () => {
    const target = document.getElementById('chat-photo-input')
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

        const fileName = `${chat.uuid}.jpg`
        const f = new File([blob], fileName, { lastModified: new Date().getTime(), type: blob.type })
        form.photo = f
    })

    closeCrop()
}

const closeCrop = () => {
    cropping.value = false
    cropImageUrl.value = null
}

const submit = () => {
    form.post(route('chat.update', { uuid: chat.uuid }), {
        onSuccess: () => {
            emit('close')
        },
        onError: (errs) => {
            errors.value = errs
        }
    })
}
</script>

<template>
    <VueFinalModal overlay-transition="vfm-fade" content-transition="vfm-fade" class="flex justify-center items-center" content-class="w-full max-w-xl p-2">
        <Card styles="w-full">
            <input :name="chat.name" @change="handleFileChange" id="chat-photo-input" type="file" hidden />

            <div v-if="cropping && cropImageUrl">
                <div>
                    <img id="crop-image" class="w-full max-w-full block" :src="cropImageUrl" alt="cropper" ref="imageElement" />
                </div>
                <div class="mt-2 flex justify-between gap-2 items-center">
                    <Button :styles="'w-full'" color="base" @click="closeCrop">Cancel</Button>
                    <Button :styles="'w-full'" @click="completeCrop">Confirm</Button>
                </div>
            </div>
            <div v-else>
                <div class="flex mb-4 items-center gap-4">
                    <AvatarGroup v-if="!form.photo" :avatars="avatars" width="w-12" />
                    <Avatar v-else :src="src" styles="w-12 h-12" />

                    <Button styles="text-xs" variant="light" color="secondary" :on-click="handlePhotoEditClick">
                        <FontAwesomeIcon :icon="faPencil" />
                        Edit chat photo
                    </Button>
                </div>

                <form class="flex flex-col gap-4" @submit.prevent="submit">
                    <Textbox 
                    v-model="form.name"
                    label="Chat Name"
                    placeholder="Your chat's name..."
                    :error="errors.name"
                    />

                    <Button type="submit">Confirm</Button>
                </form>
            </div>
        </Card>
    </VueFinalModal>
</template>