<template>
  <AuthenticatedLayout>
  <div class="p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">ছাত্র তথ্য ব্যবস্থাপনা</h2>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="md:col-span-5">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
              </svg>
            </div>
            <input
              type="text"
              class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
              placeholder="গ্লোবাল সার্চ..."
              v-model="filters.globalSearch"
              @input="applyFilters"
            />
          </div>
        </div>

        <div class="md:col-span-3">
          <div class="flex">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
              বছর
            </span>
            <select
              class="block w-full rounded-r-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
              v-model="filters.year"
              @change="applyFilters"
            >
              <option value="">সকল বছর</option>
              <option v-for="year in yearOptions" :key="year" :value="year">{{ year }}</option>
            </select>
          </div>
        </div>

        <div class="md:col-span-3">
          <div class="flex">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
              শ্রেণী
            </span>
            <select
              class="block w-full rounded-r-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
              v-model="filters.class"
              @change="applyFilters"
            >
              <option value="">সকল শ্রেণী</option>
              <option v-for="className in classOptions" :key="className" :value="className">{{ className }}</option>
            </select>
          </div>
        </div>

        <!-- শিক্ষার্থী টাইপ ফিল্টার -->
        <div class="md:col-span-2">
          <div class="flex">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
              শিক্ষার্থী টাইপ
            </span>
            <select
              class="block w-full rounded-r-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
              v-model="filters.srtype"
              @change="applyFilters"
            >
              <option value="">সব</option>
              <option v-for="type in srTypeOptions" :key="type" :value="type">
                {{ type == 1 ? 'ছাত্র' : (type == 0 ? 'ছাত্রী' : type) }}
              </option>
            </select>
          </div>
        </div>

        <div class="md:col-span-1">
          <button
            class="w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
            @click="resetFilters"
          >
            রিসেট
          </button>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow">
      <div class="p-4 flex flex-col sm:flex-row justify-between items-center border-b">
        <div class="text-sm text-gray-500 mb-2 sm:mb-0">
          দেখানো হচ্ছে {{ pagination.from || 0 }} থেকে {{ pagination.to || 0 }} (মোট {{ pagination.total || 0 }})
        </div>
        <div class="flex items-center">
          <span class="mr-2 text-sm text-gray-600">দেখানো</span>
          <select
            class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
            v-model="itemsPerPage"
            @change="changeItemsPerPage"
          >
            <option v-for="size in [10, 25, 50, 100]" :key="size" :value="size">{{ size }}</option>
          </select>
          <span class="ml-2 text-sm text-gray-600">এন্ট্রি</span>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                @click="sortBy('Roll')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
              >
                <div class="flex items-center">
                  রোল
                  <svg v-if="sortConfig.key === 'Roll'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'asc'" />
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'desc'" />
                  </svg>
                </div>
              </th>
              <th
                @click="sortBy('Name')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
              >
                <div class="flex items-center">
                  নাম
                  <svg v-if="sortConfig.key === 'Name'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'asc'" />
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'desc'" />
                  </svg>
                </div>
              </th>
              <th
                @click="sortBy('Father')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
              >
                <div class="flex items-center">
                  পিতার নাম
                  <svg v-if="sortConfig.key === 'Father'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'asc'" />
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'desc'" />
                  </svg>
                </div>
              </th>
              <th
                @click="sortBy('DateofBirth')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
              >
                <div class="flex items-center">
                  জন্ম তারিখ
                  <svg v-if="sortConfig.key === 'DateofBirth'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'asc'" />
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'desc'" />
                  </svg>
                </div>
              </th>
              <th
                @click="sortBy('Madrasha')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
              >
                <div class="flex items-center">
                  মাদ্রাসা নাম
                  <svg v-if="sortConfig.key === 'Madrasha'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'asc'" />
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'desc'" />
                  </svg>
                </div>
              </th>
              <th
                @click="sortBy('Class')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
              >
                <div class="flex items-center">
                  শ্রেণী
                  <svg v-if="sortConfig.key === 'Class'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'asc'" />
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" v-if="sortConfig.direction === 'desc'" />
                  </svg>
                </div>
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                অ্যাকশন
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="student in students.data" :key="student.IDs" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">{{ student.Roll }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ student.Name }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ student.Father }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(student.DateofBirth) }}</td>
              <td class="px-6 py-4">{{ student.Madrasha }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ student.Class }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex space-x-2">
                  <button @click="viewStudent(student)" class="text-indigo-600 hover:text-indigo-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                      <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <button @click="editStudent(student)" class="text-blue-600 hover:text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                  </button>
                  <button @click="confirmDelete(student)" class="text-red-600 hover:text-red-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!students.data || students.data.length === 0">
              <td colspan="7" class="px-6 py-10 text-center">
                <div class="flex flex-col items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                  <h3 class="text-lg font-medium text-gray-900">কোন ছাত্র পাওয়া যায়নি</h3>
                  <p class="mt-1 text-sm text-gray-500">আপনার অনুসন্ধান বা ফিল্টার মানদণ্ড সামঞ্জস্য করুন</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-4 py-3 border-t border-gray-200 flex flex-col md:flex-row items-center justify-between">
        <div class="text-sm text-gray-700 mb-4 md:mb-0">
          পৃষ্ঠা {{ pagination.current_page || 1 }} / {{ pagination.last_page || 1 }}
        </div>
        <div>
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <button
              @click="changePage(1)"
              :disabled="!pagination.prev_page_url"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium"
              :class="!pagination.prev_page_url ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50'"
            >
              <span class="sr-only">First</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <button
              @click="changePage(pagination.current_page - 1)"
              :disabled="!pagination.prev_page_url"
              class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium"
              :class="!pagination.prev_page_url ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50'"
            >
              <span class="sr-only">Previous</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </button>

            <!-- Page numbers -->
            <template v-for="page in displayedPages" :key="page">
              <button
                @click="changePage(page)"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium"
                :class="pagination.current_page === page ? 'bg-indigo-50 border-indigo-500 text-indigo-600 z-10' : 'bg-white text-gray-700 hover:bg-gray-50'"
              >
                {{ page }}
              </button>
            </template>

            <button
              @click="changePage(pagination.current_page + 1)"
              :disabled="!pagination.next_page_url"
              class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium"
              :class="!pagination.next_page_url ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50'"
            >
              <span class="sr-only">Next</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </button>
            <button
              @click="changePage(pagination.last_page)"
              :disabled="!pagination.next_page_url"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium"
              :class="!pagination.next_page_url ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50'"
            >
              <span class="sr-only">Last</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </button>
          </nav>
        </div>
      </div>
    </div>
  </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';

// Define state
const students = ref({
  data: [],
  links: {},
  meta: {}
});

const filters = ref({
  globalSearch: '',
  year: '',
  class: ''
});

const isLoading = ref(false);
const itemsPerPage = ref(10);

const sortConfig = ref({
  key: 'Roll',
  direction: 'asc'
});

// REMOVE THESE TWO LINES - they're duplicated in the computed properties below
// const yearOptions = ref([]);
// const classOptions = ref([]);

const currentPage = ref(1);

const pagination = ref({
  current_page: 1,
  from: 0,
  last_page: 1,
  links: [],
  next_page_url: null,
  path: '',
  per_page: 10,
  prev_page_url: null,
  to: 0,
  total: 0
});

// Sample data for initial development and testing
const sampleData = [
  {
    "Roll": 18,
    "Name": "‡gv: Avey ûivqiv",
    "Madrasha": "†kL RbyiæÏxb (it) `viæj KziAvb gv`ivmv, 8bs ct †PŠayixcvov, wW.AvB.wU †ivW, wLjMuvI, XvKv, 1/54",
    "MID": "1135",
    "Class": "wndhyj KziAvb",
    "SubLabel_1": "wndh (Bqv`)",
    "SubValue_1": 89,
    "SubLabel_2": "ZvRwe`",
    "SubValue_2": 50,
    "SubLabel_3": "`xwbqvZ (ˆgvwLK)",
    "SubValue_3": 50,
    "SubLabel_4": "",
    "SubValue_4": 0,
    "SubLabel_5": "",
    "SubValue_5": 0,
    "SubLabel_6": "",
    "SubValue_6": 0,
    "SubLabel_7": "",
    "SubValue_7": 0,
    "SubLabel_8": "",
    "SubValue_8": 0,
    "SubLabel_9": "",
    "SubValue_9": 0,
    "SubLabel_10": "",
    "SubValue_10": 0,
    "SubLabel_11": "",
    "SubValue_11": 0,
    "Total": 189,
    "GraceLabel": "0",
    "GraceValue": 0,
    "Positions": 0,
    "Division": "gygZvh",
    "Markaj": "†kL RbyiæÏxb (it) `viæj KziAvb gv`ivmv, 8bs ct †PŠayixcvov, wW.AvB.wU †ivW, wLjMuvI, XvKv, 1/54",
    "MarID": 1135,
    "SRType": 1,
    "MElhaq": "1/54",
    "CID": 7,
    "Father": "‡gv: b~iæb bex",
    "DateofBirth": "2003-10-31",
    "Absence": "",
    "PosSub": null,
    "IDs": 209738,
    "years": 2019,
    "reg_id": 13,
    "type": "ছাত্র",
    "NumberToWord": null,
    "id": 0
  }
];

// Computed properties
const yearOptions = ref([]);
const classOptions = ref([]);

const displayedPages = computed(() => {
  if (!pagination.value) return [];

  const totalPages = pagination.value.last_page;
  const currentPage = pagination.value.current_page;
  const maxDisplayPages = 5;

  let startPage = Math.max(1, currentPage - Math.floor(maxDisplayPages / 2));
  let endPage = Math.min(totalPages, startPage + maxDisplayPages - 1);

  if (endPage - startPage < maxDisplayPages - 1) {
    startPage = Math.max(1, endPage - maxDisplayPages + 1);
  }

  const pages = [];
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }

  return pages;
});

// Methods
const fetchStudents = async () => {
  isLoading.value = true;
  try {
    // Build query parameters
    const params = new URLSearchParams();
    if (filters.value.globalSearch) params.append('search', filters.value.globalSearch);
    if (filters.value.year) params.append('year', filters.value.year);
    if (filters.value.class) params.append('class', filters.value.class);
    if (sortConfig.value.key) {
      params.append('sortBy', sortConfig.value.key);
      params.append('sortDirection', sortConfig.value.direction);
    }
    params.append('per_page', itemsPerPage.value);
    params.append('page', currentPage.value);

    // Make API call to your Laravel controller
    const response = await fetch(`/getStudentList?${params.toString()}`);
    const data = await response.json();

    // students, years, classes
    students.value = data.students;
    yearOptions.value = data.years;
    classOptions.value = data.classes;
    pagination.value = {
      current_page: data.students.current_page,
      from: data.students.from,
      last_page: data.students.last_page,
      links: data.students.links,
      next_page_url: data.students.next_page_url,
      path: data.students.path,
      per_page: data.students.per_page,
      prev_page_url: data.students.prev_page_url,
      to: data.students.to,
      total: data.students.total
    };
  } catch (error) {
    console.error('Error fetching students:', error);
    // You can add toast notification here
  } finally {
    isLoading.value = false;
  }
};

const sortBy = (key) => {
  if (sortConfig.value.key === key) {
    sortConfig.value.direction = sortConfig.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    sortConfig.value.key = key;
    sortConfig.value.direction = 'asc';
  }
  fetchStudents();
};

const applyFilters = () => {
  fetchStudents();
};

const resetFilters = () => {
  filters.value = {
    globalSearch: '',
    year: '',
    class: ''
  };
  fetchStudents();
};

const changePage = (page) => {
  if (page < 1 || (pagination.value && page > pagination.value.last_page)) return;

  const params = new URLSearchParams();
  params.append('page', page);
  if (filters.value.globalSearch) params.append('search', filters.value.globalSearch);
  if (filters.value.year) params.append('year', filters.value.year);
  if (filters.value.class) params.append('class', filters.value.class);
  if (sortConfig.value.key) {
    params.append('sortBy', sortConfig.value.key);
    params.append('sortDirection', sortConfig.value.direction);
  }
  params.append('perPage', itemsPerPage.value);

  const url = `/api/students?${params.toString()}`;
  axios.get(url)
    .then(response => {
      students.value = response.data;

      // Update pagination
      pagination.value = {
        current_page: response.data.current_page,
        from: response.data.from,
        last_page: response.data.last_page,
        links: response.data.links,
        next_page_url: response.data.next_page_url,
        path: response.data.path,
        per_page: response.data.per_page,
        prev_page_url: response.data.prev_page_url,
        to: response.data.to,
        total: response.data.total
      };
    })
    .catch(error => {
      console.error('Error changing page:', error);

      // For development/testing without backend
      if (process.env.NODE_ENV === 'development') {
        // Mock changing page
        const mockPage = {
          data: Array(10).fill().map((_, i) => ({
            ...sampleData[0],
            IDs: 209738 + (page - 1) * 10 + i,
            Roll: 18 + (page - 1) * 10 + i,
            Name: `ছাত্র ${(page - 1) * 10 + i + 1}`,
            Father: `পিতা ${(page - 1) * 10 + i + 1}`,
            years: 2019 + (i % 3)
          })),
          current_page: page,
          from: (page - 1) * 10 + 1,
          last_page: 5,
          links: [],
          next_page_url: page < 5 ? `/api/students?page=${page + 1}` : null,
          path: '/api/students',
          per_page: 10,
          prev_page_url: page > 1 ? `/api/students?page=${page - 1}` : null,
          to: Math.min(page * 10, 50),
          total: 50
        };

        students.value = mockPage;
        pagination.value = {
          current_page: mockPage.current_page,
          from: mockPage.from,
          last_page: mockPage.last_page,
          links: mockPage.links,
          next_page_url: mockPage.next_page_url,
          path: mockPage.path,
          per_page: mockPage.per_page,
          prev_page_url: mockPage.prev_page_url,
          to: mockPage.to,
          total: mockPage.total
        };
      }
    });
};

const changeItemsPerPage = () => {
  fetchStudents();
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);

  // Format with Intl API if available for Bangla
  try {
    return new Intl.DateTimeFormat('bn-BD', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    }).format(date);
  } catch (e) {
    // Fallback format
    return date.toLocaleDateString();
  }
};

// Action handlers
const viewStudent = (student) => {
  console.log('View student:', student);
  // Implement view functionality
  alert(`বিস্তারিত দেখুন: ${student.Name}`);
};

const editStudent = (student) => {
  console.log('Edit student:', student);
  // Implement edit functionality
  alert(`সম্পাদনা করুন: ${student.Name}`);
};

const confirmDelete = (student) => {
  if (confirm(`আপনি কি নিশ্চিত ${student.Name} মুছতে চান?`)) {
    // Implement delete functionality
    console.log('Delete student:', student);
    alert(`${student.Name} মুছে ফেলা হয়েছে`);
  }
};

// Watch for changes in filters or itemsPerPage
watch([filters, itemsPerPage], () => {
  applyFilters();
}, { deep: true });

// Initialize on component mount
onMounted(() => {
  fetchStudents();
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap');

/* Apply Bangla font to specific elements if needed */
.student-table {
  font-family: 'Noto Sans Bengali', sans-serif;
}

/* Override any default font in Tailwind as needed */
html {
  font-family: 'Noto Sans Bengali', sans-serif;
}
</style>
