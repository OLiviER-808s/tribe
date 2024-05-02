<script setup>
import ThemeSwitch from './ThemeSwitch.vue';

</script>

<template>
    <div class="flex flex-col h-full p-3">
        <div class="flex-grow h-0 overflow-auto">
            <a
                :href="route('settings.profile')"
                class="hover:bg-base rounded-md py-2 px-4 cursor-pointer text-lg block"
                :class="{ 'selected-link': $page.url == '/settings/profile' }"
            >
                Profile
            </a>

            <a
                :href="route('settings.account')"
                class="hover:bg-base rounded-md py-2 px-4 cursor-pointer text-lg block"
                :class="{ 'selected-link': $page.url == '/settings/account' }"
            >
                Account
            </a>
        </div>

        <ThemeSwitch />
    </div>
</template>

<style>
.selected-link {
    @apply bg-base font-semibold;
}
.mobile {
    @apply text-xl font-medium;
}
</style>
