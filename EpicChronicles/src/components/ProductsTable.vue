<script setup>
import { ref, onMounted } from "vue";
import { useStore } from "vuex";
import Spinner from "../components/Spinner.vue";
import TableHeaderCell from "../components/TableHeaderCell.vue";
import ProductModal from "../components/ProductModal.vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { DotsVerticalIcon, PencilIcon, TrashIcon } from '@heroicons/vue/outline'

const store = useStore();

const perPage = ref(10);
const search = ref('');
const sortField = ref('updated_at');
const sortDirection = ref('desc');
const loading = ref(false);
const isModalOpen = ref(false);
const selectedProduct = ref(null);

// Store products locally instead of in Vuex
const productsData = ref({
  data: [],
  total: 0,
  from: 0,
  to: 0,
  limit: 10,
  links: []
});

onMounted(() => {
  getProducts();
})

function openModal(product) {
  selectedProduct.value = product;
  isModalOpen.value = true;
}

function closeModal() {
  isModalOpen.value = false;
  selectedProduct.value = null;
}

function handleDelete(product) {
  closeModal();
  deleteProduct(product);
}

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }
  getProducts(link.url)
}

async function getProducts(url = null) {
  loading.value = true;
  
  try {
    const params = {
      search: search.value,
      per_page: perPage.value,
      sort_by: sortField.value,
      sort_order: sortDirection.value
    };
    
    const response = await store.dispatch("getProducts", params);
    
    // Store response in local data
    productsData.value = {
      data: response.data || [],
      total: response.meta?.total || 0,
      from: response.meta?.from || 0,
      to: response.meta?.to || 0,
      limit: response.meta?.per_page || 10,
      links: response.meta?.links || []
    };
  } catch (error) {
    console.error('Failed to fetch products:', error);
  } finally {
    loading.value = false;
  }
}

function sortProducts(field) {
  if (field === sortField.value) {
    if (sortDirection.value === 'desc') {
      sortDirection.value = 'asc'
    } else {
      sortDirection.value = 'desc'
    }
  } else {
    sortField.value = field;
    sortDirection.value = 'asc'
  }

  getProducts()
}

async function deleteProduct(product) {
  if (!confirm(`Are you sure you want to delete "${product.title}"?`)) {
    return
  }
  
  try {
    await store.dispatch('deleteProduct', product.id);
    alert('Product was successfully deleted');
    getProducts();
  } catch (error) {
    alert('Failed to delete product');
    console.error(error);
  }
}

function changePage(link) {
  if (!link.url || link.active) return;
  
  // Extract page number from URL
  const url = new URL(link.url);
  const page = url.searchParams.get('page');
  
  loading.value = true;
  
  const params = {
    search: search.value,
    per_page: perPage.value,
    sort_by: sortField.value,
    sort_order: sortDirection.value,
    page: page
  };
  
  store.dispatch("getProducts", params)
    .then(response => {
      productsData.value = {
        data: response.data || [],
        total: response.meta?.total || 0,
        from: response.meta?.from || 0,
        to: response.meta?.to || 0,
        limit: response.meta?.per_page || 10,
        links: response.meta?.links || []
      };
    })
    .catch(error => {
      console.error('Failed to fetch products:', error);
    })
    .finally(() => {
      loading.value = false;
    });
}
</script>

<template>
  <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
    <div class="flex justify-between border-b-2 pb-3">
      <div class="flex items-center">
        <span class="whitespace-nowrap mr-3">Per Page</span>
        <select @change="getProducts()" v-model="perPage"
                class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span class="ml-3">Found {{ productsData.total }} products</span>
      </div>
      <div>
        <input v-model="search" @change="getProducts()"
               class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
               placeholder="Type to Search products">
      </div>
    </div>

    <table class="table-auto w-full">
      <thead>
      <tr>
        <TableHeaderCell field="id" :sort-field="sortField" :sort-direction="sortDirection" @click="sortProducts('id')">
          ID
        </TableHeaderCell>
        <TableHeaderCell field="image" :sort-field="sortField" :sort-direction="sortDirection" :sortable="false">
          Image
        </TableHeaderCell>
        <TableHeaderCell field="title" :sort-field="sortField" :sort-direction="sortDirection"
                         @click="sortProducts('title')">
          Title
        </TableHeaderCell>
        <TableHeaderCell field="price" :sort-field="sortField" :sort-direction="sortDirection"
                         @click="sortProducts('price')">
          Price
        </TableHeaderCell>
        <TableHeaderCell field="stock_quantity" :sort-field="sortField" :sort-direction="sortDirection"
                         @click="sortProducts('stock_quantity')">
          Stock
        </TableHeaderCell>
        <TableHeaderCell field="updated_at" :sort-field="sortField" :sort-direction="sortDirection"
                         @click="sortProducts('updated_at')">
          Updated
        </TableHeaderCell>
        <TableHeaderCell field="actions" :sortable="false">
          Actions
        </TableHeaderCell>
      </tr>
      </thead>
      <tbody v-if="loading || !productsData.data.length">
      <tr>
        <td colspan="7" class="text-center py-8">
          <Spinner v-if="loading"/>
          <p v-else class="text-gray-700">
            There are no products
          </p>
        </td>
      </tr>
      </tbody>
      <tbody v-else>
      <tr 
  v-for="product in productsData.data" 
  :key="product.id"
  @click="openModal(product)"
  class="cursor-pointer hover:bg-gray-50 transition-colors"
>
        <td class="border-b p-2">{{ product.id }}</td>
        <td class="border-b p-2">
          <img v-if="product.image_url" class="w-16 h-16 object-cover rounded" :src="product.image_url" :alt="product.title">
          <div v-else class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">
            No Image
          </div>
        </td>
        <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
          {{ product.title }}
        </td>
        <td class="border-b p-2">
          ${{ product.display_price }}
        </td>
        <td class="border-b p-2">
          {{ product.stock_quantity }}
        </td>
        <td class="border-b p-2">
          {{ product.updated_at }}
        </td>
        <td class="border-b p-2">
          <Menu as="div" class="relative inline-block text-left">
            <div>
              <MenuButton
                class="inline-flex items-center justify-center w-full justify-center rounded-full w-10 h-10 bg-black bg-opacity-0 text-sm font-medium text-white hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
              >
                <DotsVerticalIcon
                  class="h-5 w-5 text-indigo-500"
                  aria-hidden="true"/>
              </MenuButton>
            </div>

            <transition
              enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-75 ease-in"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0"
            >
              <MenuItems
                class="absolute z-10 right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
              >
                <div class="px-1 py-1">
                  <MenuItem v-slot="{ active }">
                    <router-link
                      :to="{name: 'app.products.edit', params: {id: product.id}}"
                      :class="[
                        active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                    >
                      <PencilIcon
                        :active="active"
                        class="mr-2 h-5 w-5 text-indigo-400"
                        aria-hidden="true"
                      />
                      Edit
                    </router-link>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <button
                      :class="[
                        active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                      @click="deleteProduct(product)"
                    >
                      <TrashIcon
                        :active="active"
                        class="mr-2 h-5 w-5 text-indigo-400"
                        aria-hidden="true"
                      />
                      Delete
                    </button>
                  </MenuItem>
                </div>
              </MenuItems>
            </transition>
          </Menu>
        </td>
      </tr>
      </tbody>
    </table>

    <div v-if="!loading && productsData.data.length" class="flex justify-between items-center mt-5">
  <div>
    Showing from {{ productsData.from }} to {{ productsData.to }} of {{ productsData.total }}
  </div>
  
  <nav
    v-if="productsData.total > productsData.limit"
    class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
    aria-label="Pagination"
  >
    <button
      v-for="(link, i) in productsData.links"
      :key="i"
      @click="changePage(link)"
      :disabled="!link.url || link.active"
      class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
      :class="[
        link.active
          ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600 cursor-default'
          : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
        i === 0 ? 'rounded-l-md' : '',
        i === productsData.links.length - 1 ? 'rounded-r-md' : '',
        !link.url ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'cursor-pointer'
      ]"
      v-html="link.label"
    ></button>
  </nav>
</div>

 <ProductModal
      :is-open="isModalOpen"
      :product="selectedProduct"
      @close="closeModal"
      @delete="handleDelete"
    />

  </div>
</template>