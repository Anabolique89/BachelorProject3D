<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-white mb-4">⚡ Seer's Circle</h1>
      <p class="text-lg text-gray-400">Discover the power of ancient Norse runes</p>
    </div>

    <!-- User's Rune Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
        <div class="text-4xl mb-2">⚡</div>
        <h3 class="text-2xl font-bold text-yellow-400">{{ runesCollected }}</h3>
        <p class="text-gray-400">Runes Collected</p>
      </div>
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
        <div class="text-4xl mb-2">🔮</div>
        <h3 class="text-2xl font-bold text-blue-400">{{ totalRunePower }}</h3>
        <p class="text-gray-400">Total Rune Power</p>
      </div>
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
        <div class="text-4xl mb-2">📿</div>
        <h3 class="text-2xl font-bold text-purple-400">{{ completedSets }}</h3>
        <p class="text-gray-400">Completed Sets</p>
      </div>
    </div>

    <!-- Rune Collection Coming Soon -->
    <div class="bg-gradient-to-r from-blue-500/20 to-purple-500/20 backdrop-blur-lg rounded-lg border border-blue-400/30 p-8 mb-12">
      <h2 class="text-2xl font-bold text-blue-400 mb-4 text-center">The Elder Futhark Awaits</h2>
      <p class="text-gray-300 text-center mb-6">
        Soon you'll be able to collect all 24 runes of the Elder Futhark alphabet. Each rune grants unique powers and bonuses!
      </p>

      <!-- Sample Runes Preview -->
      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 max-w-4xl mx-auto">
        <div v-for="rune in sampleRunes" :key="rune.name" class="bg-white/10 rounded-lg p-4 text-center border border-white/20">
          <div class="text-3xl mb-2">{{ rune.symbol }}</div>
          <p class="text-xs text-gray-300 font-bold">{{ rune.name }}</p>
          <p class="text-xs text-gray-500">{{ rune.meaning }}</p>
        </div>
      </div>
    </div>

    <!-- How to Earn Runes -->
    <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-8 mb-12">
      <h2 class="text-2xl font-bold text-white mb-6 text-center">How to Earn Runes</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="text-center">
          <div class="text-4xl mb-3">🛍️</div>
          <h3 class="text-yellow-400 font-bold mb-2">Make Purchases</h3>
          <p class="text-sm text-gray-400">Earn runes with every order</p>
        </div>
        <div class="text-center">
          <div class="text-4xl mb-3">📜</div>
          <h3 class="text-yellow-400 font-bold mb-2">Complete Quests</h3>
          <p class="text-sm text-gray-400">Quest rewards include runes</p>
        </div>
        <div class="text-center">
          <div class="text-4xl mb-3">🎮</div>
          <h3 class="text-yellow-400 font-bold mb-2">Play Games</h3>
          <p class="text-sm text-gray-400">Win runes in challenges</p>
        </div>
        <div class="text-center">
          <div class="text-4xl mb-3">🎁</div>
          <h3 class="text-yellow-400 font-bold mb-2">Daily Login</h3>
          <p class="text-sm text-gray-400">Get daily rune rewards</p>
        </div>
      </div>
    </div>

    <!-- Rune Powers Info -->
    <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-8">
      <h2 class="text-2xl font-bold text-white mb-6 text-center">Rune Powers</h2>
      <div class="space-y-4 max-w-2xl mx-auto">
        <div class="flex items-start gap-4 p-4 bg-white/5 rounded-lg">
          <span class="text-2xl">💰</span>
          <div>
            <h3 class="text-yellow-400 font-bold">Discount Runes</h3>
            <p class="text-sm text-gray-400">Use runes to get discounts on purchases</p>
          </div>
        </div>
        <div class="flex items-start gap-4 p-4 bg-white/5 rounded-lg">
          <span class="text-2xl">⬆️</span>
          <div>
            <h3 class="text-blue-400 font-bold">Level Boost Runes</h3>
            <p class="text-sm text-gray-400">Accelerate your Viking level progression</p>
          </div>
        </div>
        <div class="flex items-start gap-4 p-4 bg-white/5 rounded-lg">
          <span class="text-2xl">🎁</span>
          <div>
            <h3 class="text-purple-400 font-bold">Bonus Runes</h3>
            <p class="text-sm text-gray-400">Unlock exclusive products and rewards</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Back Button -->
    <div class="mt-12 text-center">
      <router-link
        :to="{ name: 'island-hub' }"
        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 transition-all"
      >
        ← Return to Island Hub
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Placeholder stats
const runesCollected = ref(0)
const totalRunePower = ref(0)
const completedSets = ref(0)

// Sample runes from Elder Futhark
const sampleRunes = ref([
  { name: 'Fehu', symbol: 'ᚠ', meaning: 'Wealth' },
  { name: 'Uruz', symbol: 'ᚢ', meaning: 'Strength' },
  { name: 'Thurisaz', symbol: 'ᚦ', meaning: 'Protection' },
  { name: 'Ansuz', symbol: 'ᚨ', meaning: 'Wisdom' },
  { name: 'Raido', symbol: 'ᚱ', meaning: 'Journey' },
  { name: 'Kenaz', symbol: 'ᚲ', meaning: 'Knowledge' },
])
</script>