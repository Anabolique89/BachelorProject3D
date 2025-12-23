<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <!-- Header with User Info -->
    <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6 mb-8">
      <div class="flex items-center gap-6">
        <!-- Avatar -->
        <div class="h-24 w-24 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-gray-900 text-4xl font-bold">
          {{ user?.name?.charAt(0).toUpperCase() }}
        </div>

        <!-- User Info -->
        <div class="flex-1">
          <h1 class="text-3xl font-bold text-white">{{ user?.name }}</h1>
          <p class="text-gray-400 mt-1">{{ user?.email }}</p>
          
          <!-- Stats -->
          <div class="flex gap-6 mt-4">
            <div>
              <p class="text-sm text-gray-400">Level</p>
              <p class="text-xl font-bold text-yellow-400">{{ customer?.user_level?.name || 'Thrall' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-400">Total Tokens</p>
              <p class="text-xl font-bold text-yellow-400">{{ customer?.total_tokens || 0 }} 🪙</p>
            </div>
            <div>
              <p class="text-sm text-gray-400">Available</p>
              <p class="text-xl font-bold text-green-400">{{ customer?.available_tokens || 0 }} 🪙</p>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="hidden md:flex flex-col gap-2">
          <router-link
            :to="{ name: 'orders' }"
            class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white hover:bg-white/20 transition-colors text-sm"
          >
            View Orders
          </router-link>
          <router-link
            :to="{ name: 'shop' }"
            class="px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg text-gray-900 hover:from-yellow-500 hover:to-orange-600 transition-colors text-sm font-bold"
          >
            Continue Shopping
          </router-link>
        </div>
      </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-white/10 mb-8">
      <nav class="-mb-px flex space-x-8">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            activeTab === tab.id
              ? 'border-yellow-400 text-yellow-400'
              : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-300',
            'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors'
          ]"
        >
          {{ tab.name }}
        </button>
      </nav>
    </div>

    <!-- Tab Content -->
    <div class="space-y-8">
      <!-- Overview Tab -->
      <div v-if="activeTab === 'overview'">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Orders Card -->
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-white">Orders</h3>
              <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
              </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ orderCount }}</p>
            <p class="text-sm text-gray-400 mt-1">Total orders placed</p>
            <router-link
              :to="{ name: 'orders' }"
              class="mt-4 block text-sm text-yellow-400 hover:text-yellow-300"
            >
              View all orders →
            </router-link>
          </div>

          <!-- Quests Card -->
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-white">Quests</h3>
              <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ questsCompleted }}</p>
            <p class="text-sm text-gray-400 mt-1">Quests completed</p>
            <button
              @click="activeTab = 'quests'"
              class="mt-4 block text-sm text-yellow-400 hover:text-yellow-300"
            >
              View quests →
            </button>
          </div>

          <!-- Runes Card -->
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-white">Runes</h3>
              <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
              </svg>
            </div>
            <p class="text-3xl font-bold text-white">{{ runesCollected }}</p>
            <p class="text-sm text-gray-400 mt-1">Runes collected</p>
            <button
              @click="activeTab = 'runes'"
              class="mt-4 block text-sm text-yellow-400 hover:text-yellow-300"
            >
              View collection →
            </button>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8 bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
          <h3 class="text-lg font-medium text-white mb-4">Recent Activity</h3>
          <div class="space-y-4">
            <div v-if="recentOrders.length === 0" class="text-center py-8 text-gray-400">
              No recent activity
            </div>
            <div
              v-for="order in recentOrders"
              :key="order.id"
              class="flex items-center justify-between py-3 border-b border-white/10 last:border-0"
            >
              <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-full bg-green-500/20 flex items-center justify-center">
                  <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
                <div>
                  <p class="text-white font-medium">Order {{ order.order_number }}</p>
                  <p class="text-sm text-gray-400">{{ formatDate(order.created_at) }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-white font-medium">${{ parseFloat(order.total_price).toFixed(2) }}</p>
                <p class="text-sm text-gray-400 capitalize">{{ order.status }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Settings Tab -->
      <div v-if="activeTab === 'settings'">
        <div class="space-y-8">
          <!-- Personal Information -->
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
            <h2 class="text-lg font-medium text-white mb-6">Personal Information</h2>
            
            <form @submit.prevent="updateProfile" class="space-y-6">
              <!-- Name -->
              <div>
                <label for="name" class="block text-sm font-medium text-gray-300">
                  Full Name
                </label>
                <div class="mt-1">
                  <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    required
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <!-- Email -->
              <div>
                <label for="email" class="block text-sm font-medium text-gray-300">
                  Email Address
                </label>
                <div class="mt-1">
                  <input
                    v-model="form.email"
                    type="email"
                    id="email"
                    required
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <!-- Save Button -->
              <div class="flex justify-end">
                <button
                  type="submit"
                  :disabled="saving"
                  class="px-6 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {{ saving ? 'Saving...' : 'Save Changes' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Change Password -->
          <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
            <h2 class="text-lg font-medium text-white mb-6">Change Password</h2>
            
            <form @submit.prevent="changePassword" class="space-y-6">
              <!-- Current Password -->
              <div>
                <label for="current_password" class="block text-sm font-medium text-gray-300">
                  Current Password
                </label>
                <div class="mt-1">
                  <input
                    v-model="passwordForm.current_password"
                    type="password"
                    id="current_password"
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <!-- New Password -->
              <div>
                <label for="new_password" class="block text-sm font-medium text-gray-300">
                  New Password
                </label>
                <div class="mt-1">
                  <input
                    v-model="passwordForm.new_password"
                    type="password"
                    id="new_password"
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <!-- Confirm Password -->
              <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-300">
                  Confirm New Password
                </label>
                <div class="mt-1">
                  <input
                    v-model="passwordForm.confirm_password"
                    type="password"
                    id="confirm_password"
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <!-- Update Password Button -->
              <div class="flex justify-end">
                <button
                  type="submit"
                  :disabled="changingPassword"
                  class="px-6 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {{ changingPassword ? 'Updating...' : 'Update Password' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Orders Tab -->
      <div v-if="activeTab === 'orders'">
        <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
          <p class="text-gray-400 text-center py-8">
            View your full order history on the 
            <router-link :to="{ name: 'orders' }" class="text-yellow-400 hover:text-yellow-300">
              Orders page
            </router-link>
          </p>
        </div>
      </div>

      <!-- Quests Tab -->
      <div v-if="activeTab === 'quests'">
        <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
          <h2 class="text-lg font-medium text-white mb-4">Your Quests</h2>
          <p class="text-gray-400 text-center py-8">
            Quest system coming soon! Complete challenges to earn tokens and level up your Viking status.
          </p>
        </div>
      </div>

      <!-- Runes Tab -->
      <div v-if="activeTab === 'runes'">
        <div class="bg-white/5 backdrop-blur-lg rounded-lg border border-white/10 p-6">
          <h2 class="text-lg font-medium text-white mb-4">Rune Collection</h2>
          <p class="text-gray-400 text-center py-8">
            Collect powerful runes by completing orders and quests. Rune system coming soon!
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useStore } from 'vuex'
import axiosClient from '../axios'

const store = useStore()

const user = computed(() => store.state.user.data)
const customer = computed(() => user.value?.customer)

const activeTab = ref('overview')
const loading = ref(false)
const saving = ref(false)
const changingPassword = ref(false)

// Stats
const orderCount = ref(0)
const questsCompleted = ref(0)
const runesCollected = ref(0)
const recentOrders = ref([])

const tabs = [
  { id: 'overview', name: 'Overview' },
  { id: 'settings', name: 'Settings' },
  { id: 'orders', name: 'Orders' },
  { id: 'quests', name: 'Quests' },
  { id: 'runes', name: 'Runes' },
]

const form = ref({
  name: '',
  email: '',
})

const passwordForm = ref({
  current_password: '',
  new_password: '',
  confirm_password: '',
})

watch(user, (newUser) => {
  if (newUser) {
    form.value.name = newUser.name || ''
    form.value.email = newUser.email || ''
  }
}, { immediate: true })

onMounted(async () => {
  await loadProfileData()
})

async function loadProfileData() {
  loading.value = true
  try {
    // Load user data
    await store.dispatch('getCurrentUser')
    
    if (user.value) {
      form.value.name = user.value.name || ''
      form.value.email = user.value.email || ''
    }

    // Load orders count and recent orders
    const ordersResponse = await axiosClient.get('/orders')
    const orders = ordersResponse.data.data || []
    orderCount.value = orders.length
    recentOrders.value = orders.slice(0, 3) // Get 3 most recent

    // TODO: Load quests and runes when implemented
    questsCompleted.value = 0
    runesCollected.value = 0

  } catch (error) {
    console.error('Failed to load profile data:', error)
  } finally {
    loading.value = false
  }
}

async function updateProfile() {
  saving.value = true
  try {
    await axiosClient.put('/profile', form.value)
    await store.dispatch('getCurrentUser')
    alert('✅ Profile updated successfully!')
  } catch (error) {
    if (error.response?.data?.errors) {
      const errors = Object.values(error.response.data.errors).flat()
      alert('❌ ' + errors.join('\n'))
    } else {
      alert('❌ Failed to update profile')
    }
  } finally {
    saving.value = false
  }
}

async function changePassword() {
  if (passwordForm.value.new_password !== passwordForm.value.confirm_password) {
    alert('❌ Passwords do not match!')
    return
  }

  if (passwordForm.value.new_password.length < 8) {
    alert('❌ Password must be at least 8 characters!')
    return
  }

  changingPassword.value = true
  try {
    await axiosClient.put('/profile/password', {
      current_password: passwordForm.value.current_password,
      password: passwordForm.value.new_password,
      password_confirmation: passwordForm.value.confirm_password,
    })
    
    alert('✅ Password updated successfully!')
    
    passwordForm.value = {
      current_password: '',
      new_password: '',
      confirm_password: '',
    }
  } catch (error) {
    if (error.response?.data?.message) {
      alert('❌ ' + error.response.data.message)
    } else {
      alert('❌ Failed to update password')
    }
  } finally {
    changingPassword.value = false
  }
}

function formatDate(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}
</script>