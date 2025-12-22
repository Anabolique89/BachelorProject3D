<template>
  <div class="p-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-semibold">
        {{ isEdit ? `Edit Product: "${product.title}"` : 'Create New Product' }}
      </h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <!-- Loading Spinner -->
      <div v-if="loading" class="flex items-center justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Form -->
      <form v-else @submit.prevent="onSubmit" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Left Column - Form Fields -->
          <div class="lg:col-span-2 space-y-4">
            <!-- Title -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Product Title *
              </label>
              <input
                v-model="product.title"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Viking Battle Axe"
              />
              <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title[0] }}</p>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Description
              </label>
              <textarea
                v-model="product.description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Product description..."
              ></textarea>
              <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description[0] }}</p>
            </div>

            <!-- Price & Sale Price -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Price *
                </label>
                <div class="relative">
                  <span class="absolute left-3 top-2 text-gray-500">$</span>
                  <input
                    v-model="product.price"
                    type="number"
                    step="0.01"
                    required
                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="99.99"
                  />
                </div>
                <p v-if="errors.price" class="mt-1 text-sm text-red-600">{{ errors.price[0] }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Sale Price
                </label>
                <div class="relative">
                  <span class="absolute left-3 top-2 text-gray-500">$</span>
                  <input
                    v-model="product.sale_price"
                    type="number"
                    step="0.01"
                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="79.99"
                  />
                </div>
                <p v-if="errors.sale_price" class="mt-1 text-sm text-red-600">{{ errors.sale_price[0] }}</p>
              </div>
            </div>

            <!-- Stock & Token Reward -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Stock Quantity *
                </label>
                <input
                  v-model="product.stock_quantity"
                  type="number"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder="10"
                />
                <p v-if="errors.stock_quantity" class="mt-1 text-sm text-red-600">{{ errors.stock_quantity[0] }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Token Reward *
                </label>
                <input
                  v-model="product.token_reward_on_purchase"
                  type="number"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder="100"
                />
                <p v-if="errors.token_reward_on_purchase" class="mt-1 text-sm text-red-600">{{ errors.token_reward_on_purchase[0] }}</p>
              </div>
            </div>

            <!-- Category & Status -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Category
                </label>
                <select
                  v-model="product.category_id"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                >
                  <option :value="null">Select Category</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
                <p v-if="errors.category_id" class="mt-1 text-sm text-red-600">{{ errors.category_id[0] }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Status *
                </label>
                <select
                  v-model="product.status"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                >
                  <option value="draft">Draft</option>
                  <option value="published">Published</option>
                  <option value="out_of_stock">Out of Stock</option>
                  <option value="discontinued">Discontinued</option>
                </select>
                <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status[0] }}</p>
              </div>
            </div>

            <!-- Featured -->
            <div>
              <label class="flex items-center">
                <input
                  v-model="product.is_featured"
                  type="checkbox"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <span class="ml-2 text-sm text-gray-700">Featured Product</span>
              </label>
            </div>
          </div>

          <!-- Right Column - Image Upload -->
          <div class="lg:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Product Image
            </label>

            <!-- Image Preview -->
            <div v-if="imagePreview || product.image" class="mb-4">
              <img 
                :src="imagePreview || product.image_url" 
                alt="Product preview" 
                class="w-full h-64 object-cover rounded-lg border-2 border-gray-300"
              />
              <button
                type="button"
                @click="removeImage"
                class="mt-2 w-full px-3 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-sm"
              >
                Remove Image
              </button>
            </div>

            <!-- Upload Button -->
            <div v-else class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <p class="mt-2 text-sm text-gray-600">No image selected</p>
            </div>

            <!-- File Input -->
            <input
              ref="fileInput"
              type="file"
              accept="image/*"
              @change="handleFileChange"
              class="hidden"
            />

            <button
              type="button"
              @click="$refs.fileInput.click()"
              class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Choose Image
            </button>

            <p v-if="errors.image" class="mt-2 text-sm text-red-600">{{ errors.image[0] }}</p>
            <p class="mt-2 text-xs text-gray-500">
              PNG, JPG, GIF, WEBP up to 2MB
            </p>
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 pt-6 mt-6 border-t">
          <router-link
            :to="{ name: 'app.products' }"
            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Cancel
          </router-link>
          
          <button
            type="submit"
            :disabled="loading"
            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
          >
            {{ isEdit ? 'Update Product' : 'Create Product' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useStore } from 'vuex'
import axiosClient from '../axios'

const route = useRoute()
const router = useRouter()
const store = useStore()

const loading = ref(false)
const errors = ref({})
const categories = ref([])
const imageFile = ref(null)
const imagePreview = ref(null)

const product = ref({
  title: '',
  description: '',
  price: null,
  sale_price: null,
  stock_quantity: 0,
  token_reward_on_purchase: 0,
  category_id: null,
  status: 'draft',
  image: '',
  is_featured: false
})

const isEdit = computed(() => !!route.params.id)

onMounted(async () => {
  // Load categories
  try {
    const response = await axiosClient.get('/categories')
    categories.value = response.data
  } catch (error) {
    console.error('Failed to load categories:', error)
  }

  // Load product if editing
  if (isEdit.value) {
    loading.value = true
    try {
      const productData = await store.dispatch('getProduct', route.params.id)
      product.value = {
        ...productData,
        category_id: productData.category?.id || null
      }
    } catch (error) {
      console.error('Failed to load product:', error)
      alert('Failed to load product')
    } finally {
      loading.value = false
    }
  }
})

function handleFileChange(event) {
  const file = event.target.files[0]
  if (file) {
    imageFile.value = file
    
    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

function removeImage() {
  imageFile.value = null
  imagePreview.value = null
  product.value.image = ''
}

async function onSubmit() {
  loading.value = true
  errors.value = {}

  try {
    // Create FormData to handle file upload
    const formData = new FormData()
    
    // Append all product fields
    formData.append('title', product.value.title || '')
    formData.append('description', product.value.description || '')
    formData.append('price', product.value.price || 0)
    formData.append('sale_price', product.value.sale_price || '')
    formData.append('stock_quantity', product.value.stock_quantity || 0)
    formData.append('token_reward_on_purchase', product.value.token_reward_on_purchase || 0)
    formData.append('category_id', product.value.category_id || '')
    formData.append('status', product.value.status || 'draft')
    formData.append('is_featured', product.value.is_featured ? 1 : 0)
    
    // Append image if selected
    if (imageFile.value) {
      formData.append('image', imageFile.value)
    }

    if (isEdit.value) {
      // Update existing product
      const response = await axiosClient.post(`/admin/products/${route.params.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
        params: {
          _method: 'PUT' // Laravel method spoofing
        }
      })
      alert('Product updated successfully!')
      product.value = response.data.data
      imagePreview.value = null
      imageFile.value = null
    } else {
      // Create new product
      const response = await axiosClient.post('/admin/products', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      alert('Product created successfully!')
      router.push({ name: 'app.products.edit', params: { id: response.data.data.id } })
    }
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      alert('Failed to save product')
    }
    console.error('Error saving product:', error)
  } finally {
    loading.value = false
  }
}
</script>