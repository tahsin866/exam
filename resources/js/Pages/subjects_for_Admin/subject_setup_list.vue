<template>
  <AuthenticatedLayout>
    <div class="py-12 bg-gray-50">
      <div class=" mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-xl border-t-4 border-gray-300">
          <div class="px-8 py-10">
            <!-- Header -->
            <div class="relative mb-10">
              <div class="absolute inset-0 rounded-lg bg-gray-100 opacity-40"></div>
              <h2 class="relative z-10 text-3xl font-extrabold text-center text-gray-800 tracking-tight py-4">
                মারহালা তথ্য
              </h2>
            </div>
            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                      মারহালা নাম
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                      মোট বিষয়
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                      পুরুষ
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                      মহিলা
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                      উভয়
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                      অ্যাকশন
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                  <tr v-for="marhala in marhalas" :key="marhala.id" class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3 whitespace-nowrap text-center">
                      <span class="font-semibold text-gray-800">{{ marhala.marhala_name_bn }}</span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-center">
                      <span class="font-medium text-gray-700">{{ marhala.total_subjects }}</span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-center">
                      <span class="font-medium text-gray-700">{{ marhala.male_subjects }}</span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-center">
                      <span class="font-medium text-gray-700">{{ marhala.female_subjects }}</span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-center">
                      <span class="font-medium text-gray-700">{{ marhala.both_subjects }}</span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-center">
                      <Link
                        :href="route('subjects_for_Admin.subject_setup', { marhala: marhala.id })"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 disabled:opacity-25 transition"
                      >
                        বিষয় সেটাপ করুন
                      </Link>
                    </td>
                  </tr>
                  <!-- Empty state when no marhalas are available -->
                  <tr v-if="marhalas.length === 0">
                    <td colspan="6" class="px-6 py-6 text-center text-base text-gray-400 font-medium">
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
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'

const marhalas = ref([])

const loadMarhalaStats = async () => {
  try {
    const response = await axios.get('/api/subject-marhala-counts')
    marhalas.value = response.data
  } catch (error) {
    console.error('Error loading marhala stats:', error)
  }
}

onMounted(() => {
  loadMarhalaStats()
})
</script>