<template>
  <TransitionRoot :show="show" as="template">
    <Dialog as="div" class="relative z-50" @close="handleClose">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/70 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel class="relative transform overflow-hidden bg-gradient-to-br from-amber-50 via-orange-50 to-red-50 rounded-3xl shadow-2xl border-4 border-amber-600 transition-all sm:my-4 sm:w-full sm:max-w-3xl lg:max-w-md">
              <div class="bg-gradient-to-br from-amber-50 via-orange-50 to-red-50 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="text-center">
                  <div class="absolute -top-4 -left-4 animate-bounce">⚔️</div>
                  <div class="absolute -top-4 -right-4 animate-bounce delay-300">🛡️</div>
                  
                  <h1 class="lg:text-2xl sm:text-4xl font-bold mb-4 text-amber-900 flex items-center justify-center flex-wrap gap-2">
                    <span class="lg:text-3xl sm:text-6xl">🏴‍☠️</span>
                    <span>Welcome, Viking Warrior!</span>
                  </h1>
                  
                  <div class="bg-amber-100 p-4 sm:p-6 rounded-xl mb-6 border-2 border-amber-300">
                    <h2 class="lg:text-lg sm:text-4xl font-bold mb-3 text-amber-800 flex items-center justify-center gap-2">
                      <span class="lg:text-2xl sm:text-6xl">📜</span>
                      The Legend of Ragnar's Discovery
                    </h2>
                    <p class="text-amber-700 lg:text-sm sm:text-4xl leading-relaxed mb-4">
                      As Ragnar sailed the treacherous northern seas, a mystical fog lifted to reveal a floating island filled with ancient treasures and exotic wares. The Island Merchants, guardians of legendary artifacts, have been waiting centuries for worthy traders...
                    </p>
                    <p class="text-amber-800 font-semibold lg:text-sm sm:text-xl">
                      <span class="lg:text-sm sm:text-2xl mr-2">⚡</span>
                      Will you join this epic trading adventure?
                    </p>
                  </div>
                </div>
              </div>
              
              <div class="bg-amber-50/50 px-4 py-2 sm:flex sm:flex-row sm:px-6 gap-4 justify-center sm:mb-4">
                <router-link
                  :to="{ name: 'shop' }"
                  class="inline-flex w-full justify-center rounded-xl bg-gradient-to-r from-red-600 via-orange-600 to-amber-600 lg:px-3 sm:px-4 py-2 lg:text-sm sm:text-4xl font-semibold text-white shadow-sm hover:from-red-500 hover:via-orange-500 hover:to-amber-500 sm:ml-3 sm:w-auto border-2 border-yellow-400 relative overflow-hidden group transform hover:scale-105 active:scale-95 transition-all"
                >
                  <span class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-400 opacity-0 group-hover:opacity-20 transition-opacity"></span>
                  <span class="relative flex items-center justify-center gap-2">
                    <span>⚔️</span>
                    Begin Your Quest!
                    <span>💰</span>
                  </span>
                </router-link>
                
                <button 
                  @click="handleClose"
                  class="mt-3 inline-flex w-full justify-center rounded-xl bg-gradient-to-r from-gray-600 to-gray-700 px-3 py-2 lg:text-sm sm:text-3xl font-semibold text-white shadow-sm hover:from-gray-500 hover:to-gray-600 sm:mt-0 sm:w-auto transform hover:scale-105 active:scale-95 transition-all"
                >
                  <span class="flex items-center justify-center gap-2">
                    <span>🌊</span>
                    Explore First
                  </span>
                </button>
              </div>

              <!-- Attention-grabbing elements -->
              <div class="px-4 pb-4 sm:px-6 flex justify-center items-center gap-4 text-amber-700 text-xs">
                <div class="flex items-center gap-1">
                  <span class="animate-pulse">🔥</span>
                  <span class="font-semibold">Limited Edition</span>
                </div>
                <div class="flex items-center gap-1">
                  <span class="animate-pulse delay-500">⚡</span>
                  <span class="font-semibold">Rare Artifacts</span>
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
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])

const handleClose = () => {
  emit('close')
}
</script>

<style scoped>
.delay-300 {
  animation-delay: 300ms;
}

.delay-500 {
  animation-delay: 500ms;
}
</style>