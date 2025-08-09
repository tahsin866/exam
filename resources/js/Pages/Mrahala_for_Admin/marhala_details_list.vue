<template>
  <AuthenticatedLayout>
    <div class="py-12 min-h-screen bg-gray-50">
      <div class=" mx-auto px-4 sm:px-8">
        <div class="bg-white shadow-lg rounded-2xl border border-gray-200">
          <div class="px-10 py-10">

            <!-- Header & Stats -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
              <div>
                <h2 class="text-3xl font-extrabold text-gray-900 flex items-center gap-3">
                  <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                  </span>
                  মারহালা ব্যবস্থাপনা
                </h2>
                <p class="text-gray-500 mt-2 text-base font-medium">
                  সকল মারহালার বিষয় এবং পাঠ্যক্রম সংক্রান্ত তথ্য
                </p>
              </div>
              <div class="flex items-center gap-4">
                <div v-if="marhalas.length > 0" class="flex gap-4">
                  <div class="text-center px-5 py-3 rounded-lg shadow-sm border border-gray-100 bg-gray-50">
                    <div class="text-2xl font-bold text-gray-800">{{ marhalas.length }}</div>
                    <div class="text-xs text-gray-500">মোট মারহালা</div>
                  </div>
                  <div class="text-center px-5 py-3 rounded-lg shadow-sm border border-gray-100 bg-gray-50">
                    <div class="text-2xl font-bold text-gray-800">{{ getTotalSubjects() }}</div>
                    <div class="text-xs text-gray-500">মোট বিষয়</div>
                  </div>
                </div>
                <button
                  @click="addNewMarhala"
                  class="inline-flex items-center px-5 py-2 border border-gray-400 rounded-md font-semibold text-base text-gray-800 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 transition shadow-sm"
                >
                  <svg class="w-5 h-5 mr-2 -ml-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  নতুন মারহালা যোগ করুন
                </button>
              </div>
            </div>

            <!-- Mobile Card View -->
            <div class="grid grid-cols-1 gap-4 md:hidden">
              <div
                v-for="marhala in marhalas"
                :key="marhala.id"
                class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition"
              >
                <div class="border-b border-gray-100 bg-gray-50 px-4 py-3">
                  <h3 class="font-semibold text-gray-900">{{ marhala.marhala_name_bn }}</h3>
                </div>
                <div class="p-4 space-y-3">
                  <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium">মোট বিষয়:</span>
                    <span class="text-xs font-semibold text-gray-900">{{ marhala.total_subjects }}</span>
                  </div>
                  <div class="grid grid-cols-3 gap-2 border-t border-gray-100 pt-3">
                    <div class="text-center p-2 bg-gray-100 rounded">
                      <div class="text-xxs text-gray-500">পুরুষ</div>
                      <div class="font-semibold text-gray-700">{{ marhala.male_subjects }}</div>
                    </div>
                    <div class="text-center p-2 bg-gray-100 rounded">
                      <div class="text-xxs text-gray-500">মহিলা</div>
                      <div class="font-semibold text-gray-700">{{ marhala.female_subjects }}</div>
                    </div>
                    <div class="text-center p-2 bg-gray-100 rounded">
                      <div class="text-xxs text-gray-500">উভয়</div>
                      <div class="font-semibold text-gray-700">{{ marhala.both_subjects }}</div>
                    </div>
                  </div>
                  <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <button
                      @click="navigateToSetupList(marhala.id)"
                      class="inline-flex items-center px-3 py-1.5 bg-gray-900 border border-gray-800 rounded-md text-xs text-white font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400 transition"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                      নতুন যোগ করুন
                    </button>
                    <button
                      @click="editMarhala(marhala.id)"
                      class="inline-flex items-center px-2 py-1.5 bg-white border border-gray-300 rounded-md text-xs text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 transition"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
              <div v-if="marhalas.length === 0" class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-500">কোন মারহালা তথ্য নেই</h3>
                <p class="mt-1 text-xs text-gray-400">নতুন মারহালা যোগ করতে উপরের বাটনে ক্লিক করুন</p>
              </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200 shadow-sm mt-2">
              <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                      মারহালা নাম
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                      মোট বিষয়
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                      পুরুষ
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                      মহিলা
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                      উভয়
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                      অ্যাকশন
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                  <tr v-for="marhala in marhalas" :key="marhala.id" class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-700 mr-3 flex-shrink-0 font-bold text-lg">
                          {{ marhala.marhala_name_bn.charAt(0) }}
                        </div>
                        <div>
                          <div class="text-base font-semibold text-gray-800">{{ marhala.marhala_name_bn }}</div>
                          <div class="text-xs text-gray-400">ID: {{ marhala.id }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <div class="text-base font-semibold text-gray-900">{{ marhala.total_subjects }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full font-medium text-xs">
                        {{ marhala.male_subjects }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full font-medium text-xs">
                        {{ marhala.female_subjects }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full font-medium text-xs">
                        {{ marhala.both_subjects }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <div class="flex justify-center gap-2">
                        <button
                          @click="navigateToSetupList(marhala.id)"
                          class="inline-flex items-center px-3 py-1.5 bg-gray-900 border border-gray-800 rounded-md text-xs text-white font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400 transition"
                          title="নতুন বিষয় যোগ করুন"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                          </svg>
                          <span>নতুন বিষয়</span>
                        </button>
                        <button
                          @click="editMarhala(marhala.id)"
                          class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-xs text-gray-700 font-medium hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 transition"
                          title="মারহালা সম্পাদনা করুন"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                          <span>সম্পাদনা</span>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="marhalas.length === 0">
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-base">
                      <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                      </svg>
                      <h3 class="mt-2 font-medium">কোন মারহালা তথ্য নেই</h3>
                      <p class="mt-1 text-xs">নতুন মারহালা যোগ করতে উপরের বাটনে ক্লিক করুন</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Progress Visualization -->
            <div v-if="marhalas.length > 0" class="mt-12 bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
              <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                বিষয় বিতরণ ভিজুয়ালাইজেশন
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="(marhala, index) in marhalas" :key="index" class="bg-gray-50 rounded-lg p-5 border border-gray-100">
                  <div class="flex justify-between items-center mb-2">
                    <h4 class="font-semibold text-gray-800">{{ marhala.marhala_name_bn }}</h4>
                    <span class="text-xs text-gray-500">{{ marhala.total_subjects }} বিষয়</span>
                  </div>
                  <div class="h-6 bg-gray-200 rounded-full overflow-hidden">
                    <div class="flex h-full">
                      <div
                        v-if="marhala.total_subjects > 0"
                        class="h-full bg-gray-600"
                        :style="`width: ${(marhala.male_subjects / marhala.total_subjects) * 100}%`"
                        :title="`পুরুষ: ${marhala.male_subjects}`">
                      </div>
                      <div
                        v-if="marhala.total_subjects > 0"
                        class="h-full bg-gray-400"
                        :style="`width: ${(marhala.female_subjects / marhala.total_subjects) * 100}%`"
                        :title="`মহিলা: ${marhala.female_subjects}`">
                      </div>
                      <div
                        v-if="marhala.total_subjects > 0"
                        class="h-full bg-gray-300"
                        :style="`width: ${(marhala.both_subjects / marhala.total_subjects) * 100}%`"
                        :title="`উভয়: ${marhala.both_subjects}`">
                      </div>
                    </div>
                  </div>
                  <div class="flex justify-between text-xs mt-2">
                    <div class="flex items-center gap-1">
                      <span class="inline-block w-3 h-3 bg-gray-600 rounded-full"></span>
                      <span>পুরুষ: {{ marhala.male_subjects }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                      <span class="inline-block w-3 h-3 bg-gray-400 rounded-full"></span>
                      <span>মহিলা: {{ marhala.female_subjects }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                      <span class="inline-block w-3 h-3 bg-gray-300 rounded-full"></span>
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
.font-arabic {
  font-family: 'Amiri', 'Scheherazade New', serif;
}
</style>