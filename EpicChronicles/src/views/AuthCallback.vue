<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 flex items-center justify-center">
    <div class="text-center">
      <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-yellow-400 mx-auto mb-4"></div>
      <p class="text-white text-xl font-bold mb-2">⚔️ Completing sign in...</p>
      <p class="text-gray-400 text-sm">Welcome to the Brotherhood!</p>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useStore } from 'vuex'

const router = useRouter()
const route = useRoute()
const store = useStore()

onMounted(async () => {
  const token = route.query.token
  const error = route.query.error
  const isAdmin = route.query.is_admin === '1'

  console.log('🔐 Auth callback received:', { token: token?.substring(0, 20) + '...', isAdmin, error })

  if (error) {
    console.error('❌ Social auth error:', error)
    router.push({ 
      name: 'login', 
      query: { error: 'Social login failed. Please try again.' } 
    })
    return
  }

  if (token) {
    console.log('✅ Token received! Setting up session...')
    
    try {
      // Store token
      sessionStorage.setItem('TOKEN', token)
      store.commit('setToken', token)
      
      // Fetch user data
      console.log('📡 Fetching user data...')
      await store.dispatch('getCurrentUser')
      
      // Fetch cart
      console.log('🛒 Fetching cart...')
      await store.dispatch('cart/fetchCart')
      
      console.log('✅ All data loaded!')
      
      // Redirect based on role
      if (isAdmin) {
        console.log('👑 Admin detected - redirecting to dashboard')
        router.push({ name: 'app.dashboard' })
      } else {
        console.log('⚔️ Customer - redirecting to shop')
        router.push({ name: 'shop' })
      }
    } catch (error) {
      console.error('❌ Failed to load user data:', error)
      router.push({ name: 'login' })
    }
  } else {
    console.error('❌ No token received')
    router.push({ name: 'login' })
  }
})
</script>