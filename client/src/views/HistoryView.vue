<script setup>
import {ref} from 'vue'
import axios from 'axios'

const stock = ref('')

const getRequestHistory = () => {
    try {

    } catch (error) {
        console.error(error)
    }
}
</script>

<template>
    <div class="history">
        <h1>Show history requests</h1>
        <button type="submit" @click="getRequestHistory">Get Request History</button>
    </div>
</template>

<style>
@media (min-width: 1024px) {
    .history {
        min-height: 100vh;
        display: flex;
        align-items: center;
    }
}
</style>
