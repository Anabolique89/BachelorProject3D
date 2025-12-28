<template>
  <div 
    v-if="isVisible" 
    :class="`fixed inset-0 bg-black transition-all duration-300 flex items-center justify-center z-50 p-4 ${
      isAnimating ? 'bg-opacity-70' : 'bg-opacity-0'
    }`"
    @click="handleClose"
    style="z-index: 9999;"
  >
    <div 
      :class="`bg-gradient-to-br ${currentStory.bgGradient} p-6 sm:p-4 rounded-2xl w-full lg:max-w-md sm:max-w-4xl relative shadow-2xl border-4 border-yellow-400 transform transition-all duration-500 ${
        isAnimating ? 'scale-100 opacity-100 rotate-0' : 'scale-75 opacity-0 rotate-12'
      }`"
      @click.stop
    >
      <!-- REPLAY MODE BANNER -->
      <div v-if="isReplay" class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-purple-600 text-white px-4 sm:px-6 py-1 sm:py-2 rounded-full font-bold text-xs sm:text-sm shadow-lg border-2 border-purple-400 z-10 animate-pulse">
        🎭 REPLAY MODE - NO REWARDS
      </div>

      <!-- Floating emoji animation -->
      <div class="absolute -top-4 -right-4 lg:text-2xl sm:text-8xl animate-bounce">
        {{ currentStory.emoji }}
      </div>

      <!-- Close button -->
      <button 
        @click="handleClose"
        class="absolute top-2 right-2 sm:top-3 sm:right-3 text-white hover:text-yellow-300 lg:text-md sm:text-4xl font-bold lg:w-8 lg:h-8 sm:w-15 sm:h-15 flex items-center justify-center rounded-full hover:bg-white hover:bg-opacity-20 transition-all transform hover:scale-110 active:scale-95"
      >
        ×
      </button>

      <!-- Interactive story progress indicator -->
      <div class="flex justify-center lg:mb-4 sm:mb-8 sm:mt-8">
        <div class="flex lg:space-x-2 sm:space-x-3">
          <button
            v-for="step in 4"
            :key="step"
            @click="handleStepClick(step)"
            :class="`lg:w-4 lg:h-4 sm:w-8 sm:h-8 rounded-full transition-all duration-300 transform hover:scale-110 active:scale-95 ${
              step === currentStep 
                ? 'bg-yellow-400 shadow-lg scale-125 animate-pulse' 
                : step < currentStep 
                ? 'bg-green-400 hover:bg-green-300 cursor-pointer' 
                : 'bg-gray-400 bg-opacity-50 cursor-not-allowed'
            } ${step <= currentStep ? 'shadow-lg' : ''}`"
            :disabled="step > currentStep && currentStep !== 4"
          />
        </div>
      </div>

      <!-- Story content with slide animation -->
      <div class="text-center lg:mb-2 sm:mb-6 overflow-auto sm:p-4">
        <h2 class="lg:text-xl sm:text-4xl font-bold lg:mb-0 sm:mb-4 text-yellow-100 flex items-center justify-center animate-fadeIn">
          <span class="mr-2 lg:text-xl sm:text-6xl animate-pulse">{{ currentStory.title.split(' ')[0] }}</span>
          <span class="animate-slideIn">{{ currentStory.title.split(' ').slice(1).join(' ') }}</span>
        </h2>
        
        <div class="text-white lg:text-sm sm:text-4xl leading-relaxed lg:mb-0 sm:mb-4 overflow-y-auto scrollbar-thin bg-opacity-30 p-2 rounded-lg backdrop-blur-sm animate-slideUp">
          <p class="animate-typewriter">{{ currentStory.content }}</p>
        </div>
      </div>

      <!-- Reward Preview (Step 4 only) -->
      <div v-if="currentStep === 4" class="bg-yellow-400/20 border-2 border-yellow-400 rounded-lg lg:p-4 sm:p-6 lg:mb-2 sm:mb-4 animate-fadeIn">
        <h3 class="text-yellow-400 font-bold text-center lg:text-base sm:text-3xl lg:mb-2 sm:mb-4">🎁 Quest Rewards:</h3>
        <div class="grid grid-cols-3 gap-3 text-white text-center">
          <div>
            <div class="lg:text-2xl sm:text-5xl lg:mb-1 sm:mb-2">🪙</div>
            <div class="font-bold lg:text-sm sm:text-2xl">100 Tokens</div>
          </div>
          <div>
            <div class="lg:text-2xl sm:text-5xl lg:mb-1 sm:mb-2">🔮</div>
            <div class="font-bold lg:text-sm sm:text-2xl">3 Runes</div>
          </div>
          <div>
            <div class="lg:text-2xl sm:text-5xl lg:mb-1 sm:mb-2">🎫</div>
            <div class="font-bold lg:text-sm sm:text-2xl">10% OFF</div>
          </div>
        </div>
      </div>

      <!-- Action buttons with hover effects -->
      <div class="flex sm:flex-row gap-3 justify-center">
        <button 
          @click="handleNext"
          :disabled="completing"
          :class="`text-white lg:text-sm sm:text-4xl lg:px-2 sm:px-4 lg:py-0 sm:py-3 rounded-lg transition-all duration-300 font-bold shadow-lg flex items-center justify-center gap-2 transform hover:scale-105 hover:shadow-2xl active:scale-95 group ${
            currentStep === 4 
              ? 'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 animate-pulse hover:animate-none' 
              : 'bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-400 hover:to-orange-400 animate-pulse hover:animate-none'
          } ${completing ? 'opacity-50 cursor-not-allowed' : ''}`"
        >
          <span>{{ completing ? 'Claiming...' : currentStory.button }}</span>
          <span class="group-hover:translate-x-1 transition-transform">
            {{ currentStep === 4 ? '🎁' : '→' }}
          </span>
        </button>
        
        <button 
          @click="handleClose"
          :disabled="completing"
          class="bg-gray-600 hover:bg-gray-500 text-white lg:text-sm sm:text-4xl lg:px-2 sm:px-6 py-3 rounded-lg transition-all duration-300 font-semibold shadow-lg transform hover:scale-105 active:scale-95"
        >
          {{ currentStep === 4 ? 'End Story ⚔️' : 'Close 🛡️' }}
        </button>
      </div>

      <!-- Step indicator with Viking theme -->
      <div class="text-center mt-4 lg:text-xs sm:text-2xl text-yellow-200 flex items-center justify-center gap-2 p-2">
        <span class="animate-bounce">⚔️</span>
        <span>Chapter {{ currentStep }} of 4</span>
        <span class="animate-bounce delay-300">🛡️</span>
      </div>

      <!-- Decorative elements -->
      <div class="absolute bottom-2 left-2 text-yellow-400 opacity-50 animate-pulse">
        <span class="lg:text-lg sm:text-7xl">🗿</span>
      </div>
      <div class="absolute top-1/11 left-2 text-yellow-400 opacity-30 animate-bounce delay-1000">
        <span class="lg:text-sm sm:text-4xl">⚡</span>
      </div>
      <div class="absolute bottom-1/4 right-4 text-yellow-400 opacity-40 animate-pulse delay-500">
        <span class="lg:text-sm sm:text-4xl">🌟</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import axiosClient from '../../axios'
import { useToast } from 'vue-toastification'

const toast = useToast()

const props = defineProps({
  show: Boolean,
  isReplay: Boolean // ← Added isReplay prop
})

const emit = defineEmits(['close', 'quest-complete'])

const currentStep = ref(1)
const isVisible = ref(false)
const isAnimating = ref(false)
const completing = ref(false)

// Watch for show prop changes to handle animations
watch(() => props.show, (newVal) => {
  if (newVal) {
    isVisible.value = true
    setTimeout(() => {
      isAnimating.value = true
    }, 50)
  } else {
    isAnimating.value = false
    setTimeout(() => {
      isVisible.value = false
    }, 300)
  }
})

const stories = {
  1: {
    title: "🚢 The Arrival",
    emoji: "🌊",
    content: "The longboat cuts through the morning mist, its dragon-carved prow emerging from the fog like a creature from legend. Captain Ragnar and his crew of seasoned warriors have been sailing for three days, their ship heavy with Norse goods - amber from the Baltic shores, fine iron weapons forged in the cold mountains, and warm furs from the northern wilderness.",
    button: "Continue the Journey",
    bgGradient: "from-blue-900 via-blue-800 to-slate-700"
  },
  2: {
    title: "⚓ Spotting the Island",
    emoji: "🏝️",
    content: "As the sun climbs higher, Olaf the Sharp-Eyed calls from the mast: 'Land ahead!' The mysterious floating island appears before them, defying all natural law. Ancient runes glow softly along its rocky edges, and a magnificent tree stretches toward the sky. 'By Odin's beard,' whispers Ragnar, 'this is no ordinary island. The legends spoke true.'",
    button: "Approach the Island",
    bgGradient: "from-emerald-800 via-teal-700 to-blue-800"
  },
  3: {
    title: "🤝 The Encounter",
    emoji: "✨",
    content: "As they anchor near the island's edge, figures emerge from behind the great tree - not enemies, but traders! The Island Merchants, keepers of this magical realm, have been waiting. Their leader, a wise woman named Astrid the Truthseer, approaches with a knowing smile. 'We have what you seek, northern warriors, and we know you carry treasures from distant lands.'",
    button: "Begin Trading",
    bgGradient: "from-purple-800 via-violet-700 to-indigo-800"
  },
  4: {
    title: "💎 The Great Exchange",
    emoji: "🏆",
    content: "What follows is the most extraordinary trade in Viking history. The Island Merchants offer enchanted items: crystals that never lose their light, maps to hidden treasures, and potions that heal wounds instantly. In exchange, Ragnar's amber resonates with the island's magic, his iron weapons gain mystical properties, and his furs become blessed with eternal warmth. Both parties know they've witnessed something legendary.",
    button: "Claim Rewards",
    bgGradient: "from-amber-800 via-orange-700 to-red-800"
  }
}

const currentStory = computed(() => stories[currentStep.value])

const handleNext = async () => {
  if (currentStep.value < 4) {
    currentStep.value++
  } else {
    // Complete quest and claim rewards
    await completeQuest()
  }
}

const handleStepClick = (step) => {
  if (step <= currentStep.value || currentStep.value === 4) {
    currentStep.value = step
  }
}

const completeQuest = async () => {
  if (completing.value) return
  completing.value = true

  try {
    // If replay mode, just close
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

    console.log('🚢 Completing Ragnar\'s Journey quest...')
    
    const response = await axiosClient.post('/customer/complete-quest', {
      quest_id: '1',
      rewards: {
        tokens: 100,
        runes: 3,
        discount: 10
      }
    })

    console.log('✅ Quest completed successfully:', response.data)

    emit('quest-complete', response.data)

    toast.success('🎉 Quest Complete! +100 Tokens, +3 Runes, +10% Discount!', {
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

const handleClose = () => {
  currentStep.value = 1
  emit('close')
}
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
  from { opacity: 0; transform: translateX(50px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes typewriter {
  from { opacity: 0; }
  to { opacity: 1; }
}

.animate-fadeIn {
  animation: fadeIn 0.8s ease-out;
}

.animate-slideIn {
  animation: slideIn 0.8s ease-out 0.3s both;
}

.animate-slideUp {
  animation: slideUp 0.6s ease-out 0.5s both;
}

.animate-typewriter {
  animation: typewriter 1s ease-out 0.7s both;
}

.delay-300 {
  animation-delay: 300ms;
}

.delay-500 {
  animation-delay: 500ms;
}

.delay-1000 {
  animation-delay: 1000ms;
}

/* Custom scrollbar */
.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.3);
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background: rgba(251, 191, 36, 0.5);
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: rgba(251, 191, 36, 0.7);
}
</style>