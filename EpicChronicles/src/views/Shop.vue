<template>
  <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="mb-12 text-center">
      <h1 class="text-4xl font-bold text-white mb-4">
        Welcome to the Viking Armory
      </h1>
      <p class="text-lg text-gray-300">
        Equip yourself with legendary gear from the Norse realms
      </p>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4 mb-8">
      <!-- Category Filter -->
      <select
        v-model="selectedCategory"
        @change="filterProducts"
        class="px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent backdrop-blur-sm"
      >
        <option value="">All Categories</option>
        <option v-for="category in categories" :key="category.id" :value="category.id">
          {{ category.name }}
        </option>
      </select>

      <!-- Sort -->
      <select
        v-model="sortBy"
        @change="filterProducts"
        class="px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent backdrop-blur-sm"
      >
        <option value="newest">Newest First</option>
        <option value="price_asc">Price: Low to High</option>
        <option value="price_desc">Price: High to Low</option>
        <option value="name_asc">Name: A-Z</option>
      </select>

      <!-- Per Page -->
      <select
        v-model="perPage"
        @change="filterProducts"
        class="px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent backdrop-blur-sm"
      >
        <option :value="12">12 per page</option>
        <option :value="24">24 per page</option>
        <option :value="48">48 per page</option>
      </select>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-yellow-400 mx-auto mb-4"></div>
        <p class="text-gray-300">Loading legendary items...</p>
      </div>
    </div>

    <!-- Products Grid -->
    <div v-else-if="products.data && products.data.length" class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
      <div 
        v-for="product in products.data" 
        :key="product.id"
        class="group cursor-pointer transform transition-all duration-300 hover:scale-105"
        @click="goToProduct(product.slug)"
      >
       
        <!-- Product Image -->
        <div class="relative aspect-square w-full overflow-hidden rounded-lg bg-gradient-to-br from-gray-800 to-gray-900 border border-white/10 shadow-xl">
          <img 
            v-if="product.image_url"
            :src="product.image_url" 
            :alt="product.title"
            class="h-full w-full object-cover object-center group-hover:opacity-90 transition-opacity"
            @error="handleImageError"
          />
          <div v-else class="h-full w-full flex items-center justify-center">
            <svg class="w-20 h-20 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>

          <!-- Badges -->
          <div class="absolute top-2 left-2 flex flex-col gap-2">
            <span v-if="product.is_featured" class="inline-flex items-center rounded-md bg-yellow-400 px-2 py-1 text-xs font-bold text-gray-900 shadow-lg">
              ⭐ FEATURED
            </span>
            <span v-if="product.is_on_sale" class="inline-flex items-center rounded-md bg-red-500 px-2 py-1 text-xs font-bold text-white shadow-lg">
              -{{ product.discount_percentage }}% OFF
            </span>
        
          </div>
    <!-- move quick view buttom to top right of image - doneeee <3 bam bam -->
             <button
          @click.stop="openQuickView(product)"
          class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-black/80 backdrop-blur-sm text-white px-3 py-2 rounded-lg text-xs font-medium hover:bg-yellow-400 hover:text-gray-900"
        >
          Quick View
        </button>
          <!-- Price Overlay -->
          <div class="absolute inset-x-0 bottom-0 flex h-24 items-end justify-between p-4">
            <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/90 via-black/50"></div>
            <div class="relative z-10">
              <p v-if="product.is_on_sale" class="text-sm text-gray-400 line-through">${{ product.price }}</p>
              <p class="text-xl font-bold text-yellow-400">
                ${{ product.is_on_sale ? product.sale_price : product.price }}
              </p>
            </div>
            <div class="relative z-10">
              <span 
                v-if="product.stock_quantity > 0"
                class="inline-flex items-center rounded-md bg-green-500/80 px-2 py-1 text-xs font-medium text-white backdrop-blur-sm"
              >
                {{ product.stock_quantity }} left
              </span>
              <span 
                v-else
                class="inline-flex items-center rounded-md bg-red-500/80 px-2 py-1 text-xs font-medium text-white backdrop-blur-sm"
              >
                Sold Out
              </span>
            </div>
          </div>
        </div>

        <!-- Product Info -->
        <div class="mt-4">
          <h3 class="text-base font-semibold text-white group-hover:text-yellow-400 transition-colors">
            {{ product.title }}
          </h3>
          <p class="mt-1 text-sm text-gray-400">{{ product.category?.name || 'Uncategorized' }}</p>
        </div>

        <!-- Add to Cart Button -->
        <div class="mt-4">
          <button
            @click.stop="addToCart(product)"
            :disabled="product.stock_quantity === 0"
            class="w-full flex items-center justify-center rounded-lg border border-transparent bg-gradient-to-r from-yellow-400 to-orange-500 px-4 py-3 text-sm font-bold text-gray-900 hover:from-yellow-500 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            {{ product.stock_quantity === 0 ? 'Out of Stock' : 'Add to Cart' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-20">
      <svg class="mx-auto h-24 w-24 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
      </svg>
      <h3 class="mt-4 text-xl font-medium text-white">No products found</h3>
      <p class="mt-2 text-gray-400">Check back soon for legendary Viking gear!</p>
    </div>

    <!-- Pagination -->
    <div v-if="products.data && products.data.length" class="mt-12 flex items-center justify-between border-t border-white/10 pt-6">
      <div class="flex flex-1 justify-between sm:hidden">
        <button
          @click="changePage(products.current_page - 1)"
          :disabled="products.current_page === 1"
          class="relative inline-flex items-center rounded-md bg-white/10 px-4 py-2 text-sm font-medium text-white hover:bg-white/20 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Previous
        </button>
        <button
          @click="changePage(products.current_page + 1)"
          :disabled="products.current_page === products.last_page"
          class="relative ml-3 inline-flex items-center rounded-md bg-white/10 px-4 py-2 text-sm font-medium text-white hover:bg-white/20 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Next
        </button>
      </div>
      <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-300">
            Showing <span class="font-medium">{{ products.from }}</span> to <span class="font-medium">{{ products.to }}</span> of{' '}
            <span class="font-medium">{{ products.total }}</span> results
          </p>
        </div>
        <div>
          <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
            <button
              v-for="page in displayPages"
              :key="page"
              @click="changePage(page)"
              :class="[
                page === products.current_page
                  ? 'z-10 bg-yellow-400 text-gray-900 font-bold'
                  : 'bg-white/10 text-white hover:bg-white/20',
                'relative inline-flex items-center px-4 py-2 text-sm font-medium focus:z-20'
              ]"
            >
              {{ page }}
            </button>
          </nav>
        </div>
      </div>
       <ProductQuickView
      :is-open="quickViewOpen"
      :product="selectedProduct"
      @close="closeQuickView"
      @add-to-cart="handleAddToCart"
    />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axiosClient from '../axios'
import ProductQuickView from '../components/ProductQuickView.vue'
import { useStore } from 'vuex';

const router = useRouter()
const route = useRoute()
const store = useStore()

const products = ref({
  data: [],
  current_page: 1,
  last_page: 1,
  from: 0,
  to: 0,
  total: 0
})
const categories = ref([])
const loading = ref(false)
const selectedCategory = ref('')
const sortBy = ref('newest')
const perPage = ref(12)

const quickViewOpen = ref(false)
const selectedProduct = ref(null)

const displayPages = computed(() => {
  const pages = []
  const currentPage = products.value.current_page
  const lastPage = products.value.last_page
  
  for (let i = Math.max(1, currentPage - 2); i <= Math.min(lastPage, currentPage + 2); i++) {
    pages.push(i)
  }
  
  return pages
})

// Watch route 
watch(() => route.query, (newQuery) => {
  if (newQuery.category) {
    selectedCategory.value = newQuery.category
  }
  if (newQuery.search) {
    
  }
  loadProducts()
}, { immediate: false })

onMounted(async () => {
  // Check if category
  if (route.query.category) {
    selectedCategory.value = route.query.category
  }
  
  await loadCategories()
  await loadProducts()
})

async function loadProducts() {
  loading.value = true
  try {
    const params = {
      per_page: perPage.value,
      page: products.value.current_page
    }

    if (selectedCategory.value) {
      params.category_id = selectedCategory.value
    }

    // Add sorting
    if (sortBy.value === 'price_asc') {
      params.sort = 'price'
      params.order = 'asc'
    } else if (sortBy.value === 'price_desc') {
      params.sort = 'price'
      params.order = 'desc'
    } else if (sortBy.value === 'name_asc') {
      params.sort = 'title'
      params.order = 'asc'
    }

    console.log('📦 Loading products with params:', params)

    const response = await axiosClient.get('/products', { params })
    
    console.log('Products loaded:', response.data)
    products.value = response.data

  } catch (error) {
    console.error('Failed to load products:', error)
    products.value = { data: [], current_page: 1, last_page: 1, from: 0, to: 0, total: 0 }
  } finally {
    loading.value = false
  }
}

function openQuickView(product) {
  selectedProduct.value = product
  quickViewOpen.value = true
}

function closeQuickView() {
  quickViewOpen.value = false
  selectedProduct.value = null
}

function handleAddToCart({ product, quantity }) {
  alert(`Added ${quantity}x "${product.title}" to cart!`)
  console.log('Add to cart:', { product, quantity })
  // TODO: Implement actual cart functionality
}

async function loadCategories() {
  try {
    const response = await axiosClient.get('/categories')
    categories.value = response.data || []
  } catch (error) {
    console.error('Failed to load categories:', error)
  }
}

function filterProducts() {
  products.value.current_page = 1 // Reset to page 1 when filtering
  loadProducts()
}

function changePage(page) {
  if (page < 1 || page > products.value.last_page) return
  products.value.current_page = page
  loadProducts()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function goToProduct(slug) {
  router.push({ name: 'product', params: { slug } })
}

async function addToCart(product) {
  if (!store.getters.isAuthenticated) {
    router.push({ name: 'login' })
    return
  }

  if (product.stock_quantity === 0) {
    alert('This product is out of stock')
    return
  }

  try {
    await store.dispatch('cart/addToCart', {
      product_id: product.id,
      quantity: 1,
    })

    // Show success message
    alert(`Added "${product.title}" to cart!`)

  } catch (error) {
    const message = error.response?.data?.message || 'Failed to add to cart'
    alert(`❌ ${message}`)
  }
}

function handleImageError(event) {
  event.target.style.display = 'none'
}
</script>