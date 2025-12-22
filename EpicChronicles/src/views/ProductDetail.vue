<template>
  <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-yellow-400"></div>
    </div>

    <!-- Product Content -->
    <div v-else-if="product">
      <!-- Breadcrumbs -->
      <nav aria-label="Breadcrumb" class="mb-8">
        <ol class="flex items-center space-x-2 text-sm">
          <li>
            <router-link :to="{ name: 'shop' }" class="text-gray-400 hover:text-yellow-400">
              Home
            </router-link>
          </li>
          <li>
            <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
            </svg>
          </li>
          <li v-if="product.category">
            <router-link 
              :to="{ name: 'shop', query: { category: product.category.id } }" 
              class="text-gray-400 hover:text-yellow-400"
            >
              {{ product.category.name }}
            </router-link>
          </li>
          <li>
            <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
            </svg>
          </li>
          <li class="text-yellow-400 font-medium">
            {{ product.title }}
          </li>
        </ol>
      </nav>

      <div class="lg:grid lg:grid-cols-12 lg:gap-x-8">
        <!-- Image Gallery -->
        <div class="lg:col-span-7">
          <!-- Main Image -->
          <div class="aspect-square w-full overflow-hidden rounded-lg bg-gradient-to-br from-gray-800 to-gray-900 border border-white/10">
            <img 
              v-if="product.image_url"
              :src="product.image_url" 
              :alt="product.title"
              class="h-full w-full object-cover object-center"
            />
            <div v-else class="h-full w-full flex items-center justify-center">
              <svg class="w-32 h-32 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
          </div>

          <!-- Thumbnail Gallery (if multiple images) -->
          <!-- <div class="mt-4 grid grid-cols-4 gap-4">
            <div v-for="i in 4" :key="i" class="aspect-square overflow-hidden rounded-lg border-2 border-transparent hover:border-yellow-400 cursor-pointer">
              <img :src="product.image_url" class="h-full w-full object-cover" />
            </div>
          </div> -->
        </div>

        <!-- Product Info -->
        <div class="mt-8 lg:col-span-5 lg:mt-0">
          <!-- Title & Price -->
          <div>
            <h1 class="text-3xl font-bold text-white">{{ product.title }}</h1>
            
            <div class="mt-3 flex items-center gap-4">
              <div class="flex items-baseline gap-2">
                <p v-if="product.is_on_sale" class="text-2xl line-through text-gray-500">
                  ${{ product.price }}
                </p>
                <p class="text-3xl font-bold text-yellow-400">
                  ${{ product.is_on_sale ? product.sale_price : product.price }}
                </p>
              </div>
              
              <span v-if="product.is_on_sale" class="inline-flex items-center rounded-md bg-red-500 px-3 py-1 text-sm font-bold text-white">
                -{{ product.discount_percentage }}% OFF
              </span>
            </div>

            <!-- Rating (Placeholder) -->
            <div class="mt-4 flex items-center gap-2">
              <div class="flex">
                <svg v-for="i in 5" :key="i" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
              <span class="text-gray-400 text-sm">(24 reviews)</span>
            </div>
          </div>

          <!-- Stock Status -->
          <div class="mt-6">
            <div v-if="product.stock_quantity > 0" class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <span class="text-green-400 font-medium">
                In stock - {{ product.stock_quantity }} available
              </span>
            </div>
            <div v-else class="flex items-center gap-2">
              <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              <span class="text-red-400 font-medium">Out of stock</span>
            </div>
          </div>

          <!-- Add to Cart Form -->
          <form @submit.prevent="addToCart" class="mt-8">
            <!-- Quantity Selector -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Quantity
              </label>
              <div class="flex items-center gap-3">
                <button 
                  type="button"
                  @click="decrementQuantity"
                  class="w-10 h-10 rounded-lg bg-white/10 border border-white/20 text-white hover:bg-white/20 transition-colors"
                >
                  <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
                
                <input 
                  v-model.number="quantity"
                  type="number" 
                  min="1" 
                  :max="product.stock_quantity"
                  class="w-20 h-10 text-center bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-500"
                />
                
                <button 
                  type="button"
                  @click="incrementQuantity"
                  :disabled="quantity >= product.stock_quantity"
                  class="w-10 h-10 rounded-lg bg-white/10 border border-white/20 text-white hover:bg-white/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Add to Cart Button -->
            <button
              type="submit"
              :disabled="product.stock_quantity === 0"
              class="w-full flex items-center justify-center rounded-lg border border-transparent bg-gradient-to-r from-yellow-400 to-orange-500 px-8 py-4 text-base font-bold text-gray-900 hover:from-yellow-500 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg"
            >
              <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              {{ product.stock_quantity === 0 ? 'Out of Stock' : 'Add to Cart' }}
            </button>
          </form>

          <!-- Features -->
          <div class="mt-8 border-t border-white/10 pt-8">
            <div class="grid grid-cols-2 gap-4">
              <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="text-sm text-gray-300">Lifetime Warranty</span>
              </div>
              <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                <span class="text-sm text-gray-300">Free Shipping</span>
              </div>
              <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="text-sm text-gray-300">Easy Returns</span>
              </div>
              <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm text-gray-300">Secure Payment</span>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="mt-8 border-t border-white/10 pt-8">
            <h2 class="text-lg font-bold text-white mb-4">Description</h2>
            <div class="prose prose-invert prose-sm max-w-none text-gray-300">
              {{ product.description || 'No description available.' }}
            </div>
          </div>

          <!-- Category -->
          <div v-if="product.category" class="mt-6">
            <p class="text-sm text-gray-400">
              Category: 
              <router-link 
                :to="{ name: 'shop', query: { category: product.category.id } }" 
                class="text-yellow-400 hover:text-yellow-300"
              >
                {{ product.category.name }}
              </router-link>
            </p>
          </div>
        </div>
      </div>

      <!-- Related Products -->
      <div v-if="relatedProducts.length" class="mt-20">
        <h2 class="text-2xl font-bold text-white mb-8">You May Also Like</h2>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div 
            v-for="relatedProduct in relatedProducts" 
            :key="relatedProduct.id"
            @click="goToProduct(relatedProduct.slug)"
            class="group cursor-pointer"
          >
            <div class="aspect-square w-full overflow-hidden rounded-lg bg-gradient-to-br from-gray-800 to-gray-900 border border-white/10">
              <img 
                v-if="relatedProduct.image_url"
                :src="relatedProduct.image_url" 
                :alt="relatedProduct.title"
                class="h-full w-full object-cover object-center group-hover:opacity-75"
              />
            </div>
            <h3 class="mt-4 text-sm font-medium text-white group-hover:text-yellow-400">
              {{ relatedProduct.title }}
            </h3>
            <p class="mt-1 text-lg font-bold text-yellow-400">
              ${{ relatedProduct.is_on_sale ? relatedProduct.sale_price : relatedProduct.price }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Not Found -->
    <div v-else class="text-center py-20">
      <h2 class="text-2xl font-bold text-white">Product Not Found</h2>
      <router-link :to="{ name: 'shop' }" class="mt-4 inline-block text-yellow-400 hover:text-yellow-300">
        ← Back to Shop
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axiosClient from '../axios'
import { useStore } from 'vuex'

const route = useRoute()
const router = useRouter()
const store = useStore()

const product = ref(null)
const relatedProducts = ref([])
const loading = ref(false)
const quantity = ref(1)

onMounted(async () => {
  await loadProduct()
})

async function loadProduct() {
  loading.value = true
  try {
    const slug = route.params.slug
    const response = await axiosClient.get(`/products/${slug}`)
    product.value = response.data.data || response.data
    
    // Load related products
    if (product.value.category_id) {
      await loadRelatedProducts()
    }
  } catch (error) {
    console.error('Failed to load product:', error)
  } finally {
    loading.value = false
  }
}

async function loadRelatedProducts() {
  try {
    const response = await axiosClient.get('/products', {
      params: {
        category_id: product.value.category_id,
        per_page: 4
      }
    })
    // Filter out current product
    relatedProducts.value = (response.data.data || []).filter(p => p.id !== product.value.id).slice(0, 4)
  } catch (error) {
    console.error('Failed to load related products:', error)
  }
}

function incrementQuantity() {
  if (quantity.value < product.value.stock_quantity) {
    quantity.value++
  }
}

function decrementQuantity() {
  if (quantity.value > 1) {
    quantity.value--
  }
}

async function addToCart() {
  if (!store.getters.isAuthenticated) {
    // Redirect to login if not authenticated
    router.push({ name: 'login' })
    return
  }

  if (product.value.stock_quantity === 0) {
    alert('This product is out of stock')
    return
  }

  try {
    await store.dispatch('cart/addToCart', {
      product_id: product.value.id,
      quantity: quantity.value,
    })

    // Show success message
    alert(`Successfully Added ${quantity.value}x "${product.value.title}" to cart!`)

    // Reset quantity
    quantity.value = 1

  } catch (error) {
    const message = error.response?.data?.message || 'Failed to add to cart'
    alert(`❌ ${message}`)
  }
}

function goToProduct(slug) {
  router.push({ name: 'product', params: { slug } })
  window.scrollTo({ top: 0, behavior: 'smooth' })
  loadProduct() // Reload product
}
</script>