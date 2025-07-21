<template>
      <div class="bg-gradient-to-r rounded-t-md from-[#0B4D2F] to-[#116D45] py-2 shadow-lg">
      <div class="container-fluid mx-auto px-4">
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-3">

          </div>

          <div class="relative">
            <input
              type="text"
              placeholder="অনুসন্ধান করুন..."
              class="w-64 bg-white/10 border border-white/20 rounded-md px-4 py-2 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/30"
            >
            <button class="absolute right-3 top-1/2 transform -translate-y-1/2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>
        </div>


      </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid mx-auto px-4 py-8">
        <div class="grid grid-cols-1 gap-6 w-full">
  <!-- Exam Information Card -->
  <div class="bg-white rounded-md shadow-md overflow-hidden">
    <div class="border-b border-gray-100 p-6">
      <h2 class="text-xl font-bold text-[#116D45] flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        পরীক্ষা সংক্রান্ত তথ্য
      </h2>
    </div>

    <!-- Exam Dates -->
    <div class="bg-[#FFF3CD] p-4 border-l-4 border-[#856404]">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#856404]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <p class="text-[#856404]">পরীক্ষা শুরু: ১৩ ফেব্রুয়ারী ২০২৫ ঈসাব্দ</p>
        </div>
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#856404]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-[#856404]">পরীক্ষা শেষ: ২৪ ফেব্রুয়ারী ২০২৫ ঈসাব্দ</p>
        </div>
      </div>
    </div>

    <!-- Statistics Grid -->
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Registration Statistics -->
      <div class="space-y-4">
        <h3 class="font-semibold text-[#116D45] flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          নিবন্ধন উপাত্ত
        </h3>

        <div class="bg-gray-50 p-4 rounded-md space-y-3">
          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">সর্বমোট নিবন্ধনের সংখ্যা</span>
            <span class="font-semibold">{{ studentStats.totalStudents }}</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">পেমেন্ট সম্পন্নকৃত নিবন্ধনের সংখ্যা</span>
            <span class="font-semibold">২৮৩০৫</span>
          </div>



          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">বোর্ডে দাখিলকৃত নিবন্ধন সংখ্যা</span>
            <span class="font-semibold">{{ studentStats.boardSubmittedStudents }}</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">অনুমোদিত নিবন্ধন সংখ্যা</span>
            <span class="font-semibold">{{ studentStats.approvedStudents }}</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">সংশোধনের জন্য ফেরত পাঠানো নিবন্ধন সংখ্যা</span>
            <span class="font-semibold">{{ studentStats.boardReturnedStudents }}</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">স্থগিত নিবন্ধন সংখ্যা</span>
            <span class="font-semibold">৪</span>
          </div>

          <div class="flex justify-between items-center">
            <span class="text-gray-700">প্রত্যাখ্যাত নিবন্ধন সংখ্যা</span>
            <span class="font-semibold">৯</span>
          </div>
        </div>
      </div>

      <!-- Payment Statistics -->
      <div class="space-y-4">
        <h3 class="font-semibold text-[#116D45] flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          পেমেন্টের উপাত্ত
        </h3>

        <div class="bg-gray-50 p-4 rounded-md space-y-3">
          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">সর্বমোট পেমেন্ট</span>
            <span class="font-semibold">১৭২৫২৯০০ টাকা</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">বোর্ডের প্রাপ্য পেমেন্ট</span>
            <span class="font-semibold">২,১৫৬,৬১২.৫০ টাকা</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">নিয়মিত পরীক্ষার্থীর পেমেন্ট (নিয়মিত)</span>
            <span class="font-semibold">১৫৪৯১৪০০ টাকা</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">অনিয়মিত পরীক্ষার্থীর পেমেন্ট (নিয়মিত)</span>
            <span class="font-semibold">৪৭০৪০০ টাকা</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">নিয়মিত পরীক্ষার্থীর পেমেন্ট (বিলম্বিত)</span>
            <span class="font-semibold">১১২০৭০০ টাকা</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">অনিয়মিত পরীক্ষার্থীর পেমেন্ট (বিলম্বিত)</span>
            <span class="font-semibold">১৭০৪০০ টাকা</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">নগদ এর মাধ্যমে পেমেন্ট</span>
            <span class="font-semibold">১৬৭৬০৫৪৬ টাকা</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">আইপেসেফ এর মাধ্যমে পেমেন্ট</span>
            <span class="font-semibold">৬৫৪৬৪০ টাকা</span>
          </div>

          <div class="flex justify-between items-center border-b pb-2">
            <span class="text-gray-700">পেমেন্ট গেটওয়ে চার্জ</span>
            <span class="font-semibold">১৭৩৭৮৬ টাকা</span>
          </div>

          <div class="flex justify-between items-center">
            <span class="text-gray-700">ক্যাশ পেমেন্ট</span>
            <span class="font-semibold">২৫০০০ টাকা</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const studentStats = ref({
    totalStudents: 0,
    boardSubmittedStudents: 0,
    approvedStudents: 0,
    boardReturnedStudents: 0
});

const loading = ref(true);
const error = ref(null);

onMounted(async () => {
    try {
        const response = await axios.get('/api/dashboard/student-stats');
        studentStats.value = response.data;
        loading.value = false;
    } catch (err) {
        error.value = 'ডাটা লোড করতে সমস্যা হয়েছে';
        loading.value = false;
        console.error(err);
    }
});
</script>
