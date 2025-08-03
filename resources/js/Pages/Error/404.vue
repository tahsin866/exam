<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full text-center">
      <div class="mb-8">
        <h1 class="text-9xl font-bold text-gray-300">404</h1>
      </div>
      
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
          পেজ খুঁজে পাওয়া যায়নি
        </h2>
        <p class="text-gray-600 mb-6">
          আপনি যে পেজটি খুঁজছেন সেটি বিদ্যমান নেই অথবা আপনার এই পেজে প্রবেশের অনুমতি নেই।
        </p>
      </div>

      <div class="space-y-4">
        <button
          @click="goToDashboard"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200"
        >
          ড্যাশবোর্ডে ফিরে যান
        </button>
        
        <button
          @click="goToLogin"
          class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200"
        >
          লগইন পেজে যান
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const auth = computed(() => page.props.auth);

const goToDashboard = () => {
  if (auth.value.user) {
    if (auth.value.isAdmin) {
      router.visit('/admin/dashboard');
    } else if (auth.value.isMadrasa) {
      router.visit('/dashboard');
    } else {
      router.visit('/login');
    }
  } else {
    router.visit('/login');
  }
};

const goToLogin = () => {
  router.visit('/login');
};
</script>
