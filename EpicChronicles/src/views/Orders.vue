<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
    <div class="mx-auto max-w-2xl lg:max-w-4xl">
      <h1 class="text-3xl font-bold text-white">Order History</h1>
      <p class="mt-2 text-sm text-gray-400">Check the status of recent orders and reorder items.</p>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-yellow-400"></div>
    </div>

    <!-- Orders List -->
    <div v-else-if="orders.length" class="mt-16">
      <div class="mx-auto max-w-2xl space-y-8 lg:max-w-4xl">
        <div
          v-for="order in orders"
          :key="order.id"
          class="border border-white/10 bg-white/5 backdrop-blur-lg shadow-sm rounded-lg overflow-hidden"
        >
          <!-- Order Header -->
          <div class="p-6 border-b border-white/10 sm:grid sm:grid-cols-4 sm:gap-x-6">
            <dl class="grid flex-1 grid-cols-2 gap-x-6 text-sm sm:col-span-3 sm:grid-cols-3">
              <div>
                <dt class="font-medium text-gray-300">Order number</dt>
                <dd class="mt-1 text-white">{{ order.order_number }}</dd>
              </div>
              <div class="hidden sm:block">
                <dt class="font-medium text-gray-300">Date placed</dt>
                <dd class="mt-1 text-white">
                  {{ formatDate(order.created_at) }}
                </dd>
              </div>
              <div>
                <dt class="font-medium text-gray-300">Total amount</dt>
                <dd class="mt-1 font-medium text-yellow-400">
                  ${{ parseFloat(order.total_price).toFixed(2) }}
                </dd>
              </div>
            </dl>

            <div class="mt-6 sm:mt-0 sm:flex sm:items-center sm:justify-end">
              <router-link
                :to="{ name: 'order-detail', params: { id: order.id } }"
                class="flex items-center justify-center rounded-lg border border-white/20 bg-white/10 px-4 py-2 text-sm font-medium text-white hover:bg-white/20 transition-colors"
              >
                View Order
              </router-link>
            </div>
          </div>

          <!-- Order Items -->
          <ul role="list" class="divide-y divide-white/10">
            <li
              v-for="item in order.items"
              :key="item.id"
              class="p-6"
            >
              <div class="flex items-center sm:items-start">
                <!-- Product Image -->
                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-gray-800 sm:h-32 sm:w-32">
                  <img
                    v-if="item.product.image_url"
                    :src="item.product.image_url"
                    :alt="item.product.title"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="h-full w-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                </div>

                <!-- Product Details -->
                <div class="ml-6 flex-1 text-sm">
                  <div class="font-medium text-white sm:flex sm:justify-between">
                    <h5>{{ item.product.title }}</h5>
                    <p class="mt-2 sm:mt-0 text-yellow-400">
                      ${{ parseFloat(item.unit_price).toFixed(2) }}
                    </p>
                  </div>
                  <p class="mt-1 text-gray-400">Quantity: {{ item.quantity }}</p>
                  <p class="mt-1 text-gray-400">{{ item.product.category?.name }}</p>
                </div>
              </div>

              <!-- Order Status & Actions -->
              <div class="mt-6 sm:flex sm:justify-between">
                <div class="flex items-center">
                  <svg
                    v-if="order.status === 'delivered'"
                    class="h-5 w-5 text-green-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <svg
                    v-else
                    class="h-5 w-5 text-yellow-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                  </svg>
                  <p class="ml-2 text-sm font-medium text-gray-400 capitalize">
                    {{ order.status }}
                  </p>
                </div>

                <div class="mt-6 flex items-center space-x-4 divide-x divide-white/10 text-sm font-medium sm:ml-4 sm:mt-0">
                  <div class="flex flex-1 justify-center">
                    <router-link
                      :to="{ name: 'product', params: { slug: item.product.slug } }"
                      class="whitespace-nowrap text-yellow-400 hover:text-yellow-300"
                    >
                      View product
                    </router-link>
                  </div>
                  <div class="flex flex-1 justify-center pl-4">
                    <button
                      @click="buyAgain(item.product.id)"
                      class="whitespace-nowrap text-yellow-400 hover:text-yellow-300"
                    >
                      Buy again
                    </button>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-20">
      <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
      </svg>
      <h2 class="mt-4 text-2xl font-bold text-white">No orders yet</h2>
      <p class="mt-2 text-gray-400">Start shopping to see your orders here!</p>
      <router-link
        :to="{ name: 'shop' }"
        class="mt-6 inline-block px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600"
      >
        Continue Shopping
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import axiosClient from '../axios'

const store = useStore()
const router = useRouter()

const orders = ref([])
const loading = ref(true)

onMounted(async () => {
  await loadOrders()
})

async function loadOrders() {
  loading.value = true
  try {
    const response = await axiosClient.get('/orders')
    orders.value = response.data.data || []
  } catch (error) {
    console.error('Failed to load orders:', error)
  } finally {
    loading.value = false
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

async function buyAgain(productId) {
  try {
    await store.dispatch('cart/addToCart', {
      product_id: productId,
      quantity: 1,
    })
    alert('✅ Product added to cart!')
  } catch (error) {
    alert('❌ Failed to add product to cart')
  }
}
</script>