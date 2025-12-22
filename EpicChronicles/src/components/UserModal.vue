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
                {{ user?.id ? 'Edit User' : 'Create User' }}
              </DialogTitle>

              <form @submit.prevent="handleSubmit">
                <!-- Name -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Name *
                  </label>
                  <input
                    v-model="formData.name"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  />
                </div>

                <!-- Email -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email *
                  </label>
                  <input
                    v-model="formData.email"
                    type="email"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  />
                </div>

                <!-- Password -->
                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password {{ user?.id ? '(leave empty to keep current)' : '*' }}
                  </label>
                  <input
                    v-model="formData.password"
                    type="password"
                    :required="!user?.id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  />
                </div>

                <!-- Role -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Role *
                  </label>
                  <select
                    v-model="formData.customer.role"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  >
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                  </select>
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
                    {{ user?.id ? 'Update' : 'Create' }}
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
  user: Object
})

const emit = defineEmits(['close', 'save'])

const formData = ref({
  name: '',
  email: '',
  password: '',
  customer: {
    role: 'customer'
  }
})

watch(() => props.user, (newUser) => {
  if (newUser) {
    formData.value = {
      ...newUser,
      password: '',
      customer: {
        role: newUser.customer?.role || 'customer'
      }
    }
  }
}, { immediate: true })

function handleSubmit() {
  emit('save', formData.value)
}
</script>