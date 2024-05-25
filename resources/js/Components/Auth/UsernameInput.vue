<script setup>
import { ref, computed, watch } from 'vue'
import { faAt } from '@fortawesome/free-solid-svg-icons'
import Textbox from '../Generic/Textbox.vue'
import axios from 'axios'
import { watchDebounced } from '@vueuse/core'

const props = defineProps({
    modelValue: String,
    existingUsername: {
        type: String,
        default: ''
    },
    disabled: Boolean,
    error: [Boolean, String],
})

const emit = defineEmits(['update:username'])

const username = ref(props.modelValue)

const isTouched = ref(false)
const loading = ref(false)

const isAvailable = ref('')
const isValid = computed(() => {
    const re = /^(?=[a-zA-Z0-9._]{3,16}$)(?!.*[_.]{2})[^_.].*[^_.]$/
    return username.value?.length > 2 && username.value?.length < 16 && re.test(username.value)
})
const isTaken = computed(() => isValid.value && !isAvailable.value && !loading.value && !(username.value === props.existingUsername))

watch(username, () => {
    emit('update:modelValue', username)

    isTouched.value = true

    if (!isValid.value) {
        loading.value = false
        return
    }

    loading.value = true
})

watchDebounced(username, async () => {
    try {
        const response = await axios.get(route('username.check', { username: username.value }))
        isAvailable.value = !response.data.taken && !(username.value === props.existingUsername)
    } catch (error) {
        console.error("Error checking username availability:", error)
    } finally {
        loading.value = false
    }
}, { debounce: 500, maxWait: 1000 })
</script>

<template>
    <div>
        <Textbox
            :icon="faAt"
            placeholder="Your username..."
            name="username"
            label="Username"
            v-model="username"
            :disabled="disabled"
            :error="((!isValid || isTaken) && isTouched && !loading) || !!error"
            :success="isValid && isAvailable && !loading && !(username === existingUsername)"
        />
        <p v-if="loading" class="text-primary text-sm">Checking availability of @{{username}}...</p>
        <p v-if="!isValid && isTouched" class="text-error text-sm">Username must be 3-16 characters long, alphanumeric only</p>
        <p v-if="isValid && !isAvailable && !loading && !(username === existingUsername)" class="text-error text-sm">@{{username}} is not available</p>
        <p v-if="error" class="text-error text-sm">{{ error }}</p>
        <p v-if="isValid && isAvailable && !loading && !(username === existingUsername)" class="text-success text-sm">@{{username}} is available!</p>
    </div>
</template>
