<template>
  <div class="min-h-screen bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-white mb-4">💰 Your Treasury</h1>
        <p class="text-lg text-gray-400">Orders, Discounts & Viking Level Progress</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-20">
        <div class="animate-spin text-6xl mb-4">⚔️</div>
        <p class="text-gray-400 text-xl">Loading your treasury...</p>
      </div>

      <!-- Main Content -->
      <div v-else>
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
            <div class="text-4xl mb-2">🪙</div>
            <h3 class="text-2xl font-bold text-yellow-400">{{ totalTokens }}</h3>
            <p class="text-gray-400">Total Tokens</p>
          </div>
          
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
            <div class="text-4xl mb-2">⚡</div>
            <h3 class="text-2xl font-bold text-blue-400">{{ runesCollected }}</h3>
            <p class="text-gray-400">Runes Collected</p>
          </div>
          
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
            <div class="text-4xl mb-2">📦</div>
            <h3 class="text-2xl font-bold text-purple-400">{{ totalOrders }}</h3>
            <p class="text-gray-400">Total Orders</p>
          </div>
          
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 text-center">
            <div class="text-4xl mb-2">💎</div>
            <h3 class="text-2xl font-bold text-green-400">${{ totalSaved.toFixed(2) }}</h3>
            <p class="text-gray-400">Total Saved</p>
          </div>
        </div>

        <!-- Viking Level Progress -->
        <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-8 mb-12">
          <h2 class="text-2xl font-bold text-white mb-6">Viking Level Progress</h2>
          
          <div class="flex items-center gap-4 mb-4">
            <div class="text-6xl">⚔️</div>
            <div class="flex-1">
              <div class="flex justify-between mb-2">
                <span class="text-yellow-400 font-bold text-xl">{{ currentLevel }}</span>
                <span class="text-gray-400">{{ tokensToNextLevel }} tokens to next level</span>
              </div>
              <div class="w-full bg-gray-700 rounded-full h-4">
                <div 
                  class="bg-gradient-to-r from-yellow-400 to-orange-500 h-4 rounded-full transition-all"
                  :style="{ width: levelProgress + '%' }"
                ></div>
              </div>
            </div>
          </div>
          
          <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-6">
            <div v-for="level in levels" :key="level.name" 
              class="text-center p-3 rounded-lg"
              :class="level.achieved ? 'bg-yellow-400/20 border border-yellow-400' : 'bg-gray-800 border border-gray-700'"
            >
              <div class="text-2xl mb-1">{{ level.icon }}</div>
              <p class="text-sm font-bold" :class="level.achieved ? 'text-yellow-400' : 'text-gray-500'">
                {{ level.name }}
              </p>
            </div>
          </div>
        </div>

        <!-- Active Discounts -->
        <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-8 mb-12">
          <h2 class="text-2xl font-bold text-white mb-6">Active Discounts</h2>
          
          <div v-if="activeDiscounts.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="discount in activeDiscounts" :key="discount.code" 
              class="bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-400/30 rounded-lg p-4"
            >
              <div class="flex justify-between items-start mb-2">
                <div>
                  <h3 class="text-lg font-bold text-purple-400">{{ discount.code }}</h3>
                  <p class="text-sm text-gray-300">{{ discount.description }}</p>
                </div>
                <span class="text-2xl font-bold text-yellow-400">{{ discount.value }}%</span>
              </div>
              <p class="text-xs text-gray-500">Expires: {{ discount.expiresAt }}</p>
            </div>
          </div>
          
          <div v-else class="text-center py-8 text-gray-400">
            <p class="mb-4">No active discounts yet.</p>
            <p class="text-sm">Complete quests to earn discount rewards!</p>
            <router-link :to="{ name: 'quests' }" 
              class="inline-block mt-4 px-6 py-3 bg-purple-500 hover:bg-purple-600 rounded-lg font-bold transition-colors"
            >
              View Quests
            </router-link>
          </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Recent Orders</h2>
            <router-link :to="{ name: 'orders' }" 
              class="text-yellow-400 hover:text-yellow-500 font-bold"
            >
              View All →
            </router-link>
          </div>
          
          <div v-if="recentOrders.length > 0" class="space-y-4">
            <div v-for="order in recentOrders" :key="order.id" 
              class="bg-white/5 border border-white/10 rounded-lg p-4 hover:bg-white/10 transition-colors cursor-pointer"
              @click="$router.push({ name: 'order-details', params: { id: order.id } })"
            >
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-bold text-white">Order #{{ order.order_number }}</h3>
                  <p class="text-sm text-gray-400">{{ order.created_at }}</p>
                </div>
                <div class="text-right">
                  <p class="font-bold text-yellow-400">${{ order.total_price }}</p>
                  <span class="text-xs px-2 py-1 rounded-full"
                    :class="{
                      'bg-yellow-400/20 text-yellow-400': order.status === 'pending',
                      'bg-blue-400/20 text-blue-400': order.status === 'processing',
                      'bg-green-400/20 text-green-400': order.status === 'delivered'
                    }"
                  >
                    {{ order.status }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-8 text-gray-400">
            <p class="mb-4">No orders yet</p>
            <router-link :to="{ name: 'shop' }" 
              class="inline-block px-6 py-3 bg-yellow-400 text-gray-900 rounded-lg font-bold hover:bg-yellow-500 transition-colors"
            >
              Start Shopping
            </router-link>
          </div>
        </div>

        <!-- Back Button -->
        <div class="mt-12 text-center">
          <router-link
            :to="{ name: 'shop' }"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 transition-all"
          >
            ← Return to Shop
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axiosClient from '../axios'

const loading = ref(true)
const totalTokens = ref(0)
const runesCollected = ref(0)
const totalOrders = ref(0)
const totalSaved = ref(0)
const currentLevel = ref('Thrall')
const tokensToNextLevel = ref(500)
const levelProgress = ref(0)
const activeDiscounts = ref([])
const recentOrders = ref([])

const levels = ref([
  { name: 'Thrall', icon: '⚔️', achieved: true },
  { name: 'Karl', icon: '🛡️', achieved: false },
  { name: 'Jarl', icon: '👑', achieved: false },
  { name: 'Hirdman', icon: '⚡', achieved: false },
  { name: 'Hersir', icon: '🔱', achieved: false },
])

onMounted(async () => {
  await fetchTreasuryData()
})

async function fetchTreasuryData() {
  loading.value = true
  
  try {
    console.log('💰 Fetching treasury data...')
    const response = await axiosClient.get('/customer/treasury')
    console.log('✅ Treasury response:', response.data)
    
    totalTokens.value = response.data.tokens || 0
    runesCollected.value = response.data.runes || 0
    totalOrders.value = response.data.order_count || 0
    totalSaved.value = response.data.total_saved || 0
    currentLevel.value = response.data.level?.name || 'Thrall'
    tokensToNextLevel.value = response.data.tokens_to_next_level || 500
    levelProgress.value = response.data.level_progress || 0
    activeDiscounts.value = response.data.active_discounts || []
    recentOrders.value = response.data.recent_orders || []
    
    console.log('💰 Treasury data loaded successfully!')
  } catch (error) {
    console.error('❌ Failed to fetch treasury data:', error)
    // Don't throw - just show empty state
  } finally {
    loading.value = false
  }
}
</script>