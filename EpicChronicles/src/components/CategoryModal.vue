<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="$emit('close')" class="relative z-50">
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
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                {{ category?.id ? 'Edit Category' : 'Create Category' }}
              </DialogTitle>

              <form @submit.prevent="handleSubmit">
                <!-- Name -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Category Name *
                  </label>
                  <input
                    v-model="formData.name"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="e.g., Weapons"
                  />
                </div>

                <!-- Slug -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Slug
                  </label>
                  <input
                    v-model="formData.slug"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="weapons (auto-generated if empty)"
                  />
                </div>

                <!-- Parent Category -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Parent Category
                  </label>
                  <select
                    v-model="formData.parent_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  >
                    <option :value="null">None (Main Category)</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                      {{ cat.name }}
                    </option>
                  </select>
                </div>

                <!-- Sort Order -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Sort Order
                  </label>
                  <input
                    v-model.number="formData.sort_order"
                    type="number"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="0"
                  />
                </div>

                <!-- Active -->
                <div class="mb-6">
                  <label class="flex items-center">
                    <input
                      v-model="formData.is_active"
                      type="checkbox"
                      class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                  </label>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                  <button
                    type="button"
                    @click="$emit('close')"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700"
                  >
                    {{ category?.id ? 'Update' : 'Create' }}
                  </button>
                </div>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, watch } from 'vue'
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue'

const props = defineProps({
  isOpen: Boolean,
  category: Object,
  categories: Array
})

const emit = defineEmits(['close', 'save'])

const formData = ref({
  name: '',
  slug: '',
  parent_id: null,
  is_active: true,
  sort_order: 0
})

watch(() => props.category, (newCategory) => {
  if (newCategory) {
    formData.value = { ...newCategory }
  }
}, { immediate: true })

function handleSubmit() {
  emit('save', formData.value)
}
</script>