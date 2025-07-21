<script setup>
defineProps({
  pastExamForm: Object,
  currentExamForm: Object
});
</script>

<template>
  <div class="bg-white border border-emerald-100 rounded-lg shadow">
    <div class="bg-gradient-to-r rounded-t-md from-emerald-800 overflow-hidden px-6 py-3 relative to-emerald-600">
      <div class="flex gap-3 items-center relative z-10">
        <i class="text-2xl text-white fa-graduation-cap fas"></i>
        <h5 class="text-white text-xl font-arabic">বিগত পরীক্ষার তথ্য</h5>
      </div>
    </div>
    <div class="bg-opacity-5 bg-white p-6">
      <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <!-- Madrasha -->
        <div class="relative">
          <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
            <i class="text-emerald-600 fa-school fas"></i>
            মাদরাসা
          </label>
          <input v-model="pastExamForm.Madrasha" type="text" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
        </div>
        <!-- Markaj -->
        <div class="relative">
          <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
            <i class="text-emerald-600 fa-school fas"></i>
            মারকায
          </label>
          <input v-model="pastExamForm.Markaj" type="text" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
        </div>
        <!-- Class -->
        <div class="relative">
          <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
            <i class="text-emerald-600 fa-layer-group fas"></i>
            শ্রেণী
          </label>
          <input v-model="pastExamForm.Class" type="text" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
        </div>
        <!-- Roll Number -->
        <div class="relative">
          <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
            <i class="text-emerald-600 fa-id-card fas"></i>
            রোল নম্বর
          </label>
          <input v-model="pastExamForm.Roll" type="text" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
        </div>
        <!-- Registration ID -->
        <div class="relative">
          <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
            <i class="text-emerald-600 fa-id-card fas"></i>
            রেজিস্ট্রেশন আইডি
          </label>
          <input v-model="pastExamForm.reg_id" type="text" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
        </div>
        <!-- Date of Birth -->
        <div class="relative">
          <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
            <i class="text-emerald-600 fa-calendar-alt fas"></i>
            জন্ম-তারিখ
          </label>
          <input v-model="pastExamForm.DateofBirth" type="date" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
        </div>
        <!-- Result -->
        <div class="relative">
          <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
            <i class="text-emerald-600 fa-calendar-alt fas"></i>
            ফলাফল
          </label>
          <input v-model="pastExamForm.Division" type="text" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
        </div>
        <!-- পরীক্ষার্থীর ধরন -->
        <div class="relative">
          <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
            পরীক্ষার্থীর ধরন
          </label>
          <input v-model="currentExamForm.student_type" type="text" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
        </div>
        <!-- অনিয়মিত হলে পরীক্ষা দিতে হবে এমন কিতাবের নাম -->
        <div class="relative"
          v-if="currentExamForm.student_type === 'অনিয়মিত যেমনী' || currentExamForm.student_type === 'অনিয়মিত অন্যান্য'">
          <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
            অনিয়মিত হলে পরীক্ষা দিতে হবে এমন কিতাবের নাম
          </label>
          <input v-model="currentExamForm.irregular_subjects" type="text" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
        </div>
      </div>
    </div>
  </div>
</template>
