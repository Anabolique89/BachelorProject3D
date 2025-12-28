<template>
  <div 
    v-if="show" 
    class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 p-4"
    @click="handleClose"
  >
    <div 
      class="bg-gradient-to-br from-amber-900 via-orange-800 to-red-900 p-4 sm:p-6 rounded-3xl w-full lg:max-w-lg sm:max-w-3xl relative shadow-2xl border-4 border-yellow-400 transform transition-all"
      @click.stop
    >
      <!-- Floating decorations -->
      <div class="absolute lg:-top-3 sm:-top-4 lg:-left-3 sm:-left-4 lg:text-2xl sm:text-5xl animate-bounce">⚔️</div>
      <div class="absolute lg:-top-3 sm:-top-4 lg:-right-3 sm:-right-4 lg:text-2xl sm:text-5xl animate-bounce delay-300">🛡️</div>
      
      <!-- Close button -->
      <button 
        @click="handleClose"
        class="absolute lg:top-2 sm:top-3 lg:right-2 sm:right-3 text-white hover:text-yellow-300 lg:text-2xl sm:text-4xl font-bold lg:w-8 lg:h-8 sm:w-12 sm:h-12 flex items-center justify-center rounded-full hover:bg-white/20 transition-all"
      >
        ×
      </button>

      <!-- Title -->
      <h1 class="lg:text-2xl sm:text-5xl font-bold lg:mb-4 sm:mb-6 text-yellow-400 text-center flex items-center justify-center gap-2">
        <span class="lg:text-3xl sm:text-6xl">🎰</span>
        <span>Viking's Fortune!</span>
      </h1>

      <!-- Wheel Container -->
      <div class="relative lg:mb-4 sm:mb-6 flex justify-center">
        <div class="relative lg:w-64 lg:h-64 sm:w-96 sm:h-96">
          
          <div 
            v-if="useCustomImage"
            :style="{ transform: `rotate(${rotation}deg)` }"
            class="w-full h-full transition-transform duration-[4000ms] ease-out"
          >
            <img 
              src="/images/spin-wheel.png" 
              alt="Spin Wheel" 
              class="w-full h-full object-contain"
            />
          </div>

          <!-- SVG Wheel (Fallback if no image) -->
          <svg 
            v-else
            :style="{ transform: `rotate(${rotation}deg)` }"
            class="w-full h-full transition-transform duration-[4000ms] ease-out"
            viewBox="0 0 200 200"
          >
            <!-- Wheel segments -->
            <g v-for="(prize, index) in prizes" :key="index">
              <path
                :d="getSegmentPath(index)"
                :fill="prize.color"
                stroke="#000"
                stroke-width="2"
              />
              <text
                :x="getTextX(index)"
                :y="getTextY(index)"
                :transform="`rotate(${getTextRotation(index)}, ${getTextX(index)}, ${getTextY(index)})`"
                text-anchor="middle"
                class="lg:text-xs sm:text-sm font-bold fill-white"
              >
                {{ prize.label }}
              </text>
            </g>
            
            <!-- Center circle -->
            <circle cx="100" cy="100" r="15" fill="#FFD700" stroke="#000" stroke-width="2"/>
            <text x="100" y="105" text-anchor="middle" class="text-xs font-bold">SPIN</text>
          </svg>
          
          <!-- Pointer -->
          <div class="absolute top-0 left-1/2 transform -translate-x-1/2 lg:-translate-y-2 sm:-translate-y-3 z-10">
            <div class="lg:w-0 lg:h-0 lg:border-l-[12px] lg:border-r-[12px] lg:border-t-[24px] sm:border-l-[20px] sm:border-r-[20px] sm:border-t-[40px] border-l-transparent border-r-transparent border-t-red-600 drop-shadow-lg"></div>
          </div>
        </div>
      </div>

      <!-- Spin Button -->
      <button
        v-if="!hasSpun"
        @click="spin"
        :disabled="isSpinning"
        class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 disabled:from-gray-600 disabled:to-gray-700 text-white lg:text-lg sm:text-4xl font-bold lg:py-3 sm:py-4 rounded-xl transition-all transform hover:scale-105 active:scale-95 disabled:cursor-not-allowed shadow-lg"
        :class="{ 'animate-pulse': !isSpinning }"
      >
        {{ isSpinning ? '🎰 SPINNING...' : '🎯 SPIN NOW!' }}
      </button>

      <!-- Prize Display -->
      <div 
        v-if="wonPrize" 
        class="lg:mt-4 sm:mt-6 bg-yellow-400 lg:p-3 sm:p-4 rounded-xl border-4 border-yellow-600 animate-bounce"
      >
        <p class="text-center lg:text-xl sm:text-4xl font-bold text-gray-900 lg:mb-1 sm:mb-2">
          🎉 YOU WON! 🎉
        </p>
        <p class="text-center lg:text-lg sm:text-3xl font-bold text-gray-800">
          {{ wonPrize.label }}
        </p>
        <p class="text-center lg:text-xs sm:text-2xl text-gray-700 lg:mt-1 sm:mt-2">
          {{ wonPrize.description }}
        </p>
      </div>

      <!-- Claim Button -->
      <button
        v-if="wonPrize"
        @click="claimPrize"
        :disabled="claiming"
        class="w-full lg:mt-3 sm:mt-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 disabled:from-gray-600 disabled:to-gray-700 text-white lg:text-base sm:text-3xl font-bold lg:py-2 sm:py-3 rounded-xl transition-all transform hover:scale-105 active:scale-95 shadow-lg disabled:cursor-not-allowed"
      >
        {{ claiming ? 'Claiming...' : '⚔️ Claim Your Reward!' }}
      </button>

      <!-- Info Text -->
      <p class="text-center text-yellow-200 lg:text-xs sm:text-2xl lg:mt-3 sm:mt-4">
        <span class="animate-pulse">✨</span>
        {{ hasSpun ? 'Come back tomorrow for another spin!' : 'First-time bonus spin!' }}
        <span class="animate-pulse">✨</span>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useToast } from 'vue-toastification'
import axiosClient from '../../axios'

const toast = useToast()

const props = defineProps({
  show: Boolean
})

const emit = defineEmits(['close', 'prize-won'])

const isSpinning = ref(false)
const hasSpun = ref(false)
const claiming = ref(false)
const rotation = ref(0)
const wonPrize = ref(null)


const useCustomImage = ref(true) 

const prizes = [
  { id: 1, label: '50 Tokens', value: 50, type: 'tokens', color: '#EF4444', description: '50 Viking Tokens added to your account!' },
  { id: 2, label: 'FREE SHIP', value: 0, type: 'free_shipping', color: '#10B981', description: 'Free shipping on your next order!' },
  { id: 3, label: '20% OFF', value: 20, type: 'discount', color: '#F59E0B', description: '20% discount code for your next order!' },
  { id: 4, label: '100 Tokens', value: 100, type: 'tokens', color: '#3B82F6', description: '100 Viking Tokens - Epic reward!' },
  { id: 5, label: '10% OFF', value: 10, type: 'discount', color: '#8B5CF6', description: '10% discount on your next order!' },
  { id: 6, label: '2 Runes', value: 2, type: 'runes', color: '#EC4899', description: '2 Mystical Runes added to your collection!' },
]

const segmentAngle = 360 / prizes.length

const getSegmentPath = (index) => {
  const startAngle = index * segmentAngle - 90
  const endAngle = (index + 1) * segmentAngle - 90
  
  const startRad = (startAngle * Math.PI) / 180
  const endRad = (endAngle * Math.PI) / 180
  
  const x1 = 100 + 90 * Math.cos(startRad)
  const y1 = 100 + 90 * Math.sin(startRad)
  const x2 = 100 + 90 * Math.cos(endRad)
  const y2 = 100 + 90 * Math.sin(endRad)
  
  return `M 100 100 L ${x1} ${y1} A 90 90 0 0 1 ${x2} ${y2} Z`
}

const getTextX = (index) => {
  const angle = (index * segmentAngle + segmentAngle / 2 - 90) * Math.PI / 180
  return 100 + 60 * Math.cos(angle)
}

const getTextY = (index) => {
  const angle = (index * segmentAngle + segmentAngle / 2 - 90) * Math.PI / 180
  return 100 + 60 * Math.sin(angle)
}

const getTextRotation = (index) => {
  return index * segmentAngle + segmentAngle / 2
}

const spin = () => {
  if (isSpinning.value || hasSpun.value) return
  
  isSpinning.value = true
  
  toast.info('🎰 Spinning the wheel of fortune...', { timeout: 2000 })
  
  // Random prize selection
  const randomIndex = Math.floor(Math.random() * prizes.length)
  const targetPrize = prizes[randomIndex]
  
  // Calculate rotation to land on prize
  const baseRotation = 1800 // 5 full spins
  const prizeAngle = randomIndex * segmentAngle
  const finalRotation = baseRotation + (360 - prizeAngle) + segmentAngle / 2
  
  rotation.value = finalRotation
  
  // After spin completes
  setTimeout(() => {
    isSpinning.value = false
    hasSpun.value = true
    wonPrize.value = targetPrize
    
    toast.success(`🎉 You won: ${targetPrize.label}!`, { timeout: 5000 })
  }, 4000)
}

const claimPrize = async () => {
  if (claiming.value) return
  claiming.value = true

  try {
    console.log('💰 Claiming prize:', wonPrize.value)
    
    // Save prize to backend
    const response = await axiosClient.post('/customer/claim-spin-prize', {
      prize_type: wonPrize.value.type,
      prize_value: wonPrize.value.value,
      prize_label: wonPrize.value.label
    })
    
    console.log('✅ Prize claimed successfully:', response.data)
    
    emit('prize-won', wonPrize.value)
    
    toast.success('⚔️ Prize claimed! Check your account!', {
      timeout: 5000
    })
    
    // Store in localStorage to prevent repeated spins
    localStorage.setItem('last-spin-date', new Date().toDateString())
    
    setTimeout(() => {
      emit('close')
    }, 500)
    
  } catch (error) {
    console.error('❌ Failed to claim prize:', error)
    
    if (error.response?.status === 401) {
      toast.error('⚠️ Please login to claim prizes!')
    } else if (error.response?.data?.message) {
      toast.error('❌ ' + error.response.data.message)
    } else {
      toast.error('❌ Failed to claim prize. Please try again!')
    }
  } finally {
    claiming.value = false
  }
}

const handleClose = () => {
  if (!isSpinning.value) {
    emit('close')
  }
}
</script>

<style scoped>
.delay-300 {
  animation-delay: 300ms;
}
</style>
