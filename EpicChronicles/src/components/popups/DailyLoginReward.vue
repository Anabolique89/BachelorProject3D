<template>
  <div 
    v-if="show" 
    class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 p-4"
    @click="handleClose"
  >
    <div 
      class="bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 p-8 rounded-3xl max-w-2xl w-full relative shadow-2xl border-4 border-purple-400"
      @click.stop
    >
      <!-- Decorations -->
      <div class="absolute -top-6 -left-6 text-6xl animate-bounce">🎁</div>
      <div class="absolute -top-6 -right-6 text-6xl animate-pulse">✨</div>
      
      <!-- Close button -->
      <button 
        @click="handleClose"
        class="absolute top-3 right-3 text-white hover:text-purple-300 text-3xl font-bold w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/20 transition-all"
      >
        ×
      </button>

      <!-- Title -->
      <h1 class="text-4xl font-bold mb-2 text-purple-200 text-center">
        Daily Login Reward
      </h1>
      <p class="text-center text-purple-300 mb-6">
        Day {{ currentDay }} of your Viking journey!
      </p>

      <!-- Current Day Reward (Big Display) -->
      <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl p-6 mb-6 border-4 border-yellow-600 animate-pulse">
        <p class="text-center text-gray-900 font-bold text-2xl mb-4">
          🎉 TODAY'S REWARD 🎉
        </p>
        <div class="flex justify-center items-center gap-8">
          <div class="text-center">
            <div class="text-6xl mb-2">{{ todayReward.icon }}</div>
            <div class="text-3xl font-bold text-gray-900">{{ todayReward.amount }}</div>
            <div class="text-sm text-gray-800">{{ todayReward.type }}</div>
          </div>
        </div>
        <p v-if="todayReward.bonus" class="text-center text-red-700 font-bold mt-4 animate-bounce">
          ⚡ BONUS: {{ todayReward.bonus }} ⚡
        </p>
      </div>

      <!-- 7-Day Calendar -->
      <div class="mb-6">
        <h3 class="text-white font-bold text-center mb-4">7-Day Login Streak</h3>
        <div class="grid grid-cols-7 gap-2">
          <div
            v-for="day in 7"
            :key="day"
            :class="`
              relative p-4 rounded-lg text-center transition-all transform hover:scale-105
              ${day < currentDay ? 'bg-green-600 border-2 border-green-400' : ''}
              ${day === currentDay ? 'bg-yellow-400 border-4 border-yellow-600 scale-110 animate-pulse' : ''}
              ${day > currentDay ? 'bg-gray-700 border-2 border-gray-600 opacity-50' : ''}
            `"
          >
            <div class="text-xs text-white font-bold mb-1">Day {{ day }}</div>
            <div class="text-2xl mb-1">{{ rewards[day - 1].icon }}</div>
            <div :class="`text-xs font-bold ${day === currentDay ? 'text-gray-900' : 'text-white'}`">
              {{ rewards[day - 1].amount }}
            </div>
            
            <!-- Checkmark for claimed days -->
            <div v-if="day < currentDay" class="absolute top-1 right-1 text-white text-xl">✓</div>
            
            <!-- Special day 7 indicator -->
            <div v-if="day === 7" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-bounce">
              MEGA
            </div>
          </div>
        </div>
      </div>

      <!-- Streak Bonus Info -->
      <div class="bg-purple-800/50 rounded-lg p-4 mb-6 border-2 border-purple-400">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-purple-200 font-bold">Current Streak:</p>
            <p class="text-3xl font-bold text-yellow-400">{{ currentStreak }} days 🔥</p>
          </div>
          <div class="text-right">
            <p class="text-purple-200 font-bold">Next Milestone:</p>
            <p class="text-xl text-white">Day 7 = 500 Tokens + Legendary Rune!</p>
          </div>
        </div>
      </div>

      <!-- Claim Button -->
      <button
        v-if="!hasClaimed"
        @click="claimReward"
        :disabled="claiming"
        class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 disabled:from-gray-600 disabled:to-gray-700 text-white text-2xl font-bold py-4 rounded-xl transition-all transform hover:scale-105 active:scale-95 shadow-lg"
        :class="{ 'animate-pulse': !claiming }"
      >
        {{ claiming ? '⏳ Claiming...' : '⚔️ Claim Your Reward!' }}
      </button>

      <!-- Already Claimed Message -->
      <div v-else class="bg-green-600 text-white text-center py-4 rounded-xl font-bold">
        ✅ Reward Claimed! Come back tomorrow!
      </div>

      <!-- Reminder -->
      <p class="text-center text-purple-300 text-sm mt-4">
        <span class="animate-pulse">✨</span>
        Login daily to maintain your streak!
        <span class="animate-pulse">✨</span>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axiosClient from '../../axios'

const props = defineProps({
  show: Boolean,
  loginData: {
    type: Object,
    default: () => ({
      current_day: 1,
      current_streak: 1,
      has_claimed_today: false
    })
  }
})

const emit = defineEmits(['close', 'reward-claimed'])

const claiming = ref(false)
const currentDay = ref(props.loginData.current_day)
const currentStreak = ref(props.loginData.current_streak)
const hasClaimed = ref(props.loginData.has_claimed_today)

const rewards = [
  { day: 1, icon: '🪙', amount: '10 Tokens', type: 'Tokens' },
  { day: 2, icon: '🪙', amount: '20 Tokens', type: 'Tokens' },
  { day: 3, icon: '⚡', amount: '1 Rune', type: 'Rune', bonus: '+ 30 Tokens' },
  { day: 4, icon: '🪙', amount: '50 Tokens', type: 'Tokens' },
  { day: 5, icon: '⚡', amount: '2 Runes', type: 'Runes', bonus: '+ 50 Tokens' },
  { day: 6, icon: '🎟️', amount: '15% OFF', type: 'Discount' },
  { day: 7, icon: '👑', amount: '500 Tokens', type: 'MEGA', bonus: '+ Legendary Rune + 20% OFF' },
]

const todayReward = computed(() => rewards[currentDay.value - 1])

const claimReward = async () => {
  if (claiming.value || hasClaimed.value) return
  
  claiming.value = true
  
  try {
    const response = await axiosClient.post('/customer/claim-daily-reward', {
      day: currentDay.value
    })
    
    hasClaimed.value = true
    emit('reward-claimed', response.data)
    
    // Show success message for 2 seconds then close
    setTimeout(() => {
      emit('close')
    }, 2000)
  } catch (error) {
    console.error('Failed to claim reward:', error)
    alert('Failed to claim reward. Please try again!')
  } finally {
    claiming.value = false
  }
}

const handleClose = () => {
  if (!claiming.value) {
    emit('close')
  }
}
</script>