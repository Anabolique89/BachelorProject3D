<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <!-- Logo/Brand -->
      <div class="flex justify-center">
        <h1 class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">
          ⚔️ EPIC CHRONICLES
        </h1>
      </div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
        Check Your Email
      </h2>
      <p class="mt-2 text-center text-sm text-gray-300">
        We've sent a verification link to your inbox
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white/10 backdrop-blur-lg py-8 px-4 shadow-2xl sm:rounded-lg sm:px-10 border border-white/20">
        <!-- Success Message -->
        <div class="mb-6 rounded-md bg-green-500/20 border border-green-500/50 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-green-400">Account Created Successfully!</h3>
              <div class="mt-2 text-sm text-green-300">
                <p>We've sent a verification email to:</p>
                <p class="font-semibold mt-1">{{ email }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Email Icon -->
        <div class="flex justify-center mb-6">
          <div class="bg-yellow-400/20 rounded-full p-6">
            <svg class="w-16 h-16 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
          </div>
        </div>

        <!-- Instructions -->
        <div class="text-center mb-6">
          <h3 class="text-lg font-medium text-white mb-3">What's Next?</h3>
          <ol class="text-left text-sm text-gray-300 space-y-3">
            <li class="flex items-start">
              <span class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-yellow-400/20 text-yellow-400 font-semibold text-xs mr-3">1</span>
              <span>Open your email inbox</span>
            </li>
            <li class="flex items-start">
              <span class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-yellow-400/20 text-yellow-400 font-semibold text-xs mr-3">2</span>
              <span>Look for an email from Epic Chronicles</span>
            </li>
            <li class="flex items-start">
              <span class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-yellow-400/20 text-yellow-400 font-semibold text-xs mr-3">3</span>
              <span>Click the verification link to activate your account</span>
            </li>
          </ol>
        </div>

        <!-- Resend Section -->
        <div class="border-t border-gray-600 pt-6">
          <p class="text-center text-sm text-gray-300 mb-4">
            Didn't receive the email?
          </p>

          <!-- Success Message for Resend -->
          <div v-if="resendSuccess" class="mb-4 rounded-md bg-green-500/20 border border-green-500/50 p-3">
            <p class="text-sm text-green-400 text-center">✓ Verification email sent!</p>
          </div>

          <!-- Error Message for Resend -->
          <div v-if="resendError" class="mb-4 rounded-md bg-red-500/20 border border-red-500/50 p-3">
            <p class="text-sm text-red-400 text-center">{{ resendError }}</p>
          </div>

          <button
            @click="resendEmail"
            :disabled="resending || countdown > 0"
            class="w-full flex justify-center py-2 px-4 border border-yellow-400/50 rounded-md shadow-sm text-sm font-medium text-yellow-400 bg-yellow-400/10 hover:bg-yellow-400/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
          >
            <svg v-if="resending" class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span v-if="countdown > 0">Resend in {{ countdown }}s</span>
            <span v-else-if="resending">Sending...</span>
            <span v-else>Resend Verification Email</span>
          </button>

          <p class="mt-4 text-center text-xs text-gray-400">
            Check your spam folder if you don't see it
          </p>
        </div>

        <!-- Back to Login -->
        <div class="mt-6 text-center">
          <router-link :to="{ name: 'login' }" class="text-sm font-medium text-yellow-400 hover:text-yellow-300">
            ← Back to Login
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axiosClient from '../axios'

const route = useRoute()

const email = ref(route.query.email || '')
const resending = ref(false)
const resendSuccess = ref(false)
const resendError = ref('')
const countdown = ref(0)

let countdownInterval = null

async function resendEmail() {
  if (!email.value) {
    resendError.value = 'Email address not found'
    return
  }

  resending.value = true
  resendSuccess.value = false
  resendError.value = ''

  try {
    await axiosClient.post('/email/resend', {
      email: email.value
    })

    resendSuccess.value = true
    
    // Start countdown
    countdown.value = 60
    countdownInterval = setInterval(() => {
      countdown.value--
      if (countdown.value <= 0) {
        clearInterval(countdownInterval)
      }
    }, 1000)

    setTimeout(() => {
      resendSuccess.value = false
    }, 5000)

  } catch (error) {
    resendError.value = error.response?.data?.message || 'Failed to resend email. Please try again.'
  } finally {
    resending.value = false
  }
}

onMounted(() => {
  
  countdown.value = 60
  countdownInterval = setInterval(() => {
    countdown.value--
    if (countdown.value <= 0) {
      clearInterval(countdownInterval)
    }
  }, 1000)
})
</script>