<template>
  <AuthenticatedLayout>
    <div class="py-12 bg-gradient-to-br from-green-50 to-blue-50 min-h-screen">
      <div class="mx-auto  px-4 sm:px-6 lg:px-8">
        <div class="bg-white/90 shadow-2xl rounded-2xl overflow-hidden ring-1 ring-gray-100">
          <!-- Header -->
          <div class="flex flex-col md:flex-row items-center md:items-start justify-between px-8 pt-10 pb-6 border-b border-gray-200 bg-gradient-to-r from-indigo-100 via-white to-white">
            <div class="flex-1 flex flex-col gap-2">
              <h1 class="text-3xl font-extrabold text-gray-900 mb-1 tracking-tight leading-tight">ব্যক্তিগত বিস্তারিত তথ্য</h1>
              <p class="text-base text-gray-600">আপনার প্রোফাইল তথ্য নিচে বিস্তারিতভাবে দেখানো হলো</p>
            </div>
            <div class="relative ml-0 md:ml-8 mt-6 md:mt-0 group hover:scale-105 transition-transform duration-300">
              <img
                v-if="student.student_image"
                :src="'/storage/' + student.student_image"
                alt="Profile Photo"
                class="w-32 h-32 object-cover rounded-full border-4 border-white shadow-xl ring-2 ring-indigo-300 transition-all duration-300"
              />
              <div v-else class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center border-4 border-white shadow-xl">
                <span class="text-gray-400 text-xl font-semibold text-center">কোনো ছবি নেই</span>
              </div>
              <span class="absolute bottom-2 right-2 bg-indigo-500 text-white rounded-full px-2 py-0.5 text-xs shadow group-hover:bg-indigo-600 transition">Active</span>
            </div>
          </div>

          <!-- Main Info Grid -->
          <div class="px-8 py-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left: Personal & Contact -->
            <div class="flex flex-col gap-8">
              <!-- Personal Info Card -->
              <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-indigo-700 mb-3 flex items-center gap-2">
                  <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.21 0 4.315.535 6.121 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0zm2 10H7a2 2 0 01-2-2v-1a7 7 0 0114 0v1a2 2 0 01-2 2z"/></svg>
                  ব্যক্তিগত তথ্য
                </h2>
                <ul class="divide-y divide-gray-100">
                  <li class="py-1 flex justify-between"><span class="font-medium">নাম:</span> <span>{{ student.name_bn }}</span></li>
                  <li class="py-1 flex justify-between"><span class="font-medium">পিতা:</span> <span>{{ student.father_name_bn }}</span></li>
                  <li class="py-1 flex justify-between"><span class="font-medium">মাতা:</span> <span>{{ student.mother_name_bn }}</span></li>
                  <li class="py-1 flex justify-between"><span class="font-medium">জন্ম তারিখ:</span> <span>{{ student.Date_of_birth }}</span></li>
                  <li class="py-1 flex justify-between"><span class="font-medium">পরীক্ষার নাম:</span> <span>{{ student.exam_name_Bn }}</span></li>
                  <li class="py-1 flex justify-between"><span class="font-medium">ধর্ম:</span> <span>ইসলাম</span></li>
                </ul>
              </div>
              <!-- Contact Info Card -->
              <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-indigo-700 mb-3 flex items-center gap-2">
                  <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-9 13h.01M5 19a9 9 0 0114 0"/></svg>
                  যোগাযোগ
                </h2>
                <ul class="divide-y divide-gray-100">
                  <li class="py-1 flex justify-between"><span class="font-medium">ফোন:</span> <span>01766839308</span></li>
                  <li class="py-1 flex justify-between"><span class="font-medium">ইমেইল:</span> <span>{{ student.email || 'প্রদান করা হয়নি' }}</span></li>
                  <li class="py-1 flex flex-col gap-1">
                    <span class="font-medium">বর্তমান ঠিকানা:</span>
                    <span class="text-sm text-gray-600">
                      {{ student.present_thana_name }}, {{ student.present_district_name }}, {{ student.present_division_name }}
                    </span>
                  </li>
                  <li class="py-1 flex flex-col gap-1">
                    <span class="font-medium">স্থায়ী ঠিকানা:</span>
                    <span class="text-sm text-gray-600">
                      {{ student.parmanent_thana_name }}, {{ student.parmanent_district_name }}, {{ student.parmanent_division_name }}
                    </span>
                  </li>
                </ul>
              </div>
            </div>
            <!-- Right: Other Info -->
            <div>
              <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-indigo-700 mb-3 flex items-center gap-2">
                  <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m0-5V3a1 1 0 00-1-1h-6a1 1 0 00-1 1v9m10 4v3a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3m-4 0v3a1 1 0 01-1 1H7a1 1 0 01-1-1v-3"/></svg>
                  অন্যান্য তথ্য
                </h2>
                <ul class="divide-y divide-gray-100">
                  <li class="py-1 flex justify-between items-center">
                    <span class="font-medium">পরিচয়পত্র:</span>
                    <span>
                      {{ student.NID_no || 'প্রদান করা হয়নি' }}
                      <button
                        v-if="student.NID_attach"
                        @click="showIdModal = true"
                        class="ml-2 text-xs text-indigo-600 hover:underline underline-offset-2 font-semibold"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-0.5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        স্ক্যান দেখুন
                      </button>
                    </span>
                  </li>
                  <li class="py-1 flex justify-between"><span class="font-medium">পরীক্ষার্থীর ধরন:</span> <span>{{ student.student_type || 'পরীক্ষার্থীর ধরন নাই' }}</span></li>
                  <li class="py-1 flex justify-between"><span class="font-medium">বর্তমান মারহালা:</span> <span>{{ student.current_class || 'পরীক্ষার্থীর ধরন নাই' }}</span></li>
                  <li class="py-1 flex justify-between"><span class="font-medium">পরীক্ষা দিবে এমন কিতাবের নাম:</span> <span>{{ student.exam_books_name }}</span></li>
                  <li class="py-1 flex justify-between"><span class="font-medium">পরীক্ষার নাম:</span> <span>{{ student.exam_name_Bn }}</span></li>
                  <li class="py-1 flex justify-between"><span class="font-medium">বর্তমান মারকাযের নাম:</span> <span>{{ student.current_markaz }}</span></li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Academic Qualification -->
          <div v-if="student.is_old_student === 1" class="px-8 pb-8">
            <div class="bg-gradient-to-r from-green-100 to-blue-100 rounded-xl shadow-md p-6 mt-8">
              <h2 class="text-xl font-semibold text-green-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.104 0-2 .672-2 1.5S10.896 11 12 11s2-.672 2-1.5S13.104 8 12 8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 16v1a3 3 0 01-3 3H8a3 3 0 01-3-3v-1"/></svg>
                বিগত পরীক্ষার তথ্য
              </h2>
              <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border rounded-lg overflow-hidden">
                  <thead class="bg-green-500 text-white">
                    <tr>
                      <th class="px-4 py-2 border">#</th>
                      <th class="px-4 py-2 border">পাসের বছর</th>
                      <th class="px-4 py-2 border">মারহালা</th>
                      <th class="px-4 py-2 border">মাদরাসার নাম</th>
                      <th class="px-4 py-2 border">মারকাযের নাম</th>
                      <th class="px-4 py-2 border">রোল নম্বর</th>
                      <th class="px-4 py-2 border">নিবন্ধন নম্বর</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="hover:bg-green-50">
                      <td class="px-4 py-2 border">১</td>
                      <td class="px-4 py-2 border">{{ student.passing_year }}</td>
                      <td class="px-4 py-2 border">{{ student.class }}</td>
                      <td class="px-4 py-2 border">{{ student.madrasha_name }}</td>
                      <td class="px-4 py-2 border">{{ student.markaz_name }}</td>
                      <td class="px-4 py-2 border">{{ student.past_Roll }}</td>
                      <td class="px-4 py-2 border">{{ student.past_reg_id }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Registration Observation -->
          <div class="px-8 pb-8">
            <div class="bg-white rounded-xl shadow-md p-6 mt-8">
              <h2 class="text-xl font-semibold text-indigo-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m0-5V3a1 1 0 00-1-1h-6a1 1 0 00-1 1v9m10 4v3a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3m-4 0v3a1 1 0 01-1 1H7a1 1 0 01-1-1v-3"/></svg>
                নিবন্ধন পর্যবেক্ষণ
              </h2>
              <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border rounded-lg overflow-hidden">
                  <thead class="bg-indigo-500 text-white">
                    <tr>
                      <th class="px-4 py-2 border">#</th>
                      <th class="px-4 py-2 border">সিদ্ধান্ত গ্রহনকারী</th>
                      <th class="px-4 py-2 border">অবস্থা</th>
                      <th class="px-4 py-2 border">মন্তব্য</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(log, index) in studentLogs" :key="index" class="hover:bg-indigo-50">
                      <td class="px-4 py-2 border">{{ index + 1 }}</td>
                      <td class="px-4 py-2 border flex items-center gap-2">
                        <svg v-if="log.actor_type === 'সুপার এডমিন'" class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg v-else-if="log.actor_type === 'সহ সুপার এডমিন'" class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                        <svg v-else-if="log.actor_type === 'বোর্ড এডমিন'" class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                        <svg v-else class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        {{ log.actor_type }}
                      </td>
                      <td class="px-4 py-2 border">
                        <span :class="{
                          'text-green-600 font-semibold': log.status === 'approved',
                          'text-red-600 font-semibold': log.status === 'returned',
                          'text-yellow-600 font-semibold': log.status === 'submitted'
                        }">
                          <template v-if="log.status === 'approved'">বোর্ড অনুমোদিত</template>
                          <template v-else-if="log.status === 'returned'">বোর্ড ফেরত</template>
                          <template v-else-if="log.status === 'submitted'">বোর্ড দাখিল</template>
                          <template v-else>{{ log.status }}</template>
                        </span>
                      </td>
                      <td class="px-4 py-2 border">
                        <span :class="{
                          'text-red-600 bg-red-50 px-2 py-1 rounded': log.message && log.message.includes('ফেরত')
                        }">
                          {{ log.message }}
                        </span>
                      </td>
                    </tr>
                    <tr v-if="!studentLogs || studentLogs.length === 0" class="hover:bg-gray-50">
                      <td colspan="5" class="px-4 py-2 border text-center text-gray-400">কোন পর্যবেক্ষণ ডাটা পাওয়া যায়নি</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Footer with Verification -->
          <div class="mt-10 px-8 pb-10 flex flex-col md:flex-row md:justify-between items-center gap-4">
            <div class="text-gray-500 text-xs">আপনার তথ্য সংরক্ষিত হয়েছে। যাচাই করুন এবং প্রয়োজনে ডাউনলোড করুন বা প্রিন্ট করুন।</div>
            <div class="flex space-x-3">
              <button
                class="inline-flex items-center px-5 py-2 bg-gray-800 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-300 transition"
                @click="downloadProfile"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                ডাউনলোড
              </button>
              <button
                class="inline-flex items-center px-5 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition"
                @click="printProfile"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                প্রিন্ট করুন
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- ID Document Modal -->
      <transition name="fade">
        <div v-if="showIdModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4">
          <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden border-2 border-indigo-100 relative">
            <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-200 flex justify-between items-center">
              <h3 class="text-lg font-bold text-indigo-900">পরিচয়পত্রের বিবরণ</h3>
              <button @click="showIdModal = false" class="text-indigo-400 hover:text-indigo-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-4rem)]">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- ID Document Image -->
                <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-center border">
                  <img
                    v-if="student.NID_attach"
                    :src="'/storage/' + student.NID_attach"
                    alt="Student NID"
                    class="w-full h-full object-contain rounded-md shadow"
                  />
                  <div v-else class="text-gray-400 text-center">কোনো স্ক্যান সংযুক্ত নেই</div>
                </div>
                <!-- ID Document Details -->
                <div>
                  <h4 class="text-lg font-medium text-indigo-900 mb-4">জন্মসনদ বিবরণ</h4>
                  <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">নাম:</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ student.name_bn }}</td>
                      </tr>
                      <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">পিতার নাম:</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ student.father_name_bn }}</td>
                      </tr>
                      <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">মাতার নাম:</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ student.mother_name_bn }}</td>
                      </tr>
                      <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">জন্ম তারিখ:</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ student.Date_of_birth }}</td>
                      </tr>
                      <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">জন্মসনদ নম্বর:</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ student.NID_no }}</td>
                      </tr>
                      <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">নিবন্ধন নম্বর:</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ student.registration_no || '-' }}</td>
                      </tr>
                      <tr>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">ইস্যু তারিখ:</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">{{ student.nid_issue_date || '-' }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- Modal Footer Buttons -->
              <div class="mt-6 flex justify-end">
                <button
                  @click="showIdModal = false"
                  class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest hover:bg-indigo-100 active:bg-indigo-200 focus:outline-none focus:border-gray-400 focus:ring ring-indigo-200 disabled:opacity-25 transition"
                >
                  বন্ধ করুন
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';

// Props
const props = defineProps({
  student: Object,
  studentLogs: Array
});

// State for modal
const showIdModal = ref(false);

// Print functionality
function printProfile() {
  window.print();
}

// Download profile as PDF (basic example, needs actual implementation with a library like html2pdf if needed)
function downloadProfile() {
  alert('ডাউনলোড ফিচারটি উন্নয়নাধীন। অনুগ্রহ করে প্রিন্ট অপশন ব্যবহার করুন অথবা ডাউনলোড সুবিধার জন্য অ্যাডমিনের সাথে যোগাযোগ করুন।');
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity .3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
@media print {
  .no-print, [class*='no-print'] {
    display: none !important;
  }
  body {
    background: white !important;
  }
}
</style>
