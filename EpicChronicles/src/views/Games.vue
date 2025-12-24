<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-white mb-4">🏛️ Hall of Legends</h1>
      <p class="text-lg text-gray-400">Test your skills in Viking games and challenges</p>
    </div>

    <!-- Coming Soon Banner -->
    <div class="bg-gradient-to-r from-yellow-400/20 to-orange-500/20 backdrop-blur-lg rounded-lg border border-yellow-400/30 p-8 mb-12 text-center">
      <h2 class="text-2xl font-bold text-yellow-400 mb-4">⚔️ Prepare for Battle!</h2>
      <p class="text-gray-300 mb-6">
        The Hall of Legends is being prepared for your arrival. Soon you'll be able to:
      </p>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl mx-auto">
        <div class="bg-white/5 p-4 rounded-lg">
          <div class="text-3xl mb-2">🎯</div>
          <h3 class="text-white font-bold mb-2">Axe Throwing</h3>
          <p class="text-sm text-gray-400">Test your aim and precision</p>
        </div>
        <div class="bg-white/5 p-4 rounded-lg">
          <div class="text-3xl mb-2">⚡</div>
          <h3 class="text-white font-bold mb-2">Rune Matching</h3>
          <p class="text-sm text-gray-400">Match ancient Norse symbols</p>
        </div>
        <div class="bg-white/5 p-4 rounded-lg">
          <div class="text-3xl mb-2">🗡️</div>
          <h3 class="text-white font-bold mb-2">Sword Combat</h3>
          <p class="text-sm text-gray-400">Duel other warriors</p>
        </div>
        <div class="bg-white/5 p-4 rounded-lg">
          <div class="text-3xl mb-2">🏹</div>
          <h3 class="text-white font-bold mb-2">Archery Challenge</h3>
          <p class="text-sm text-gray-400">Hit the target for rewards</p>
        </div>
        <div class="bg-white/5 p-4 rounded-lg">
          <div class="text-3xl mb-2">🎲</div>
          <h3 class="text-white font-bold mb-2">Viking Dice</h3>
          <p class="text-sm text-gray-400">Traditional Norse games</p>
        </div>
        <div class="bg-white/5 p-4 rounded-lg">
          <div class="text-3xl mb-2">🏆</div>
          <h3 class="text-white font-bold mb-2">Leaderboards</h3>
          <p class="text-sm text-gray-400">Compete for glory</p>
        </div>
      </div>
    </div>

    <!-- Rewards Preview -->
    <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-8">
      <h2 class="text-2xl font-bold text-white mb-6 text-center">Earn Rewards</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center">
          <div class="text-5xl mb-4">🪙</div>
          <h3 class="text-yellow-400 font-bold text-xl mb-2">Tokens</h3>
          <p class="text-gray-400">Win tokens by completing challenges</p>
        </div>
        <div class="text-center">
          <div class="text-5xl mb-4">⚡</div>
          <h3 class="text-blue-400 font-bold text-xl mb-2">Runes</h3>
          <p class="text-gray-400">Collect powerful runes for bonuses</p>
        </div>
        <div class="text-center">
          <div class="text-5xl mb-4">🎖️</div>
          <h3 class="text-purple-400 font-bold text-xl mb-2">Badges</h3>
          <p class="text-gray-400">Unlock achievements and titles</p>
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
// Games functionality will be added here
</script>