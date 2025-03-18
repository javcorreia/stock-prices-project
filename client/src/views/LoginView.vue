<script setup>
import {ref} from 'vue'
import axios from 'axios'

const email = ref('')
const password = ref('')
const errorShow = ref('')

const handleSubmit = async () => {
    try {
        const response = await axios.post('http://127.0.0.1:8001/api/login_check', {
            email: email.value,
            password: password.value,
        }, {
            headers: {
                'Content-Type': 'application/json',
            }
        })

        const {token} = response.data
        sessionStorage.setItem('token', token)
        window.location.href = '/'
    } catch (error) {
        console.error(error.response.data)
        errorShow.value = error.response.data.message
    }
}
</script>

<template>
    <section class="flex items-center justify-center min-h-screen ">
        <div class="bg-gray-500 shadow-md rounded-lg p-8 max-w-md w-full">
            <h1 class="text-2xl font-semibold text-center text-gray-800 mb-6">Login</h1>
            <form @submit.prevent="handleSubmit">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        type="email"
                        v-model="email"
                        required
                        placeholder="Enter your email"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        id="password"
                        type="password"
                        v-model="password"
                        required
                        placeholder="Enter your password"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Login</button>
                </div>
            </form>
            <div v-if="errorShow" class="mt-4 text-center text-sm font-medium text-red-500">{{ errorShow }}</div>
        </div>
    </section>
</template>
