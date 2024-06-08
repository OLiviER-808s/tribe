<script setup>
import { VueFinalModal } from 'vue-final-modal'
import Card from '../Generic/Card.vue'
import ProfileForm from '../Forms/ProfileForm.vue'

const emit = defineEmits(['close'])
</script>

<template>
    <VueFinalModal overlay-transition="vfm-fade" content-transition="vfm-fade" class="flex justify-center items-center" content-class="w-full max-w-xl p-2">
        <Card styles="w-full">
            <ProfileForm
                with-name-field
                with-date-of-birth-field
                next-route="settings.profile"
                :existing-profile="$page.props.profile"
                :on-success="() => emit('close')" />
        </Card>
    </VueFinalModal>
</template>
