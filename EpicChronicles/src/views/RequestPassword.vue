<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <!-- Logo/Brand -->
      <div class="flex justify-center">
        <div class="flex justify-center">
        <img :src="logo" alt="Epic Chronicles Logo" class="h-12 w-auto mx-2">
      </div>
      </div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
        Reset Your Password
      </h2>
      <p class="mt-2 text-center text-sm text-gray-300">
        Remember your password?
        <router-link :to="{ name: 'login' }" class="font-medium text-yellow-400 hover:text-yellow-300">
          Sign in here
        </router-link>
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white/10 backdrop-blur-lg py-8 px-4 shadow-2xl sm:rounded-lg sm:px-10 border border-white/20">
        <!-- Success Message -->
        <div v-if="successMessage" class="mb-6 rounded-md bg-green-500/20 border border-green-500/50 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-400">{{ successMessage }}</p>
            </div>
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="mb-6 rounded-md bg-red-500/20 border border-red-500/50 p-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <svg class="h-5 w-5 text-red-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              <p class="text-sm font-medium text-red-400">{{ errorMessage }}</p>
            </div>
            <button @click="errorMessage = ''" class="text-red-400 hover:text-red-300">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Request Password Form -->
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-200">
              Email Address
            </label>
            <div class="mt-1">
              <input
                id="email"
                v-model="email"
                type="email"
                autocomplete="email"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 bg-white/10 text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm"
                placeholder="ragnar@vikings.com"
              />
            </div>
            <p class="mt-2 text-xs text-gray-400">
              Enter your email and we'll send you instructions to reset your password
            </p>
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              :disabled="loading"
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-900 bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
            >
              <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-900" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ loading ? 'Sending...' : 'Send Reset Instructions' }}
            </button>
          </div>
        </form>

        <!-- Back to Login -->
        <div class="mt-6">
          <p class="text-center text-sm text-gray-300">
            <router-link :to="{ name: 'login' }" class="font-medium text-yellow-400 hover:text-yellow-300">
              ← Back to Login
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axiosClient from '../axios'

const email = ref('')
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
 import logo from '../assets/logo.png';

async function handleSubmit() {
  loading.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    const response = await axiosClient.post('/forgot-password', {
      email: email.value
    })

    successMessage.value = 'Password reset instructions have been sent to your email!'
    email.value = '' // Clear form

  } catch (error) {
    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = 'Failed to send reset instructions. Please try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>