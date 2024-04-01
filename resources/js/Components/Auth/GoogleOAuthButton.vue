<script setup>

</script>

<template>
    <a class="google-btn" :href="route('google.redirect')">
        <img src="google.svg" />
        Continue With Google
    </a>
</template>

<style>
.google-btn {
    @apply px-6 py-2 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2;
    @apply text-base-text border-base-text hover:bg-base-text/10 border-2;
}
</style>