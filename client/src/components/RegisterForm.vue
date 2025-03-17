<script setup>
import {ref} from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const message = ref('')
const router = useRouter()

const registerUser = () => {
    try {
        const response = axios.post('http://127.0.0.1:8001/api/user/create', {
            email: email.value,
            password: password.value,
        }, {
            headers: {
                'Content-Type': 'application/json',
            }
        })

        message.value = 'Success!'
        setTimeout(() => {
            router.push('login')
        }, 500)
    } catch (error) {
        console.error(error)
        message.value = error
    }
}
</script>

<template>
    <form @submit.prevent="registerUser">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" v-model="email" required/>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" v-model="password" required/>
        </div>
        <button type="submit">Register User</button>
    </form>
    <div v-if="message" class="error">{{ message }}</div>
</template>

<style scoped>

</style>
