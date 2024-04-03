<script setup>

</script>

<template>
    <div class="flex flex-col h-full p-3">
        <div class="flex-grow h-0 overflow-auto">
            <a
                :href="href"
                class="hover:bg-base rounded-md py-2 px-4 cursor-pointer text-lg block"
                :class="{ 'selected': $page.url == '/settings/account' }"
            >
                Account
            </a>
        </div>
    </div>
</template>

<style>
.selected {
    @apply bg-base font-semibold;
}
.mobile {
    @apply text-xl font-medium;
}
</style>
