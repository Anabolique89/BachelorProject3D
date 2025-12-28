<template>
  <div class="min-h-screen bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-amber-400 mb-4 animate-pulse">⚔️ Viking Quests ⚔️</h1>
        <p class="text-xl text-gray-300">Complete legendary quests to earn tokens and glory!</p>
        
        <!-- Stats Bar -->
        <div v-if="stats" class="mt-8 flex flex-wrap justify-center gap-4 sm:gap-8">
          <div class="bg-slate-800 px-6 py-4 rounded-xl border-2 border-amber-600 transform hover:scale-105 transition-all">
            <div class="text-3xl font-bold text-amber-400">{{ stats.completed_quests }}/{{ stats.total_quests }}</div>
            <div class="text-sm text-gray-400">Quests Completed</div>
          </div>
          <div class="bg-slate-800 px-6 py-4 rounded-xl border-2 border-emerald-600 transform hover:scale-105 transition-all">
            <div class="text-3xl font-bold text-emerald-400">{{ stats.available_tokens }}</div>
            <div class="text-sm text-gray-400">Available Tokens</div>
          </div>
          <div class="bg-slate-800 px-6 py-4 rounded-xl border-2 border-purple-600 transform hover:scale-105 transition-all">
            <div class="text-3xl font-bold text-purple-400">Level {{ stats.level }}</div>
            <div class="text-sm text-gray-400">{{ stats.level_name }}</div>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-20">
        <div class="animate-spin text-6xl mb-4">⚔️</div>
        <p class="text-gray-400 text-xl">Loading quests...</p>
      </div>

      <!-- Filter Tabs -->
      <div v-else class="mb-8 flex flex-wrap justify-center gap-3">
        <button
          v-for="filter in filters"
          :key="filter.value"
          @click="selectedFilter = filter.value"
          :class="`px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 ${
            selectedFilter === filter.value
              ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-lg'
              : 'bg-slate-700 text-gray-300 hover:bg-slate-600'
          }`"
        >
          {{ filter.icon }} {{ filter.label }}
        </button>
      </div>

      <!-- Quests Grid -->
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="quest in filteredQuests"
          :key="quest.id"
          class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl overflow-hidden border-2 hover:border-amber-500 transition-all transform hover:scale-105 cursor-pointer"
          :class="quest.is_completed ? 'border-green-500 opacity-90' : 'border-slate-700'"
        >
          <!-- Quest Image/Icon -->
          <div class="h-48 bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center relative">
            <div class="text-8xl">{{ quest.icon }}</div>
            
            <!-- Completed Badge -->
            <div v-if="quest.is_completed" class="absolute top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-full font-bold text-sm animate-pulse">
              ✓ COMPLETED
            </div>
            
            <!-- Difficulty Badge -->
            <div 
              class="absolute top-4 left-4 px-4 py-2 rounded-full font-bold text-sm"
              :class="{
                'bg-green-600 text-white': quest.difficulty === 'easy',
                'bg-yellow-600 text-white': quest.difficulty === 'medium',
                'bg-red-600 text-white': quest.difficulty === 'hard',
                'bg-purple-600 text-white': quest.difficulty === 'legendary',
              }"
            >
              {{ quest.difficulty.toUpperCase() }}
            </div>
          </div>

          <!-- Quest Info -->
          <div class="p-6">
            <h3 class="text-2xl font-bold text-amber-400 mb-2">{{ quest.title }}</h3>
            <p class="text-gray-300 mb-4 line-clamp-3">{{ quest.description }}</p>

            <!-- Quest Type & Category -->
            <div class="flex gap-2 mb-4">
              <span class="bg-slate-700 text-amber-300 px-3 py-1 rounded-full text-xs font-semibold">
                {{ quest.quest_type }}
              </span>
              <span class="bg-slate-700 text-emerald-300 px-3 py-1 rounded-full text-xs font-semibold">
                {{ quest.quest_category }}
              </span>
            </div>

            <!-- Rewards -->
            <div class="bg-slate-700/50 p-4 rounded-xl mb-4">
              <div class="text-sm text-gray-400 mb-2">Rewards:</div>
              <div class="flex gap-4">
                <div class="flex items-center gap-2">
                  <span class="text-2xl">🪙</span>
                  <span class="text-amber-400 font-bold">{{ quest.rewards.tokens }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-2xl">⭐</span>
                  <span class="text-purple-400 font-bold">{{ quest.rewards.experience }} XP</span>
                </div>
              </div>
            </div>

            <!-- Completed At -->
            <div v-if="quest.is_completed && quest.completed_at" class="text-sm text-gray-500 mb-3">
              Completed: {{ formatDate(quest.completed_at) }}
            </div>

            <!-- Action Button -->
            <button
              v-if="!quest.is_completed"
              class="w-full bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-bold py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg"
            >
              Start Quest →
            </button>
            <button
              v-else
              class="w-full bg-gray-600 text-gray-300 font-bold py-3 rounded-xl cursor-not-allowed"
              disabled
            >
              ✓ Completed
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredQuests.length === 0" class="text-center py-20">
        <div class="text-6xl mb-4">🏆</div>
        <p class="text-xl text-gray-400">No quests found in this category</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axiosClient from '../axios'

const quests = ref([])
const stats = ref(null)
const loading = ref(true)
const selectedFilter = ref('all')

const filters = [
  { value: 'all', label: 'All Quests', icon: '⚔️' },
  { value: 'daily', label: 'Daily', icon: '☀️' },
  { value: 'weekly', label: 'Weekly', icon: '📅' },
  { value: 'achievement', label: 'Achievements', icon: '🏆' },
  { value: 'completed', label: 'Completed', icon: '✅' },
]

const filteredQuests = computed(() => {
  if (selectedFilter.value === 'all') {
    return quests.value
  }
  if (selectedFilter.value === 'completed') {
    return quests.value.filter(q => q.is_completed)
  }
  return quests.value.filter(q => q.quest_type === selectedFilter.value)
})

const fetchQuests = async () => {
  try {
    const [questsResponse, statsResponse] = await Promise.all([
      axiosClient.get('/customer/quests'),
      axiosClient.get('/customer/stats')
    ])

    quests.value = questsResponse.data.quests
    stats.value = statsResponse.data.stats

    console.log('✅ Quests loaded:', quests.value)
    console.log('✅ Stats loaded:', stats.value)
  } catch (error) {
    console.error('❌ Failed to fetch quests:', error)
    if (error.response?.status === 401) {
      alert('⚠️ Please login to view quests!')
    }
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

onMounted(() => {
  fetchQuests()
})
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>