<template>
  <section class="w-full h-screen relative overflow-hidden">
   

    <!-- Desktop Home Info -->
    <div class="absolute top-16 sm:top-20 right-0 left-0 z-10 flex items-center justify-center px-4">
      <HomeInfo v-if="currentStage" :current-stage="currentStage" />
    </div>

    <!-- Canvas Container -->
    <div 
      ref="containerRef" 
      class="w-full h-screen bg-transparent"
      :class="isRotating ? 'cursor-grabbing' : 'cursor-grab'"
    ></div>

    <!-- All popups rendered OUTSIDE the Canvas -->
    <WelcomePopup :show="showWelcomePopup" @close="closeWelcomePopup" />
    <IslandMerchantQuest 
      :show="showIslandPopup"
      :is-replay="questsStatus.island_merchant_completed"
      @close="closeIslandPopup"
      @quest-complete="handleQuestComplete"
    />
 <VikingBoatQuest 
      :show="showBoatPopup" 
      :is-replay="questsStatus.ragnars_journey_completed"
      @close="closeBoatPopup"
      @quest-complete="handleQuestComplete"
    />
 <!-- Quest Status Indicators -->
    <div class="fixed top-20 right-4 bg-slate-800/90 backdrop-blur rounded-lg p-4 text-white text-sm space-y-2 border-2 border-amber-600/50 z-40 lg:block hidden">
      <div class="font-bold text-amber-400 text-center">⚔️ Quests ⚔️</div>
      <div class="flex items-center gap-2">
        <span v-if="questsStatus.ragnars_journey_completed" class="text-green-400 text-lg">✅</span>
        <span v-else class="text-amber-400 text-lg animate-pulse">🔥</span>
        <span :class="questsStatus.ragnars_journey_completed ? 'text-gray-300 line-through' : 'text-amber-300 font-semibold'">
          Ragnar's Journey
        </span>
      </div>
      <div class="flex items-center gap-2">
        <span v-if="questsStatus.island_merchant_completed" class="text-green-400 text-lg">✅</span>
        <span v-else class="text-emerald-400 text-lg animate-pulse">🌟</span>
        <span :class="questsStatus.island_merchant_completed ? 'text-gray-300 line-through' : 'text-emerald-300 font-semibold'">
          Island Merchant
        </span>
      </div>
      <div class="text-xs text-gray-400 text-center pt-2 border-t border-gray-600">
        Click to replay
      </div>
    </div>

    <!-- Floating Shop Button for mobile -->
    <router-link
      v-if="isMobile"
      :to="{ name: 'shop' }"
      class="fixed bottom-[25%] right-4 z-40 bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-500 hover:to-orange-500 text-white px-4 py-3 rounded-full text-sm sm:text-4xl font-bold shadow-2xl transform hover:scale-110 active:scale-95 transition-all border-2 border-yellow-400 animate-bounce-slow lg:hidden sm:p-6"
    >
      <span class="flex items-center gap-2">
        <span class="text-lg sm:text-4xl">🛒</span>
        <span>SHOP</span>
      </span>
    </router-link>

    <!-- Enhanced Desktop Controls -->
    <div class="absolute sm:bottom-[15%] lg:bottom-[20%] left-4 z-10 max-w-md opacity-50">
      <div class="bg-black bg-opacity-85 text-white p-5 rounded-xl lg:text-sm sm:text-xl backdrop-blur-sm border border-amber-600">
        <ul class="lg:text-xs sm:text-2xl space-y-2 opacity-100 p-1">
          <li class="flex items-center gap-2">
            <span>🖱️</span>
            Drag to navigate the seas
          </li>
          <li class="flex items-center gap-2">
            <span>🏝️</span>
            Click island for trader tales
          </li>
          <li class="flex items-center gap-2">
            <span>🚢</span>
            Click boat for Ragnar's story
          </li>
          <li class="flex items-center gap-2 text-amber-400 font-semibold">
            <span>⚔️</span>
            Visit shop for legendary items!
          </li>
        </ul>
      </div>
    </div>

    <!-- Enhanced Desktop Action Buttons -->
    <div :class="`absolute sm:bottom-[15%] lg:bottom-[20%] right-4 flex flex-col gap-3 opacity-70 ${!isMobile ? 'hidden' : 'block'}`">
      <router-link
        :to="{ name: 'shop' }"
        class="bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-500 hover:to-orange-500 text-white px-6 py-4 rounded-xl lg:text-sm font-bold shadow-lg transform hover:scale-105 active:scale-95 transition-all flex items-center gap-2 border-2 border-yellow-400 sm:text-4xl p-6"
      >
        <span >⚔️</span>
        VIKING SHOP
        <span>💰</span>
      </router-link>
      <button 
        class="bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white px-4 py-3 rounded-xl lg:text-sm font-semibold shadow-lg transform hover:scale-105 active:scale-95 transition-all flex items-center gap-2 sm:text-4xl p-6"
        @click="handleIslandClick"
      >
        <span>🏝️</span>
        Island
      </button>
      <button 
        class="bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white px-4 py-3 rounded-xl lg:text-sm font-semibold shadow-lg transform hover:scale-105 active:scale-95 transition-all flex items-center gap-2 sm:text-4xl p-6"
        @click="handleBoatClick"
      >
        <span>🚢</span>
        Boat
      </button>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import * as THREE from 'three'
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader'
import { DRACOLoader } from 'three/examples/jsm/loaders/DRACOLoader'
import HomeInfo from '../components/island/HomeInfo.vue'
import WelcomePopup from '../components/popups/WelcomePopup.vue'
import VikingBoatQuest from '../components/popups/VikingBoatQuest.vue'
import IslandMerchantQuest from '../components/popups/IslandMerchantQuest.vue'
import axiosClient from '../axios'

// State
const containerRef = ref(null)
const isRotating = ref(false)
const currentStage = ref(null)
const showIslandPopup = ref(false)
const showBoatPopup = ref(false)
const showWelcomePopup = ref(false)
const isMobile = ref(false)

// Quest status state
const questsStatus = ref({
  ragnars_journey_completed: false,
  island_merchant_completed: false
})


let scene, camera, renderer, island, boat, sky, bird
let lastX = 0
let rotationSpeed = 0
const dampingFactor = 0.95

// Click detection 
let isDragging = false
const dragThreshold = 5
let startPosition = { x: 0, y: 0 }
let hasMovedEnough = false

// Raycaster
let raycaster, mouse
let clickableIslandParts = []
let clickableBoatParts = []

// Initialize Three.js scene
const initThree = () => {
  scene = new THREE.Scene()
  scene.background = new THREE.Color(0x87CEEB)

  const aspect = window.innerWidth / window.innerHeight
  camera = new THREE.PerspectiveCamera(75, aspect, 0.1, 1000)
  camera.position.set(0, 10, 10)

  renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true })
  renderer.setSize(window.innerWidth, window.innerHeight)
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2))
  containerRef.value.appendChild(renderer.domElement)

  // Lights (exactly like React)
  const directionalLight = new THREE.DirectionalLight(0xffffff, 2)
  directionalLight.position.set(1, 1, 1)
  scene.add(directionalLight)

  const ambientLight = new THREE.AmbientLight(0xffffff, 0.5)
  scene.add(ambientLight)

  const hemisphereLight = new THREE.HemisphereLight(0xb1e1ff, 0x000000, 1)
  scene.add(hemisphereLight)

  // Raycaster
  raycaster = new THREE.Raycaster()
  mouse = new THREE.Vector2()

  loadModels()

  // Events
  renderer.domElement.addEventListener('mousedown', handlePointerDown)
  renderer.domElement.addEventListener('mouseup', handlePointerUp)
  renderer.domElement.addEventListener('mousemove', handlePointerMove)
  renderer.domElement.addEventListener('click', handleCanvasClick)
  renderer.domElement.addEventListener('touchstart', handlePointerDown, { passive: false })
  renderer.domElement.addEventListener('touchend', handlePointerUp)
  renderer.domElement.addEventListener('touchmove', handlePointerMove, { passive: false })
  document.addEventListener('keydown', handleKeyDown)
  document.addEventListener('keyup', handleKeyUp)

  animate()
}

// Load models
const loadModels = () => {
  const loader = new GLTFLoader()
  const dracoLoader = new DRACOLoader()
  dracoLoader.setDecoderPath('https://www.gstatic.com/draco/versioned/decoders/1.5.6/')
  loader.setDRACOLoader(dracoLoader)

  // Adjust for screen size (from React)
  const adjustIslandForScreenSize = () => {
    const actualWidth = window.innerWidth || document.documentElement.clientWidth
    const isMobileDevice = actualWidth < 768 || /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
    
    const screenScale = isMobileDevice ? [0.4, 0.4, 0.4] : [0.32, 0.32, 0.32]
    const screenPosition = [0, 2, -35]
    const rotation = [0.1, 0.2, 0]
    
    return { screenScale, screenPosition, rotation }
  }

const adjustBoatForScreenSize = () => {
  const screenScale = [0.1, 0.1, 0.1]  // Bigger!
  const screenPosition =[ 0, 4.5, 0] 
  const rotation = [0, Math.PI/2, 0]
  
  return { screenScale, screenPosition, rotation }
}

  const islandConfig = adjustIslandForScreenSize()
  const boatConfig = adjustBoatForScreenSize()

  // Island
  loader.load('/models/fantasy_island.glb', (gltf) => {
    island = gltf.scene
    island.position.set(...islandConfig.screenPosition)
    island.scale.set(...islandConfig.screenScale)
    island.rotation.set(...islandConfig.rotation)
    
    // Make tree bark, leaves, and pillars clickable (from your React code)
    island.traverse((child) => {
      if (child.isMesh && child.material) {
        const materialName = child.material.name
        if (materialName === 'tree_bark' || 
            materialName === 'leaves' || 
            materialName === 'leaves.001' ||
            materialName === 'pillar') {
          clickableIslandParts.push(child)
          child.userData.clickable = 'island'
        }
      }
    })
    
    scene.add(island)
    console.log('Island loaded!', clickableIslandParts.length, 'clickable parts')
  })

 // Boat
loader.load('/models/gislinge_viking_boat_draco.glb', (gltf) => {
  boat = gltf.scene
  
  boat.position.set(0, 4.5, 0)
  boat.scale.set(0.01, 0.01, 0.01)
  boat.rotation.set(0, Math.PI / 2, 0)  
  
  // clickable parts 
  boat.traverse((child) => {
    if (child.isMesh) {
      
      if (child.name === 'Planking_Ship_Oak_Aged_0' ||
          child.name === 'Keel_Ship_Oak_Aged_0' ||
          child.name === 'Sail_Full_Sail_Full_0') {
        clickableBoatParts.push(child)
        child.userData.clickable = 'boat'
        console.log('Added clickable boat part:', child.name)
      }
    }
  })
  
  scene.add(boat)
  console.log('Boat loaded!', clickableBoatParts.length, 'clickable parts')
  console.log('Boat position:', boat.position)
  console.log('Boat rotation:', boat.rotation)
})
  // Sky
  loader.load('/models/sky_draco.glb', (gltf) => {
    sky = gltf.scene
    scene.add(sky)
    console.log('✅ Sky loaded!')
  })

  // Bird
// loader.load('/models/bird_draco.glb', (gltf) => {
//   bird = gltf.scene
//   bird.position.set(-20, 60, )
//   bird.scale.set(0.005, 0.005, 0.005)
  
//   // Setup animation mixer for the bird
//   if (gltf.animations && gltf.animations.length > 0) {
//     window.birdMixer = new THREE.AnimationMixer(bird)
//     const action = window.birdMixer.clipAction(gltf.animations[0]) // "Take 001"
//     action.play()
//     console.log('✅ Bird animation playing:', gltf.animations[0].name)
//   }
  
//   scene.add(bird)
//   console.log('✅ Bird loaded with', gltf.animations.length, 'animations')
// })
}

// Pointer handlers 
const handlePointerDown = (e) => {
  e.preventDefault()
  
  const clientX = e.clientX || (e.touches?.[0]?.clientX) || 0
  const clientY = e.clientY || (e.touches?.[0]?.clientY) || 0
  
  lastX = clientX
  startPosition = { x: clientX, y: clientY }
  isDragging = false
  hasMovedEnough = false
  isRotating.value = true
  
  console.log('👇 Pointer down')
}

const handlePointerUp = () => {
  console.log('👆 Pointer up, hasMovedEnough:', hasMovedEnough)
  isRotating.value = false
  
  setTimeout(() => {
    isDragging = false
    hasMovedEnough = false
  }, 100)
}

const handlePointerMove = (e) => {
  if (!isRotating.value || !island) return
  
  const clientX = e.clientX || (e.touches?.[0]?.clientX) || 0
  const clientY = e.clientY || (e.touches?.[0]?.clientY) || 0
  
  // Calculate drag distance
  const dragDistance = Math.sqrt(
    Math.pow(clientX - startPosition.x, 2) +
    Math.pow(clientY - startPosition.y, 2)
  )
  
  if (dragDistance > dragThreshold) {
    isDragging = true
    hasMovedEnough = true
    console.log('🔄 Started dragging')
  }
  
const delta = (clientX - lastX) / window.innerWidth
island.rotation.y += delta * 0.5 * Math.PI
  lastX = clientX
  rotationSpeed = delta * 0.5 * Math.PI
  
  updateStage()
}

// Click handler
const handleCanvasClick = (e) => {
  console.log('🎯 Click detected!')
  console.log('isDragging:', isDragging)
  console.log('hasMovedEnough:', hasMovedEnough)
  
  if (!hasMovedEnough && !isDragging) {
    console.log('✅ Valid click - checking raycasting')
    
    // Calculate mouse position
    const rect = renderer.domElement.getBoundingClientRect()
    mouse.x = ((e.clientX - rect.left) / rect.width) * 2 - 1
    mouse.y = -((e.clientY - rect.top) / rect.height) * 2 + 1
    
    raycaster.setFromCamera(mouse, camera)
    
    // Check island
    const islandIntersects = raycaster.intersectObjects(clickableIslandParts, false)
    if (islandIntersects.length > 0) {
      console.log('✅✅✅ ISLAND CLICKED!')
      handleIslandClick()
      return
    }
    
    // Check boat
    const boatIntersects = raycaster.intersectObjects(clickableBoatParts, false)
    if (boatIntersects.length > 0) {
      console.log('✅✅✅ BOAT CLICKED!')
      handleBoatClick()
      return
    }
  } else {
    console.log('❌ Click blocked - was dragging')
  }
}

// Keyboard handlers (from your React code)
const handleKeyDown = (e) => {
  if (!island) return
  
  if (e.key === 'ArrowLeft') {
    if (!isRotating.value) isRotating.value = true
    island.rotation.y += 0.01 * Math.PI
    rotationSpeed = 0.0125
  } else if (e.key === 'ArrowRight') {
    if (!isRotating.value) isRotating.value = true
    island.rotation.y -= 0.01 * Math.PI
    rotationSpeed = -0.0125
  }
}

const handleKeyUp = (e) => {
  if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
    isRotating.value = false
  }
}

// Update stage (from your React useFrame)
const updateStage = () => {
  if (!island) return
  
  const rotation = island.rotation.y
  const normalizedRotation = ((rotation % (2 * Math.PI)) + 2 * Math.PI) % (2 * Math.PI)
  
  if (normalizedRotation >= 5.45 && normalizedRotation <= 5.85) {
    currentStage.value = 4
  } else if (normalizedRotation >= 0.85 && normalizedRotation <= 1.3) {
    currentStage.value = 3
  } else if (normalizedRotation >= 2.4 && normalizedRotation <= 2.6) {
    currentStage.value = 2
  } else if (normalizedRotation >= 4.25 && normalizedRotation <= 4.75) {
    currentStage.value = 1
  } else {
    currentStage.value = null
  }
}

// Animation loop (from your React useFrame)
const animate = () => {
  requestAnimationFrame(animate)

  if (!isRotating.value && island) {
    rotationSpeed *= dampingFactor
    if (Math.abs(rotationSpeed) < 0.001) {
      rotationSpeed = 0
    }
    island.rotation.y += rotationSpeed
    updateStage()
  }

  if (sky && isRotating.value) {
    sky.rotation.y += 0.001
  }

// Bird animation - FULL VERSION
// if (bird) {
//   const clock = performance.now() * 0.001 // Convert to seconds
  
//   // Update animation mixer (for wing flapping)
//   if (window.birdMixer) {
//     window.birdMixer.update(0.016) // ~60fps
//   }
  
//   // Sine wave motion for Y position
//   bird.position.y = Math.sin(clock) * 0.2 + 2
  
//   // Check if bird exited camera view
//   if (bird.position.x > camera.position.x + 10) {
//     // Change direction to backward and rotate 180 degrees
//     bird.rotation.y = Math.PI
//   } else if (bird.position.x < camera.position.x - 10) {
//     // Change direction to forward and reset rotation
//     bird.rotation.y = 0
//   }
  
//   // Update X and Z positions based on direction
//   if (bird.rotation.y === 0) {
//     // Moving forward
//     bird.position.x += 0.01
//     bird.position.z -= 0.01
//   } else {
//     // Moving backward
//     bird.position.x -= 0.01
//     bird.position.z += 0.01
//   }
// }

  renderer.render(scene, camera)
}

// Click handlers
const handleIslandClick = () => {
  console.log('🏝️ Island clicked! Completed:', questsStatus.value.island_merchant_completed)
  
  if (questsStatus.value.island_merchant_completed) {
    if (confirm('🏝️ You\'ve already completed the Island Merchant quest!\n\n🎭 Replay the mystical journey? (No additional rewards will be given)')) {
      showIslandPopup.value = true
    }
    return
  }
  
  showIslandPopup.value = true
}

const handleBoatClick = () => {
  console.log('🚢 Boat clicked! Completed:', questsStatus.value.ragnars_journey_completed)
  
  if (questsStatus.value.ragnars_journey_completed) {
    if (confirm('⚔️ You\'ve already completed Ragnar\'s Journey!\n\n🎭 Replay the epic story? (No additional rewards will be given)')) {
      showBoatPopup.value = true
    }
    return
  }
  
  showBoatPopup.value = true
}

const closeIslandPopup = () => {
  showIslandPopup.value = false
}

const closeBoatPopup = () => {
  showBoatPopup.value = false
}

const handleQuestComplete = async (data) => {
  console.log('✅ Quest completed:', data)
  
  // Refresh quest status
  await checkQuestStatus()
  
  // Optionally show a success toast or update user stats
}

const checkQuestStatus = async () => {
  try {
    const response = await axiosClient.get('/customer/check-popups')
    questsStatus.value = {
      ragnars_journey_completed: response.data.ragnars_journey_completed,
      island_merchant_completed: response.data.island_merchant_completed
    }
    console.log('📊 Quest status loaded:', questsStatus.value)
  } catch (error) {
    console.error('❌ Failed to check quest status:', error)
  }
}

// Mobile detection (from your React useEffect)
const checkMobile = () => {
  const isMobileWidth = window.innerWidth < 768
  const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0
  isMobile.value = isMobileWidth || isTouchDevice
}

// First visit check (from your React useEffect)
const checkFirstVisit = () => {
  const hasVisited = sessionStorage.getItem('hasVisitedViking')
  if (!hasVisited) {
    setTimeout(() => {
      showWelcomePopup.value = true
    }, 1500)
  }
}

const closeWelcomePopup = () => {
  showWelcomePopup.value = false
  sessionStorage.setItem('hasVisitedViking', 'true')
}

// Resize handler
const onWindowResize = () => {
  camera.aspect = window.innerWidth / window.innerHeight
  camera.updateProjectionMatrix()
  renderer.setSize(window.innerWidth, window.innerHeight)
}

// Lifecycle
onMounted(() => {
  initThree()
  checkMobile()
  checkFirstVisit()
   checkQuestStatus() 
  window.addEventListener('resize', onWindowResize)
  window.addEventListener('resize', checkMobile)
  window.addEventListener('orientationchange', checkMobile)
})

onUnmounted(() => {
  window.removeEventListener('resize', onWindowResize)
  window.removeEventListener('resize', checkMobile)
  window.removeEventListener('orientationchange', checkMobile)
  document.removeEventListener('keydown', handleKeyDown)
  document.removeEventListener('keyup', handleKeyUp)
  
  if (renderer && containerRef.value) {
    renderer.dispose()
    containerRef.value.removeChild(renderer.domElement)
  }
})
</script>
 <!-- Viewport styling -->
    <style>
    section {
  height: 100vh;
  overflow: hidden;
}

      :root {
        --vh: 100%;
      }

      @media (max-width: 768px) {
        :root {
          --vh: 100vh;
        }
      }

      .fullscreen {
        height: calc(var(--vh, 1vh) * 100);
        width: 100%;
        overflow: hidden;
      }

      @media (max-width: 768px) {
        .mobile-text {
          font-size: clamp(0.75rem, 3vw, 1rem);
        }
        .mobile-button {
          min-height: 66px;
          min-width: 66px;
        }
        .mobile-padding {
          padding: 1rem;
        }
      }

      @keyframes bounce-slow {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
      }

      .animate-bounce-slow {
        animation: bounce-slow 3s infinite;
      }

      @keyframes pulse-once {
        0% { transform: scale(0.95); opacity: 0; }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); opacity: 1; }
      }

      .animate-pulse-once {
        animation: pulse-once 0.6s ease-out;
      }
    </style>