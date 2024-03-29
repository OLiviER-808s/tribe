<script setup lang="ts">
import EntryLayout from '@/Layouts/EntryLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

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
    });
};
</script>

<template>
    <EntryLayout>
        test
    </EntryLayout>
</template>
