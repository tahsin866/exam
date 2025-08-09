<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

import dashboard_card from './dashboard-component/dashboard_card.vue';
import Notice_bord_and_upcomming_exam from './dashboard-component/notice_bord_and_upcomming_exam.vue';
import Registrasion_update_dashboard from './dashboard-component/registrasion_update_dashboard.vue';

// Recent activities (expand as needed)
const activities = ref([
  {
    id: 1,
    type: 'exam_created',
    description: 'New exam schedule published for Computer Science',
    time: '2 hours ago',
    icon: '📝'
  },
]);

const selectedDaura = ref('1446');
const madrasaCode = ref('');

const search = () => {
  // Perform search logic here using selectedDaura and madrasaCode
  console.log('Searching for:', selectedDaura.value, madrasaCode.value);
  // You would typically make an API call or filter data here.
};
</script>

<template>
  <Head title="WEMS" />
  <AuthenticatedLayout>
    <div class="py-8 min-h-screen bg-gray-50">
      <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6">
          <h1 class="text-2xl font-bold text-gray-900">ড্যাশবোর্ড</h1>
          <p class="mt-1 text-sm text-gray-600">
            {{ new Date('2025-08-07 04:29:07').toLocaleDateString('bn-BD', {year: 'numeric', month: 'long', day: 'numeric'}) }} • tahsin866
          </p>
        </div>

        <!-- Main Search/Filter Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
          <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <h2 class="text-lg font-medium text-gray-900">পরীক্ষা অনুসন্ধান</h2>
            </div>
          </div>
          <div class="p-6">
            <div class="flex flex-wrap items-center gap-4">
              <div class="flex-1 min-w-[220px]">
                <label for="exam-year" class="block text-sm font-medium text-gray-700 mb-1">পরীক্ষা সিলেক্ট করুন</label>
                <div class="relative">
                  <select
                    id="exam-year"
                    v-model="selectedDaura"
                    class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md"
                  >
                    <option value="1446">দাওরায়ে হাদীস ১৪৪৬ হিজরি</option>
                    <option value="1445">দাওরায়ে হাদীস ১৪৪৫ হিজরি</option>
                  </select>
                  <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                   
                  </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">৪৮ তম কেন্দ্রীয় পরীক্ষা- ১৪৪৬ হিজরি / ২০২৫ ঈসাব্দ</p>
              </div>
              <div class="flex-1 min-w-[220px]">
                <label for="madrasa-code" class="block text-sm font-medium text-gray-700 mb-1">মাদরাসা কোড</label>
                <div class="relative rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                  </div>
                  <input
                    id="madrasa-code"
                    type="text"
                    v-model="madrasaCode"
                    class="block w-full pl-10 pr-3 py-2.5 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="মাদরাসা কোড লিখুন"
                  >
                </div>
                <p class="mt-1 text-xs text-gray-500">আপনার অধিনস্ত ১৫ টি মাদরাসার উপাত্ত</p>
              </div>
              <div class="flex items-end">
                <button
                  @click="search"
                  type="button"
                  class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                  অনুসন্ধান করুন
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistics Cards -->
        <dashboard_card class="mb-6" />

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
          <!-- Notice Board and Upcoming Exam -->
          <div class="lg:col-span-2">
            <Notice_bord_and_upcomming_exam />
          </div>
          <!-- Registration Update Dashboard -->
          <div>
            <Registrasion_update_dashboard />
          </div>
        </div>

        <!-- Recent Activities -->
      
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* No custom styles needed, we're using consistent Tailwind classes */
</style>