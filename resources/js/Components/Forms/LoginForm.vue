<script setup>
import { useForm } from '@inertiajs/vue3'
import Button from '../../Components/Generic/Button.vue'
import Textbox from '../../Components/Generic/Textbox.vue'

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    })
}
</script>

<template>
    <form class="flex flex-col gap-4" @submit.prevent="submit">
        <Textbox 
        v-model="form.email"
        :error="form.errors.email"
        name="email"
        label="Email"
        placeholder="Your email..."
        />

        <Textbox 
        v-model="form.password"
        :error="form.errors.password"
        name="password"
        label="Password"
        type="password"
        placeholder="Your password..."
        />

        <Button type="submit">Continue</Button>
    </form>
</template>