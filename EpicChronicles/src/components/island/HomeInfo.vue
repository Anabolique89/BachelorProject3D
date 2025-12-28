<template>
  <div v-if="currentStage === 1" class="text-xl text-center bg-black rounded-md py-2 px-6 text-white mx-5">
    <h1>
      Welcome viking fans! We are
      <span class="font-semibold mx-2 text-white">
        <br/> Epic Chronicles <br/>
      </span>
      Your source for authentic Viking gear and Norse mythology
    </h1>
  </div>

  <div v-else-if="currentStage === 2" class="text-xl leading-snug text-center bg-black rounded-md py-2 px-6 text-white mx-5">
    <h1 class="font-medium text-center m-2 mb-4">
      We offer historical Viking products <br /> filled with authentic Norse craftsmanship
    </h1>

    <router-link 
      :to="{ name: 'shop' }" 
      class="inline-block bg-yellow-400 hover:bg-yellow-500 rounded-md text-black font-bold mb-2 text-center p-2 transition-all"
    >
      Explore Our Collection
    </router-link>
  </div>

  <div v-else-if="currentStage === 3" class="text-xl text-center bg-black rounded-md py-2 px-2 text-white mx-4">
    <h1 class="font-medium text-center text-md p-2 mb-4">
      Find all things about Viking history, gear, <br/> tokens, quests and much more!
    </h1>
    
    <router-link
      :to="{ name: 'shop' }"
      class="inline-block bg-yellow-400 hover:bg-yellow-500 rounded-md text-black font-bold p-1 mb-2 transition-all"
    >
      Shop Your Favourite Gear
    </router-link>
  </div>

  <div v-else-if="currentStage === 4" class="text-xl leading-snug text-center bg-black rounded-md py-2 px-6 text-white mx-5">
    <h1 class="font-medium text-center p-2 mb-4">
      We are just a few keystrokes away <br/> so reach out and
    </h1>

    <router-link 
      :to="{ name: 'contact' }" 
      class="inline-block bg-yellow-400 hover:bg-yellow-500 rounded-md text-black font-bold p-1 mb-2 transition-all"
    >
      Ask Us Anything!
    </router-link>
  </div>
</template>

<script setup>
defineProps({
  currentStage: {
    type: Number,
    default: null
  }
})
</script>