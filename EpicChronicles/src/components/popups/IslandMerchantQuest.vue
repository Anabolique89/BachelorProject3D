<template>
  <div 
    v-if="show" 
    class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    @click="handleBackdropClick"
  >
    <div 
      class="bg-gradient-to-br from-emerald-900 via-teal-900 to-cyan-900 rounded-3xl shadow-2xl sm:w-full sm:max-w-4xl lg:max-w-2xl w-full border-4 border-emerald-400 relative overflow-hidden"
      @click.stop
    >
      <!-- REPLAY MODE BANNER -->
      <div v-if="isReplay" class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-purple-600 text-white px-4 sm:px-6 py-1 sm:py-2 rounded-full font-bold text-xs sm:text-sm shadow-lg border-2 border-purple-400 z-10 animate-pulse">
        🎭 REPLAY MODE - NO REWARDS
      </div>

      <!-- Floating magical elements -->
      <div class="absolute lg:-top-0 lg:-left-0 sm:-top-6 sm:-left-6 lg:text-2xl sm:text-6xl text-3xl animate-bounce">🌟</div>
      <div class="absolute lg:-top-0 lg:-right-0 sm:-top-6 sm:-right-6 lg:text-2xl sm:text-6xl text-3xl animate-pulse">🔮</div>
      <div class="absolute bottom-4 left-2 lg:text-2xl sm:text-6xl text-3xl animate-bounce delay-500">✨</div>

      <!-- Decorative gradient bar -->
      <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400"></div>

      <div class="relative px-4 pb-4 pt-5 sm:p-8 sm:pb-6">
        <!-- Close button -->
        <button 
          @click="handleClose"
          class="absolute top-3 right-3 text-emerald-300 hover:text-white text-2xl sm:text-4xl lg:text-3xl font-bold transition-colors z-10 w-8 h-8 sm:w-12 sm:h-12 lg:w-10 lg:h-10 flex items-center justify-center rounded-full hover:bg-emerald-700/50"
        >
          ×
        </button>

        <!-- Progress dots -->
        <div class="flex justify-center gap-2 mb-4 sm:mb-6">
          <button
            v-for="i in 5"
            :key="i"
            @click="currentStep = i"
            class="w-2 h-2 sm:w-3 sm:h-3 rounded-full transition-all duration-300"
            :class="currentStep === i ? 'bg-emerald-400 scale-125' : currentStep > i ? 'bg-emerald-600' : 'bg-gray-600'"
          ></button>
        </div>

        <!-- Step 1: The Island Merchant -->
        <div v-if="currentStep === 1" class="space-y-4 sm:space-y-6 animate-fadeIn">
          <div class="text-center">
            <div class="text-4xl sm:text-6xl lg:text-6xl mb-3 sm:mb-4">🏝️</div>
            <h2 class="text-2xl sm:text-5xl lg:text-4xl font-bold text-emerald-300 mb-2">The Island Merchant</h2>
            <p class="text-sm sm:text-3xl lg:text-lg text-emerald-100">A Mystical Encounter</p>
          </div>

          <div class="bg-black/30 p-4 sm:p-6 rounded-xl border-2 border-emerald-500/30">
            <p class="text-white leading-relaxed text-sm sm:text-3xl lg:text-lg">
              As your ship approaches the mysterious floating island, you notice a figure standing at the shore. 
              She waves gracefully, her robes shimmering with an otherworldly glow...
            </p>
          </div>

          <button 
            @click="nextStep"
            class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-4 py-3 sm:px-6 sm:py-4 rounded-xl font-bold text-sm sm:text-3xl lg:text-lg transform hover:scale-105 transition-all shadow-lg"
          >
            Continue → 
          </button>
        </div>

        <!-- Step 2: Meeting Astrid -->
        <div v-else-if="currentStep === 2" class="space-y-4 sm:space-y-6 animate-slideIn">
          <div class="text-center">
            <div class="text-4xl sm:text-6xl lg:text-6xl mb-3 sm:mb-4">👩‍🦰</div>
            <h2 class="text-2xl sm:text-5xl lg:text-4xl font-bold text-emerald-300 mb-2">Astrid the Wise</h2>
            <p class="text-sm sm:text-3xl lg:text-lg text-emerald-100">Guardian of Ancient Treasures</p>
          </div>

          <div class="bg-black/30 p-4 sm:p-6 rounded-xl border-2 border-emerald-500/30">
            <p class="text-white leading-relaxed text-sm sm:text-3xl lg:text-lg mb-3 sm:mb-4">
              "Welcome, traveler," she says with a warm smile. "I am Astrid, keeper of this enchanted marketplace. 
              For centuries, I've been collecting rare artifacts from across the nine realms."
            </p>
            <p class="text-emerald-300 font-semibold text-sm sm:text-3xl lg:text-lg">
              "Those who prove themselves worthy may trade with me..."
            </p>
          </div>

          <button 
            @click="nextStep"
            class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-4 py-3 sm:px-6 sm:py-4 rounded-xl font-bold text-sm sm:text-3xl lg:text-lg transform hover:scale-105 transition-all shadow-lg"
          >
            Continue →
          </button>
        </div>

        <!-- Step 3: The Challenge -->
        <div v-else-if="currentStep === 3" class="space-y-4 sm:space-y-6 animate-slideIn">
          <div class="text-center">
            <div class="text-4xl sm:text-6xl lg:text-6xl mb-3 sm:mb-4">⚔️</div>
            <h2 class="text-2xl sm:text-5xl lg:text-4xl font-bold text-emerald-300 mb-2">The Trial</h2>
            <p class="text-sm sm:text-3xl lg:text-lg text-emerald-100">Prove Your Worth</p>
          </div>

          <div class="bg-black/30 p-4 sm:p-6 rounded-xl border-2 border-emerald-500/30">
            <p class="text-white leading-relaxed text-sm sm:text-3xl lg:text-lg mb-3 sm:mb-4">
              Astrid gestures toward a glowing pedestal. "To earn my trust, you must demonstrate your commitment 
              to the ancient ways. Explore my island, discover its secrets..."
            </p>
            <div class="bg-emerald-900/50 p-3 sm:p-4 rounded-lg border border-emerald-500/50">
              <p class="text-emerald-200 font-semibold mb-2 text-xs sm:text-3xl lg:text-base">🎯 Your Quest:</p>
              <ul class="text-white space-y-1 sm:space-y-2 text-xs sm:text-2xl lg:text-sm">
                <li>✓ Visit the Sacred Trees</li>
                <li>✓ Touch the Ancient Pillars</li>
                <li>✓ Discover the Hidden Flowers</li>
              </ul>
            </div>
          </div>

          <button 
            @click="nextStep"
            class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-4 py-3 sm:px-6 sm:py-4 rounded-xl font-bold text-sm sm:text-3xl lg:text-lg transform hover:scale-105 transition-all shadow-lg"
          >
            Accept the Challenge →
          </button>
        </div>

        <!-- Step 4: The Reward -->
        <div v-else-if="currentStep === 4" class="space-y-4 sm:space-y-6 animate-slideIn">
          <div class="text-center">
            <div class="text-4xl sm:text-6xl lg:text-6xl mb-3 sm:mb-4">✨</div>
            <h2 class="text-2xl sm:text-5xl lg:text-4xl font-bold text-emerald-300 mb-2">The Merchant's Blessing</h2>
            <p class="text-sm sm:text-3xl lg:text-lg text-emerald-100">Your Reward Awaits</p>
          </div>

          <div class="bg-black/30 p-4 sm:p-6 rounded-xl border-2 border-emerald-500/30">
            <p class="text-white leading-relaxed text-sm sm:text-3xl lg:text-lg mb-4 sm:mb-6">
              "You have proven yourself worthy!" Astrid exclaims. "As a token of my appreciation, 
              accept these gifts from my personal collection..."
            </p>

            <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
              <div class="bg-gradient-to-br from-yellow-900/50 to-amber-900/50 p-3 sm:p-4 rounded-lg border-2 border-yellow-500/50">
                <div class="text-2xl sm:text-4xl lg:text-3xl mb-1 sm:mb-2">🪙</div>
                <div class="text-yellow-400 font-bold text-lg sm:text-4xl lg:text-2xl">150 Tokens</div>
                <div class="text-yellow-200 text-xs sm:text-2xl lg:text-xs">Ancient Currency</div>
              </div>
              <div class="bg-gradient-to-br from-purple-900/50 to-violet-900/50 p-3 sm:p-4 rounded-lg border-2 border-purple-500/50">
                <div class="text-2xl sm:text-4xl lg:text-3xl mb-1 sm:mb-2">🔮</div>
                <div class="text-purple-400 font-bold text-lg sm:text-4xl lg:text-2xl">5 Runes</div>
                <div class="text-purple-200 text-xs sm:text-2xl lg:text-xs">Mystical Power</div>
              </div>
              <div class="bg-gradient-to-br from-red-900/50 to-orange-900/50 p-3 sm:p-4 rounded-lg border-2 border-red-500/50">
                <div class="text-2xl sm:text-4xl lg:text-3xl mb-1 sm:mb-2">🎫</div>
                <div class="text-red-400 font-bold text-lg sm:text-4xl lg:text-2xl">20% OFF</div>
                <div class="text-red-200 text-xs sm:text-2xl lg:text-xs">Special Discount</div>
              </div>
              <div class="bg-gradient-to-br from-emerald-900/50 to-teal-900/50 p-3 sm:p-4 rounded-lg border-2 border-emerald-500/50">
                <div class="text-2xl sm:text-4xl lg:text-3xl mb-1 sm:mb-2">🏅</div>
                <div class="text-emerald-400 font-bold text-base sm:text-3xl lg:text-xl">Merchant's Blessing</div>
                <div class="text-emerald-200 text-xs sm:text-2xl lg:text-xs">Rare Badge</div>
              </div>
            </div>
          </div>

          <button 
            @click="nextStep"
            class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-4 py-3 sm:px-6 sm:py-4 rounded-xl font-bold text-sm sm:text-3xl lg:text-lg transform hover:scale-105 transition-all shadow-lg animate-pulse"
          >
            Claim Rewards →
          </button>
        </div>

        <!-- Step 5: Completion -->
        <div v-else-if="currentStep === 5" class="space-y-4 sm:space-y-6 animate-fadeIn text-center">
          <div class="text-6xl sm:text-9xl lg:text-8xl mb-3 sm:mb-4 animate-bounce">🎉</div>
          <h2 class="text-3xl sm:text-6xl lg:text-5xl font-bold text-emerald-300 mb-3 sm:mb-4">Quest Complete!</h2>
          
          <div class="bg-gradient-to-r from-emerald-500/20 to-teal-500/20 p-6 sm:p-8 rounded-xl border-2 border-emerald-400">
            <p class="text-white text-base sm:text-4xl lg:text-xl mb-3 sm:mb-4">
              {{ isReplay ? "You've relived Astrid's blessing!" : "You've earned Astrid's trust and received her blessing!" }}
            </p>
            <p class="text-emerald-300 text-sm sm:text-3xl lg:text-lg">
              {{ isReplay ? "Quest already completed - no new rewards." : "Your rewards have been added to your account." }}
            </p>
          </div>

          <button 
            @click="completeQuest"
            :disabled="completing"
            :class="isReplay ? 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500' : 'bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-500 hover:to-orange-500'"
            class="w-full text-white px-4 py-3 sm:px-6 sm:py-4 rounded-xl font-bold text-sm sm:text-3xl lg:text-lg transform hover:scale-105 transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ completing ? 'Processing...' : (isReplay ? '🎭 Finish Story' : '⚔️ Claim Rewards') }}
          </button>
        </div>

        <!-- Mystical elements footer -->
        <div class="mt-4 sm:mt-6">
          <div class="flex justify-center items-center gap-3 sm:gap-6 text-emerald-300">
            <div class="flex flex-col items-center gap-1 text-xs sm:text-2xl lg:text-xs">
              <span class="text-lg sm:text-4xl lg:text-xl animate-pulse">💎</span>
              <span class="font-semibold">Enchanted Crystals</span>
            </div>
            <div class="flex flex-col items-center gap-1 text-xs sm:text-2xl lg:text-xs">
              <span class="text-lg sm:text-4xl lg:text-xl animate-pulse delay-300">🗺️</span>
              <span class="font-semibold">Treasure Maps</span>
            </div>
            <div class="flex flex-col items-center gap-1 text-xs sm:text-2xl lg:text-xs">
              <span class="text-lg sm:text-4xl lg:text-xl animate-pulse delay-500">🧪</span>
              <span class="font-semibold">Healing Potions</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useToast } from 'vue-toastification'
import axiosClient from '../../axios'

const toast = useToast()

const props = defineProps({
  show: Boolean,
  isReplay: Boolean
})

const emit = defineEmits(['close', 'quest-complete'])

const currentStep = ref(1)
const completing = ref(false)

const nextStep = () => {
  if (currentStep.value < 5) {
    currentStep.value++
  }
}

const handleBackdropClick = () => {
  if (currentStep.value === 5) {
    completeQuest()
  }
}

const handleClose = () => {
  currentStep.value = 1
  emit('close')
}

const completeQuest = async () => {
  if (completing.value) return
  completing.value = true

  try {
    if (props.isReplay) {
      toast.info('🎭 Thanks for replaying! Quest already completed.', {
        timeout: 3000
      })
      
      setTimeout(() => {
        handleClose()
      }, 300)
      
      completing.value = false
      return
    }

    console.log('🏝️ Completing Island Merchant quest...')
    
    const response = await axiosClient.post('/customer/complete-quest', {
      quest_id: '2',
      rewards: {
        tokens: 150,
        runes: 5,
        discount: 20,
        badge: 'merchant-blessing'
      }
    })

    console.log('✅ Island quest completed:', response.data)

    emit('quest-complete', response.data)

    toast.success('🎉 Quest Complete! +150 Tokens, +5 Runes, +20% Discount, +Merchant Badge!', {
      timeout: 5000
    })

    setTimeout(() => {
      handleClose()
    }, 500)

  } catch (error) {
    console.error('❌ Failed to complete quest:', error)
    
    if (error.response?.status === 401) {
      toast.error('⚠️ Please login to complete quests!')
    } else if (error.response?.data?.message) {
      toast.error('⚠️ ' + error.response.data.message)
    } else {
      toast.error('❌ Failed to complete quest. Please try again.')
    }
  } finally {
    completing.value = false
  }
}
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.5s ease-out;
}

.animate-slideIn {
  animation: slideIn 0.5s ease-out;
}

.delay-300 {
  animation-delay: 300ms;
}

.delay-500 {
  animation-delay: 500ms;
}
</style>