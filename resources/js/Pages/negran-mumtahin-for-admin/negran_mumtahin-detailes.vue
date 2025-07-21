<template>
  <AuthenticatedLayout>
    <div class=" p-6 bg-white rounded-xl shadow-lg">
    <!-- Header -->
    <div class="flex justify-between items-start mb-8 border-b pb-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 mb-1">ব্যক্তিগত বিস্তারিত তথ্য</h1>
        <p class="text-sm text-gray-500">আপনার প্রোফাইল তথ্য নিচে বিস্তারিতভাবে দেখানো হলো</p>
      </div>
      <img
        src="https://via.placeholder.com/100"
        alt="Profile Photo"
        class="w-28 h-28 object-cover rounded shadow border"
      />
    </div>

    <!-- Personal Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
      <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-2">ব্যক্তিগত তথ্য</h2>
        <ul class="space-y-1">
          <li><strong>নাম:</strong> জন ডো</li>
          <li><strong>পিতা:</strong> রফিকুল ইসলাম</li>
          <li><strong>মাতা:</strong> হাসিনা বেগম</li>
          <li><strong>জন্ম তারিখ:</strong> ০১ জানুয়ারী ১৯৮০</li>
          <li><strong>লিঙ্গ:</strong> পুরুষ</li>
          <li><strong>ধর্ম:</strong> ইসলাম</li>
        </ul>
      </div>
      <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-2">যোগাযোগ</h2>
        <ul class="space-y-1">
          <li><strong>ফোন:</strong> ০১৭xxxxxxxx</li>
          <li><strong>ইমেইল:</strong> example@mail.com</li>
          <li><strong>বর্তমান ঠিকানা:</strong> ধানমন্ডি, ঢাকা</li>
          <li><strong>স্থায়ী ঠিকানা:</strong> কুমিল্লা সদর</li>
        </ul>
      </div>
    </div>

    <!-- Academic Qualifications -->
    <div class="mt-10">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">শিক্ষাগত যোগ্যতা</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left border">
          <thead class="bg-gray-100 text-gray-600">
            <tr>
              <th class="px-4 py-2 border">#</th>
              <th class="px-4 py-2 border">পাসের বছর</th>
              <th class="px-4 py-2 border">পরীক্ষার নাম</th>
              <th class="px-4 py-2 border">বোর্ড</th>
              <th class="px-4 py-2 border">রোল</th>
            </tr>
          </thead>
          <tbody>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 border">১</td>
              <td class="px-4 py-2 border">২০০০</td>
              <td class="px-4 py-2 border">SSC</td>
              <td class="px-4 py-2 border">ঢাকা</td>
              <td class="px-4 py-2 border">১২৩৪৫৬</td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-2 border">২</td>
              <td class="px-4 py-2 border">২০০২</td>
              <td class="px-4 py-2 border">HSC</td>
              <td class="px-4 py-2 border">ঢাকা</td>
              <td class="px-4 py-2 border">১২৩৪৫৭</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Additional Info -->
    <div class="mt-10">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">অন্যান্য তথ্য</h2>
      <ul class="space-y-2 text-gray-700">
        <li><strong>পেশা:</strong> শিক্ষক</li>
        <li><strong>জাতীয় পরিচয়পত্র নম্বর:</strong> ১৯৮০০০০০০০০০০</li>
        <li><strong>ব্লাড গ্রুপ:</strong> B+</li>
      </ul>
    </div>
  </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';

// Static dummy layout. Replace with props or data bindings if needed.
</script>

<style scoped>
</style>
