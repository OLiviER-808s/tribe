<script setup>
import {VueFinalModal} from "vue-final-modal"
import Card from "@/Components/Generic/Card.vue"
import {inject, onMounted, ref} from "vue"
import Loader from "@/Components/Generic/Loader.vue"
import {useForm} from "@inertiajs/vue3";
import Radio from "@/Components/Generic/Radio.vue"
import Button from "@/Components/Generic/Button.vue"

const props = defineProps({
    conversation: Object
})
const emit = defineEmits(['close'])

const { addFlashMessage } = inject('flashMessages')
const form = useForm({
    uuid: ''
})

const categories = ref([])
const loading = ref(true)

const submit = () => {
    if (form.uuid) {
        form.post(route('conversation.report', {
            uuid: props.conversation.uuid
        }), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                addFlashMessage({
                    content: 'Post reported successfully.'
                })
                emit('close')
            }
        })
    } else {
        emit('close')
    }
}

onMounted(async () => {
    const response = await axios.get(route('report-categories', {
        type: 'conversation'
    }))

    categories.value = response.data.categories
    loading.value = false
})
</script>

<template>
    <VueFinalModal overlay-transition="vfm-fade" content-transition="vfm-fade" class="flex justify-center items-center" content-class="w-full max-w-xl p-2">
        <Card styles="w-full">
            <Loader v-if="loading" />
            <form v-else class="flex flex-col gap-2" @submit.prevent="submit">
                <h3 class="mb-2 text-lg font-medium">Report Post</h3>

                <div v-for="category in categories" class="flex items-center gap-2">
                    <Radio
                    name="category"
                    :label="category.name"
                    :value="category.uuid"
                    v-model="form.uuid"
                    />
                </div>

                <div v-if="form.uuid" class="flex items-center justify-end gap-2 text-sm">
                    <Button variant="light" color="base" :on-click="() => emit('close')">Cancel</Button>
                    <Button type="submit">Report</Button>
                </div>
            </form>
        </Card>
    </VueFinalModal>
</template>
