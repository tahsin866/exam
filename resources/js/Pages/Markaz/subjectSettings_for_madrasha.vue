<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        পরীক্ষার বিষয় সেটাপ
      </h2>
    </template>

    <div class="py-12">
      <div class="sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div class="overflow-x-auto">
              <table class="w-full border-collapse mb-8">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border border-gray-300 p-3 text-left">মারহালা নাম</th>
                    <th class="border border-gray-300 p-3 text-left">মোট বিষয়</th>
                    <th class="border border-gray-300 p-3 text-left">পুরুষ</th>
                    <th class="border border-gray-300 p-3 text-left">মহিলা</th>
                    <th class="border border-gray-300 p-3 text-left">উভয়</th>
                    <th class="border border-gray-300 p-3 text-left">অ্যাকশন</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="marhala in marhalas" :key="marhala.id" class="hover:bg-gray-50">
                    <td class="border border-gray-300 p-3 font-semibold text-gray-700">{{ marhala.marhala_name_bn }}</td>
                    <td class="border border-gray-300 p-3 text-center font-bold text-gray-700">{{ marhala.total_subjects }}</td>
                    <td class="border border-gray-300 p-3 text-center font-bold text-gray-700">{{ marhala.male_subjects }}</td>
                    <td class="border border-gray-300 p-3 text-center font-bold text-gray-700">{{ marhala.female_subjects }}</td>
                    <td class="border border-gray-300 p-3 text-center font-bold text-gray-700">{{ marhala.both_subjects }}</td>
                    <td class="border border-gray-300 p-3">
                      <div class="flex justify-center gap-3">
                        <Link
                          :href="route('Markaz.subjectSetting_list', { marhala: marhala.id })"
                          class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                          বিষয় সেটাপ করুন
                        </Link>
                      </div>
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
