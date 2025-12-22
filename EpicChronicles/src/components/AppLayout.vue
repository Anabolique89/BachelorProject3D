<template>
<div v-if="currentUser.id" class="min-h-full bg-gray-200 flex">
    
  <!-- Sidebar -->
   
 
  <Sidebar
      class="transition-all duration-300 w-[200px]"
      :class="{'-ml-[200px]': !sidebarOpened}"
    />
    <div class="flex-1 p-4 overflow-y-auto">
     <Navbar @toggle-sidebar="toggleSidebar"/>
        <main class="p-6">
           
            <router-view></router-view>
           
        </main>
    </div>
</div>
 <div v-else class="min-h-full bg-gray-200 flex items-center justify-center">
    <Spinner />
  </div>
</template>

<script setup>
import {ref, onMounted, onUnmounted, computed} from 'vue'
import Navbar from './Navbar.vue';
import Sidebar from './Sidebar.vue';
import store from '../store';
import Spinner from '../components/Spinner.vue'


const {title} = defineProps({
  title: String
})



const sidebarOpened = ref(true);
const currentUser = computed(() => store.state.user.data)

function toggleSidebar(){
 sidebarOpened.value = !sidebarOpened.value
}



onMounted(() => {
  store.dispatch('getCurrentUser');
//   store.dispatch('getCountries')
  updateSidebarState();
  window.addEventListener('resize', updateSidebarState)
})

onUnmounted(() => {
  window.removeEventListener('resize', updateSidebarState)
})

function updateSidebarState() {
  sidebarOpened.value = window.outerWidth > 768;
}

</script>

<style scoped>

</style>

