<script setup>
import {ref} from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const router = useRouter();
const error = ref('');

const handleSubmit = async () => {
    try {
        const response = await axios.post('http://127.0.0.1:8001/api/login_check', {
            email: email.value,
            password: password.value,
        }, {
            headers: {
                'Content-Type': 'application/json',
            }
        });

        const {token} = response.data;
        document.cookie = `jwt=${token}; path=/; httpOnly;`;
        await router.push('/');
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
    <div class="login">
        <form @submit.prevent="handleSubmit">
            <div>
                <label for="email">Email:</label>
                <input
                    id="email"
                    type="email"
                    v-model="email"
                    required
                />
            </div>
            <div>
                <label for="password">Password:</label>
                <input
                    id="password"
                    type="password"
                    v-model="password"
                    required
                />
            </div>
            <button type="submit">Login</button>
        </form>
        <div v-if="error" class="error">{{ error }}</div>
    </div>
</template>

<style>
  @media (min-width: 1024px) {
    .login {
      min-height: 100vh;
      display: flex;
      align-items: center;
    }
  }
</style>
