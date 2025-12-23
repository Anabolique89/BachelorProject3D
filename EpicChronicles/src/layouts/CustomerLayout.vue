<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900">
    <!-- Navigation -->
    <nav class="bg-black/30 backdrop-blur-md border-b border-white/10 sticky top-0 z-50">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
        
          <!-- Logo -->
          <div class="flex items-center">
            <router-link :to="{ name: 'shop' }" class="flex items-center">
              <img :src="logo" alt="Epic Chronicles Logo" class="h-12 w-auto">
            </router-link>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex md:items-center md:space-x-8">
            <router-link 
              :to="{ name: 'shop' }" 
              class="text-gray-300 hover:text-yellow-400 px-3 py-2 text-sm font-medium transition-colors"
              active-class="text-yellow-400"
            >
              HOME
            </router-link>

            <!-- Categories Dropdown -->
            <div class="relative" @mouseenter="showCategories = true" @mouseleave="showCategories = false">
              <button class="text-gray-300 hover:text-yellow-400 px-3 py-2 text-sm font-medium transition-colors flex items-center">
                CATEGORIES
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>

              <!-- Dropdown -->
              <div 
                v-show="showCategories"
                class="absolute left-0 mt-2 w-48 rounded-md bg-gray-900/95 backdrop-blur-lg shadow-lg ring-1 ring-white/10"
              >
                <div class="py-1">
                  <router-link 
                    :to="{ name: 'shop' }"
                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-yellow-400"
                  >
                    All Products
                  </router-link>
                  <router-link 
                    v-for="category in categories.slice(0, 6)" 
                    :key="category.id"
                    :to="{ name: 'shop', query: { category: category.id } }"
                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-yellow-400"
                  >
                    {{ category.name }}
                  </router-link>
                </div>
              </div>
            </div>

            <router-link 
              :to="{ name: 'island-hub' }" 
              class="text-gray-300 hover:text-yellow-400 px-3 py-2 text-sm font-medium transition-colors"
              active-class="text-yellow-400"
            >
              ISLAND HUB
            </router-link>

            <router-link 
              :to="{ name: 'favorites' }" 
              class="text-gray-300 hover:text-yellow-400 px-3 py-2 text-sm font-medium transition-colors flex items-center"
              active-class="text-yellow-400"
            >
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
              FAVORITES
            </router-link>
          </div>

          <!-- Right Side Icons -->
          <div class="flex items-center space-x-4">
            <!-- Search Icon -->
            <button 
              @click="toggleSearch"
              class="text-gray-300 hover:text-yellow-400 transition-colors"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </button>

            <!-- Cart -->
            <router-link 
              :to="{ name: 'cart' }" 
              class="text-gray-300 hover:text-yellow-400 transition-colors relative"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              <span 
                v-if="cartCount > 0"
                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
              >
                {{ cartCount }}
              </span>
            </router-link>

            <!-- User Menu (HeadlessUI) -->
            <Menu as="div" v-if="user" class="relative hidden md:block">
              <MenuButton class="flex items-center text-gray-300 hover:text-yellow-400 transition-colors focus:outline-none">
                <div class="h-8 w-8 rounded-full bg-yellow-400 flex items-center justify-center text-gray-900 font-bold">
                  {{ user.name?.charAt(0).toUpperCase() }}
                </div>
              </MenuButton>

              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-gray-900/95 backdrop-blur-lg shadow-lg ring-1 ring-white/10 focus:outline-none">
                  <div class="py-1">
                    <MenuItem v-slot="{ active }">
                      <router-link
                        :to="{ name: 'profile' }"
                        :class="[active ? 'bg-white/10 text-yellow-400' : 'text-gray-300', 'block px-4 py-2 text-sm']"
                      >
                        My Profile
                      </router-link>
                    </MenuItem>
                    
                    <MenuItem v-slot="{ active }">
                      <router-link
                        :to="{ name: 'orders' }"
                        :class="[active ? 'bg-white/10 text-yellow-400' : 'text-gray-300', 'block px-4 py-2 text-sm']"
                      >
                        My Orders
                      </router-link>
                    </MenuItem>
                    
                    <MenuItem v-slot="{ active }">
                      <router-link
                        :to="{ name: 'favorites' }"
                        :class="[active ? 'bg-white/10 text-yellow-400' : 'text-gray-300', 'block px-4 py-2 text-sm']"
                      >
                        Favorites
                      </router-link>
                    </MenuItem>
                    
                    <MenuItem v-slot="{ active }">
                      <button
                        @click="logout"
                        :class="[active ? 'bg-white/10 text-red-400' : 'text-gray-300', 'block w-full text-left px-4 py-2 text-sm']"
                      >
                        Sign Out
                      </button>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>

            <!-- Login Button (if not authenticated) -->
            <router-link 
              v-else
              :to="{ name: 'login' }" 
              class="hidden md:block text-gray-300 hover:text-yellow-400 px-3 py-2 text-sm font-medium transition-colors"
            >
              SIGN IN
            </router-link>

            <!-- Mobile Menu Button -->
            <button 
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="md:hidden text-gray-300 hover:text-yellow-400"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div v-if="mobileMenuOpen" class="md:hidden bg-gray-900/95 backdrop-blur-lg border-t border-white/10">
        <div class="px-2 pt-2 pb-3 space-y-1">
          <router-link 
            :to="{ name: 'shop' }" 
            class="block px-3 py-2 text-base font-medium text-gray-300 hover:text-yellow-400 hover:bg-white/10 rounded-md"
            @click="mobileMenuOpen = false"
          >
            HOME
          </router-link>
          <router-link 
            :to="{ name: 'island-hub' }" 
            class="block px-3 py-2 text-base font-medium text-gray-300 hover:text-yellow-400 hover:bg-white/10 rounded-md"
            @click="mobileMenuOpen = false"
          >
            ISLAND HUB
          </router-link>
          <router-link 
            :to="{ name: 'favorites' }" 
            class="block px-3 py-2 text-base font-medium text-gray-300 hover:text-yellow-400 hover:bg-white/10 rounded-md"
            @click="mobileMenuOpen = false"
          >
            FAVORITES
          </router-link>

          <!-- Mobile User Menu -->
          <div v-if="user" class="border-t border-white/10 pt-2 mt-2">
            <router-link 
              :to="{ name: 'profile' }" 
              class="block px-3 py-2 text-base font-medium text-gray-300 hover:text-yellow-400 hover:bg-white/10 rounded-md"
              @click="mobileMenuOpen = false"
            >
              My Profile
            </router-link>
            <router-link 
              :to="{ name: 'orders' }" 
              class="block px-3 py-2 text-base font-medium text-gray-300 hover:text-yellow-400 hover:bg-white/10 rounded-md"
              @click="mobileMenuOpen = false"
            >
              My Orders
            </router-link>
            <button 
              @click="logout"
              class="block w-full text-left px-3 py-2 text-base font-medium text-gray-300 hover:text-red-400 hover:bg-white/10 rounded-md"
            >
              Sign Out
            </button>
          </div>
          <router-link 
            v-else
            :to="{ name: 'login' }" 
            class="block px-3 py-2 text-base font-medium text-gray-300 hover:text-yellow-400 hover:bg-white/10 rounded-md"
            @click="mobileMenuOpen = false"
          >
            SIGN IN
          </router-link>
        </div>
      </div>
    </nav>

    <!-- Search Bar (toggleable) -->
    <div v-if="searchOpen" class="bg-black/40 backdrop-blur-md border-b border-white/10 py-4">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search for Viking gear..."
          class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500"
          @keyup.enter="search"
        />
      </div>
    </div>

    <!-- Main Content -->
    <main>
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="bg-black/30 backdrop-blur-md border-t border-white/10 mt-20">
      <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 class="text-lg font-bold text-yellow-400 mb-4">Epic Chronicles</h3>
            <p class="text-gray-400 text-sm">Your source for authentic Viking gear and Norse mythology.</p>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-white mb-4">Shop</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><router-link to="/shop" class="hover:text-yellow-400">All Products</router-link></li>
              <li><router-link to="/shop" class="hover:text-yellow-400">Categories</router-link></li>
              <li><router-link to="/shop" class="hover:text-yellow-400">New Arrivals</router-link></li>
            </ul>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-white mb-4">Support</h4>
            <ul class="space-y-2 text-sm text-gray-400">
              <li><a href="#" class="hover:text-yellow-400">Contact Us</a></li>
              <li><a href="#" class="hover:text-yellow-400">Shipping Info</a></li>
              <li><a href="#" class="hover:text-yellow-400">Returns</a></li>
            </ul>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-white mb-4">Follow Us</h4>
            <div class="flex space-x-4">
              <a href="#" class="text-gray-400 hover:text-yellow-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-yellow-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
              </a>
            </div>
          </div>
        </div>
        <div class="mt-8 border-t border-white/10 pt-8 text-center text-sm text-gray-400">
          © 2025 Epic Chronicles. All rights reserved.
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import logo from '../assets/logo.png'
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'
import axiosClient from '../axios'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'

const router = useRouter()
const store = useStore()

const user = computed(() => store.state.user.data)
const cartCount = computed(() => store.getters['cart/cartItemCount'])
const categories = ref([])
const showCategories = ref(false)
const mobileMenuOpen = ref(false)
const searchOpen = ref(false)
const searchQuery = ref('')

onMounted(async () => {
  await loadCategories()
  if (store.getters.isAuthenticated) {
    await store.dispatch('cart/fetchCartCount')
  }
})

async function loadCategories() {
  try {
    const response = await axiosClient.get('/categories')
    categories.value = response.data || []
  } catch (error) {
    console.error('Failed to load categories:', error)
  }
}

function toggleSearch() {
  searchOpen.value = !searchOpen.value
}

function search() {
  if (searchQuery.value) {
    router.push({ name: 'shop', query: { search: searchQuery.value } })
    searchOpen.value = false
  }
}

function logout() {
  store.dispatch('logout')
    .then(() => {
      router.push({ name: 'login' })
    })
}
</script>