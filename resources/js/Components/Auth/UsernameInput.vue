<script setup>
import { ref, watch, computed } from 'vue'
import { faAt } from '@fortawesome/free-solid-svg-icons'
import Textbox from '../Generic/Textbox.vue'
import axios from 'axios'

const { username, existingUsername, disabled, error } = defineProps({
    username: {
        type: String,
        default: ''
    },
    existingUsername: {
        type: String,
        default: ''
    },
    disabled: Boolean,
    error: [Boolean, String],
})

const emit = defineEmits(['update:username'])

const internalUsername = ref(username)

const isTouched = ref(false)
const loading = ref(false)
let debounceTimer = null

const isAvailable = ref('')
const isValid = computed(() => {
    const re = /^(?=[a-zA-Z0-9._]{3,16}$)(?!.*[_.]{2})[^_.].*[^_.]$/
    return internalUsername.value.length > 2 && internalUsername.value.length < 16 && re.test(internalUsername.value)
})
const isTaken = computed(() => isValid.value && !isAvailable.value && !loading.value && !(internalUsername.value === existingUsername))

const handleInput = () => {
    emit('update:username', internalUsername)

    isTouched.value = true
    clearTimeout(debounceTimer)

    if (!isValid.value) {
        loading.value = false
        return
    }

    loading.value = true

    debounceTimer = setTimeout(async () => {
        try {
            const response = await axios.get(route('username.check', { username: internalUsername.value }))
            isAvailable.value = !response.data.taken && !(internalUsername.value === existingUsername)
        } catch (error) {
            console.error("Error checking username availability:", error)
        } finally {
            loading.value = false
        }
    }, 500)
}
</script>

<template>
    <div>
        <Textbox
            :icon="faAt"
            placeholder="Your username..."
            name="username"
            label="Username*"
            v-model="internalUsername"
            :disabled="disabled"
            :error="((!isValid || isTaken) && isTouched && !loading) || !!error"
            :success="isValid && isAvailable && !loading && !(internalUsername === existingUsername)"
            :on-input="handleInput"
        />
        <p v-if="loading" class="text-primary text-sm">Checking availability of @{{internalUsername}}...</p>
        <p v-if="!isValid && isTouched" class="text-error text-sm">Username must be 3-16 characters long, alphanumeric only</p>
        <p v-if="isValid && !isAvailable && !loading && !(internalUsername === existingUsername)" class="text-error text-sm">@{{username}} is not available</p>
        <p v-if="error" class="text-error text-sm">{{ error }}</p>
        <p v-if="isValid && isAvailable && !loading && !(internalUsername === existingUsername)" class="text-success text-sm">@{{username}} is available!</p>
    </div>
</template>
