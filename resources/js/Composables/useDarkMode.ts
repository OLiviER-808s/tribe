import { usePage } from "@inertiajs/vue3"
import { computed, onMounted, provide, watch, ref } from "vue"
import { usePreferredDark } from '@vueuse/core'

export const useDarkMode = () => {
    const page = usePage()
    const prefersDark = usePreferredDark()

    const theme = ref(() => page.props.theme ?? prefersDark ? 'dark' : 'light')
    provide('theme', theme)

    onMounted(() => {
        if (page.props.theme === 'dark' || (prefersDark && page.props.theme !== 'light')) {
            document.getElementsByTagName('body')[0].className += ' dark'
        }
    })

    watch(page, (newPage) => {
        if (newPage.props.theme === 'dark' || (prefersDark && newPage.props.theme !== 'light')) {
            document.getElementsByTagName('body')[0].className += ' dark'
        } else {
            const className = document.getElementsByTagName('body')[0].className

            document.getElementsByTagName('body')[0].className = className.replace(' dark', '')
        }

        theme.value = newPage.props.theme ?? prefersDark ? 'dark' : 'light'
    }, { deep: true })
}
