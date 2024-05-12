<script setup>
import { VueFinalModal } from 'vue-final-modal'
import Card from '../Generic/Card.vue'
import InterestForm from '../Forms/InterestForm.vue'

const { categories } = defineProps({
    categories: Array
})
const emit = defineEmits(['close'])

const close = () => emit('close')
</script>

<template>
    <VueFinalModal overlay-transition="vfm-fade" content-transition="vfm-fade" class="flex justify-center items-center" content-class="w-full max-w-xl p-2">
        <Card styles="w-full flex flex-col max-h-[90vh]">
            <InterestForm 
            next-route="settings.profile"
            :default-categories="categories" 
            :default-selected-interests="$page.props.profile.interests.map(topic => topic.uuid)" 
            :on-submited="close" 
            />
        </Card>
    </VueFinalModal>
</template>