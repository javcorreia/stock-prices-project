<script setup>
import {ref} from 'vue'
import axios from 'axios'

const stock = ref('')

const checkStock = () => {
    try {

    } catch (error) {
        console.error(error)
    }
}
</script>

<template>
    <main>
        <form @submit.prevent="checkStock">
            <div>
                <label for="stock">Stock Symbol:</label>
                <input type="text" id="stock" v-model="stock" required/>
            </div>
            <button type="submit">Check Stock</button>
        </form>
    </main>
</template>
