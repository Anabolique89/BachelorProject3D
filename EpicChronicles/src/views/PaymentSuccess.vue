<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-2xl text-center">
      <!-- Loading State -->
      <div v-if="loading" class="py-20">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-400/20 mb-4">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-400"></div>
        </div>
        <h2 class="text-2xl font-bold text-white">Confirming your payment...</h2>
        <p class="mt-2 text-gray-400">Please wait while we process your order.</p>
      </div>

      <!-- Success State -->
      <div v-else-if="success">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-500/20 mb-6">
          <svg class="h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-white mb-4">Payment Successful!</h1>
        <p class="text-lg text-gray-400 mb-8">
          Thank you for your order! We're preparing your Viking gear for shipment.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            :to="{ name: 'order-detail', params: { id: orderId } }"
            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 transition-all"
          >
            View Order Details
          </router-link>
          <router-link
            :to="{ name: 'shop' }"
            class="inline-flex items-center justify-center px-6 py-3 border border-white/20 rounded-lg text-white hover:bg-white/10 transition-colors"
          >
            Continue Shopping
          </router-link>
        </div>
      </div>

      <!-- Error State -->
      <div v-else>
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-500/20 mb-6">
          <svg class="h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-white mb-4">Payment Verification Failed</h1>
        <p class="text-lg text-gray-400 mb-8">
          We couldn't verify your payment. Please check your orders or contact support.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            :to="{ name: 'orders' }"
            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 transition-all"
          >
            View My Orders
          </router-link>
          <router-link
            :to="{ name: 'shop' }"
            class="inline-flex items-center justify-center px-6 py-3 border border-white/20 rounded-lg text-white hover:bg-white/10 transition-colors"
          >
            Back to Shop
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useStore } from 'vuex'
import axiosClient from '../axios'

const route = useRoute()
const store = useStore() // ✅ Add this

const loading = ref(true)
const success = ref(false)
const orderId = ref(null)

onMounted(async () => {
  const sessionId = route.query.session_id

  if (!sessionId) {
    loading.value = false
    success.value = false
    return
  }

  try {
    // Verify payment with backend
    const response = await axiosClient.get(`/stripe/verify-session/${sessionId}`)
    
    if (response.data.success) {
      success.value = true
      orderId.value = response.data.order_id
      
      //Clear cart after successful payment
      await store.dispatch('cart/clearCart')
      
    } else {
      success.value = false
    }
  } catch (error) {
    console.error('Payment verification failed:', error)
    success.value = false
  } finally {
    loading.value = false
  }
})
</script>