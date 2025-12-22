<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <!-- Logo/Brand -->
      <div class="flex justify-center">
        <img :src="logo" alt="Epic Chronicles Logo" class="h-12 w-auto mx-2">
      </div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
        Welcome Back, Warrior
      </h2>
      <p class="mt-2 text-center text-sm text-gray-300">
        Don't have an account?
        <router-link :to="{ name: 'register' }" class="font-medium text-yellow-400 hover:text-yellow-300">
          Join the Brotherhood
        </router-link>
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white/10 backdrop-blur-lg py-8 px-4 shadow-2xl sm:rounded-lg sm:px-10 border border-white/20">
        <!-- Error Message -->
        <div v-if="errorMsg" class="mb-6 rounded-md bg-red-500/20 border border-red-500/50 p-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <svg class="h-5 w-5 text-red-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              <p class="text-sm font-medium text-red-400">{{ errorMsg }}</p>
            </div>
            <button @click="errorMsg = ''" class="text-red-400 hover:text-red-300">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="login" class="space-y-6">
          <!-- Email -->
          <div>
            <label for="email-address" class="block text-sm font-medium text-gray-200">
              Email Address
            </label>
            <div class="mt-1">
              <input
                id="email-address"
                v-model="user.email"
                type="email"
                autocomplete="email"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 bg-white/10 text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm"
                placeholder="ragnar@vikings.com"
              />
            </div>
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-200">
              Password
            </label>
            <div class="mt-1">
              <input
                id="password"
                v-model="user.password"
                type="password"
                autocomplete="current-password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 bg-white/10 text-white focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm"
                placeholder="Enter your password"
              />
            </div>
          </div>

          <!-- Remember Me & Forgot Password -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember-me"
                v-model="user.remember"
                type="checkbox"
                class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-600 rounded bg-white/10"
              />
              <label for="remember-me" class="ml-2 block text-sm text-gray-300">
                Remember me
              </label>
            </div>

            <div class="text-sm">
              <router-link :to="{ name: 'requestPassword' }" class="font-medium text-yellow-400 hover:text-yellow-300">
                Forgot password?
              </router-link>
            </div>
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
              <span v-if="!loading" class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Sign In
              </span>
              <span v-else>Signing in...</span>
            </button>
          </div>
        </form>

        <!-- Divider -->
        <div class="mt-6">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-transparent text-gray-400">Or continue with</span>
            </div>
          </div>

          <!-- Social Login -->
          <div class="mt-6 grid grid-cols-2 gap-3">
            <button
              type="button"
              class="w-full inline-flex justify-center py-2 px-4 border border-gray-600 rounded-md shadow-sm bg-white/10 text-sm font-medium text-gray-300 hover:bg-white/20 transition-colors"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84" />
              </svg>
            </button>

            <button
              type="button"
              class="w-full inline-flex justify-center py-2 px-4 border border-gray-600 rounded-md shadow-sm bg-white/10 text-sm font-medium text-gray-300 hover:bg-white/20 transition-colors"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Sign Up Link -->
        <div class="mt-6">
          <p class="text-center text-sm text-gray-300">
            New to Epic Chronicles?
            <router-link :to="{ name: 'register' }" class="font-medium text-yellow-400 hover:text-yellow-300">
              Create your account
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import logo from '../assets/logo.png';
import { ref } from 'vue'
import store from "../store"
import router from "../router"

const loading = ref(false)
const errorMsg = ref("")

const user = ref({
  email: '',
  password: '',
  remember: false
})

function login() {
  loading.value = true
  errorMsg.value = ''
  
  console.log('Attempting login with:', user.value)
  
  store.dispatch('login', user.value)
    .then(async (data) => { // ✅ Make this async
      console.log('Login success:', data)
      loading.value = false
      
      // ✅ Fetch cart after successful login
      await store.dispatch('cart/fetchCart')
      
      // Redirect based on role
      if (data.is_admin) {
        console.log('User is admin - redirecting to dashboard')
        router.push({ name: 'app.dashboard' })
      } else {
        console.log('User is customer - redirecting to shop')
        router.push({ name: 'shop' })
      }
    })
    .catch((error) => {
      loading.value = false
      
      console.error('Login error:', error)
      console.error('Error response:', error.response)
      
      if (error.response) {
        errorMsg.value = error.response.data.message || 'Login failed'
      } else if (error.request) {
        errorMsg.value = 'No response from server. Is your API running?'
      } else {
        errorMsg.value = error.message || 'Network error. Please try again.'
      }
    })
}
</script>