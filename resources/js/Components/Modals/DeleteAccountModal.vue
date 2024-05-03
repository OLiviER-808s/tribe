<script setup>
import { VueFinalModal } from 'vue-final-modal'
import Card from '../Generic/Card.vue'
import Textbox from '../Generic/Textbox.vue';
import Button from '../Generic/Button.vue';
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['close'])

const form = useForm({
    password: '',
})

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => emit('close'),
        onFinish: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <VueFinalModal overlay-transition="vfm-fade" content-transition="vfm-fade" class="flex justify-center items-center" content-class="w-full max-w-xl p-2">
        <Card styles="w-full">
            <form @submit.prevent="deleteUser">
                <div class="mb-4">
                    <h2 class="text-lg font-medium">Are you sure you want to delete your account?</h2>
                    <p class="text-sm">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please
                        enter your password to confirm you would like to permanently delete your account.
                    </p>
                </div>

                <Textbox 
                v-model="form.password" 
                :error="form.errors?.password" 
                label="Password" 
                placeholder="Your password..." 
                type="password"
                />

                <div class="flex items-center justify-end gap-4 mt-6">
                    <Button color="base" variant="light" :on-click="() => $emit('close')">Cancel</Button>
                    <Button color="error" type="submit">Confirm</Button>
                </div>
            </form>
        </Card>
    </VueFinalModal>
</template>