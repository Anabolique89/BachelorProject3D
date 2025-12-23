<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-2xl text-center">
      <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-yellow-500/20 mb-6">
        <svg class="h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-white mb-4">Payment Cancelled</h1>
      <p class="text-lg text-gray-400 mb-8">
        Your payment was cancelled. Your order has been saved and is waiting for payment.
      </p>
      
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <router-link
          :to="{ name: 'cart' }"
          class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 transition-all"
        >
          Return to Cart
        </router-link>
        <router-link
          :to="{ name: 'shop' }"
          class="inline-flex items-center justify-center px-6 py-3 border border-white/20 rounded-lg text-white hover:bg-white/10 transition-colors"
        >
          Continue Shopping
        </router-link>
      </div>
    </div>
  </div>
</template>