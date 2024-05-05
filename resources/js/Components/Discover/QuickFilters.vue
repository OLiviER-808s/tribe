<script setup>

</script>

<template>
    <div class="flex items-center gap-2 mb-6">
        <button class="filter bg-neutral-400 hover:bg-neutral-800 hover:text-dropdown-text">
            test
        </button>
        <button class="filter selected-filter">
            test 2
        </button>
    </div>
</template>

<style>
    .filter {
        @apply rounded-md duration-300 font-medium py-1 px-4;
    }
    .selected-filter {
        @apply bg-neutral-800 text-dropdown-text;
    }
</style>