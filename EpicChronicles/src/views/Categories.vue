<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-semibold">Product Categories</h1>
      <button
        @click="openCreateModal"
        class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Add Category
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Total Categories</p>
            <p class="text-3xl font-bold mt-2">{{ allCategories.length }}</p>
          </div>
          <div class="bg-indigo-100 rounded-full p-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Main Categories</p>
            <p class="text-3xl font-bold mt-2">{{ mainCategories.length }}</p>
          </div>
          <div class="bg-green-100 rounded-full p-3">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Subcategories</p>
            <p class="text-3xl font-bold mt-2">{{ subCategories.length }}</p>
          </div>
          <div class="bg-purple-100 rounded-full p-3">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="flex items-center justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <table v-else class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Category
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Parent
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Products
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Status
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Sort Order
            </th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <template v-for="category in mainCategories" :key="category.id">
            <!-- Main Category Row -->
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="text-sm font-medium text-gray-900">
                    {{ category.name }}
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                —
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                  {{ category.products_count || 0 }} products
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <button
                  @click="toggleActive(category)"
                  class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  :class="category.is_active ? 'bg-indigo-600' : 'bg-gray-200'"
                >
                  <span
                    class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                    :class="category.is_active ? 'translate-x-5' : 'translate-x-0'"
                  ></span>
                </button>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ category.sort_order || 0 }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                  @click="openEditModal(category)"
                  class="text-indigo-600 hover:text-indigo-900 mr-4"
                >
                  Edit
                </button>
                <button
                  @click="deleteCategory(category)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>

            <!-- Subcategories -->
            <tr
              v-for="subcat in getSubcategories(category.id)"
              :key="subcat.id"
              class="bg-gray-50 hover:bg-gray-100"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center pl-8">
                  <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                  <div class="text-sm text-gray-900">
                    {{ subcat.name }}
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ category.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                  {{ subcat.products_count || 0 }} products
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <button
                  @click="toggleActive(subcat)"
                  class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  :class="subcat.is_active ? 'bg-indigo-600' : 'bg-gray-200'"
                >
                  <span
                    class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                    :class="subcat.is_active ? 'translate-x-5' : 'translate-x-0'"
                  ></span>
                </button>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ subcat.sort_order || 0 }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                  @click="openEditModal(subcat)"
                  class="text-indigo-600 hover:text-indigo-900 mr-4"
                >
                  Edit
                </button>
                <button
                  @click="deleteCategory(subcat)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <!-- Category Modal -->
    <CategoryModal
      :is-open="isModalOpen"
      :category="selectedCategory"
      :categories="mainCategories"
      @close="closeModal"
      @save="saveCategory"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axiosClient from '../axios'
import CategoryModal from '../components/CategoryModal.vue'

const loading = ref(false)
const allCategories = ref([])
const isModalOpen = ref(false)
const selectedCategory = ref(null)

const mainCategories = computed(() => {
  return allCategories.value.filter(cat => !cat.parent_id)
})

const subCategories = computed(() => {
  return allCategories.value.filter(cat => cat.parent_id)
})

onMounted(() => {
  loadCategories()
})

async function loadCategories() {
  loading.value = true
  try {
    const response = await axiosClient.get('/admin/categories')
    allCategories.value = response.data || []
  } catch (error) {
    console.error('Failed to load categories:', error)
    alert('Failed to load categories')
  } finally {
    loading.value = false
  }
}

function getSubcategories(parentId) {
  return allCategories.value.filter(cat => cat.parent_id === parentId)
}

function openCreateModal() {
  selectedCategory.value = {
    name: '',
    slug: '',
    parent_id: null,
    is_active: true,
    sort_order: 0
  }
  isModalOpen.value = true
}

function openEditModal(category) {
  selectedCategory.value = { ...category }
  isModalOpen.value = true
}

function closeModal() {
  isModalOpen.value = false
  selectedCategory.value = null
}

async function saveCategory(categoryData) {
  try {
    if (categoryData.id) {
      // Update existing
      await axiosClient.put(`/admin/categories/${categoryData.id}`, categoryData)
      alert('Category updated successfully!')
    } else {
      // Create new
      await axiosClient.post('/admin/categories', categoryData)
      alert('Category created successfully!')
    }
    closeModal()
    loadCategories()
  } catch (error) {
    console.error('Failed to save category:', error)
    alert('Failed to save category: ' + (error.response?.data?.message || 'Unknown error'))
  }
}

async function toggleActive(category) {
  try {
    await axiosClient.put(`/admin/categories/${category.id}`, {
      ...category,
      is_active: !category.is_active
    })
    category.is_active = !category.is_active
  } catch (error) {
    console.error('Failed to toggle category:', error)
    alert('Failed to update category')
  }
}

async function deleteCategory(category) {
  if (!confirm(`Are you sure you want to delete "${category.name}"? This action cannot be undone.`)) {
    return
  }

  try {
    await axiosClient.delete(`/admin/categories/${category.id}`)
    alert('Category deleted successfully!')
    loadCategories()
  } catch (error) {
    console.error('Failed to delete category:', error)
    alert('Failed to delete category: ' + (error.response?.data?.message || 'This category may have products'))
  }
}
</script>