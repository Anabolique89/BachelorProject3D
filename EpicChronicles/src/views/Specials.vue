<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-white mb-4">🛢️ Merchant's Wares</h1>
      <p class="text-lg text-gray-400">Limited time offers and exclusive deals</p>
    </div>

    <!-- Special Offers Banner -->
    <div class="bg-gradient-to-r from-red-500/20 to-orange-500/20 backdrop-blur-lg rounded-lg border border-red-400/30 p-8 mb-12 text-center">
      <h2 class="text-3xl font-bold text-red-400 mb-4">🔥 Flash Sale!</h2>
      <p class="text-xl text-gray-300 mb-4">Up to 50% off select items</p>
      <div class="flex items-center justify-center gap-4 text-2xl font-bold text-white">
        <div class="bg-black/30 px-4 py-2 rounded-lg">
          <span class="text-yellow-400">{{ timeLeft.hours }}</span>
          <p class="text-xs text-gray-400">Hours</p>
        </div>
        <span>:</span>
        <div class="bg-black/30 px-4 py-2 rounded-lg">
          <span class="text-yellow-400">{{ timeLeft.minutes }}</span>
          <p class="text-xs text-gray-400">Minutes</p>
        </div>
        <span>:</span>
        <div class="bg-black/30 px-4 py-2 rounded-lg">
          <span class="text-yellow-400">{{ timeLeft.seconds }}</span>
          <p class="text-xs text-gray-400">Seconds</p>
        </div>
      </div>
    </div>

    <!-- Deal Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
      <!-- Daily Deals -->
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 hover:border-yellow-400/50 transition-all cursor-pointer">
        <div class="text-4xl mb-4 text-center">⭐</div>
        <h3 class="text-xl font-bold text-yellow-400 mb-3 text-center">Daily Deals</h3>
        <p class="text-gray-400 text-center mb-4">New deals every 24 hours</p>
        <div class="text-center">
          <span class="inline-block bg-yellow-400/20 text-yellow-400 px-3 py-1 rounded-full text-sm font-bold">
            Save up to 30%
          </span>
        </div>
      </div>

      <!-- Bundle Offers -->
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 hover:border-blue-400/50 transition-all cursor-pointer">
        <div class="text-4xl mb-4 text-center">📦</div>
        <h3 class="text-xl font-bold text-blue-400 mb-3 text-center">Bundle Deals</h3>
        <p class="text-gray-400 text-center mb-4">Buy more, save more</p>
        <div class="text-center">
          <span class="inline-block bg-blue-400/20 text-blue-400 px-3 py-1 rounded-full text-sm font-bold">
            Save up to 40%
          </span>
        </div>
      </div>

      <!-- Clearance Sale -->
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 hover:border-red-400/50 transition-all cursor-pointer">
        <div class="text-4xl mb-4 text-center">🔥</div>
        <h3 class="text-xl font-bold text-red-400 mb-3 text-center">Clearance</h3>
        <p class="text-gray-400 text-center mb-4">Last chance items</p>
        <div class="text-center">
          <span class="inline-block bg-red-400/20 text-red-400 px-3 py-1 rounded-full text-sm font-bold">
            Save up to 60%
          </span>
        </div>
      </div>
    </div>

    <!-- Coming Soon Products -->
    <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-8 mb-12">
      <h2 class="text-2xl font-bold text-white mb-6 text-center">Special Offers Coming Soon</h2>
      <p class="text-gray-400 text-center mb-8">
        The merchant is preparing exclusive deals for you. Check back soon!
      </p>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
        <div class="bg-white/5 p-6 rounded-lg border border-white/10">
          <h3 class="text-lg font-bold text-white mb-2">🎁 Token Bundles</h3>
          <p class="text-sm text-gray-400 mb-3">Purchase tokens at discounted rates</p>
          <p class="text-yellow-400 font-bold">Coming Soon</p>
        </div>
        <div class="bg-white/5 p-6 rounded-lg border border-white/10">
          <h3 class="text-lg font-bold text-white mb-2">⚡ Rune Packs</h3>
          <p class="text-sm text-gray-400 mb-3">Mystery rune collections with bonuses</p>
          <p class="text-blue-400 font-bold">Coming Soon</p>
        </div>
        <div class="bg-white/5 p-6 rounded-lg border border-white/10">
          <h3 class="text-lg font-bold text-white mb-2">🎯 Starter Kits</h3>
          <p class="text-sm text-gray-400 mb-3">Perfect for new Vikings beginning their journey</p>
          <p class="text-purple-400 font-bold">Coming Soon</p>
        </div>
        <div class="bg-white/5 p-6 rounded-lg border border-white/10">
          <h3 class="text-lg font-bold text-white mb-2">👑 VIP Membership</h3>
          <p class="text-sm text-gray-400 mb-3">Exclusive perks and monthly rewards</p>
          <p class="text-orange-400 font-bold">Coming Soon</p>
        </div>
      </div>
    </div>

    <!-- Newsletter Signup -->
    <div class="bg-gradient-to-r from-yellow-400/20 to-orange-500/20 backdrop-blur-lg rounded-lg border border-yellow-400/30 p-8 text-center">
      <h2 class="text-2xl font-bold text-white mb-4">Never Miss a Deal!</h2>
      <p class="text-gray-300 mb-6">Get notified about exclusive offers and flash sales</p>
      <div class="max-w-md mx-auto flex gap-3">
        <input
          type="email"
          placeholder="your.email@valhalla.com"
          class="flex-1 px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500"
        />
        <button class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 transition-all">
          Subscribe
        </button>
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
import { ref, onMounted, onUnmounted } from 'vue'

const timeLeft = ref({
  hours: 23,
  minutes: 59,
  seconds: 59
})

let countdownInterval = null

onMounted(() => {
  startCountdown()
})

onUnmounted(() => {
  if (countdownInterval) {
    clearInterval(countdownInterval)
  }
})

function startCountdown() {
  countdownInterval = setInterval(() => {
    if (timeLeft.value.seconds > 0) {
      timeLeft.value.seconds--
    } else {
      if (timeLeft.value.minutes > 0) {
        timeLeft.value.minutes--
        timeLeft.value.seconds = 59
      } else {
        if (timeLeft.value.hours > 0) {
          timeLeft.value.hours--
          timeLeft.value.minutes = 59
          timeLeft.value.seconds = 59
        } else {
          // Reset to 24 hours
          timeLeft.value = {
            hours: 23,
            minutes: 59,
            seconds: 59
          }
        }
      }
    }
  }, 1000)
}
</script>