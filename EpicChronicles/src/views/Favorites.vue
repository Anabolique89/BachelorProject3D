<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="text-center">
      <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
      </svg>
      <h2 class="mt-4 text-3xl font-bold text-white">Your Favorites</h2>
      <p class="mt-2 text-gray-400">Save items you love! This feature is coming soon.</p>
      <router-link 
        :to="{ name: 'shop' }"
        class="mt-6 inline-block px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600"
      >
        Browse Products
      </router-link>
    </div>
  </div>
</template>