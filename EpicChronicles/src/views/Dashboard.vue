<template>
    
  <div>
     
            <router-link :to="{ name: 'shop' }" class="flex items-center">
                 <h1 class="text-3xl font-bold mb-6 text-black">Viking Dashboard</h1>
            <img :src="logo" alt="Epic Chronicles Logo" class="h-12 w-auto mx-2">
            
            </router-link>
        
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <!-- Total Customers -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Total Customers</p>
            <p class="text-3xl font-bold mt-2">{{ stats.total_customers || 0 }}</p>
          </div>
          <div class="bg-indigo-100 rounded-full p-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <!-- Total Products -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Total Products</p>
            <p class="text-3xl font-bold mt-2">{{ stats.total_products || 0 }}</p>
          </div>
          <div class="bg-green-100 rounded-full p-3">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
          </div>
        </div>
      </div>

      <!-- Total Quests -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Active Quests</p>
            <p class="text-3xl font-bold mt-2">{{ stats.total_quests || 0 }}</p>
          </div>
          <div class="bg-yellow-100 rounded-full p-3">
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <!-- Total Runes -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Rune Types</p>
            <p class="text-3xl font-bold mt-2">{{ stats.total_runes || 0 }}</p>
          </div>
          <div class="bg-purple-100 rounded-full p-3">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">⚡ Quick Actions</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <router-link
          :to="{ name: 'app.products.create' }"
          class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-colors"
        >
          <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          <span class="text-sm font-medium">Add Product</span>
        </router-link>

        <router-link
          :to="{ name: 'app.products' }"
          class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors"
        >
          <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
          <span class="text-sm font-medium">View Products</span>
        </router-link>

        <button
          @click="$router.push({ name: 'app.products' })"
          class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 transition-colors"
        >
          <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
          <span class="text-sm font-medium">Categories</span>
        </button>

        <button
          class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors"
        >
          <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span class="text-sm font-medium">Manage Quests</span>
        </button>
      </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <!-- Recent Products -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-semibold">📦 Recent Products</h2>
          <router-link 
            :to="{ name: 'app.products' }"
            class="text-sm text-indigo-600 hover:text-indigo-800"
          >
            View All →
          </router-link>
        </div>
        
        <div v-if="loadingProducts" class="text-center py-8">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
        </div>
        
        <div v-else-if="recentProducts.length">
          <div 
            v-for="product in recentProducts" 
            :key="product.id"
            class="flex items-center space-x-4 py-3 border-b last:border-b-0 hover:bg-gray-50 cursor-pointer"
            @click="$router.push({ name: 'app.products.edit', params: { id: product.id } })"
          >
            <img 
              v-if="product.image_url" 
              :src="product.image_url" 
              :alt="product.title"
              class="w-12 h-12 object-cover rounded"
            />
            <div v-else class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
              <span class="text-xs text-gray-400">No img</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 truncate">{{ product.title }}</p>
              <p class="text-sm text-gray-500">${{ product.price }}</p>
            </div>
            <div>
              <span 
                class="px-2 py-1 text-xs font-semibold rounded"
                :class="{
                  'bg-green-100 text-green-800': product.status === 'published',
                  'bg-yellow-100 text-yellow-800': product.status === 'draft',
                  'bg-red-100 text-red-800': product.status === 'out_of_stock'
                }"
              >
                {{ product.status }}
              </span>
            </div>
          </div>
        </div>
        
        <div v-else class="text-center py-8 text-gray-500">
          No products yet
        </div>
      </div>

      <!-- Customer Levels Distribution -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">🏆 Customer Levels</h2>
        
        <div v-if="loadingLevels" class="text-center py-8">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
        </div>
        
        <div v-else-if="customerLevels.length" class="space-y-4">
          <div 
            v-for="level in customerLevels" 
            :key="level.name"
            class="flex items-center justify-between"
          >
            <div class="flex items-center space-x-3">
              <div 
                class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold"
                :style="{ backgroundColor: getLevelColor(level.name) }"
              >
                {{ level.name.charAt(0) }}
              </div>
              <div>
                <p class="font-medium">{{ level.name }}</p>
                <p class="text-sm text-gray-500">Level {{ level.level }}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="font-semibold">{{ level.count || 0 }}</p>
              <p class="text-sm text-gray-500">customers</p>
            </div>
          </div>
        </div>
        
        <div v-else class="text-center py-8 text-gray-500">
          No customer data available
        </div>
      </div>
    </div>

    <!-- System Info -->
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-semibold mb-4">⚙️ System Overview</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center p-4 bg-gray-50 rounded">
          <p class="text-2xl font-bold text-gray-900">{{ stats.total_discount_tiers || 0 }}</p>
          <p class="text-sm text-gray-600">Discount Tiers</p>
        </div>
        <div class="text-center p-4 bg-gray-50 rounded">
          <p class="text-2xl font-bold text-gray-900">{{ categories.length || 0 }}</p>
          <p class="text-sm text-gray-600">Categories</p>
        </div>
        <div class="text-center p-4 bg-gray-50 rounded">
          <p class="text-2xl font-bold text-gray-900">{{ stats.total_runes || 0 }}</p>
          <p class="text-sm text-gray-600">Rune Types</p>
        </div>
        <div class="text-center p-4 bg-gray-50 rounded">
          <p class="text-2xl font-bold text-gray-900">{{ stats.total_quests || 0 }}</p>
          <p class="text-sm text-gray-600">Active Quests</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import logo from '../assets/logo.png'
import { ref, onMounted } from 'vue'
import { useStore } from 'vuex'
import axiosClient from '../axios'

const store = useStore()

const stats = ref({})
const recentProducts = ref([])
const categories = ref([])
const customerLevels = ref([])
const loadingProducts = ref(false)
const loadingLevels = ref(false)

onMounted(async () => {
  loadDashboardData()
})

async function loadDashboardData() {
  // Load stats
  try {
    stats.value = await store.dispatch('getDashboardStats')
  } catch (error) {
    console.error('Failed to load stats:', error)
  }

  // Load recent products
  loadingProducts.value = true
  try {
    const response = await axiosClient.get('/products', {
      params: {
        per_page: 5,
        sort_by: 'created_at',
        sort_order: 'desc'
      }
    })
    recentProducts.value = response.data.data || []
    
    // Also get total products count
    stats.value.total_products = response.data.meta?.total || 0
  } catch (error) {
    console.error('Failed to load products:', error)
  } finally {
    loadingProducts.value = false
  }

  // Load categories
  try {
    const response = await axiosClient.get('/categories')
    categories.value = response.data || []
  } catch (error) {
    console.error('Failed to load categories:', error)
  }

  // Load customer levels - using mock data (no API call)
  customerLevels.value = [
    { name: 'Thrall', level: 1, count: 0 },
    { name: 'Karl', level: 2, count: 0 },
    { name: 'Jarl', level: 3, count: 0 },
    { name: 'Hersir', level: 4, count: 0 },
    { name: 'Konungr', level: 5, count: 0 },
  ]
}

function getLevelColor(levelName) {
  const colors = {
    'Thrall': '#6B7280',
    'Karl': '#10B981',
    'Jarl': '#3B82F6',
    'Hersir': '#8B5CF6',
    'Konungr': '#F59E0B',
    'Drottinn': '#EF4444',
    'Allfather': '#EC4899',
  }
  return colors[levelName] || '#6B7280'
}
</script>