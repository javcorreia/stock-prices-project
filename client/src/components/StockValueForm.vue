<script setup>
import {reactive, ref} from 'vue'
import axios from 'axios'

const errorShow = ref('')
const symbol = ref('')
const stockData = reactive({})

const handleSubmit = async () => {
  try {
    const token = sessionStorage.getItem('token')

    const response = await axios.get('http://127.0.0.1:8001/api/stock', {
      params: {
        q: symbol.value,
      },
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`
      }
    })

    Object.assign(stockData, response.data)
  } catch (error) {
    console.error(error.response.data)
    errorShow.value = error.response.data.message
  }
}

</script>

<template>
  <section class="flex items-center justify-center min-h-screen ">
    <div class="bg-gray-500 shadow-md rounded-lg p-8 max-w-md w-full">
      <h1 class="text-2xl font-semibold text-center text-gray-800 mb-6">Check Stock</h1>
      <form @submit.prevent="handleSubmit">
        <div>
          <label for="symbol" class="block text-sm font-medium text-gray-700">Stock Symbol</label>
          <input
              id="symbol"
              type="text"
              v-model="symbol"
              required
              placeholder="Enter stock symbol (e.g. IBM)"
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
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
      <h2 class="text-xl font-semibold text-center text-gray-800 mb-6">Stock Data:</h2>
      <div v-if="stockData.name" class="space-y-4">
        <div>
          <p class="text-gray-800">Name: {{ stockData.name }}</p>
        </div>
        <div>
          <p class="text-gray-800">Symbol: {{ stockData.symbol }}</p>
        </div>
        <div>
          <p class="text-gray-800">Open: {{ stockData.open }}</p>
        </div>
        <div>
          <p class="text-gray-800">High: {{ stockData.high }}</p>
        </div>
        <div>
          <p class="text-gray-800">Low: {{ stockData.low }}</p>
        </div>
        <div>
          <p class="text-gray-800">Close: {{ stockData.close }}</p>
        </div>
      </div>
      <div v-else class="text-center text-gray-500">
        <p>No stock data available.</p>
      </div>
    </div>
  </section>
</template>
