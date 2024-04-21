<script setup>
import { VueFinalModal } from 'vue-final-modal'
import Card from '../Generic/Card.vue'
import { useForm } from '@inertiajs/vue3'
import Button from '@/Components/Generic/Button.vue'
import Textbox from '../Generic/Textbox.vue'
import { ref } from 'vue'

const emit = defineEmits(['close'])

const cropping = ref(false)
const src = ref('')

const errors = ref({})

const form = useForm({
    _method: 'PATCH',
    name: '',
    photo: null,
})
</script>

<template>
    <VueFinalModal overlay-transition="vfm-fade" content-transition="vfm-fade" class="flex justify-center items-center" content-class="w-full max-w-xl p-2">
        <Card styles="w-full">
            <div class="flex justify-center mb-4"></div>

            <form class="flex flex-col gap-4" @submit.prevent="submit" v-if="!cropping">
                <Textbox 
                v-model="form.name"
                name="name"
                label="Chat Name"
                placeholder="Your chat's name..."
                :error="errors.name"
                />

                <Button type="submit">Confirm</Button>
            </form>
        </Card>
    </VueFinalModal>
</template>