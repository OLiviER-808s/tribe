import { ref, onMounted, onUnmounted } from 'vue'

export const useIsHandheld = () => {
    const isHandheld = ref(innerWidth < 800)

    const onResize = () => isHandheld.value = innerWidth < 800

    onMounted(() => addEventListener('resize', onResize))
    onUnmounted(() => removeEventListener('resize', onResize))

    return { isHandheld }
}