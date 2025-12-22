<template>
  <th 
    @click="$emit('click')"
    class="border-b p-2 text-left cursor-pointer hover:bg-gray-50"
    :class="{ 'cursor-default': !sortable }"
  >
    <div class="flex items-center">
      <slot></slot>
      <span v-if="sortable && sortField === field" class="ml-2">
        <span v-if="sortDirection === 'asc'">↑</span>
        <span v-else>↓</span>
      </span>
    </div>
  </th>
</template>

<script setup>
defineProps({
  field: String,
  sortField: String,
  sortDirection: String,
  sortable: {
    type: Boolean,
    default: true
  }
})

defineEmits(['click'])
</script>