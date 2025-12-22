<template>
  <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
    <h1 class="text-3xl font-bold text-white">Shopping Cart</h1>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-yellow-400"></div>
    </div>

    <!-- Empty Cart -->
    <div v-else-if="!items.length" class="text-center py-20">
      <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
      </svg>
      <h2 class="mt-4 text-2xl font-bold text-white">Your cart is empty</h2>
      <p class="mt-2 text-gray-400">Add some legendary Viking gear to get started!</p>
      <router-link
        :to="{ name: 'shop' }"
        class="mt-6 inline-block px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600"
      >
        Continue Shopping
      </router-link>
    </div>

    <!-- Cart with Items -->
    <div v-else class="mt-12">
      <div>
        <h2 class="sr-only">Items in your shopping cart</h2>

        <ul role="list" class="divide-y divide-white/10 border-b border-t border-white/10">
          <li
            v-for="item in items"
            :key="item.id"
            class="flex py-6 sm:py-10"
          >
            <!-- Product Image -->
            <div class="shrink-0">
              <img
                v-if="item.product.image_url"
                :src="item.product.image_url"
                :alt="item.product.title"
                class="h-24 w-24 rounded-lg object-cover sm:h-32 sm:w-32 border border-white/10"
              />
              <div v-else class="h-24 w-24 sm:h-32 sm:w-32 rounded-lg bg-gray-800 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
            </div>

            <!-- Product Details -->
            <div class="relative ml-4 flex flex-1 flex-col justify-between sm:ml-6">
              <div>
                <div class="flex justify-between sm:grid sm:grid-cols-2">
                  <div class="pr-6">
                    <h3 class="text-sm">
                      <router-link
                        :to="{ name: 'product', params: { slug: item.product.slug } }"
                        class="font-medium text-white hover:text-yellow-400"
                      >
                        {{ item.product.title }}
                      </router-link>
                    </h3>
                    <p v-if="item.product.category" class="mt-1 text-sm text-gray-400">
                      {{ item.product.category.name }}
                    </p>
                    <p v-if="item.product.is_on_sale" class="mt-1 text-sm text-red-400">
                      Sale: -{{ Math.round(((item.product.price - item.product.sale_price) / item.product.price) * 100) }}%
                    </p>
                  </div>

                  <!-- Price -->
                  <p class="text-right text-sm font-medium text-yellow-400">
                    ${{ item.price }}
                  </p>
                </div>

                <!-- Quantity & Remove -->
                <div class="mt-4 flex items-center sm:absolute sm:left-1/2 sm:top-0 sm:mt-0 sm:block">
                  <!-- Quantity Selector -->
                  <div class="flex items-center gap-2">
                    <button
                      @click="decrementQuantity(item)"
                      class="w-8 h-8 rounded bg-white/10 border border-white/20 text-white hover:bg-white/20"
                    >
                      -
                    </button>
                    
                    <input
                      :value="item.quantity"
                      @change="updateQuantity(item, $event)"
                      type="number"
                      min="1"
                      :max="item.product.stock_quantity"
                      class="w-16 h-8 text-center bg-white/10 border border-white/20 rounded text-white focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    />
                    
                    <button
                      @click="incrementQuantity(item)"
                      :disabled="item.quantity >= item.product.stock_quantity"
                      class="w-8 h-8 rounded bg-white/10 border border-white/20 text-white hover:bg-white/20 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      +
                    </button>
                  </div>

                  <!-- Remove Button -->
                  <button
                    @click="removeItem(item.id)"
                    type="button"
                    class="ml-4 text-sm font-medium text-red-400 hover:text-red-300 sm:ml-0 sm:mt-3"
                  >
                    Remove
                  </button>
                </div>
              </div>

              <!-- Stock Status -->
              <p class="mt-4 flex items-center gap-2 text-sm">
                <svg
                  v-if="item.product.stock_quantity > 0"
                  class="h-5 w-5 text-green-400"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <svg
                  v-else
                  class="h-5 w-5 text-red-400"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span :class="item.product.stock_quantity > 0 ? 'text-green-400' : 'text-red-400'">
                  {{ item.product.stock_quantity > 0 ? `${item.product.stock_quantity} in stock` : 'Out of stock' }}
                </span>
              </p>

              <!-- Item Subtotal -->
              <p class="mt-2 text-sm text-gray-400">
                Item total: <span class="font-medium text-white">${{ item.subtotal.toFixed(2) }}</span>
              </p>
            </div>
          </li>
        </ul>
      </div>

      <!-- Order Summary -->
      <div class="mt-10 sm:ml-32 sm:pl-6">
        <div class="rounded-lg bg-white/10 backdrop-blur-lg px-4 py-6 sm:p-6 lg:p-8 border border-white/20">
          <h2 class="text-lg font-medium text-white mb-4">Order Summary</h2>

          <div class="flow-root">
            <dl class="-my-4 divide-y divide-white/10 text-sm">
              <div class="flex items-center justify-between py-4">
                <dt class="text-gray-300">Subtotal</dt>
                <dd class="font-medium text-white">${{ summary.subtotal.toFixed(2) }}</dd>
              </div>
              <div class="flex items-center justify-between py-4">
                <dt class="text-gray-300">Tax (10%)</dt>
                <dd class="font-medium text-white">${{ summary.tax.toFixed(2) }}</dd>
              </div>
              <div class="flex items-center justify-between py-4 border-t border-white/10">
                <dt class="text-base font-medium text-white">Order total</dt>
                <dd class="text-base font-bold text-yellow-400">${{ summary.total.toFixed(2) }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Checkout Button -->
        <div class="mt-10">
          <button
            @click="goToCheckout"
            type="button"
            class="w-full rounded-lg border border-transparent bg-gradient-to-r from-yellow-400 to-orange-500 px-4 py-3 text-base font-bold text-gray-900 shadow-sm hover:from-yellow-500 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900"
          >
            Proceed to Checkout
          </button>
        </div>

        <!-- Continue Shopping -->
        <div class="mt-6 text-center text-sm text-gray-400">
          <p>
            or
            <router-link
              :to="{ name: 'shop' }"
              class="font-medium text-yellow-400 hover:text-yellow-300"
            >
              Continue Shopping
              <span aria-hidden="true"> →</span>
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const store = useStore()
const router = useRouter()

const items = computed(() => store.getters['cart/cartItems'])
const summary = computed(() => store.getters['cart/cartSummary'])
const loading = computed(() => store.getters['cart/isLoading'])

onMounted(async () => {
  await store.dispatch('cart/fetchCart')
})

async function updateQuantity(item, event) {
  const newQuantity = parseInt(event.target.value)
  if (newQuantity < 1 || newQuantity > item.product.stock_quantity) {
    return
  }

  try {
    await store.dispatch('cart/updateCartItem', {
      id: item.id,
      quantity: newQuantity,
    })
  } catch (error) {
    alert(error.response?.data?.message || 'Failed to update quantity')
  }
}

async function incrementQuantity(item) {
  if (item.quantity >= item.product.stock_quantity) return

  try {
    await store.dispatch('cart/updateCartItem', {
      id: item.id,
      quantity: item.quantity + 1,
    })
  } catch (error) {
    alert(error.response?.data?.message || 'Failed to update quantity')
  }
}

async function decrementQuantity(item) {
  if (item.quantity <= 1) return

  try {
    await store.dispatch('cart/updateCartItem', {
      id: item.id,
      quantity: item.quantity - 1,
    })
  } catch (error) {
    alert(error.response?.data?.message || 'Failed to update quantity')
  }
}

async function removeItem(id) {
  if (!confirm('Remove this item from your cart?')) return

  try {
    await store.dispatch('cart/removeFromCart', id)
  } catch (error) {
    alert('Failed to remove item')
  }
}

function goToCheckout() {
  router.push({ name: 'checkout' })
}
</script>