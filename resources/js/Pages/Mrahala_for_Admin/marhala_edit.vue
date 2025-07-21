<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-xl">
          <!-- Form Header -->
          <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <div class="flex items-center">
              <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-bold text-gray-900">মারহালা সাবজেক্ট ইনফরমেশন</h2>
                <p class="text-sm text-gray-600">মারহালা এবং এর অন্তর্গত বিষয়সমূহ সম্পাদনা করুন</p>
              </div>
            </div>

            <div class="flex items-center">
              <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                <span class="w-2 h-2 rounded-full bg-indigo-500 mr-1.5"></span>
                ID: {{ marhalaId }}
              </span>
            </div>
          </div>

          <form @submit.prevent="handleSubmit" class="p-6">
            <!-- Marhala Info Card -->
            <div class="mb-8 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
              <div class="px-5 py-4 bg-gradient-to-r from-indigo-50 to-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  মূল তথ্য
                </h3>
              </div>

              <div class="p-5 bg-white">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div class="space-y-2">
                    <label class="block text-gray-700 text-sm font-medium mb-1">
                      মারহালা নাম (বাংলা)
                    </label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500">বাং</span>
                      </div>
                      <input
                        type="text"
                        v-model="form.marhalaNameBn"
                        class="block w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        placeholder="মারহালা নাম লিখুন..."
                      />
                    </div>
                  </div>
                  <div class="space-y-2">
                    <label class="block text-gray-700 text-sm font-medium mb-1">
                      মারহালা নাম (ইংরেজি)
                    </label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500">En</span>
                      </div>
                      <input
                        type="text"
                        v-model="form.marhalaNameEn"
                        class="block w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        placeholder="Enter marhala name..."
                      />
                    </div>
                  </div>
                  <div class="space-y-2">
                    <label class="block text-gray-700 text-sm font-medium mb-1">
                      মারহালা নাম (আরবি)
                    </label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500">عر</span>
                      </div>
                      <input
                        type="text"
                        v-model="form.marhalaNameAr"
                        class="block w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        style="font-family: 'Amiri', 'Scheherazade New', serif;"
                        placeholder="...اكتب اسم المرحلة"
                        dir="rtl"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Subjects Card -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
              <div class="px-5 py-4 bg-gradient-to-r from-indigo-50 to-white border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                  বিষয়সমূহ
                </h3>

                <div class="flex items-center">
                  <button
                    type="button"
                    @click="addNewRow"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    নতুন সাবজেক্ট যোগ করুন
                  </button>
                </div>
              </div>

              <div class="p-5 bg-white">
                <!-- Subject Count Tags -->
                <div v-if="form.subjects.length > 0" class="mb-4 flex flex-wrap gap-2">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    মোট: {{ form.subjects.length }}
                  </span>
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    ছাত্র: {{ getMaleCount() }}
                  </span>
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    ছাত্রী: {{ getFemaleCount() }}
                  </span>
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                    </svg>
                    উভয়: {{ getBothCount() }}
                  </span>
                </div>

                <!-- Subjects Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          সাবজেক্ট কোড
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          কিতাব নাম (বাংলা)
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          কিতাব নাম (ইংরেজি)
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          কিতাব নাম (আরবি)
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          স্ট্যাটাস
                        </th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                          অ্যাকশন
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="(subject, index) in form.subjects" :key="index"
                          class="hover:bg-gray-50 transition-colors"
                          :class="{'bg-yellow-50': isNewRow(index)}">
                        <td class="px-4 py-3 whitespace-nowrap">
                          <div class="relative">
                            <input
                              type="text"
                              v-model="form.subjects[index].code"
                              class="block w-full border border-gray-300 rounded-lg px-3 py-1.5 shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                              placeholder="কোড..."
                            />
                          </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                          <input
                            type="text"
                            v-model="form.subjects[index].nameBangla"
                            class="block w-full border border-gray-300 rounded-lg px-3 py-1.5 shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="বাংলায় কিতাবের নাম..."
                          />
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                          <input
                            type="text"
                            v-model="form.subjects[index].nameEnglish"
                            class="block w-full border border-gray-300 rounded-lg px-3 py-1.5 shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Book name in English..."
                          />
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                          <input
                            type="text"
                            v-model="form.subjects[index].nameArabic"
                            dir="rtl"
                            class="block w-full border border-gray-300 rounded-lg px-3 py-1.5 shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            style="font-family: 'Amiri', 'Scheherazade New', serif;"
                            placeholder="...اسم الكتاب بالعربية"
                          />
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                          <div class="flex items-center space-x-3">
                            <label :for="'status1_'+index" class="flex items-center cursor-pointer">
                              <div class="relative">
                                <input
                                  type="radio"
                                  v-model="form.subjects[index].status"
                                  value="SRtype_1"
                                  :id="'status1_'+index"
                                  class="sr-only"
                                />
                                <div class="w-10 h-5 bg-gray-200 rounded-full shadow-inner transition"></div>
                                <div class="dot absolute w-5 h-5 bg-white rounded-full shadow -left-1 -top-0 transition"
                                     :class="{'transform translate-x-full bg-blue-600': form.subjects[index].status === 'SRtype_1'}">
                                </div>
                              </div>
                              <div class="ml-2 text-sm font-medium text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                ছাত্র
                              </div>
                            </label>

                            <label :for="'status2_'+index" class="flex items-center cursor-pointer">
                              <div class="relative">
                                <input
                                  type="radio"
                                  v-model="form.subjects[index].status"
                                  value="SRtype_0"
                                  :id="'status2_'+index"
                                  class="sr-only"
                                />
                                <div class="w-10 h-5 bg-gray-200 rounded-full shadow-inner transition"></div>
                                <div class="dot absolute w-5 h-5 bg-white rounded-full shadow -left-1 -top-0 transition"
                                     :class="{'transform translate-x-full bg-pink-600': form.subjects[index].status === 'SRtype_0'}">
                                </div>
                              </div>
                              <div class="ml-2 text-sm font-medium text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                ছাত্রী
                              </div>
                            </label>

                            <label :for="'status3_'+index" class="flex items-center cursor-pointer">
                              <div class="relative">
                                <input
                                  type="radio"
                                  v-model="form.subjects[index].status"
                                  value="Both"
                                  :id="'status3_'+index"
                                  class="sr-only"
                                />
                                <div class="w-10 h-5 bg-gray-200 rounded-full shadow-inner transition"></div>
                                <div class="dot absolute w-5 h-5 bg-white rounded-full shadow -left-1 -top-0 transition"
                                     :class="{'transform translate-x-full bg-purple-600': form.subjects[index].status === 'Both'}">
                                </div>
                              </div>
                              <div class="ml-2 text-sm font-medium text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                  <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                </svg>
                                উভয়
                              </div>
                            </label>
                          </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                          <div class="flex items-center justify-end">
                            <button
                              type="button"
                              @click="removeRow(index)"
                              class="inline-flex items-center p-1.5 border border-red-300 rounded-md bg-white text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                              title="সারি মুছুন"
                            >
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                              </svg>
                            </button>
                          </div>
                        </td>
                      </tr>

                      <!-- Empty state when no subjects are available -->
                      <tr v-if="form.subjects.length === 0">
                        <td colspan="6" class="py-8 text-center">
                          <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <p class="mt-2 text-gray-500">কোন সাবজেক্ট যোগ করা হয়নি</p>
                            <button
                              type="button"
                              @click="addNewRow"
                              class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                              <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                              </svg>
                              সাবজেক্ট যোগ করুন
                            </button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-between mt-6">
              <button
                type="button"
                @click="goBack"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                ফিরে যান
              </button>

              <button
                type="submit"
                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                সংরক্ষণ করুন
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  marhala: {
    type: Object,
    required: true
  }
});

const marhalaId = props.marhala.id;

const form = ref({
  marhalaNameBn: props.marhala.marhala_name_bn,
  marhalaNameEn: props.marhala.marhala_name_en,
  marhalaNameAr: props.marhala.marhala_name_ar,
  subjects: props.marhala.subjects.map(subject => ({
    code: subject.subject_code,
    nameBangla: subject.name_bangla,
    nameEnglish: subject.name_english,
    nameArabic: subject.name_arabic,
    status: subject.status || 'Both',
    isNew: false
  }))
});

// নতুন সারি যোগ করা
const addNewRow = () => {
  form.value.subjects.push({
    code: '',
    nameBangla: '',
    nameEnglish: '',
    nameArabic: '',
    status: 'Both',
    isNew: true
  });
};

// সারি রিমুভ করা
const removeRow = (index) => {
  if (confirm('আপনি কি নিশ্চিত যে এই সাবজেক্টটি মুছে ফেলতে চান?')) {
    form.value.subjects.splice(index, 1);
  }
};

// ফর্ম সাবমিট
const handleSubmit = () => {
  router.put(`/marhalas/${marhalaId}`, form.value, {
    onSuccess: () => {
      alert('সফলভাবে সংরক্ষিত হয়েছে!');
    }
  });
};

// ফিরে যাওয়া
const goBack = () => {
  router.visit(route('Mrahala_for_Admin.marhala_setup'));
};

// Check if row is newly added
const isNewRow = (index) => {
  return form.value.subjects[index].isNew;
};

// Get count of male subjects
const getMaleCount = () => {
  return form.value.subjects.filter(subject => subject.status === 'SRtype_1').length;
};

// Get count of female subjects
const getFemaleCount = () => {
  return form.value.subjects.filter(subject => subject.status === 'SRtype_0').length;
};

// Get count of both type subjects
const getBothCount = () => {
  return form.value.subjects.filter(subject => subject.status === 'Both').length;
};
</script>

<style scoped>
/* Toggle switch transitions */
.dot {
  transition: all 0.3s ease-in-out;
}
input[type="radio"]:checked ~ .dot {
  transform: translateX(100%);
}

/* Table row highlight transition */
tr {
  transition: background-color 0.2s ease;
}

/* Button transitions */
button {
  transition: all 0.2s ease;
}

/* Input field focus styling */
input:focus, select:focus {
  outline: none;
  border-color: #6366f1;
  box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.2);
}
</style>
