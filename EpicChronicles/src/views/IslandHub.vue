<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="text-center">
      <h1 class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500 mb-4">
        🏝️ Island Hub
      </h1>
      <p class="text-xl text-gray-300 mb-8">Your Viking Community Center</p>
      <p class="text-gray-400 max-w-2xl mx-auto">
        This is where Vikings gather! Here you'll find Norse mythology articles, community discussions, quest updates, and legendary tales. Check back soon for epic content!
      </p>
      <router-link 
        :to="{ name: 'shop' }"
        class="mt-6 inline-block px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600"
      >
        Back to Shop
      </router-link>
    </div>
  </div>
</template>