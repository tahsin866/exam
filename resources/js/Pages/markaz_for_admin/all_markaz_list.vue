<template>
  <AuthenticatedLayout>
    <div class="py-6 bg-gray-50 ">
      <div class=" mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6">
          <h1 class="text-2xl font-bold text-gray-900">সকল মারকাযের তালিকা</h1>
          <p class="mt-1 text-sm text-gray-600">সকল মারকায এবং তাদের সম্পর্কিত তথ্য</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex items-center">
              <div class="p-2 bg-blue-100 rounded-md mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <div>
                <h2 class="text-sm font-medium text-gray-500">মোট মারকায</h2>
                <p class="text-lg font-semibold text-gray-900">{{ applications.data?.length || 0 }}</p>
              </div>
            </div>
          </div>
          <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex items-center">
              <div class="p-2 bg-green-100 rounded-md mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <h2 class="text-sm font-medium text-gray-500">সক্রিয় মারকায</h2>
                <p class="text-lg font-semibold text-gray-900">{{ getActiveCount() }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Search & Filter Panel -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
          <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
            <div class="flex justify-between items-center">
              <h2 class="text-base font-medium text-gray-700">অনুসন্ধান ফিল্টার</h2>
              <div class="flex space-x-2">
                <button @click="resetFilters" class="px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 border border-gray-300 rounded-md">
                  রিসেট
                </button>
                <button @click="exportData" class="px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 border border-gray-300 rounded-md">
                  এক্সপোর্ট
                </button>
              </div>
            </div>
          </div>

          <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <!-- Name Search -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">মারকাযের নাম</label>
                <input type="text" v-model="searchFilters.madrasahName" placeholder="মারকাযের নাম লিখুন..." 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
              </div>

              <!-- Type -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">মাদ্রাসার ধরন</label>
                <select v-model="searchFilters.type" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                  <option value="">সকল</option>
                  <option value="ছাত্র">ছাত্র</option>
                  <option value="ছাত্রী">ছাত্রী</option>
                </select>
              </div>

              <!-- Level -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">মাদরাসার স্তর</label>
                <select v-model="searchFilters.level" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                  <option value="">সকল</option>
                  <option value="takmil">তাকমিল</option>
                  <option value="fazilat">ফযিলত</option>
                  <option value="sanabiya_uliya">সানাবিয়া উলইয়া</option>
                  <option value="sanabiya">সানাবিয়া</option>
                  <option value="mutawassita">মুতাওয়াসসিতা</option>
                  <option value="ibtedaiya">ইবতেদাইয়া</option>
                  <option value="hifzul_quran">হিফজুল কোরআন</option>
                  <option value="ilmul_qiraat">ইলমুল কিরাআত</option>
                </select>
              </div>

              <!-- Markaz Type -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">মারকাযের ধরন</label>
                <select v-model="searchFilters.status" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                  <option value="">সকল</option>
                  <option value="darsiyat">দরসিয়াত</option>
                  <option value="hifzul_quran">হিফজুল কুরআন</option>
                  <option value="ilmul_qiraat">ইলমুল কিরাআত</option>
                  <option value="all">সকল মারকাজ</option>
                </select>
              </div>
            </div>

            <!-- Active Filters -->
            <div v-if="hasActiveFilters" class="mt-4 flex flex-wrap gap-2 items-center">
              <span class="text-sm font-medium text-gray-700">সক্রিয় ফিল্টারসমূহ:</span>
              <div v-if="searchFilters.madrasahName" class="px-2 py-1 bg-gray-100 border border-gray-200 rounded-md text-gray-700 text-sm flex items-center">
                নাম: {{ searchFilters.madrasahName }}
                <button @click="searchFilters.madrasahName = ''" class="ml-1 focus:outline-none hover:text-gray-900">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div v-if="searchFilters.type" class="px-2 py-1 bg-gray-100 border border-gray-200 rounded-md text-gray-700 text-sm flex items-center">
                মাদ্রাসার ধরন: {{ searchFilters.type }}
                <button @click="searchFilters.type = ''" class="ml-1 focus:outline-none hover:text-gray-900">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div v-if="searchFilters.level" class="px-2 py-1 bg-gray-100 border border-gray-200 rounded-md text-gray-700 text-sm flex items-center">
                মাদরাসার স্তর: {{ getLevelName(searchFilters.level) }}
                <button @click="searchFilters.level = ''" class="ml-1 focus:outline-none hover:text-gray-900">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div v-if="searchFilters.status" class="px-2 py-1 bg-gray-100 border border-gray-200 rounded-md text-gray-700 text-sm flex items-center">
                মারকাযের ধরন: {{ getMarkazTypeName(searchFilters.status) }}
                <button @click="searchFilters.status = ''" class="ml-1 focus:outline-none hover:text-gray-900">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Search Button -->
            <div class="mt-4 flex justify-end">
              <button @click="search" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                অনুসন্ধান করুন
              </button>
            </div>
          </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
          <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
              <h2 class="text-base font-medium text-gray-700">মারকাযের তালিকা</h2>
              <div class="text-sm text-gray-500">
                মোট উপাত্ত: <span class="font-semibold">{{ applications.data?.length || 0 }}</span>
              </div>
            </div>
          </div>

          <div class="p-0">
            <DataTable :value="applications.data" v-model:filters="filters" filterDisplay="menu" :loading="loading" dataKey="id" 
              paginator :rows="perPage" :totalRecords="applications.total" :first="first" :rowsPerPageOptions="[10, 20, 50, 100]"
              paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" 
              currentPageReportTemplate="দেখানো হচ্ছে {first} থেকে {last} মোট {totalRecords} এর মধ্যে" 
              responsiveLayout="stack" breakpoint="960px" class="p-datatable-sm"
              :scrollable="true" scrollHeight="flex" 
              @page="onPageChange" @rows-per-page-change="onRowsPerPageChange" stripedRows 
              :globalFilterFields="['name', 'Elhaq_no', 'MType', 'id', 'mobile_no', 'division_name', 'district_name', 'thana_name']">
              <template #header>
                <div class="flex justify-between items-center p-2">
                  <span class="p-input-icon-left w-64">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <InputText v-model="filters['global'].value" placeholder="সার্চ করুন..." class="w-full pl-9 py-1 border border-gray-300 rounded-md" />
                  </span>

                  <button @click="showAllColumns = !showAllColumns" class="text-sm text-gray-700 border border-gray-300 px-2 py-1 rounded-md hover:bg-gray-50">
                    {{ showAllColumns ? 'কলাম লুকান' : 'সব কলাম দেখুন' }}
                  </button>

                  <div class="text-sm text-gray-500">
                    <span v-if="loading">লোড হচ্ছে...</span>
                    <span v-else>সর্বশেষ আপডেট: {{ getCurrentDateTime() }}</span>
                  </div>
                </div>
              </template>

              <Column field="id" header="ক্রমিক" sortable style="min-width: 5rem">
                <template #body="{ index }">
                  <div class="font-medium text-gray-900">{{ index + 1 }}</div>
                </template>
              </Column>

              <Column field="name" header="মারকাযের নাম" sortable style="min-width: 14rem">
                <template #body="{ data }">
                  <Link :href="route('markaz_for_admin.madrasha_list_underMarkaz', data.center_id)" class="text-blue-600 hover:text-blue-800 hover:underline">
                    {{ data.name }}
                  </Link>
                </template>
              </Column>

              <Column field="Elhaq_no" header="এলহাক নং" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #body="{ data }">
                  <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md text-xs">
                    {{ data.Elhaq_no || 'N/A' }}
                  </span>
                </template>
              </Column>

              <Column field="MType" header="মাদ্রাসার ধরন" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #body="{ data }">
                  <span :class="['px-2 py-0.5 rounded-md text-xs', data.MType === 'ছাত্র' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700']">
                    {{ data.MType || 'N/A' }}
                  </span>
                </template>
              </Column>

              <Column field="mobile_no" header="মোবাইল নম্বর" sortable style="min-width: 10rem">
                <template #body="{ data }">
                  <a :href="`tel:${data.mobile_no}`" class="text-gray-700 hover:text-blue-600">
                    {{ data.mobile_no || 'N/A' }}
                  </a>
                </template>
              </Column>

              <Column field="division_name" header="বিভাগ" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #filter="{ filterModel }">
                  <Dropdown v-model="filterModel.value" :options="divisions" optionLabel="Division" optionValue="Division" 
                    placeholder="বিভাগ নির্বাচন করুন" class="w-full" @change="handleTableDivisionChange($event, filterModel)" />
                </template>
              </Column>

              <Column field="district_name" header="জেলা" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #filter="{ filterModel }">
                  <Dropdown v-model="filterModel.value" :options="tableFilterDistricts" optionLabel="District" optionValue="District" 
                    placeholder="জেলা নির্বাচন করুন" class="w-full" @change="handleTableDistrictChange($event, filterModel)" />
                </template>
              </Column>

              <Column field="thana_name" header="থানা/উপজেলা" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #filter="{ filterModel }">
                  <Dropdown v-model="filterModel.value" :options="tableFilterThanas" optionLabel="Thana" optionValue="Thana" 
                    placeholder="থানা নির্বাচন করুন" class="w-full" />
                </template>
              </Column>

              <Column header="করনীয়" style="min-width: 10rem">
                <template #body="{ data }">
                  <div class="flex space-x-2">
                    <button @click="viewApplication(data)" class="p-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" title="বিস্তারিত দেখুন">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>

                    <button @click="editApplication(data)" class="p-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" title="সম্পাদনা করুন">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>

                    <button @click="sendMessage(data)" class="p-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" title="বার্তা পাঠান">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                    </button>
                  </div>
                </template>
              </Column>

              <template #empty>
                <div class="text-center py-6">
                  <div class="text-gray-500">কোন তথ্য পাওয়া যায়নি</div>
                  <div class="text-sm text-gray-400 mt-1">আপনার অনুসন্ধান অনুযায়ী কোন মারকায পাওয়া যায়নি।</div>
                </div>
              </template>

              <template #loading>
                <div class="flex justify-center items-center py-6">
                  <div class="w-6 h-6 border-2 border-t-blue-500 border-gray-200 rounded-full animate-spin"></div>
                </div>
              </template>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, reactive, onMounted, computed, watch } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';

const FilterMatchMode = {
  STARTS_WITH: 'startswith',
  CONTAINS: 'contains',
  EQUALS: 'equals',
  DATE_IS: 'dateIs'
};

const props = defineProps({
  initialApplications: Object,
  initialDivisions: Array,
  initialDistricts: Array,
  initialThanas: Array,
});

const applications = ref(props.initialApplications || {
  data: [],
  current_page: 1,
  last_page: 1,
  total: 0,
  from: 0,
  to: 0
});
const loading = ref(false);
const perPage = ref(10);
const first = ref(0);
const divisions = ref(props.initialDivisions || []);
const districts = ref(props.initialDistricts || []);
const thanas = ref(props.initialThanas || []);
const showAllColumns = ref(true);

const tableFilterDistricts = ref([]);
const tableFilterThanas = ref([]);
const selectedTableDivision = ref(null);
const selectedTableDistrict = ref(null);

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  name: { value: null, matchMode: FilterMatchMode.CONTAINS },
  Elhaq_no: { value: null, matchMode: FilterMatchMode.CONTAINS },
  MType: { value: null, matchMode: FilterMatchMode.CONTAINS },
  id: { value: null, matchMode: FilterMatchMode.CONTAINS },
  mobile_no: { value: null, matchMode: FilterMatchMode.CONTAINS },
  division_name: { value: null, matchMode: FilterMatchMode.EQUALS },
  district_name: { value: null, matchMode: FilterMatchMode.EQUALS },
  thana_name: { value: null, matchMode: FilterMatchMode.EQUALS },
});

const searchFilters = reactive({
  madrasahName: '',
  type: '',
  level: '',
  status: '',
  division: '',
  district: '',
  thana: ''
});

const hasActiveFilters = computed(() => {
  return Object.values(searchFilters).some(value => value !== '');
});

const getActiveCount = () => {
  return applications.value?.data?.length || 0;
};

const getCurrentDateTime = () => {
  return new Date().toLocaleString('bn-BD', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const getLevelName = (level) => {
  const levelMap = {
    'takmil': 'তাকমিল',
    'fazilat': 'ফযিলত',
    'sanabiya_uliya': 'সানাবিয়া উলইয়া',
    'sanabiya': 'সানাবিয়া',
    'mutawassita': 'মুতাওয়াসসিতা',
    'ibtedaiya': 'ইবতেদাইয়া',
    'hifzul_quran': 'হিফজুল কোরআন',
    'ilmul_qiraat': 'ইলমুল কিরাআত'
  };
  return levelMap[level] || level;
};

const getMarkazTypeName = (type) => {
  const typeMap = {
    'darsiyat': 'দরসিয়াত',
    'hifzul_quran': 'হিফজুল কুরআন',
    'ilmul_qiraat': 'ইলমুল কিরাআত',
    'all': 'সকল মারকাজ'
  };
  return typeMap[type] || type;
};

const loadDivisions = async () => {
  try {
    const response = await axios.get('/api/divisions');
    divisions.value = response.data;
  } catch (error) {
    console.error('Error loading divisions:', error);
  }
};

const handleDivisionChange = async () => {
  searchFilters.district = '';
  searchFilters.thana = '';
  districts.value = [];
  thanas.value = [];
  if (searchFilters.division) {
    try {
      const response = await axios.get(`/api/districts/${searchFilters.division}`);
      districts.value = response.data;
    } catch (error) {
      console.error('Error loading districts:', error);
    }
  }
};

const handleDistrictChange = async () => {
  searchFilters.thana = '';
  thanas.value = [];
  if (searchFilters.district) {
    try {
      const response = await axios.get(`/api/thanas/${searchFilters.district}`);
      thanas.value = response.data;
    } catch (error) {
      console.error('Error loading thanas:', error);
    }
  }
};

const handleTableDivisionChange = async (event, filterModel) => {
  selectedTableDivision.value = event.value;
  tableFilterDistricts.value = [];
  tableFilterThanas.value = [];

  if (event.value) {
    try {
      const division = divisions.value.find(d => d.Division === event.value);
      if (division) {
        const response = await axios.get(`/api/districts/${division.id}`);
        tableFilterDistricts.value = response.data;
      }
    } catch (error) {
      console.error('Error loading districts for table filter:', error);
    }
  }

  filterModel.value = event.value;
};

const handleTableDistrictChange = async (event, filterModel) => {
  selectedTableDistrict.value = event.value;
  tableFilterThanas.value = [];

  if (event.value) {
    try {
      const district = tableFilterDistricts.value.find(d => d.District === event.value);
      if (district) {
        const response = await axios.get(`/api/thanas/${district.DesID}`);
        tableFilterThanas.value = response.data;
      }
    } catch (error) {
      console.error('Error loading thanas for table filter:', error);
    }
  }

  filterModel.value = event.value;
};

const fetchMadrasaData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/markaz-list', {
      params: {
        page: Math.floor(first.value / perPage.value) + 1,
        per_page: perPage.value,
      }
    });

    let formattedData = response.data.data || [];
    formattedData = formattedData.map(item => ({
      id: item.center_id || item.id,
      center_id: item.center_id || item.id,
      name: item.name || item.MName,
      Elhaq_no: item.Elhaq_no || item.ElhaqNo,
      MType: item.MType,
      mobile_no: item.mobile_no || item.Mobile || item.MobileNo,
      division_name: item.division_name,
      district_name: item.district_name,
      thana_name: item.thana_name,
    }));

    applications.value = {
      ...response.data,
      data: formattedData
    };

    first.value = ((response.data.current_page || 1) - 1) * perPage.value;
  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    loading.value = false;
  }
};

const onPageChange = (event) => {
  first.value = event.first;
  perPage.value = event.rows;
  fetchMadrasaData();
};

const onRowsPerPageChange = (event) => {
  perPage.value = event.value || event;
  first.value = 0;
  fetchMadrasaData();
};

watch(() => props.initialApplications, (newVal) => {
  applications.value = newVal;
  first.value = ((applications.value.current_page || 1) - 1) * perPage.value;
});

watch(perPage, () => {
  first.value = 0;
  fetchMadrasaData();
});

const search = async () => {
  loading.value = true;
  
  if (searchFilters.madrasahName) {
    searchFilters.madrasahName = searchFilters.madrasahName.trim();
  }
  
  try {
    const response = await axios.get('/api/markaz-list', {
      params: {
        ...searchFilters,
        page: Math.floor(first.value / perPage.value) + 1,
        per_page: perPage.value,
      }
    });
    
    let formattedData = response.data.data || response.data;
    formattedData = formattedData.map(item => ({
      id: item.center_id || item.id,
      center_id: item.center_id || item.id,
      name: item.name || item.MName,
      Elhaq_no: item.Elhaq_no || item.ElhaqNo,
      MType: item.MType,
      mobile_no: item.mobile_no || item.Mobile || item.MobileNo,
      division_name: item.division_name,
      district_name: item.district_name,
      thana_name: item.thana_name,
    }));
    
    applications.value = {
      ...response.data,
      data: formattedData
    };
    
    first.value = ((response.data.current_page || 1) - 1) * perPage.value;
  } catch (error) {
    console.error('Error searching markaz:', error);
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  Object.keys(searchFilters).forEach(key => {
    searchFilters[key] = '';
  });
  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: { value: null, matchMode: FilterMatchMode.CONTAINS },
    Elhaq_no: { value: null, matchMode: FilterMatchMode.CONTAINS },
    MType: { value: null, matchMode: FilterMatchMode.CONTAINS },
    id: { value: null, matchMode: FilterMatchMode.CONTAINS },
    mobile_no: { value: null, matchMode: FilterMatchMode.CONTAINS },
    division_name: { value: null, matchMode: FilterMatchMode.EQUALS },
    district_name: { value: null, matchMode: FilterMatchMode.EQUALS },
    thana_name: { value: null, matchMode: FilterMatchMode.EQUALS },
  };
  districts.value = [];
  thanas.value = [];
  tableFilterDistricts.value = [];
  tableFilterThanas.value = [];
  selectedTableDivision.value = null;
  selectedTableDistrict.value = null;
  perPage.value = 10;
  first.value = 0;
  fetchMadrasaData();
};

const exportData = async () => {
  try {
    const response = await axios.get('/api/export-madrasa', {
      params: searchFilters,
      responseType: 'blob'
    });
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'madrasa-list.xlsx');
    document.body.appendChild(link);
    link.click();
    link.remove();
  } catch (error) {
    console.error('Error exporting data:', error);
  }
};

const viewApplication = async (application) => {
  try {
    await axios.get(`/api/madrasahs/${application.id}`);
  } catch (error) {
    console.log('Error viewing application:', error);
  }
};

const editApplication = async (application) => {
  try {
    await axios.get(`/api/madrasahs/${application.id}/edit`);
  } catch (error) {
    console.log('Error editing application:', error);
  }
};

const sendMessage = async (application) => {
  alert(`${application.name} কে বার্তা পাঠানো হচ্ছে...`);
};

onMounted(() => {
  loadDivisions();
  fetchMadrasaData();
});
</script>