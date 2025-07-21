<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-indigo-600">
          <div class="p-6">
            <!-- Header -->
            <div class="relative mb-8">
              <div class="absolute inset-0 bg-gray-100 opacity-25 pattern-dots"></div>
              <h2 class="text-2xl font-bold text-center text-gray-800 py-4 relative z-10">মারহালা তথ্য</h2>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      মারহালা নাম
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      মোট বিষয়
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      পুরুষ
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      মহিলা
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      উভয়
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                      অ্যাকশন
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="marhala in marhalas" :key="marhala.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span class="font-semibold text-gray-700">{{ marhala.marhala_name_bn }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span class="font-bold text-gray-700">{{ marhala.total_subjects }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span class="font-bold text-gray-700">{{ marhala.male_subjects }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span class="font-bold text-gray-700">{{ marhala.female_subjects }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span class="font-bold text-gray-700">{{ marhala.both_subjects }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <Link
                        :href="route('subjects_for_Admin.subject_setup', { marhala: marhala.id })"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150"
                      >
                        বিষয় সেটাপ করুন
                      </Link>
                    </td>
                  </tr>
                  <!-- Empty state when no marhalas are available -->
                  <tr v-if="marhalas.length === 0">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      কোন মারহালা তথ্য পাওয়া যায়নি
                    </td>
                  </tr>
                </tbody>
              </table>
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
import { Link } from '@inertiajs/vue3';

const marhalas = ref([]);

const loadMarhalaStats = async () => {
  try {
    const response = await axios.get('/api/subject-marhala-counts');
    marhalas.value = response.data;
  } catch (error) {
    console.error('Error loading marhala stats:', error);
  }
};

onMounted(() => {
  loadMarhalaStats();
});
</script>

<style scoped>
/* Simple dot pattern for the header background */

</style>
