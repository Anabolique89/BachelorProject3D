<template>
  <div class="island-hub-container">
    <!-- Base Island Image -->
    <div class="island-wrapper">
      <img 
        src="/island-assets/upscaled.png" 
        alt="Viking Island Settlement" 
        class="base-image"
      />

      <!-- Interactive Navigation Elements -->
      
      <!-- 1. Longship - GAMES (3D Island) -->
      <div 
        class="nav-element longship"
        @click="navigateTo('games')"
        @mouseenter="showTooltip('games')"
        @mouseleave="hideTooltip"
      >
        <img src="/island-assets/overlays/longship.png" alt="Longship">
        <div v-if="activeTooltip === 'games'" class="nav-tooltip">
          <h3>🚢 Journey to the Isle</h3>
          <p>Sail to the 3D island and explore interactive adventures</p>
        </div>
      </div>

      <!-- 2. Great Hall - QUESTS -->
      <div 
        class="nav-element great-hall"
        @click="navigateTo('quests')"
        @mouseenter="showTooltip('quests')"
        @mouseleave="hideTooltip"
      >
        <img src="/island-assets/overlays/great-hall.png" alt="Great Hall">
        <div v-if="activeTooltip === 'quests'" class="nav-tooltip">
          <h3>🏛️ Great Hall</h3>
          <p>Accept quests from the chieftains and earn rewards</p>
        </div>
      </div>

      <!-- 3. Rune Circle - RUNES (Encyclopedia) -->
      <div 
        class="nav-element rune-circle"
        @click="navigateTo('runes')"
        @mouseenter="showTooltip('runes')"
        @mouseleave="hideTooltip"
      >
        <img src="/island-assets/overlays/rune-circle.png" alt="Rune Circle">
        <div v-if="activeTooltip === 'runes'" class="nav-tooltip">
          <h3>⚡ Seer's Circle</h3>
          <p>Discover rune types, values, and conversion rates</p>
        </div>
      </div>

      <!-- 4. Chieftain's Tent - PROFILE (Your Saga) -->
      <div 
        class="nav-element chieftain-tent"
        @click="navigateTo('profile')"
        @mouseenter="showTooltip('profile')"
        @mouseleave="hideTooltip"
      >
        <img src="/island-assets/overlays/rune-circle.png" alt="Chieftain's Tent">
        <div v-if="activeTooltip === 'profile'" class="nav-tooltip">
          <h3>⛺ Your Saga</h3>
          <p>View your Viking profile and personal journey</p>
        </div>
      </div>

      <!-- 5. Beacon Tower - CONTACT (Raven's Message) -->
      <div 
        class="nav-element beacon-tower"
        @click="navigateTo('contact')"
        @mouseenter="showTooltip('contact')"
        @mouseleave="hideTooltip"
      >
        <img src="/island-assets/overlays/beacon-tower.png" alt="Beacon Tower">
        <div v-if="activeTooltip === 'contact'" class="nav-tooltip">
          <h3>🔥 Raven's Message</h3>
          <p>Send word to the chieftains</p>
        </div>
      </div>

      <!-- 6. Treasure Chest - TREASURY (Orders + Level + Discounts) -->
      <div 
        class="nav-element treasure-chest"
        @click="navigateTo('treasury')"
        @mouseenter="showTooltip('treasury')"
        @mouseleave="hideTooltip"
      >
        <img src="/island-assets/overlays/chest.png" alt="Treasure Chest">
        <div v-if="activeTooltip === 'treasury'" class="nav-tooltip">
          <h3>💰 Your Treasury</h3>
          <p>View orders, discounts earned, and Viking level progress</p>
        </div>
      </div>

      <!-- 7. Quest Board - RUNE EXCHANGE (Odin's Call) -->
      <div 
        class="nav-element quest-board"
        @click="navigateTo('rune-exchange')"
        @mouseenter="showTooltip('rune-exchange')"
        @mouseleave="hideTooltip"
      >
        <img src="/island-assets/overlays/rune-exchange.png" alt="Quest Board">
        <div v-if="activeTooltip === 'rune-exchange'" class="nav-tooltip">
          <h3>📜 Odin's Call</h3>
          <p>Convert your runes into powerful discounts</p>
        </div>
      </div>

      <!-- 8. Dock Barrels - SHOP (Merchant's Wares) -->
      <div 
        class="nav-element dock-barrels"
        @click="navigateTo('shop')"
        @mouseenter="showTooltip('shop')"
        @mouseleave="hideTooltip"
      >
        <img src="/island-assets/overlays/trade-docks.png" alt="Dock Barrels">
        <div v-if="activeTooltip === 'shop'" class="nav-tooltip">
          <h3>🛢️ Merchant's Wares</h3>
          <p>Browse authentic Viking gear and legendary artifacts</p>
        </div>
      </div>

      <!-- Back to Shop Button -->
      <router-link 
        :to="{ name: 'shop' }"
        class="back-button"
      >
        ← Back to Shop
      </router-link>

      <!-- Cart Icon -->
      <router-link 
        :to="{ name: 'cart' }"
        class="floating-cart"
        v-if="cartCount > 0"
      >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <span class="cart-badge">{{ cartCount }}</span>
      </router-link>
    </div>

    <!-- Mobile Navigation -->
    <div class="mobile-nav">
      <h2 class="text-2xl font-bold text-white mb-6 text-center">Choose Your Path</h2>
      <div class="mobile-nav-grid">
        <button @click="navigateTo('games')" class="mobile-nav-card">
          <span class="icon">🚢</span>
          <span class="title">Journey to Isle</span>
        </button>
        <button @click="navigateTo('quests')" class="mobile-nav-card">
          <span class="icon">🏛️</span>
          <span class="title">Great Hall</span>
        </button>
        <button @click="navigateTo('runes')" class="mobile-nav-card">
          <span class="icon">⚡</span>
          <span class="title">Seer's Circle</span>
        </button>
        <button @click="navigateTo('profile')" class="mobile-nav-card">
          <span class="icon">⛺</span>
          <span class="title">Your Saga</span>
        </button>
        <button @click="navigateTo('contact')" class="mobile-nav-card">
          <span class="icon">🔥</span>
          <span class="title">Raven's Message</span>
        </button>
        <button @click="navigateTo('treasury')" class="mobile-nav-card">
          <span class="icon">💰</span>
          <span class="title">Your Treasury</span>
        </button>
        <button @click="navigateTo('rune-exchange')" class="mobile-nav-card">
          <span class="icon">📜</span>
          <span class="title">Odin's Call</span>
        </button>
        <button @click="navigateTo('shop')" class="mobile-nav-card">
          <span class="icon">🛢️</span>
          <span class="title">Merchant's Wares</span>
        </button>
      </div>
    </div>

    <!-- Instructions -->
    <div v-if="showInstructions" class="instructions-overlay">
      <div class="instructions-box">
        <h2>Welcome to the Viking Settlement!</h2>
        <p>Click on different parts of the island to explore:</p>
        <ul>
          <li>🚢 Sail the longship to reach the 3D island</li>
          <li>🏛️ Visit the Great Hall for quests and glory</li>
          <li>⚡ Study runes at the Seer's Circle</li>
          <li>💰 Check your treasury for rewards and progress</li>
        </ul>
        <button @click="closeInstructions" class="close-instructions">
          Begin Your Journey
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'

const router = useRouter()
const store = useStore()

const activeTooltip = ref(null)
const showInstructions = ref(false)
const debugMode = ref(false)
const navbarHidden = ref(false)
const cartCount = computed(() => store.getters['cart/cartItemCount'])

onMounted(() => {
  // Show instructions on first visit
  const hasVisited = localStorage.getItem('island-hub-visited')
  if (!hasVisited) {
    showInstructions.value = true
    localStorage.setItem('island-hub-visited', 'true')
  }
  
  // Add debug mode toggle
  window.addEventListener('keydown', handleDebugToggle)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleDebugToggle)
})

function showTooltip(element, event) {
  activeTooltip.value = element
  
  // Wait for tooltip to render
  setTimeout(() => {
    const tooltipEl = document.querySelector('.nav-tooltip')
    if (tooltipEl) {
      const rect = tooltipEl.getBoundingClientRect()
      
      // If tooltip goes off top of screen
      if (rect.top < 0) {
        tooltipEl.style.top = '110%'
        tooltipEl.style.bottom = 'auto'
      }
      
      // If tooltip goes off bottom of screen
      if (rect.bottom > window.innerHeight) {
        tooltipEl.style.bottom = '110%'
        tooltipEl.style.top = 'auto'
      }
    }
  }, 0)
}

function handleDebugToggle(event) {
  if (event.key === 'd' || event.key === 'D') {
    debugMode.value = !debugMode.value
    const hubContainer = document.querySelector('.island-hub-container')
    if (hubContainer) {
      if (debugMode.value) {
        hubContainer.classList.add('debug-mode')
        console.log('🐛 Debug mode: ON - Red circles show clickable areas')
      } else {
        hubContainer.classList.remove('debug-mode')
        console.log('✅ Debug mode: OFF')
      }
    }
  }
}

function navigateTo(route) {
  router.push({ name: route })
}

function hideTooltip() {
  activeTooltip.value = null
}

function closeInstructions() {
  showInstructions.value = false
}
</script>

<style scoped>
.island-hub-container {
  min-height: 100vh;
  background: linear-gradient(to bottom, #0c1445 0%, #1a1a2e 100%);
  overflow-x: hidden;
  position: relative;
}

.island-wrapper {
  position: relative;
  width: 100%;
  max-width: 1920px;
  margin: 0 auto;
}

.base-image {
  width: 100%;
  height: auto;
  display: block;
}

/* ✅ HIDE NAVBAR COMPLETELY ON THIS PAGE */
:deep(nav) {
  display: none !important;
}

/* Back Button (Replaces navbar) */
.back-button {
  position: fixed;
  top: 20px;
  left: 20px;
  background: rgba(251, 191, 36, 0.2);
  backdrop-filter: blur(10px);
  border: 2px solid #fbbf24;
  border-radius: 8px;
  padding: 12px 24px;
  color: #fbbf24;
  font-weight: bold;
  z-index: 100;
  transition: all 0.3s ease;
  font-size: 14px;
}

.back-button:hover {
  background: rgba(251, 191, 36, 0.3);
  transform: translateX(-4px);
}

/* Navigation Elements - Glowing animated circles */
.nav-element {
  position: absolute;
  cursor: pointer;
  transition: all 0.4s ease;
  z-index: 10;
  border: none;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Glowing animated circle indicator */
.nav-element::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 60px;
  height: 60px;
  border-radius: 50%;
  border: 3px solid rgba(251, 191, 36, 0.4);
  background: radial-gradient(circle, rgba(251, 191, 36, 0.2) 0%, transparent 70%);
  animation: pulseGlow 2s ease-in-out infinite;
  pointer-events: none;
  transition: all 0.3s ease;
}

/* Pulsing glow animation */
@keyframes pulseGlow {
  0%, 100% {
    transform: translate(-50%, -50%) scale(1);
    opacity: 0.6;
    box-shadow: 
      0 0 20px rgba(251, 191, 36, 0.3),
      0 0 40px rgba(251, 191, 36, 0.2),
      inset 0 0 20px rgba(251, 191, 36, 0.1);
  }
  50% {
    transform: translate(-50%, -50%) scale(1.15);
    opacity: 1;
    box-shadow: 
      0 0 30px rgba(251, 191, 36, 0.5),
      0 0 60px rgba(251, 191, 36, 0.3),
      inset 0 0 30px rgba(251, 191, 36, 0.2);
  }
}

/* On hover - expand and intensify the glow */
.nav-element:hover::before {
  width: 100px;
  height: 100px;
  border-color: rgba(251, 191, 36, 0.8);
  background: radial-gradient(circle, rgba(251, 191, 36, 0.3) 0%, transparent 70%);
  animation: pulseGlowHover 1s ease-in-out infinite;
  box-shadow: 
    0 0 40px rgba(251, 191, 36, 0.6),
    0 0 80px rgba(251, 191, 36, 0.4),
    inset 0 0 40px rgba(251, 191, 36, 0.3);
}

/* Faster pulse on hover */
@keyframes pulseGlowHover {
  0%, 100% {
    transform: translate(-50%, -50%) scale(1);
    opacity: 0.8;
  }
  50% {
    transform: translate(-50%, -50%) scale(1.1);
    opacity: 1;
  }
}

/* Center dot indicator */
.nav-element::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(251, 191, 36, 0.6);
  box-shadow: 
    0 0 10px rgba(251, 191, 36, 0.8),
    0 0 20px rgba(251, 191, 36, 0.4);
  pointer-events: none;
  transition: all 0.3s ease;
  z-index: 1;
}

/* Expand center dot on hover */
.nav-element:hover::after {
  width: 20px;
  height: 20px;
  background: rgba(251, 191, 36, 0.9);
  box-shadow: 
    0 0 20px rgba(251, 191, 36, 1),
    0 0 40px rgba(251, 191, 36, 0.6);
}

/* Overlay images - invisible by default */
.nav-element img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.3s ease;
  position: relative;
  z-index: 2;
}

/* Show overlay image on hover */
.nav-element:hover img {
  opacity: 1;
}

/* On hover - add extra visual feedback */
.nav-element:hover {
  transform: scale(1.08);
  z-index: 9998;
}

/* Specific Positions - ADJUST THESE based on your actual image */
.longship {
  bottom: 8.5%;
  left: 7.5%;
  width: 35%;
  height: 45%;
  opacity: 60%;
}

.great-hall {
  top: 9%;
  left: 4%;
  width: 50%;
  height: 35%;
  opacity: 60%;
}

.rune-circle {
  top: 28%;
  right: 35%;
  width: 28%;
  height: 28%;
  opacity: 60%;
}

.chieftain-tent {
  top: 35%;
  left: 15%;
  width: 12%;
  height: 18%;
  opacity: 60%;
}

.beacon-tower {
  top: 14%;
  left: 49%;
  width: 20%;
  height: 25%;
  opacity: 60%;
}

.treasure-chest {
  bottom: 27%;
  right: 3%;
  width: 25%;
  height: 25%;
  opacity: 60%;
}

.quest-board {
  top: 40%;
  right: 10%;
  width: 10%;
  height: 15%;
  opacity: 60%;
}

.dock-barrels {
  bottom: 27%;
  right: 35%;
  width: 25%;
  height: 25%;
  opacity: 60%;
}

/* Tooltips */
.nav-tooltip {
  position: absolute;
  top: 110%;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.95);
  border: 2px solid #fbbf24;
  border-radius: 8px;
  padding: 12px 16px;
  min-width: 200px;
  text-align: center;
  pointer-events: none;
  animation: tooltipFade 0.3s ease;
  z-index: 9999;
  white-space: nowrap;
}

.nav-tooltip h3 {
  color: #fbbf24;
  font-size: 16px;
  font-weight: bold;
  margin: 0 0 6px 0;
}

.nav-tooltip p {
  color: #d1d5db;
  font-size: 13px;
  margin: 0;
}

@keyframes tooltipFade {
  from {
    opacity: 0;
    transform: translateX(-50%) translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
  }
}

/* Floating Cart */
.floating-cart {
  position: fixed;
  top: 20px;
  right: 20px;
  background: rgba(251, 191, 36, 0.2);
  backdrop-filter: blur(10px);
  border: 2px solid #fbbf24;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fbbf24;
  z-index: 100;
  transition: all 0.3s ease;
}

.floating-cart:hover {
  background: rgba(251, 191, 36, 0.3);
  transform: scale(1.1);
}

.cart-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #ef4444;
  color: white;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: bold;
}

/* Mobile Navigation */
.mobile-nav {
  display: none;
  padding: 40px 20px;
}

.mobile-nav-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
  max-width: 600px;
  margin: 0 auto;
}

.mobile-nav-card {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  border: 2px solid rgba(251, 191, 36, 0.3);
  border-radius: 12px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.mobile-nav-card:hover {
  background: rgba(251, 191, 36, 0.1);
  border-color: #fbbf24;
  transform: translateY(-4px);
}

.mobile-nav-card .icon {
  font-size: 32px;
}

.mobile-nav-card .title {
  color: white;
  font-weight: 600;
  text-align: center;
}

/* Instructions Overlay */
.instructions-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.9);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10000;
  animation: fadeIn 0.5s ease;
}

.instructions-box {
  background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
  border: 3px solid #fbbf24;
  border-radius: 16px;
  padding: 40px;
  max-width: 500px;
  color: white;
}

.instructions-box h2 {
  color: #fbbf24;
  font-size: 24px;
  margin-bottom: 16px;
}

.instructions-box ul {
  margin: 20px 0;
  padding-left: 20px;
}

.instructions-box li {
  margin: 8px 0;
  font-size: 16px;
}

.close-instructions {
  background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);
  color: #1f2937;
  border: none;
  padding: 12px 32px;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  width: 100%;
  margin-top: 20px;
  transition: all 0.3s ease;
}

.close-instructions:hover {
  transform: scale(1.05);
}

/* Debug Mode - Press 'D' key to toggle */
.debug-mode .nav-element::before {
  border-color: red !important;
  background: radial-gradient(circle, rgba(255, 0, 0, 0.3) 0%, transparent 70%) !important;
  animation: none !important;
  box-shadow: 
    0 0 20px rgba(255, 0, 0, 0.6),
    0 0 40px rgba(255, 0, 0, 0.4) !important;
}

.debug-mode .nav-element::after {
  background: rgba(255, 0, 0, 0.8) !important;
  box-shadow: 
    0 0 20px rgba(255, 0, 0, 0.8),
    0 0 40px rgba(255, 0, 0, 0.4) !important;
}

.debug-mode .nav-element img {
  opacity: 0.5 !important;
}

/* Responsive */
@media (max-width: 768px) {
  .island-wrapper {
    display: none;
  }

  .mobile-nav {
    display: block;
  }
  
  .back-button {
    top: 10px;
    left: 10px;
    padding: 8px 16px;
    font-size: 12px;
  }
}

/* Animations */
@keyframes fadeIn {
  from { 
    opacity: 0; 
  }
  to { 
    opacity: 1; 
  }
}
</style>