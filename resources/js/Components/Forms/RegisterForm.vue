<script setup>
import { useForm } from '@inertiajs/vue3'
import Button from '../../Components/Generic/Button.vue'
import Textbox from '../../Components/Generic/Textbox.vue'
import DatePicker from '../../Components/Generic/DatePicker.vue'

const form = useForm({
    name: '',
    email: '',
    password: '',
    dob: null
})

const submit = () => form.post(route('register'))
</script>

<template>
    <form class="flex flex-col gap-4" @submit.prevent="submit">
        <Textbox 
        v-model="form.name"
        name="name"
        placeholder="Full Name"
        :error="form.errors.name"
        />

        <Textbox 
        v-model="form.email"
        name="email"
        placeholder="Email"
        :error="form.errors.email"
        />

        <Textbox 
        v-model="form.password"
        name="password"
        type="password"
        placeholder="Password"
        :error="form.errors.password"
        />

        <DatePicker v-model="form.dob" :error="form.errors.dob" />

        <Button type="submit">Continue</Button>
    </form>
</template>