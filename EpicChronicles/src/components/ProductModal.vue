<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-50">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <!-- Close Button -->
              <button
                @click="closeModal"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
              >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>

              <!-- Modal Content -->
              <div v-if="product" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left: Image -->
                <div>
                  <img 
                    v-if="product.image_url" 
                    :src="product.image_url" 
                    :alt="product.title"
                    class="w-full h-96 object-cover rounded-lg border"
                  />
                  <div v-else class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-gray-400">No Image</span>
                  </div>
                </div>

                <!-- Right: Details -->
                <div class="flex flex-col">
                  <DialogTitle as="h3" class="text-2xl font-bold text-gray-900 mb-2">
                    {{ product.title }}
                  </DialogTitle>

                  <!-- Category -->
                  <div v-if="product.category" class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                      {{ product.category.name }}
                    </span>
                  </div>

                  <!-- Price -->
                  <div class="mb-4">
                    <div v-if="product.is_on_sale" class="flex items-center space-x-2">
                      <span class="text-3xl font-bold text-red-600">${{ product.sale_price }}</span>
                      <span class="text-xl text-gray-400 line-through">${{ product.price }}</span>
                      <span class="px-2 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded">
                        -{{ product.discount_percentage }}%
                      </span>
                    </div>
                    <div v-else>
                      <span class="text-3xl font-bold text-gray-900">${{ product.price }}</span>
                    </div>
                  </div>

                  <!-- Stock -->
                  <div class="mb-4">
                    <div class="flex items-center space-x-2">
                      <span class="text-sm text-gray-600">Stock:</span>
                      <span 
                        class="px-2 py-1 text-sm font-semibold rounded"
                        :class="product.is_in_stock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      >
                        {{ product.stock_quantity }} {{ product.is_in_stock ? 'In Stock' : 'Out of Stock' }}
                      </span>
                    </div>
                  </div>

                  <!-- Status -->
                  <div class="mb-4">
                    <div class="flex items-center space-x-2">
                      <span class="text-sm text-gray-600">Status:</span>
                      <span class="px-2 py-1 text-sm font-semibold rounded capitalize"
                        :class="{
                          'bg-green-100 text-green-800': product.status === 'published',
                          'bg-yellow-100 text-yellow-800': product.status === 'draft',
                          'bg-red-100 text-red-800': product.status === 'out_of_stock',
                          'bg-gray-100 text-gray-800': product.status === 'discontinued'
                        }"
                      >
                        {{ product.status }}
                      </span>
                    </div>
                  </div>

                  <!-- Token Reward -->
                  <div class="mb-4">
                    <div class="flex items-center space-x-2">
                      <span class="text-sm text-gray-600">Token Reward:</span>
                      <span class="px-2 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded">
                        {{ product.token_reward_on_purchase }} tokens
                      </span>
                    </div>
                  </div>

                  <!-- Featured Badge -->
                  <div v-if="product.is_featured" class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                      ⭐ Featured Product
                    </span>
                  </div>

                  <!-- Description -->
                  <div class="mb-6 flex-1">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Description</h4>
                    <p class="text-gray-600 text-sm whitespace-pre-wrap">{{ product.description || 'No description available.' }}</p>
                  </div>

                  <!-- Meta Info -->
                  <div class="text-xs text-gray-500 mb-6 border-t pt-4">
                    <div>Product ID: {{ product.id }}</div>
                    <div>Slug: {{ product.slug }}</div>
                    <div v-if="product.updated_at">Last Updated: {{ product.updated_at }}</div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex space-x-3">
                    <router-link
                      :to="{ name: 'app.products.edit', params: { id: product.id } }"
                      class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-center font-medium"
                    >
                      Edit Product
                    </router-link>
                    <button
                      @click="$emit('delete', product)"
                      class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 font-medium"
                    >
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue'

defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  product: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'delete'])

function closeModal() {
  emit('close')
}
</script>