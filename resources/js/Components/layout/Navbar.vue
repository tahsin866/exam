<template>
  <nav class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 fixed top-0 w-full z-50 shadow-lg border-b border-emerald-700/30"
       style="font-family: 'Merriweather', 'SolaimanLipi', sans-serif;">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-20">

        <!-- Logo and Brand -->
        <div class="flex items-center">
          <div class="h-12 w-12 rounded-full bg-gradient-to-br from-emerald-700 to-emerald-900 flex items-center justify-center mr-3 shadow-lg border border-emerald-600/30 transform transition-all duration-300 hover:scale-105">
            <img src="/public/images/find.png" alt="Logo" class="h-8 w-8"/>
          </div>
          <div class="flex flex-col">
            <span class="text-white text-xs tracking-wide opacity-80">বাংলাদেশ কওমি মাদরাসা শিক্ষাবোর্ড</span>
            <span class="text-emerald-300 text-2xl font-bold tracking-wider">WEMS</span>
          </div>
        </div>

        <!-- Navigation Links -->
        <div class="hidden lg:flex items-center space-x-1">
          <a href="#" class="text-white text-base hover:bg-emerald-800/80 hover:text-emerald-300 px-4 py-3 rounded-md transition-all duration-200 flex items-center gap-1.5 font-medium relative group">
            <span class="absolute inset-x-0 bottom-0 h-0.5 bg-emerald-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
            <i class="pi pi-home-fill text-emerald-300/90"></i>
            বেফাক ইয়ারপি
          </a>
          <a href="#" class="text-white text-base hover:bg-emerald-800/80 hover:text-emerald-300 px-4 py-3 rounded-md transition-all duration-200 flex items-center gap-1.5 font-medium relative group">
            <span class="absolute inset-x-0 bottom-0 h-0.5 bg-emerald-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
            <i class="pi pi-building text-emerald-300/90"></i>
            মাদরাসা পেনেল
          </a>
          <a href="#" class="text-white text-base hover:bg-emerald-800/80 hover:text-emerald-300 px-4 py-3 rounded-md transition-all duration-200 flex items-center gap-1.5 font-medium relative group">
            <span class="absolute inset-x-0 bottom-0 h-0.5 bg-emerald-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
            <i class="pi pi-users text-emerald-300/90"></i>
            শিক্ষক পেনেল
          </a>
          <a href="#" class="text-white text-base hover:bg-emerald-800/80 hover:text-emerald-300 px-4 py-3 rounded-md transition-all duration-200 flex items-center gap-1.5 font-medium relative group">
            <span class="absolute inset-x-0 bottom-0 h-0.5 bg-emerald-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
            <i class="pi pi-user text-emerald-300/90"></i>
            ছাত্র-ছাত্রী পেনেল
          </a>
          <a href="#" class="text-white text-base hover:bg-emerald-800/80 hover:text-emerald-300 px-4 py-3 rounded-md transition-all duration-200 flex items-center gap-1.5 font-medium relative group">
            <span class="absolute inset-x-0 bottom-0 h-0.5 bg-emerald-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
            <i class="pi pi-globe text-emerald-300/90"></i>
            ওয়েব সাইট
          </a>

          <!-- Auth Links -->
          <div class="ml-6 flex items-center">
            <template v-if="!$page.props.auth.user">
              <Link :href="route('login')"
                    class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white hover:from-emerald-500 hover:to-emerald-600 px-6 py-2.5 rounded-md transition-all duration-200 font-medium shadow-lg hover:shadow-emerald-700/50 flex items-center gap-2">
                <i class="pi pi-sign-in"></i>
                Login
              </Link>
            </template>
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <div class="lg:hidden">
          <button @click="mobileMenuOpen = !mobileMenuOpen"
                  class="text-white hover:text-emerald-300 focus:outline-none transition-colors duration-200 bg-emerald-800/50 p-2 rounded-lg">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div v-show="mobileMenuOpen"
         class="lg:hidden bg-emerald-900/95 border-t border-emerald-800 backdrop-blur-sm"
         v-motion
         :initial="{ opacity: 0, y: -10 }"
         :enter="{ opacity: 1, y: 0, transition: { duration: 300 } }">
      <div class="px-4 py-5 space-y-3">
        <a href="#" class="text-white flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-800 transition-colors duration-200 font-medium">
          <i class="pi pi-home-fill text-emerald-300"></i>
          বেফাক ইয়ারপি
        </a>
        <a href="#" class="text-white flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-800 transition-colors duration-200 font-medium">
          <i class="pi pi-building text-emerald-300"></i>
          মাদরাসা পেনেল
        </a>
        <a href="#" class="text-white flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-800 transition-colors duration-200 font-medium">
          <i class="pi pi-users text-emerald-300"></i>
          শিক্ষক পেনেল
        </a>
        <a href="#" class="text-white flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-800 transition-colors duration-200 font-medium">
          <i class="pi pi-user text-emerald-300"></i>
          ছাত্র-ছাত্রী পেনেল
        </a>
        <a href="#" class="text-white flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-800 transition-colors duration-200 font-medium">
          <i class="pi pi-globe text-emerald-300"></i>
          ওয়েব সাইট
        </a>

        <div class="pt-3 mt-3 border-t border-emerald-800">
          <Link :href="route('login')"
                class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 text-white hover:from-emerald-500 hover:to-emerald-600 px-6 py-3 rounded-lg transition-all duration-200 font-medium shadow-md flex items-center justify-center gap-2">
            <i class="pi pi-sign-in"></i>
            Login
          </Link>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const mobileMenuOpen = ref(false)
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
