<template>
  <!-- Stage 1: Welcome + Shop -->
  <div v-if="currentStage === 1" class="bg-gradient-to-r from-yellow-400/20 to-orange-500/20 backdrop-blur-sm rounded-lg border border-yellow-400/30 p-4 mx-5">
    <!-- Desktop: Horizontal -->
    <div class="hidden sm:flex items-center justify-between gap-4">
      <div class="flex-1">
        <h2 class="text-lg font-bold text-white mb-1">⚔️ Welcome, Warrior!</h2>
        <p class="text-sm text-gray-900">Explore authentic Viking gear & Norse mythology</p>
      </div>
      <router-link 
        :to="{ name: 'shop' }" 
        class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 rounded-lg text-black font-bold text-sm transition-all whitespace-nowrap"
      >
        Shop Now →
      </router-link>
    </div>

    <!-- Mobile: Stacked -->
    <div class="sm:hidden">
      <h2 class="text-base font-bold text-white mb-1">⚔️ Welcome, Warrior!</h2>
      <p class="text-xs text-gray-900 mb-3">Explore authentic Viking gear & Norse mythology</p>
      <router-link 
        :to="{ name: 'shop' }" 
        class="block text-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 rounded-lg text-black font-bold text-sm transition-all"
      >
        Shop Now →
      </router-link>
    </div>
  </div>

  <!-- Stage 2: Quests + Games -->
  <div v-else-if="currentStage === 2" class="bg-gradient-to-r from-purple-400/20 to-blue-500/20 backdrop-blur-sm rounded-lg border border-purple-400/30 p-4 mx-5">
    <!-- Desktop: Horizontal -->
    <div class="hidden sm:flex items-center justify-between gap-4">
      <div class="flex-1">
        <h2 class="text-lg font-bold text-white mb-1">🪙 Complete Quests & Earn Tokens</h2>
        <p class="text-sm text-gray-900">Unlock rewards by completing challenges</p>
      </div>
      <router-link 
        :to="{ name: 'games' }" 
        class="px-4 py-2 bg-purple-400 hover:bg-purple-500 rounded-lg text-white font-bold text-sm transition-all whitespace-nowrap"
      >
        View Quests →
      </router-link>
    </div>

    <!-- Mobile: Stacked -->
    <div class="sm:hidden">
      <h2 class="text-base font-bold text-white mb-1">🪙 Complete Quests & Earn Tokens</h2>
      <p class="text-xs text-gray-900 mb-3">Unlock rewards by completing challenges</p>
      <router-link 
        :to="{ name: 'games' }" 
        class="block text-center px-4 py-2 bg-purple-400 hover:bg-purple-500 rounded-lg text-white font-bold text-sm transition-all"
      >
        View Quests →
      </router-link>
    </div>
  </div>

  <!-- Stage 3: Treasury -->
  <div v-else-if="currentStage === 3" class="bg-gradient-to-r from-green-400/20 to-emerald-500/20 backdrop-blur-sm rounded-lg border border-green-400/30 p-4 mx-5">
    <!-- Desktop: Horizontal -->
    <div class="hidden sm:flex items-center justify-between gap-4">
      <div class="flex-1">
        <h2 class="text-lg font-bold text-white mb-1">💰 Your Viking Treasury</h2>
        <p class="text-sm text-gray-900">Track your tokens, orders & discounts</p>
      </div>
      <router-link 
        :to="{ name: 'treasury' }" 
        class="px-4 py-2 bg-green-400 hover:bg-green-500 rounded-lg text-black font-bold text-sm transition-all whitespace-nowrap"
      >
        View Treasury →
      </router-link>
    </div>

    <!-- Mobile: Stacked -->
    <div class="sm:hidden">
      <h2 class="text-base font-bold text-white mb-1">💰 Your Viking Treasury</h2>
      <p class="text-xs text-gray-900 mb-3">Track your tokens, orders & discounts</p>
      <router-link 
        :to="{ name: 'treasury' }" 
        class="block text-center px-4 py-2 bg-green-400 hover:bg-green-500 rounded-lg text-black font-bold text-sm transition-all"
      >
        View Treasury →
      </router-link>
    </div>
  </div>

  <!-- Stage 4: Contact -->
  <div v-else-if="currentStage === 4" class="bg-gradient-to-r from-blue-400/20 to-cyan-500/20 backdrop-blur-sm rounded-lg border border-blue-400/30 p-4 mx-5">
    <!-- Desktop: Horizontal -->
    <div class="hidden sm:flex items-center justify-between gap-4">
      <div class="flex-1">
        <h2 class="text-lg font-bold text-white mb-1">💬 Need Help?</h2>
        <p class="text-sm text-gray-900">We're here to answer your questions</p>
      </div>
      <router-link 
        :to="{ name: 'contact' }" 
        class="px-4 py-2 bg-blue-400 hover:bg-blue-500 rounded-lg text-white font-bold text-sm transition-all whitespace-nowrap"
      >
        Contact Us →
      </router-link>
    </div>

    <!-- Mobile: Stacked -->
    <div class="sm:hidden">
      <h2 class="text-base font-bold text-white mb-1">💬 Need Help?</h2>
      <p class="text-xs text-gray-900 mb-3">We're here to answer your questions</p>
      <router-link 
        :to="{ name: 'contact' }" 
        class="block text-center px-4 py-2 bg-blue-400 hover:bg-blue-500 rounded-lg text-white font-bold text-sm transition-all"
      >
        Contact Us →
      </router-link>
    </div>
  </div>
</template>

<script setup>
defineProps({
  currentStage: {
    type: Number,
    default: null
  }
})
</script>