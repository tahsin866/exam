<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-xl">
          <div class="p-6">
            <!-- Header with Statistics -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
              <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                  <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  মারহালা ব্যবস্থাপনা
                </h2>
                <p class="text-gray-500 mt-1">সকল মারহালার বিষয় এবং পাঠ্যক্রম সংক্রান্ত তথ্য</p>
              </div>

              <div class="flex items-center gap-3">
                <div v-if="marhalas.length > 0" class="flex space-x-2">
                  <div class="text-center px-4 py-2 bg-indigo-50 rounded-lg shadow-sm">
                    <div class="text-2xl font-bold text-indigo-600">{{ marhalas.length }}</div>
                    <div class="text-xs text-indigo-700">মোট মারহালা</div>
                  </div>

                  <div class="text-center px-4 py-2 bg-blue-50 rounded-lg shadow-sm">
                    <div class="text-2xl font-bold text-blue-600">{{ getTotalSubjects() }}</div>
                    <div class="text-xs text-blue-700">মোট বিষয়</div>
                  </div>
                </div>

                <button
                  @click="addNewMarhala"
                  class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg text-white shadow-sm hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  নতুন মারহালা যোগ করুন
                </button>
              </div>
            </div>

            <!-- Marhala Cards for Mobile -->
            <div class="grid grid-cols-1 gap-4 md:hidden">
              <div
                v-for="marhala in marhalas"
                :key="marhala.id"
                class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all"
              >
                <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                  <h3 class="font-medium text-gray-900">{{ marhala.marhala_name_bn }}</h3>
                </div>

                <div class="p-4 space-y-3">
                  <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-500">মোট বিষয়:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ marhala.total_subjects }}</span>
                  </div>

                  <div class="grid grid-cols-3 gap-2 border-t border-gray-100 pt-3">
                    <div class="text-center p-2 bg-blue-50 rounded">
                      <div class="text-xs text-gray-500">পুরুষ</div>
                      <div class="font-semibold text-blue-600">{{ marhala.male_subjects }}</div>
                    </div>
                    <div class="text-center p-2 bg-pink-50 rounded">
                      <div class="text-xs text-gray-500">মহিলা</div>
                      <div class="font-semibold text-pink-600">{{ marhala.female_subjects }}</div>
                    </div>
                    <div class="text-center p-2 bg-purple-50 rounded">
                      <div class="text-xs text-gray-500">উভয়</div>
                      <div class="font-semibold text-purple-600">{{ marhala.both_subjects }}</div>
                    </div>
                  </div>

                  <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
                    <button
                      @click="navigateToSetupList(marhala.id)"
                      class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                      নতুন যোগ করুন
                    </button>
                    <button
                      @click="editMarhala(marhala.id)"
                      class="inline-flex items-center px-2 py-1.5 bg-gray-100 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Empty state for mobile -->
              <div v-if="marhalas.length === 0" class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">কোন মারহালা তথ্য নেই</h3>
                <p class="mt-1 text-sm text-gray-500">নতুন মারহালা যোগ করতে উপরের বাটনে ক্লিক করুন</p>
              </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                      মারহালা নাম
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                      মোট বিষয়
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                      <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        পুরুষ
                      </div>
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                      <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-pink-500" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        মহিলা
                      </div>
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                      <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-purple-500" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                        </svg>
                        উভয়
                      </div>
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                      অ্যাকশন
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="marhala in marhalas" :key="marhala.id" class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 mr-3 flex-shrink-0">
                          {{ marhala.marhala_name_bn.charAt(0) }}
                        </div>
                        <div>
                          <div class="text-sm font-medium text-gray-900">{{ marhala.marhala_name_bn }}</div>
                          <div class="text-xs text-gray-500">ID: {{ marhala.id }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-center">
                        <div class="text-sm font-semibold text-gray-900">{{ marhala.total_subjects }}</div>
                        <div class="text-xs text-gray-500">বিষয়</div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="inline-flex px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full font-medium text-xs">
                        {{ marhala.male_subjects }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="inline-flex px-2.5 py-1 bg-pink-50 text-pink-700 rounded-full font-medium text-xs">
                        {{ marhala.female_subjects }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="inline-flex px-2.5 py-1 bg-purple-50 text-purple-700 rounded-full font-medium text-xs">
                        {{ marhala.both_subjects }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div class="flex justify-center space-x-2">
                        <button
                          @click="navigateToSetupList(marhala.id)"
                          class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm"
                          title="নতুন বিষয় যোগ করুন"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                          </svg>
                          <span>নতুন বিষয় যোগ করুন</span>
                        </button>
                        <button
                          @click="editMarhala(marhala.id)"
                          class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm"
                          title="মারহালা সম্পাদনা করুন"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                          <span>সম্পাদনা করুন</span>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Empty state for desktop -->
                  <tr v-if="marhalas.length === 0">
                    <td colspan="6" class="px-6 py-10 text-center">
                      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                      </svg>
                      <h3 class="mt-2 text-sm font-medium text-gray-900">কোন মারহালা তথ্য নেই</h3>
                      <p class="mt-1 text-sm text-gray-500">নতুন মারহালা যোগ করতে উপরের বাটনে ক্লিক করুন</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Progress Visualization -->
            <div v-if="marhalas.length > 0" class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                বিষয় বিতরণ ভিজুয়ালাইজেশন
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="(marhala, index) in marhalas" :key="index" class="bg-gray-50 rounded-lg p-4">
                  <div class="flex justify-between items-center mb-2">
                    <h4 class="font-medium text-gray-800">{{ marhala.marhala_name_bn }}</h4>
                    <span class="text-sm text-gray-500">{{ marhala.total_subjects }} বিষয়</span>
                  </div>

                  <div class="h-6 bg-gray-200 rounded-full overflow-hidden">
                    <div class="flex h-full">
                      <div
                        class="h-full bg-blue-500"
                        :style="`width: ${(marhala.male_subjects / marhala.total_subjects) * 100}%`"
                        :title="`পুরুষ: ${marhala.male_subjects}`">
                      </div>
                      <div
                        class="h-full bg-pink-500"
                        :style="`width: ${(marhala.female_subjects / marhala.total_subjects) * 100}%`"
                        :title="`মহিলা: ${marhala.female_subjects}`">
                      </div>
                      <div
                        class="h-full bg-purple-500"
                        :style="`width: ${(marhala.both_subjects / marhala.total_subjects) * 100}%`"
                        :title="`উভয়: ${marhala.both_subjects}`">
                      </div>
                    </div>
                  </div>

                  <div class="flex justify-between text-xs mt-2">
                    <div class="flex items-center">
                      <span class="w-3 h-3 bg-blue-500 rounded-full inline-block mr-1"></span>
                      <span>পুরুষ: {{ marhala.male_subjects }}</span>
                    </div>
                    <div class="flex items-center">
                      <span class="w-3 h-3 bg-pink-500 rounded-full inline-block mr-1"></span>
                      <span>মহিলা: {{ marhala.female_subjects }}</span>
                    </div>
                    <div class="flex items-center">
                      <span class="w-3 h-3 bg-purple-500 rounded-full inline-block mr-1"></span>
                      <span>উভয়: {{ marhala.both_subjects }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const marhalas = ref([]);

const fetchMarhalas = async () => {
  try {
    const response = await axios.get('/api/get-marhala-list');
    marhalas.value = response.data;
  } catch (error) {
    console.error('Error fetching marhalas:', error);
  }
};

const navigateToSetupList = (id) => {
  router.visit(route('Mrahala_for_Admin.marhala_setup_list', { id }));
};

const editMarhala = (id) => {
  router.visit(route('Mrahala_for_Admin.marhala_edit', { marhala: id }));
};

const addNewMarhala = () => {
  router.visit(route('Mrahala_for_Admin.marhala_create'));
};

const getTotalSubjects = () => {
  return marhalas.value.reduce((total, marhala) => total + marhala.total_subjects, 0);
};

onMounted(() => {
  fetchMarhalas();
});
</script>

<style scoped>
/* Smooth transitions for all interactive elements */
button, a {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Improved scrollbar for better UX */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #c5c5c5;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a0a0a0;
}
</style>
