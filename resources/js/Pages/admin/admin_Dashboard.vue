<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
// import { Chart } from 'chart.js/auto';
import dashboard_card from '../admin_dashboard/dashboard_card.vue';
import notice_bord_and_upcomming_exam from '../admin_dashboard/notice_bord_and_upcomming_exam.vue';

import registrasion_update_dashboard from '../admin_dashboard/registrasion_update_dashboard.vue';
import exam_analytics from '../admin_dashboard/exam_analytics.vue';
import center_management from '../admin_dashboard/center_management.vue';
// Enhanced statistics data


// Enhanced exam schedule


// Recent activities
const activities = ref([
    {
        id: 1,
        type: 'exam_created',
        description: 'New exam schedule published for Computer Science',
        time: '2 hours ago',
        icon: '🆕'
    },
    // Add more activities...
]);
const selectedDaura = ref('1446'); // Default value
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









        <div class="py-6">

            <div class="mx-auto px-4 sm:px-6 lg:px-8">



    <div class="bg-gradient-to-r mb-5 rounded-md from-[#0B4D2F] to-[#116D45] py-6 text-white">
    <div class="container mx-auto px-4">
      <div class="flex flex-col md:flex-row items-center justify-between">
        <!-- Title Section -->
        <div class="mb-4 md:mb-0 flex items-center gap-3">
          <div class="p-2 rounded-full bg-white/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M12 14l9-5-9-5-9 5 9 5z"/>
              <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
          </div>
          <div>
            <p class="text-2xl font-bold">৪৮ তম কেন্দ্রীয় পরীক্ষা- ১৪৪৬ হিজরি / ২০২৫ ঈসাব্দ</p>
          </div>
        </div>

        <!-- Search Controls -->
        <div class="flex flex-col md:flex-row items-center gap-3">
          <div class="relative">
            <select
              v-model="selectedDaura"
              class="appearance-none bg-white/10 border border-white/20 rounded-md px-4 py-2 pr-10 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/30 min-w-[200px]"
            >
              <option value="1446">দাওরায়ে হাদীস ১৪৪৬</option>
            </select>
            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </div>

          <div class="relative">
            <input
              type="text"
              v-model="madrasaCode"
              placeholder="মাদরাসা কোড (ইংরেজিতে)"
              class="bg-white/10 border border-white/20 rounded-md px-4 py-2 pl-10 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/30 min-w-[250px]"
            >
            <div class="absolute left-3 top-1/2 -translate-y-1/2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          </div>

          <button
            @click="search"
            class="bg-white text-[#116D45] hover:bg-white/90 font-bold py-2 px-6 rounded-md flex items-center gap-2 transition-all focus:outline-none focus:ring-2 focus:ring-white/30"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            অনুসন্ধান
          </button>
        </div>
      </div>

      <!-- Info Text -->
      <div class="mt-4 text-sm text-white/80 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p>আপনার অধিনস্ত ১৫ টি মাদরাসার উপাত্ত</p>
      </div>
    </div>
  </div>









                <!-- Statistics Cards -->
                <dashboard_card />

                <!-- Main Content Grid -->
  <notice_bord_and_upcomming_exam />
                <!-- Recent Activities -->
                <div class=" bg-[#F8F9FA] mt-5  mb-5">
    <!-- Header Section -->
 <registrasion_update_dashboard />

  </div>


              <div class=" bg-[#F8F9FA] mt-5  mb-5">
    <!-- Header Section -->
<exam_analytics />

  </div>


              <div class=" bg-[#F8F9FA] mt-5  mb-5">
    <!-- Header Section -->
<center_management />

  </div>


            </div>
        </div>

    </AuthenticatedLayout>
</template>


<style scoped>
/* .btn-primary {
    @apply px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200;
}

.btn-secondary {
    @apply px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200;
} */
</style>
