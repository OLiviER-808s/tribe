<script setup>
import { useForm } from '@inertiajs/vue3'
import Card from '../Generic/Card.vue'
import Textbox from '../Generic/Textbox.vue'
import Button from '../Generic/Button.vue';

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
})

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <Card>
        <form @submit.prevent="updatePassword" class="flex flex-col gap-4">
            <Textbox
            type="password"
            label="Current Password"
            placeholder="Your password..."
            v-model="form.current_password"
            />

            <Textbox
            type="password"
            label="New Password"
            placeholder="Your password..."
            v-model="form.password"
            />

            <Textbox
            type="password"
            label="Confirm Password"
            placeholder="Your password..."
            v-model="form.password_confirmation"
            />

            <div class="flex justify-end">
                <Button type="submit">Save</Button>
            </div>
        </form>
    </Card>
</template>