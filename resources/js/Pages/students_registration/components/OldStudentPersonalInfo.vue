<script setup>
const props = defineProps({
  modelValue: Object
});
const emit = defineEmits(['update:modelValue']);
</script>

<template>
  <div class="bg-white border border-emerald-100 rounded-sm shadow">
    <div class="flex bg-gradient-to-r rounded-t-md from-emerald-800 gap-3 items-center px-6 py-3 to-emerald-600">
      <i class="text-2xl text-white fa-user-circle fas"></i>
      <h5 class="text-white text-xl">ব্যক্তিগত তথ্য</h5>
    </div>
    <div class="p-6">
      <!-- নাম -->
      <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">নাম (বাংলা)</label>
          <input v-model="modelValue.name_bn" type="text" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-gray-700 w-full cursor-not-allowed pl-10 pr-3 py-2">
          <p class="text-gray-500 text-xs italic mt-1">* এই ফিল্ডটি পরিবর্তন করা যাবে না</p>
        </div>
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">নাম (ইংরেজি)</label>
          <input v-model="modelValue.name_en" type="text"
            class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2">
        </div>
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">নাম (আরবি)</label>
          <input v-model="modelValue.name_ar" type="text"
            class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2"
            dir="rtl">
        </div>
      </div>
      <!-- পিতার নাম -->
      <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">পিতার নাম (বাংলা)</label>
          <input v-model="modelValue.father_name_bn" type="text" disabled
            class="bg-emerald-50 border border-emerald-200 rounded-md text-gray-700 w-full cursor-not-allowed pl-10 pr-3 py-2">
          <p class="text-gray-500 text-xs italic mt-1">* এই ফিল্ডটি পরিবর্তন করা যাবে না</p>
        </div>
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">পিতার নাম (ইংরেজি)</label>
          <input v-model="modelValue.father_name_en" type="text"
            class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2">
        </div>
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">পিতার নাম (আরবি)</label>
          <input v-model="modelValue.father_name_ar" type="text"
            class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2"
            dir="rtl">
        </div>
      </div>
      <!-- মাতার নাম -->
      <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">মাতার নাম (বাংলা)</label>
          <input v-model="modelValue.mother_name_bn" type="text"
            class="border border-emerald-200 rounded-md text-gray-700 w-full pl-10 pr-3 py-2">
        </div>
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">মাতার নাম (ইংরেজি)</label>
          <input v-model="modelValue.mother_name_en" type="text"
            class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2">
        </div>
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">মাতার নাম (আরবি)</label>
          <input v-model="modelValue.mother_name_ar" type="text"
            class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2"
            dir="rtl">
        </div>
      </div>
      <!-- জন্ম-তারিখ, জন্ম-নিবন্ধন ও NID -->
      <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">জন্ম-তারিখ</label>
          <input v-model="modelValue.Date_of_birth" type="date" disabled
            class="border border-emerald-200 rounded-md w-full pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">জন্ম-নিবন্ধন নম্বর</label>
          <input v-model="modelValue.BRN_no" type="text"
            class="border border-emerald-200 rounded-md w-full pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">জাতীয় পরিচয়পত্র নম্বর</label>
          <input v-model="modelValue.NID_no" type="text"
            class="border border-emerald-200 rounded-md w-full pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
      </div>
      <!-- ফোন নম্বর -->
      <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="relative">
          <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">অভিভাবকের ফোন নম্বর</label>
          <input v-model="modelValue.mobile_no" type="text"
            class="border border-emerald-200 rounded-md w-full pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
      </div>
    </div>
  </div>
</template>
