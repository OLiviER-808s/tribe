<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import Button from "@/Components/Generic/Button.vue";
import Textbox from "@/Components/Generic/Textbox.vue";

defineProps<{
    status?: string;
}>()

const form = useForm({
    email: '',
})

const submit = () => form.post(route('password.email'))
</script>

<template>
    <form class="flex flex-col gap-6" @submit.prevent="submit">
        <Textbox
            v-model="form.email"
            :error="form.errors.email"
            name="email"
            label="Email"
            placeholder="Your email..."
        />

        <p v-if="status" class="my-2 text-sm font-medium text-secondary-text text-center">{{ status }}</p>

        <div class="flex items-center justify-end gap-2">
            <Button color="base" variant="subtle" href="/login">Cancel</Button>
            <Button color="secondary" type="submit">Send Reset Email</Button>
        </div>
    </form>
</template>
