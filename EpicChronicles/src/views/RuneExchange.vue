<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-white mb-4">📜 Odin's Call</h1>
      <p class="text-lg text-gray-400">Convert Your Runes into Powerful Discounts</p>
    </div>

    <!-- Your Runes Balance -->
    <div class="bg-gradient-to-r from-purple-500/20 to-blue-500/20 backdrop-blur-lg rounded-lg border border-purple-400/30 p-8 mb-12 text-center">
      <h2 class="text-2xl font-bold text-white mb-4">Your Rune Collection</h2>
      <div class="text-6xl font-bold text-purple-400 mb-2">{{ totalRunes }}</div>
      <p class="text-gray-300">Runes Available for Conversion</p>
    </div>

    <!-- Conversion Rates -->
    <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-8 mb-12">
      <h2 class="text-2xl font-bold text-white mb-6">Conversion Rates</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="rate in conversionRates" :key="rate.runes"
          class="bg-white/5 border border-white/10 rounded-lg p-6 hover:bg-white/10 transition-colors cursor-pointer"
          @click="selectConversion(rate)"
        >
          <div class="flex justify-between items-start mb-4">
            <div class="text-4xl">{{ rate.icon }}</div>
            <span class="text-2xl font-bold text-yellow-400">{{ rate.discount }}%</span>
          </div>
          <h3 class="text-lg font-bold text-white mb-2">{{ rate.title }}</h3>
          <p class="text-sm text-gray-400 mb-3">{{ rate.description }}</p>
          <div class="flex items-center justify-between pt-3 border-t border-white/10">
            <span class="text-purple-400 font-bold">{{ rate.runes }} Runes</span>
            <span class="text-xs text-gray-500">Valid {{ rate.validity }} days</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Active Discount Codes -->
    <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-8 mb-12">
      <h2 class="text-2xl font-bold text-white mb-6">Your Active Discount Codes</h2>
      
      <div v-if="activeDiscounts.length > 0" class="space-y-4">
        <div v-for="discount in activeDiscounts" :key="discount.code"
          class="bg-gradient-to-r from-yellow-400/20 to-orange-500/20 border border-yellow-400/30 rounded-lg p-4"
        >
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-xl font-bold text-yellow-400">{{ discount.code }}</h3>
              <p class="text-sm text-gray-300">{{ discount.description }}</p>
              <p class="text-xs text-gray-500 mt-1">Expires: {{ discount.expires_at }}</p>
            </div>
            <div class="text-center">
              <div class="text-3xl font-bold text-yellow-400">{{ discount.value }}%</div>
              <button 
                @click="copyCode(discount.code)"
                class="mt-2 px-4 py-1 bg-yellow-400 text-gray-900 rounded text-sm font-bold hover:bg-yellow-500 transition-colors"
              >
                Copy Code
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="text-center py-8 text-gray-400">
        <p>No active discount codes yet. Convert your runes above!</p>
      </div>
    </div>

    <!-- Conversion Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="bg-gray-900 border-2 border-purple-400 rounded-lg p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-4">Confirm Conversion</h3>
        <p class="text-gray-300 mb-6">
          Convert <span class="text-purple-400 font-bold">{{ selectedRate?.runes }} runes</span> 
          into a <span class="text-yellow-400 font-bold">{{ selectedRate?.discount }}% discount</span> code?
        </p>
        
        <div class="bg-white/5 rounded-lg p-4 mb-6">
          <p class="text-sm text-gray-400 mb-2">Remaining after conversion:</p>
          <p class="text-2xl font-bold text-purple-400">{{ totalRunes - (selectedRate?.runes || 0) }} runes</p>
        </div>
        
        <div class="flex gap-4">
          <button 
            @click="confirmConversion"
            class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white font-bold rounded-lg hover:from-purple-600 hover:to-blue-600 transition-all"
          >
            Convert Now
          </button>
          <button 
            @click="showModal = false"
            class="flex-1 px-6 py-3 bg-gray-700 text-white font-bold rounded-lg hover:bg-gray-600 transition-colors"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>

    <!-- Back Button -->
    <div class="text-center">
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
import { ref, onMounted } from 'vue'
import axiosClient from '../axios'

const totalRunes = ref(0)
const activeDiscounts = ref([])
const showModal = ref(false)
const selectedRate = ref(null)

const conversionRates = ref([
  {
    icon: '🥉',
    title: 'Bronze Discount',
    discount: 5,
    runes: 10,
    validity: 7,
    description: 'Save 5% on your next purchase'
  },
  {
    icon: '🥈',
    title: 'Silver Discount',
    discount: 10,
    runes: 25,
    validity: 14,
    description: 'Save 10% on your next purchase'
  },
  {
    icon: '🥇',
    title: 'Gold Discount',
    discount: 15,
    runes: 50,
    validity: 30,
    description: 'Save 15% on your next purchase'
  },
  {
    icon: '💎',
    title: 'Diamond Discount',
    discount: 20,
    runes: 100,
    validity: 60,
    description: 'Save 20% on your next purchase'
  },
  {
    icon: '👑',
    title: 'Royal Discount',
    discount: 25,
    runes: 200,
    validity: 90,
    description: 'Save 25% on your next purchase'
  },
  {
    icon: '⚡',
    title: 'Legendary Discount',
    discount: 30,
    runes: 500,
    validity: 180,
    description: 'Save 30% on your next purchase'
  },
])

onMounted(async () => {
  await fetchRuneData()
})

async function fetchRuneData() {
  try {
    const response = await axiosClient.get('/customer/runes')
    totalRunes.value = response.data.total_runes || 0
    activeDiscounts.value = response.data.active_discounts || []
  } catch (error) {
    console.error('Failed to fetch rune data:', error)
  }
}

function selectConversion(rate) {
  if (totalRunes.value < rate.runes) {
    alert(`Not enough runes! You need ${rate.runes} runes but only have ${totalRunes.value}.`)
    return
  }
  selectedRate.value = rate
  showModal.value = true
}

async function confirmConversion() {
  try {
    const response = await axiosClient.post('/customer/runes/convert', {
      runes: selectedRate.value.runes,
      discount: selectedRate.value.discount,
      validity_days: selectedRate.value.validity
    })
    
    // Update local state
    totalRunes.value -= selectedRate.value.runes
    activeDiscounts.value.unshift(response.data.discount)
    
    showModal.value = false
    alert(`Success! Your discount code: ${response.data.discount.code}`)
  } catch (error) {
    console.error('Failed to convert runes:', error)
    alert('Conversion failed. Please try again.')
  }
}

function copyCode(code) {
  navigator.clipboard.writeText(code)
  alert(`Copied code: ${code}`)
}
</script>