<template>
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-yellow-400"></div>
    </div>

    <!-- Order Details -->
    <div v-else-if="order">
      <!-- Header -->
      <div class="text-center mb-12">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-500/20 mb-4">
          <svg class="h-10 w-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-white">Order Confirmed!</h1>
        <p class="mt-2 text-gray-400">Thank you for your order. Your Viking gear is on its way!</p>
        <div class="mt-4 text-sm text-gray-400">
          You earned <span class="text-yellow-400 font-bold">{{ order.tokens_earned }} tokens</span> from this order! 🪙
        </div>
      </div>

      <!-- Order Summary -->
      <div class="mt-8 border-b border-white/10 pb-5 sm:flex sm:justify-between">
        <dl class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
          <div class="flex gap-x-2">
            <dt class="text-gray-400">Order number</dt>
            <dd class="font-medium text-white">{{ order.order_number }}</dd>
          </div>
          <div class="flex gap-x-2">
            <dt>
              <span class="sr-only">Date</span>
              <span class="text-gray-600" aria-hidden="true">•</span>
            </dt>
            <dd class="font-medium text-white">
              <time :datetime="order.created_at">{{ formatDate(order.created_at) }}</time>
            </dd>
          </div>
          <div class="flex gap-x-2">
            <dt>
              <span class="sr-only">Status</span>
              <span class="text-gray-600" aria-hidden="true">•</span>
            </dt>
            <dd class="font-medium">
              <span :class="getStatusColor(order.status)" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium">
                {{ order.status }}
              </span>
            </dd>
          </div>
        </dl>
        <div class="mt-4 sm:mt-0">
          <router-link
            :to="{ name: 'orders' }"
            class="font-medium text-yellow-400 hover:text-yellow-300"
          >
            View all orders
            <span aria-hidden="true"> →</span>
          </router-link>
        </div>
      </div>

      <!-- Products -->
      <div class="mt-8">
        <h2 class="text-lg font-medium text-white mb-6">Order Items</h2>

        <div class="space-y-8">
          <div
            v-for="item in order.items"
            :key="item.id"
            class="grid grid-cols-1 text-sm sm:grid-cols-12 sm:gap-x-6 bg-white/5 backdrop-blur-lg rounded-lg p-6 border border-white/10"
          >
            <!-- Product Image -->
            <div class="sm:col-span-3">
              <img
                v-if="item.product.image_url"
                :src="item.product.image_url"
                :alt="item.product.title"
                class="aspect-square w-full rounded-lg object-cover"
              />
              <div v-else class="aspect-square w-full rounded-lg bg-gray-800 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
            </div>

            <!-- Product Details -->
            <div class="mt-6 sm:col-span-9 sm:mt-0">
              <div class="flex justify-between">
                <div>
                  <h3 class="text-lg font-medium text-white">
                    <router-link :to="{ name: 'product', params: { slug: item.product.slug } }" class="hover:text-yellow-400">
                      {{ item.product.title }}
                    </router-link>
                  </h3>
                  <p class="mt-1 text-gray-400">{{ item.product.category?.name }}</p>
                  <p class="mt-2 text-gray-400">Quantity: {{ item.quantity }}</p>
                </div>
                <div class="text-right">
                  <p class="text-lg font-medium text-yellow-400">${{ (item.unit_price * item.quantity).toFixed(2) }}</p>
                  <p class="mt-1 text-sm text-gray-400">${{ parseFloat(item.unit_price).toFixed(2) }} each</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Shipping & Billing Info -->
      <div class="mt-16">
        <div class="rounded-lg bg-white/5 backdrop-blur-lg px-6 py-6 lg:grid lg:grid-cols-12 lg:gap-x-8 border border-white/10">
          <!-- Shipping Address -->
          <dl class="grid grid-cols-1 gap-6 text-sm sm:grid-cols-2 lg:col-span-5">
            <div>
              <dt class="font-medium text-white mb-3">Shipping Address</dt>
              <dd class="text-gray-400">
                <span class="block">{{ order.details.first_name }} {{ order.details.last_name }}</span>
                <span class="block">{{ order.details.address1 }}</span>
                <span v-if="order.details.address2" class="block">{{ order.details.address2 }}</span>
                <span class="block">{{ order.details.city }}<span v-if="order.details.state">, {{ order.details.state }}</span> {{ order.details.zipcode }}</span>
                <span class="block">{{ order.details.country_code }}</span>
              </dd>
            </div>
            <div>
              <dt class="font-medium text-white mb-3">Contact Information</dt>
              <dd class="space-y-1 text-gray-400">
                <p>{{ order.details.email }}</p>
                <p v-if="order.details.phone">{{ order.details.phone }}</p>
              </dd>
            </div>
          </dl>

          <!-- Order Totals -->
          <dl class="mt-8 divide-y divide-white/10 text-sm lg:col-span-7 lg:mt-0">
            <div class="flex items-center justify-between pb-4">
              <dt class="text-gray-400">Subtotal</dt>
              <dd class="font-medium text-white">${{ calculateSubtotal() }}</dd>
            </div>
            <div class="flex items-center justify-between py-4">
              <dt class="text-gray-400">Shipping</dt>
              <dd class="font-medium text-white">FREE</dd>
            </div>
            <div class="flex items-center justify-between py-4">
              <dt class="text-gray-400">Tax (10%)</dt>
              <dd class="font-medium text-white">${{ calculateTax() }}</dd>
            </div>
            <div v-if="order.discount_amount > 0" class="flex items-center justify-between py-4">
              <dt class="text-gray-400">Discount</dt>
              <dd class="font-medium text-green-400">-${{ parseFloat(order.discount_amount).toFixed(2) }}</dd>
            </div>
            <div class="flex items-center justify-between pt-4">
              <dt class="text-base font-medium text-white">Order Total</dt>
              <dd class="text-base font-bold text-yellow-400">${{ parseFloat(order.total_price).toFixed(2) }}</dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Order Status Progress -->
      <div class="mt-12 bg-white/5 backdrop-blur-lg rounded-lg p-6 border border-white/10">
        <h3 class="text-lg font-medium text-white mb-6">Order Status</h3>
        
        <div class="overflow-hidden rounded-full bg-gray-700">
          <div 
            class="h-3 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 transition-all duration-500"
            :style="{ width: getStatusWidth(order.status) }"
          ></div>
        </div>
        
        <div class="mt-6 grid grid-cols-4 text-center text-sm">
          <div :class="['pending', 'processing', 'shipped', 'delivered'].includes(order.status) ? 'text-yellow-400' : 'text-gray-500'">
            Order Placed
          </div>
          <div :class="['processing', 'shipped', 'delivered'].includes(order.status) ? 'text-yellow-400' : 'text-gray-500'">
            Processing
          </div>
          <div :class="['shipped', 'delivered'].includes(order.status) ? 'text-yellow-400' : 'text-gray-500'">
            Shipped
          </div>
          <div :class="order.status === 'delivered' ? 'text-yellow-400' : 'text-gray-500'">
            Delivered
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
        <router-link
          :to="{ name: 'shop' }"
          class="inline-flex items-center justify-center px-6 py-3 border border-white/20 rounded-lg text-white hover:bg-white/10 transition-colors"
        >
          Continue Shopping
        </router-link>
        <router-link
          :to="{ name: 'orders' }"
          class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 transition-all"
        >
          View All Orders
        </router-link>
      </div>
    </div>

    <!-- Error State -->
    <div v-else class="text-center py-20">
      <h2 class="text-2xl font-bold text-white">Order Not Found</h2>
      <p class="mt-2 text-gray-400">We couldn't find this order.</p>
      <router-link
        :to="{ name: 'shop' }"
        class="mt-6 inline-block px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600"
      >
        Back to Shop
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axiosClient from '../axios'

const route = useRoute()
const router = useRouter()

const order = ref(null)
const loading = ref(true)

onMounted(async () => {
  await loadOrder()
})

async function loadOrder() {
  loading.value = true
  try {
    const orderId = route.params.id
    console.log('Loading order ID:', orderId)
    
    const response = await axiosClient.get(`/orders/${orderId}`)
    console.log('Order response:', response.data)
    
    order.value = response.data.order
    console.log('Order loaded:', order.value)
    
  } catch (error) {
    console.error('Failed to load order:', error)
    console.error('Error details:', error.response)
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

function getStatusColor(status) {
  const colors = {
    pending: 'bg-yellow-500/20 text-yellow-400',
    processing: 'bg-blue-500/20 text-blue-400',
    shipped: 'bg-purple-500/20 text-purple-400',
    delivered: 'bg-green-500/20 text-green-400',
    cancelled: 'bg-red-500/20 text-red-400',
  }
  return colors[status] || 'bg-gray-500/20 text-gray-400'
}

function getStatusWidth(status) {
  const widths = {
    pending: '25%',
    processing: '50%',
    shipped: '75%',
    delivered: '100%',
  }
  return widths[status] || '25%'
}

function calculateSubtotal() {
  if (!order.value?.items) return '0.00'
  const subtotal = order.value.items.reduce((sum, item) => {
    return sum + (item.unit_price * item.quantity)
  }, 0)
  return subtotal.toFixed(2)
}

function calculateTax() {
  const subtotal = parseFloat(calculateSubtotal())
  const tax = subtotal * 0.10
  return tax.toFixed(2)
}
</script>