<script setup>
import IconButton from "@/Components/Generic/IconButton.vue";
import {faXmark} from "@fortawesome/free-solid-svg-icons";
import {onMounted, ref} from "vue";

const props = defineProps({
    opacity: {
        type: Number,
        default: 40
    },
    color: {
        type: String,
        default: 'success'
    },
    duration: {
        type: Number,
        default: 1000
    }
})

const show = ref(true)

onMounted(() => setTimeout(() => show.value = false, props.duration))
</script>

<template>
    <Transition name="slide-fade">
        <div v-if="show" :class="`w-96 flex items-center gap-2 rounded-md p-2 bg-${color}/${opacity}`">
            <div class="flex-grow">
                <slot />
            </div>

            <IconButton :icon="faXmark" color="base" variant="subtle" :on-click="() => show = false" />
        </div>
    </Transition>
</template>

<style>
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
