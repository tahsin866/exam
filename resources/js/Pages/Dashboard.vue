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
    icon: '🆕'
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
    <div class="py-8 min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-emerald-100">
      <div class="mx-auto px-2 sm:px-6 lg:px-8">
        <!-- HERO BANNER -->
        <div class="relative overflow-hidden rounded-2xl shadow-xl border border-emerald-200 mb-8">
          <!-- Mosque SVG Decoration -->
          <div class="absolute right-0 top-0 bottom-0 w-1/3 h-full opacity-10 pointer-events-none z-0 flex items-end justify-end">
            <svg class="h-full w-full" viewBox="0 0 400 800" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMax slice">
              <ellipse cx="200" cy="500" rx="150" ry="80" fill="#059669" fill-opacity="0.13" />
              <ellipse cx="200" cy="520" rx="110" ry="60" fill="#059669" fill-opacity="0.18" />
              <rect x="80" y="350" width="18" height="160" rx="8" fill="#059669" fill-opacity="0.17"/>
              <rect x="76" y="340" width="26" height="30" rx="8" fill="#059669" fill-opacity="0.12"/>
              <rect x="302" y="350" width="18" height="160" rx="8" fill="#059669" fill-opacity="0.17"/>
              <rect x="298" y="340" width="26" height="30" rx="8" fill="#059669" fill-opacity="0.12"/>
              <rect x="160" y="530" width="80" height="70" rx="20" fill="#059669" fill-opacity="0.13"/>
              <path d="M200 600 Q220 580 220 550 Q220 540 200 540 Q180 540 180 550 Q180 580 200 600 Z" fill="#059669" fill-opacity="0.23"/>
              <path d="M200 440 a30 30 0 1 0 0.01 0" fill="none" stroke="#059669" stroke-opacity="0.45" stroke-width="5"/>
              <circle cx="215" cy="435" r="8" fill="#059669" fill-opacity="0.25"/>
              <ellipse cx="180" cy="570" rx="6" ry="10" fill="#059669" fill-opacity="0.18"/>
              <ellipse cx="220" cy="570" rx="6" ry="10" fill="#059669" fill-opacity="0.18"/>
            </svg>
          </div>
          <!-- Top colored gradient -->
          <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-700 via-emerald-500 to-teal-400 z-10"></div>

          <!-- Banner Content -->
          <div class="relative z-10 px-8 py-10 flex flex-col md:flex-row items-center justify-between gap-6 bg-gradient-to-r from-[#0B4D2F] via-[#179B6B] to-[#116D45] text-white">
            <!-- Title/Info -->
            <div class="flex flex-col gap-2 md:gap-4">
              <div class="flex items-center gap-4 mb-2">
                <div class="p-3 rounded-full bg-white/20 shadow">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                  </svg>
                </div>
                <span class="text-3xl md:text-4xl font-extrabold tracking-tight drop-shadow-lg">৪৮ তম কেন্দ্রীয় পরীক্ষা- ১৪৪৬ হিজরি / ২০২৫ ঈসাব্দ</span>
              </div>
              <div class="flex items-center gap-2 mt-2 text-white/80 text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-medium">আপনার অধিনস্ত ১৫ টি মাদরাসার উপাত্ত</p>
              </div>
            </div>
            <!-- Search Controls -->
            <div class="flex flex-col md:flex-row items-center gap-4">
              <div class="relative">
                <select
                  v-model="selectedDaura"
                  class="appearance-none bg-white/10 border border-white/20 rounded-lg px-5 py-3 pr-12 text-white text-lg placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/30 min-w-[210px]"
                >
                  <option value="1446">দাওরায়ে হাদীস ১৪৪৬</option>
                </select>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
              </div>
              <div class="relative">
                <input
                  type="text"
                  v-model="madrasaCode"
                  placeholder="মাদরাসা কোড (ইংরেজিতে)"
                  class="bg-white/10 border border-white/20 rounded-lg px-5 py-3 pl-12 text-white text-lg placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/30 min-w-[260px]"
                >
                <div class="absolute left-3 top-1/2 -translate-y-1/2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </div>
              </div>
              <button
                @click="search"
                class="bg-white text-[#116D45] hover:bg-emerald-100 font-bold py-3 px-8 rounded-lg flex items-center gap-2 transition-all focus:outline-none focus:ring-2 focus:ring-white/30 border border-white/30 text-lg shadow"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                অনুসন্ধান
              </button>
            </div>
          </div>
        </div>
        <!-- End HERO BANNER -->

        <!-- Statistics Cards -->
        <dashboard_card class="mt-10" />

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-7 mt-8">
          <!-- Notice Board and Upcoming Exam -->
          <div class="md:col-span-2">
            <Notice_bord_and_upcomming_exam />
          </div>
          <!-- Registration Update Dashboard -->
          <div>
            <Registrasion_update_dashboard />
          </div>
        </div>

        <!-- Recent Activities (optional, for demo) -->
        <div class="mt-10">
          <div class="flex items-center gap-2 mb-2">
            <svg class="h-6 w-6 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h4 class="text-lg font-bold text-emerald-800">সাম্প্রতিক আপডেটসমূহ</h4>
          </div>
          <ul class="divide-y divide-emerald-100 bg-white/80 rounded-xl shadow border border-emerald-100">
            <li v-for="activity in activities" :key="activity.id" class="px-6 py-4 flex items-center gap-4 hover:bg-emerald-50 transition">
              <span class="text-2xl">{{ activity.icon }}</span>
              <div class="flex-1">
                <div class="font-medium text-emerald-900">{{ activity.description }}</div>
                <div class="text-xs text-emerald-600">{{ activity.time }}</div>
              </div>
            </li>
            <li v-if="!activities.length" class="px-6 py-6 text-emerald-700 text-center">কোনো সাম্প্রতিক আপডেট নেই</li>
          </ul>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Custom input & select focus ring and style for the dashboard */
input:focus, select:focus {
  outline: none !important;
  box-shadow: 0 0 0 2px #10b98144;
  border-color: #10b981 !important;
}
</style>
