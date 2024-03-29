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
        <section class="flex justify-center flex-1 overflow-auto h-0">
            <div class="max-w-sm flex-grow">
                <form class="flex flex-col gap-4" @submit.prevent="submit">

                </form>
            </div>
        </section>
    </EntryLayout>
</template>
