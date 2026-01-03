<template>
  <div>
    <!-- Background color split screen for large screens -->
    <div class="fixed left-0 top-0 hidden h-full w-1/2 bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 lg:block" aria-hidden="true"></div>
    <div class="fixed right-0 top-0 hidden h-full w-1/2 bg-black/30 lg:block" aria-hidden="true"></div>

    <div class="relative mx-auto grid max-w-7xl grid-cols-1 gap-x-16 lg:grid-cols-2 lg:px-8 xl:gap-x-48">
      <h1 class="sr-only">Checkout</h1>

      <!-- Order Summary (Right Side on Desktop) -->
      <section aria-labelledby="summary-heading" class="bg-black/30 backdrop-blur-lg px-4 pb-10 pt-16 sm:px-6 lg:col-start-2 lg:row-start-1 lg:bg-transparent lg:px-0 lg:pb-16">
        <div class="mx-auto max-w-lg lg:max-w-none">
          <h2 id="summary-heading" class="text-lg font-medium text-white">Order Summary</h2>

          <!-- Loading State -->
          <div v-if="loading" class="flex items-center justify-center py-10">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-400"></div>
          </div>

          <!-- Cart Items -->
          <ul v-else role="list" class="divide-y divide-white/10 text-sm font-medium text-white">
            <li v-for="item in cartItems" :key="item.id" class="flex items-start space-x-4 py-6">
              <img
                v-if="item.product.image_url"
                :src="item.product.image_url"
                :alt="item.product.title"
                class="h-20 w-20 flex-none rounded-md object-cover border border-white/10"
              />
              <div v-else class="h-20 w-20 flex-none rounded-md bg-gray-800 border border-white/10"></div>
              
              <div class="flex-auto space-y-1">
                <h3 class="text-white">{{ item.product.title }}</h3>
                <p class="text-gray-400">{{ item.product.category?.name }}</p>
                <p class="text-gray-400">Qty: {{ item.quantity }}</p>
              </div>
              <p class="flex-none text-base font-medium text-yellow-400">${{ item.subtotal.toFixed(2) }}</p>
            </li>
          </ul>

          <!-- Order Totals (Desktop) -->
          <dl class="hidden space-y-6 border-t border-white/10 pt-6 text-sm font-medium text-white lg:block">
            <div class="flex items-center justify-between">
              <dt class="text-gray-400">Subtotal</dt>
              <dd class="text-white">${{ cartSummary.subtotal.toFixed(2) }}</dd>
            </div>

            <div class="flex items-center justify-between">
              <dt class="text-gray-400">Shipping</dt>
              <dd class="text-white">FREE</dd>
            </div>

            <div class="flex items-center justify-between">
              <dt class="text-gray-400">Taxes</dt>
              <dd class="text-white">${{ cartSummary.tax.toFixed(2) }}</dd>
            </div>

            <div class="flex items-center justify-between border-t border-white/10 pt-6">
              <dt class="text-base text-white">Total</dt>
              <dd class="text-base text-yellow-400 font-bold">${{ cartSummary.total.toFixed(2) }}</dd>
            </div>
          </dl>

          <!-- Order Totals (Mobile Popover) -->
          <Popover class="fixed inset-x-0 bottom-0 flex flex-col-reverse text-sm font-medium text-white lg:hidden" v-slot="{ open }">
            <div class="relative z-10 border-t border-white/10 bg-black/80 backdrop-blur-lg px-4 sm:px-6">
              <div class="mx-auto max-w-lg">
                <PopoverButton class="flex w-full items-center py-6 font-medium text-white">
                  <span class="mr-auto text-base">Total</span>
                  <span class="mr-2 text-base">${{ cartSummary.total.toFixed(2) }}</span>
                  <ChevronUpIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                </PopoverButton>
              </div>
            </div>

            <TransitionRoot as="template" :show="open">
              <div>
                <TransitionChild
                  as="template"
                  enter="transition-opacity ease-linear duration-300"
                  enter-from="opacity-0"
                  enter-to="opacity-100"
                  leave="transition-opacity ease-linear duration-300"
                  leave-from="opacity-100"
                  leave-to="opacity-0"
                >
                  <PopoverOverlay class="fixed inset-0 bg-black/50" />
                </TransitionChild>
                <TransitionChild
                  as="template"
                  enter="transition ease-in-out duration-300 transform"
                  enter-from="translate-y-full"
                  enter-to="translate-y-0"
                  leave="transition ease-in-out duration-300 transform"
                  leave-from="translate-y-0"
                  leave-to="translate-y-full"
                >
                  <PopoverPanel class="relative bg-black/90 backdrop-blur-lg px-4 py-6 sm:px-6">
                    <dl class="mx-auto max-w-lg space-y-6 text-white">
                      <div class="flex items-center justify-between">
                        <dt class="text-gray-400">Subtotal</dt>
                        <dd>${{ cartSummary.subtotal.toFixed(2) }}</dd>
                      </div>

                      <div class="flex items-center justify-between">
                        <dt class="text-gray-400">Shipping</dt>
                        <dd>FREE</dd>
                      </div>

                      <div class="flex items-center justify-between">
                        <dt class="text-gray-400">Taxes</dt>
                        <dd>${{ cartSummary.tax.toFixed(2) }}</dd>
                      </div>
                    </dl>
                  </PopoverPanel>
                </TransitionChild>
              </div>
            </TransitionRoot>
          </Popover>
        </div>
      </section>

      <!-- Checkout Form (Left Side on Desktop) -->
      <form @submit.prevent="handleSubmit" class="px-4 pb-36 pt-16 sm:px-6 lg:col-start-1 lg:row-start-1 lg:px-0 lg:pb-16">
        <div class="mx-auto max-w-lg lg:max-w-none">
          
          <!-- Contact Information -->
          <section aria-labelledby="contact-info-heading">
            <h2 id="contact-info-heading" class="text-lg font-medium text-white">Contact Information</h2>

            <div class="mt-6">
              <label for="email" class="block text-sm font-medium text-gray-300">Email address</label>
              <div class="mt-2">
                <input
                  v-model="form.email"
                  type="email"
                  id="email"
                  name="email"
                  required
                  class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  placeholder="ragnar@vikings.com"
                />
              </div>
            </div>

            <div class="mt-6">
              <label for="phone" class="block text-sm font-medium text-gray-300">Phone number (optional)</label>
              <div class="mt-2">
                <input
                  v-model="form.phone"
                  type="tel"
                  id="phone"
                  name="phone"
                  class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  placeholder="+45 12 34 56 78"
                />
              </div>
            </div>
          </section>

          <!-- Shipping Address -->
          <section aria-labelledby="shipping-heading" class="mt-10">
            <h2 id="shipping-heading" class="text-lg font-medium text-white">Shipping Address</h2>

            <div class="mt-6 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
              <div>
                <label for="first-name" class="block text-sm font-medium text-gray-300">First name</label>
                <div class="mt-2">
                  <input
                    v-model="form.first_name"
                    type="text"
                    id="first-name"
                    required
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <div>
                <label for="last-name" class="block text-sm font-medium text-gray-300">Last name</label>
                <div class="mt-2">
                  <input
                    v-model="form.last_name"
                    type="text"
                    id="last-name"
                    required
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <div class="sm:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-300">Address</label>
                <div class="mt-2">
                  <input
                    v-model="form.address1"
                    type="text"
                    id="address"
                    required
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <div class="sm:col-span-2">
                <label for="apartment" class="block text-sm font-medium text-gray-300">Apartment, suite, etc. (optional)</label>
                <div class="mt-2">
                  <input
                    v-model="form.address2"
                    type="text"
                    id="apartment"
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <div>
                <label for="city" class="block text-sm font-medium text-gray-300">City</label>
                <div class="mt-2">
                  <input
                    v-model="form.city"
                    type="text"
                    id="city"
                    required
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <div>
                <label for="state" class="block text-sm font-medium text-gray-300">State / Province</label>
                <div class="mt-2">
                  <input
                    v-model="form.state"
                    type="text"
                    id="state"
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <div>
                <label for="zipcode" class="block text-sm font-medium text-gray-300">Postal code</label>
                <div class="mt-2">
                  <input
                    v-model="form.zipcode"
                    type="text"
                    id="zipcode"
                    required
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  />
                </div>
              </div>

              <div>
                <label for="country" class="block text-sm font-medium text-gray-300">Country</label>
                <div class="mt-2">
                  <select
                    v-model="form.country_code"
                    id="country"
                    required
                    class="block w-full rounded-md bg-white/10 px-3 py-2 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-yellow-500 sm:text-sm"
                  >
                    <option value="">Select a country</option>
                    <option value="DK">Denmark</option>
                    <option value="US">United States</option>
                    <option value="GB">United Kingdom</option>
                    <option value="DE">Germany</option>
                    <option value="FR">France</option>
                    <option value="SE">Sweden</option>
                    <option value="NO">Norway</option>
                  </select>
                </div>
              </div>
            </div>
          </section>

          <!-- Payment Details -->
          <section aria-labelledby="payment-heading" class="mt-10">
            <h2 id="payment-heading" class="text-lg font-medium text-white">Payment Details</h2>
            
            <p class="mt-4 text-sm text-gray-400">
              💳 Payment processing coming soon. For now, orders will be placed as "Pending Payment".
            </p>

            <!-- Payment method selection (placeholder) -->
            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-300 mb-3">Payment Method</label>
              <div class="space-y-3">
                <label class="flex items-center p-4 bg-white/10 border border-white/20 rounded-lg cursor-pointer hover:bg-white/20">
                  <input
                    v-model="form.payment_type"
                    type="radio"
                    value="credit_card"
                    class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-600"
                  />
                  <span class="ml-3 text-white">Credit Card</span>
                </label>
                <label class="flex items-center p-4 bg-white/10 border border-white/20 rounded-lg cursor-pointer hover:bg-white/20">
                  <input
                    v-model="form.payment_type"
                    type="radio"
                    value="paypal"
                    class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-600"
                  />
                  <span class="ml-3 text-white">PayPal</span>
                </label>
              </div>
            </div>
          </section>

          <!-- Submit Button -->
          <div class="mt-10 border-t border-white/10 pt-6 sm:flex sm:items-center sm:justify-between">
            <button
              type="submit"
              :disabled="processing || cartItems.length === 0"
              class="w-full rounded-lg border border-transparent bg-gradient-to-r from-yellow-400 to-orange-500 px-4 py-3 text-sm font-bold text-gray-900 shadow-sm hover:from-yellow-500 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-900 sm:order-last sm:ml-6 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="processing">Processing...</span>
              <span v-else>Place Order</span>
            </button>
            <p class="mt-4 text-center text-sm text-gray-400 sm:mt-0 sm:text-left">
              You'll be charged ${{ cartSummary.total.toFixed(2) }}
            </p>
          </div>

          <!-- Back to Cart -->
          <div class="mt-6 text-center">
            <router-link
              :to="{ name: 'cart' }"
              class="text-sm font-medium text-yellow-400 hover:text-yellow-300"
            >
              ← Back to Cart
            </router-link>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { Popover, PopoverButton, PopoverOverlay, PopoverPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import axiosClient from '../axios'
import { useToast } from 'vue-toastification'

const store = useStore()
const router = useRouter()
const toast = useToast()

const cartItems = computed(() => store.getters['cart/cartItems'])
const cartSummary = computed(() => store.getters['cart/cartSummary'])
const loading = computed(() => store.getters['cart/isLoading'])
const user = computed(() => store.state.user.data)

const form = ref({
  email: '',
  phone: '',
  first_name: '',
  last_name: '',
  address1: '',
  address2: '',
  city: '',
  state: '',
  zipcode: '',
  country_code: 'DK',
  payment_type: 'credit_card',
})

const processing = ref(false)

onMounted(async () => {

  await store.dispatch('cart/fetchCart')

  // Pre-fill email from user
  if (user.value?.email) {
    form.value.email = user.value.email
  }

  // Pre-fill name from user
  if (user.value?.name) {
    const nameParts = user.value.name.split(' ')
    form.value.first_name = nameParts[0] || ''
    form.value.last_name = nameParts.slice(1).join(' ') || ''
  }


  if (cartItems.value.length === 0 && !loading.value) {
 toast.error('Your cart is empty!')
    router.push({ name: 'shop' })
  }
})

async function handleSubmit() {
  if (cartItems.value.length === 0) {
    toast.error('Your cart is empty!')
    return
  }

  processing.value = true

  try {
    const orderData = {
      shipping_details: {
        first_name: form.value.first_name,
        last_name: form.value.last_name,
        email: form.value.email,
        phone: form.value.phone,
        address1: form.value.address1,
        address2: form.value.address2,
        city: form.value.city,
        state: form.value.state,
        zipcode: form.value.zipcode,
        country_code: form.value.country_code,
      },
      payment: {
        type: form.value.payment_type,
      },
    }

    const response = await axiosClient.post('/orders', orderData)

//stripe checkout
    if (response.data.stripe?.url) {
       toast.success('Order created! Redirecting to payment... 💳', {
        timeout: 2000
      })
      setTimeout(() => {
        window.location.href = response.data.stripe.url
      }, 500)
    } else {
     
    toast.error('Payment setup failed. Please try again.')
      processing.value = false
    }

  } catch (error) {
    console.error('Checkout failed:', error)
    
    if (error.response?.data?.message) {
   toast.error(error.response.data.message)
     } else if (error.response?.data?.errors) {

      Object.values(error.response.data.errors).flat().forEach(err => {
        toast.error(err)
      })
    } else {
      toast.error('Failed to place order. Please try again.')
    }
    processing.value = false
  }
}
</script>