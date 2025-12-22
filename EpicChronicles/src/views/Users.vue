<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-semibold">Users Management</h1>
      <button
        @click="openCreateModal"
        class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Add User
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Total Users</p>
            <p class="text-3xl font-bold mt-2">{{ stats.total || 0 }}</p>
          </div>
          <div class="bg-indigo-100 rounded-full p-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Admins</p>
            <p class="text-3xl font-bold mt-2">{{ stats.admins || 0 }}</p>
          </div>
          <div class="bg-red-100 rounded-full p-3">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Customers</p>
            <p class="text-3xl font-bold mt-2">{{ stats.customers || 0 }}</p>
          </div>
          <div class="bg-green-100 rounded-full p-3">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Active Today</p>
            <p class="text-3xl font-bold mt-2">{{ stats.active_today || 0 }}</p>
          </div>
          <div class="bg-yellow-100 rounded-full p-3">
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
         <input
  v-model="search"
  type="text"
  placeholder="Search by name or email..."
  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
/>
        </div>
        <div>
       <select
  v-model="roleFilter"
  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
>
  <option value="">All Roles</option>
  <option value="admin">Admin</option>
  <option value="super_admin">Super Admin</option>
  <option value="customer">Customer</option>
</select>
        </div>
        <div>
          <select
  v-model="perPage"
  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
>
  <option :value="10">10 per page</option>
  <option :value="25">25 per page</option>
  <option :value="50">50 per page</option>
  <option :value="100">100 per page</option>
</select>
        </div>
      </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="flex items-center justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <div v-else>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                User
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Role
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Customer Info
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="!users.data.length">
              <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                No users found
              </td>
            </tr>
            <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                      {{ user.name?.charAt(0).toUpperCase() || 'U' }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ user.name }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ user.email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-red-100 text-red-800': user.customer?.role === 'super_admin',
                    'bg-orange-100 text-orange-800': user.customer?.role === 'admin',
                    'bg-green-100 text-green-800': user.customer?.role === 'customer'
                  }"
                >
                  {{ user.customer?.role || 'No role' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div v-if="user.customer" class="text-sm text-gray-900">
                  <div>Level: {{ user.customer.user_level?.name || 'N/A' }}</div>
                  <div class="text-xs text-gray-500">{{ user.customer.total_tokens || 0 }} tokens</div>
                </div>
                <span v-else class="text-sm text-gray-400">No customer profile</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(user.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button
                  @click="openEditModal(user)"
                  class="text-indigo-600 hover:text-indigo-900 mr-4"
                >
                  Edit
                </button>
                <button
                  @click="deleteUser(user)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="users.data.length" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200">
          <div class="flex-1 flex justify-between sm:hidden">
            <button
              @click="changePage(users.current_page - 1)"
              :disabled="users.current_page === 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Previous
            </button>
            <button
              @click="changePage(users.current_page + 1)"
              :disabled="users.current_page === users.last_page"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Next
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ users.from }}</span>
                to
                <span class="font-medium">{{ users.to }}</span>
                of
                <span class="font-medium">{{ users.total }}</span>
                results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <button
                  v-for="page in displayPages"
                  :key="page"
                  @click="changePage(page)"
                  :class="[
                    page === users.current_page
                      ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                  ]"
                >
                  {{ page }}
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- User Modal -->
    <UserModal
      :is-open="isModalOpen"
      :user="selectedUser"
      @close="closeModal"
      @save="saveUser"
    />
  </div>
</template>

<script setup>
console.log('Users.vue loaded!');

import { ref, computed, onMounted, watch } from 'vue' 
import axiosClient from '../axios'
import UserModal from '../components/UserModal.vue'

const loading = ref(false)
const users = ref({
  data: [],
  current_page: 1,
  last_page: 1,
  from: 0,
  to: 0,
  total: 0
})
const stats = ref({})
const isModalOpen = ref(false)
const selectedUser = ref(null)

const search = ref('')
const roleFilter = ref('')
const perPage = ref(10)

const displayPages = computed(() => {
  const pages = []
  const currentPage = users.value.current_page
  const lastPage = users.value.last_page
  
  for (let i = Math.max(1, currentPage - 2); i <= Math.min(lastPage, currentPage + 2); i++) {
    pages.push(i)
  }
  
  return pages
})

// ✅ ADD THIS DEBOUNCED SEARCH WATCHER
let searchTimeout = null
watch(search, () => {
  console.log('🔍 Search changed to:', search.value)
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    console.log('⏰ Executing search after delay')
    loadUsers()
  }, 500) // Wait 500ms after user stops typing
})

// ✅ ADD THIS FOR IMMEDIATE FILTER CHANGES
watch([roleFilter, perPage], () => {
  console.log('🔄 Filter changed - loading users')
  loadUsers()
})

onMounted(() => {
  loadUsers()
  loadStats()
})

async function loadUsers() {
  console.log('📡 Loading users with params:', {
    search: search.value,
    role: roleFilter.value,
    per_page: perPage.value
  })
  
  loading.value = true
  try {
    const response = await axiosClient.get('/admin/users', {
      params: {
        search: search.value,
        role: roleFilter.value,
        per_page: perPage.value
      }
    })
    console.log('✅ Users loaded:', response.data.data.length, 'users found')
    users.value = response.data
  } catch (error) {
    console.error('❌ Failed to load users:', error)
    alert('Failed to load users')
  } finally {
    loading.value = false
  }
}

async function loadStats() {
  try {
    const response = await axiosClient.get('/admin/users/stats')
    stats.value = response.data
  } catch (error) {
    console.error('Failed to load stats:', error)
  }
}

function changePage(page) {
  if (page < 1 || page > users.value.last_page) return
  
  loading.value = true
  axiosClient.get('/admin/users', {
    params: {
      search: search.value,
      role: roleFilter.value,
      per_page: perPage.value,
      page: page
    }
  })
  .then(response => {
    users.value = response.data
  })
  .finally(() => {
    loading.value = false
  })
}

function openCreateModal() {
  selectedUser.value = {
    name: '',
    email: '',
    password: '',
    customer: {
      role: 'customer'
    }
  }
  isModalOpen.value = true
}

function openEditModal(user) {
  selectedUser.value = { ...user }
  isModalOpen.value = true
}

function closeModal() {
  isModalOpen.value = false
  selectedUser.value = null
}

async function saveUser(userData) {
  try {
    if (userData.id) {
      await axiosClient.put(`/admin/users/${userData.id}`, userData)
      alert('User updated successfully!')
    } else {
      await axiosClient.post('/admin/users', userData)
      alert('User created successfully!')
    }
    closeModal()
    loadUsers()
    loadStats()
  } catch (error) {
    console.error('Failed to save user:', error)
    alert('Failed to save user: ' + (error.response?.data?.message || 'Unknown error'))
  }
}

async function deleteUser(user) {
  if (!confirm(`Are you sure you want to delete "${user.name}"? This action cannot be undone.`)) {
    return
  }

  try {
    await axiosClient.delete(`/admin/users/${user.id}`)
    alert('User deleted successfully!')
    loadUsers()
    loadStats()
  } catch (error) {
    console.error('Failed to delete user:', error)
    alert('Failed to delete user: ' + (error.response?.data?.message || 'Unknown error'))
  }
}

function formatDate(date) {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>