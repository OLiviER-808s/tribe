<script setup>
import { useForm } from '@inertiajs/vue3'
import Button from '../../Components/Generic/Button.vue'
import Textbox from '../../Components/Generic/Textbox.vue'

const form = useForm({
    name: '',
    email: '',
    password: '',
})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password')
        }
    })
}
</script>

<template>
    <form class="flex flex-col gap-4" @submit.prevent="submit">
        <Textbox 
        v-model="form.name"
        name="name"
        label="Full Name"
        placeholder="Your name..."
        :error="form.errors.name"
        />

        <Textbox 
        v-model="form.email"
        name="email"
        label="Email"
        placeholder="Your email..."
        :error="form.errors.email"
        />

        <Textbox 
        v-model="form.password"
        name="password"
        label="Password"
        type="password"
        placeholder="Your password..."
        :error="form.errors.password"
        />

        <Button type="submit">Continue</Button>
    </form>
</template>