<template>
  <TransitionRoot :show="isOpen" as="template">
    <Dialog class="relative z-50" @close="$emit('close')">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-gradient-to-br from-gray-900 to-gray-800 border border-white/20 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
              <!-- ✅ ADD THIS v-if CHECK -->
              <div v-if="product">
                <!-- Close Button -->
                <button
                  type="button"
                  class="absolute right-4 top-4 z-10 text-gray-400 hover:text-white bg-black/50 rounded-full p-2 backdrop-blur-sm transition-colors"
                  @click="$emit('close')"
                >
                  <span class="sr-only">Close</span>
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>

                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-12 p-6 lg:p-8">
                  <!-- Product Image -->
                  <div class="sm:col-span-5">
                    <div class="aspect-square w-full overflow-hidden rounded-lg bg-gradient-to-br from-gray-800 to-gray-900 border border-white/10">
                      <img
                        v-if="product.image_url"
                        :src="product.image_url"
                        :alt="product.title"
                        class="h-full w-full object-cover object-center"
                      />
                      <div v-else class="h-full w-full flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                      </div>

                      <!-- Badges on Image -->
                      <div class="absolute top-2 left-2 flex flex-col gap-2">
                        <span v-if="product.is_featured" class="inline-flex items-center rounded-md bg-yellow-400 px-2 py-1 text-xs font-bold text-gray-900">
                          ⭐ FEATURED
                        </span>
                        <span v-if="product.is_on_sale" class="inline-flex items-center rounded-md bg-red-500 px-2 py-1 text-xs font-bold text-white">
                          -{{ product.discount_percentage }}% OFF
                        </span>
                      </div>
                    </div>

                    <!-- View Full Details Link -->
                    <router-link
                      :to="{ name: 'product', params: { slug: product.slug } }"
                      @click="$emit('close')"
                      class="mt-4 block text-center text-sm font-medium text-yellow-400 hover:text-yellow-300"
                    >
                      View full details →
                    </router-link>
                  </div>

                  <!-- Product Info -->
                  <div class="sm:col-span-7">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-white">
                      {{ product.title }}
                    </h2>

                    <!-- Category -->
                    <p v-if="product.category" class="mt-1 text-sm text-gray-400">
                      {{ product.category.name }}
                    </p>

                    <!-- Price & Rating -->
                    <div class="mt-4 flex items-center gap-4">
                      <div class="flex items-baseline gap-2">
                        <p v-if="product.is_on_sale" class="text-lg line-through text-gray-500">
                          ${{ product.price }}
                        </p>
                        <p class="text-2xl font-bold text-yellow-400">
                          ${{ product.is_on_sale ? product.sale_price : product.price }}
                        </p>
                      </div>

                      <!-- Rating Stars -->
                      <div class="flex items-center">
                        <div class="flex">
                          <svg v-for="i in 5" :key="i" class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                          </svg>
                        </div>
                      </div>
                    </div>

                    <!-- Stock Status -->
                    <div class="mt-4">
                      <div v-if="product.stock_quantity > 0" class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-green-400">
                          In stock - {{ product.stock_quantity }} available
                        </span>
                      </div>
                      <div v-else class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-red-400">Out of stock</span>
                      </div>
                    </div>

                    <!-- Description (truncated) -->
                    <div class="mt-6">
                      <h3 class="text-sm font-medium text-gray-300">Description</h3>
                      <p class="mt-2 text-sm text-gray-400 line-clamp-3">
                        {{ product.description || 'No description available.' }}
                      </p>
                    </div>

                    <!-- Quick Add Form -->
                    <form @submit.prevent="handleAddToCart" class="mt-6">
                      <!-- Quantity -->
                      <div class="mb-4">
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
                        class="w-full flex items-center justify-center rounded-lg border border-transparent bg-gradient-to-r from-yellow-400 to-orange-500 px-8 py-3 text-base font-bold text-gray-900 hover:from-yellow-500 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                      >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ product.stock_quantity === 0 ? 'Out of Stock' : 'Add to Cart' }}
                      </button>
                    </form>

                    <!-- Features -->
                    <div class="mt-6 grid grid-cols-2 gap-3 text-xs">
                      <div class="flex items-center gap-2 text-gray-400">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Lifetime Warranty</span>
                      </div>
                      <div class="flex items-center gap-2 text-gray-400">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <span>Free Shipping</span>
                      </div>
                      <div class="flex items-center gap-2 text-gray-400">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Easy Returns</span>
                      </div>
                      <div class="flex items-center gap-2 text-gray-400">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Secure Payment</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ✅ END v-if -->
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
  
    import { useStore } from 'vuex'

import { useRouter } from 'vue-router'
import { ref, watch } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'


const store = useStore()
const router = useRouter()

const props = defineProps({
  isOpen: Boolean,
  product: {
    type: Object,
    default: null  // ✅ Add default value
  }
})

const emit = defineEmits(['close', 'add-to-cart'])

const quantity = ref(1)

// Reset quantity when modal opens
watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    quantity.value = 1
  }
})

function incrementQuantity() {
  if (props.product && quantity.value < props.product.stock_quantity) {
    quantity.value++
  }
}

function decrementQuantity() {
  if (quantity.value > 1) {
    quantity.value--
  }
}

async function handleAddToCart() {
  if (!props.product) return

  if (!store.getters.isAuthenticated) {
    emit('close')
    router.push({ name: 'login' })
    return
  }

  if (props.product.stock_quantity === 0) {
    alert('This product is out of stock')
    return
  }

  try {
    await store.dispatch('cart/addToCart', {
      product_id: props.product.id,
      quantity: quantity.value,
    })

    // Show success message
    alert(`✅ Added ${quantity.value}x "${props.product.title}" to cart!`)

    // Close modal
    emit('close')

  } catch (error) {
    const message = error.response?.data?.message || 'Failed to add to cart'
    alert(`❌ ${message}`)
  }
}
</script>