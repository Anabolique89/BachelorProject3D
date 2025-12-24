<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-white mb-4">📜 Odin's Call</h1>
      <p class="text-lg text-gray-400">Accept quests and earn legendary rewards</p>
    </div>

    <!-- Quest Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
        <div class="text-3xl mb-2">📜</div>
        <h3 class="text-2xl font-bold text-yellow-400">{{ activeQuests }}</h3>
        <p class="text-gray-400">Active Quests</p>
      </div>
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
        <div class="text-3xl mb-2">✅</div>
        <h3 class="text-2xl font-bold text-green-400">{{ completedQuests }}</h3>
        <p class="text-gray-400">Completed</p>
      </div>
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
        <div class="text-3xl mb-2">🪙</div>
        <h3 class="text-2xl font-bold text-yellow-400">{{ tokensEarned }}</h3>
        <p class="text-gray-400">Tokens Earned</p>
      </div>
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
        <div class="text-3xl mb-2">⚡</div>
        <h3 class="text-2xl font-bold text-blue-400">{{ runesEarned }}</h3>
        <p class="text-gray-400">Runes Earned</p>
      </div>
    </div>

    <!-- Coming Soon -->
    <div class="bg-gradient-to-r from-purple-500/20 to-pink-500/20 backdrop-blur-lg rounded-lg border border-purple-400/30 p-8 mb-12">
      <h2 class="text-2xl font-bold text-purple-400 mb-4 text-center">The Quest Board is Being Prepared</h2>
      <p class="text-gray-300 text-center mb-6">
        Soon the All-Father will send you on epic quests across the realm!
      </p>
    </div>

    <!-- Quest Types Preview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
      <!-- Daily Quests -->
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl">☀️</span>
          <h3 class="text-xl font-bold text-yellow-400">Daily Quests</h3>
        </div>
        <p class="text-gray-400 mb-4">Complete simple tasks every day for quick rewards</p>
        <ul class="space-y-2 text-sm text-gray-300">
          <li>✓ Visit the shop</li>
          <li>✓ Check your profile</li>
          <li>✓ Play a game</li>
          <li>✓ Login for 7 days straight</li>
        </ul>
        <div class="mt-4 pt-4 border-t border-white/10">
          <p class="text-xs text-gray-500">Rewards: 10-50 tokens</p>
        </div>
      </div>

      <!-- Weekly Quests -->
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl">📅</span>
          <h3 class="text-xl font-bold text-blue-400">Weekly Quests</h3>
        </div>
        <p class="text-gray-400 mb-4">Bigger challenges with greater rewards</p>
        <ul class="space-y-2 text-sm text-gray-300">
          <li>✓ Make 3 purchases</li>
          <li>✓ Complete 5 daily quests</li>
          <li>✓ Collect 10 runes</li>
          <li>✓ Reach level 5</li>
        </ul>
        <div class="mt-4 pt-4 border-t border-white/10">
          <p class="text-xs text-gray-500">Rewards: 100-300 tokens, 1-3 runes</p>
        </div>
      </div>

      <!-- Epic Quests -->
      <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl">⚔️</span>
          <h3 class="text-xl font-bold text-purple-400">Epic Quests</h3>
        </div>
        <p class="text-gray-400 mb-4">Legendary challenges for true warriors</p>
        <ul class="space-y-2 text-sm text-gray-300">
          <li>✓ Spend $500 total</li>
          <li>✓ Complete 100 quests</li>
          <li>✓ Collect full rune set</li>
          <li>✓ Reach max level</li>
        </ul>
        <div class="mt-4 pt-4 border-t border-white/10">
          <p class="text-xs text-gray-500">Rewards: 500-1000 tokens, rare runes, exclusive badge</p>
        </div>
      </div>
    </div>

    <!-- How Quests Work -->
    <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-8">
      <h2 class="text-2xl font-bold text-white mb-6 text-center">How Quests Work</h2>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
        <div class="text-center">
          <div class="w-12 h-12 rounded-full bg-yellow-400/20 flex items-center justify-center mx-auto mb-3">
            <span class="text-2xl">1️⃣</span>
          </div>
          <h3 class="font-bold text-white mb-2">Accept Quest</h3>
          <p class="text-sm text-gray-400">Choose from available quests</p>
        </div>
        <div class="text-center">
          <div class="w-12 h-12 rounded-full bg-blue-400/20 flex items-center justify-center mx-auto mb-3">
            <span class="text-2xl">2️⃣</span>
          </div>
          <h3 class="font-bold text-white mb-2">Complete Tasks</h3>
          <p class="text-sm text-gray-400">Finish required objectives</p>
        </div>
        <div class="text-center">
          <div class="w-12 h-12 rounded-full bg-purple-400/20 flex items-center justify-center mx-auto mb-3">
            <span class="text-2xl">3️⃣</span>
          </div>
          <h3 class="font-bold text-white mb-2">Track Progress</h3>
          <p class="text-sm text-gray-400">Monitor your advancement</p>
        </div>
        <div class="text-center">
          <div class="w-12 h-12 rounded-full bg-green-400/20 flex items-center justify-center mx-auto mb-3">
            <span class="text-2xl">4️⃣</span>
          </div>
          <h3 class="font-bold text-white mb-2">Claim Rewards</h3>
          <p class="text-sm text-gray-400">Receive tokens and runes</p>
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
const activeQuests = ref(0)
const completedQuests = ref(0)
const tokensEarned = ref(0)
const runesEarned = ref(0)
</script>