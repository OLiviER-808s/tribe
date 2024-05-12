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
        placeholder="Email"
        />

        <Textbox 
        v-model="form.password"
        :error="form.errors.password"
        name="password"
        type="password"
        placeholder="Password"
        />

        <Button type="submit">Sign In</Button>
    </form>
</template>