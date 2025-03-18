<script setup>
import {reactive, ref} from 'vue'
import axios from 'axios'

const errorShow = ref('')
const historyResults = reactive({})

const handleSubmit = async () => {
  try {
    const token = sessionStorage.getItem('token')

    const response = await axios.get('http://127.0.0.1:8001/api/history', {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`
      }
    })

    Object.assign(historyResults, response.data)
  } catch (error) {
    console.error(error.response.data)
    errorShow.value = error.response.data.message
  }
}
</script>

<template>
  <section class="flex items-center justify-center min-h-screen ">
    <div class="bg-gray-500 shadow-md rounded-lg p-8 max-w-md w-full">
      <h1 class="text-2xl font-semibold text-center text-gray-800 mb-6">Show History</h1>
      <form @submit.prevent="handleSubmit">
        <div>
          <button type="submit"
                  class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Check
          </button>
        </div>
      </form>
      <div v-if="errorShow" class="mt-4 text-center text-sm font-medium text-red-500">{{ errorShow }}</div>
    </div>
    <div class="bg-gray-200 shadow-md rounded-lg p-8 max-w-lg w-full">
      <h2 class="text-xl font-semibold text-center text-gray-800 mb-6">History results:</h2>
      <div v-if="historyResults.total" class="space-y-4">
        <pre class="bg-gray-600 p-4 rounded-lg overflow-x-auto">{{ historyResults }}</pre>
      </div>
      <div v-else class="text-center text-gray-500">
        <p>No history available.</p>
      </div>
    </div>
  </section>
</template>
