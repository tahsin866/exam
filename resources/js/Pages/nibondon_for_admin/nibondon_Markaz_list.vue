<template>
  <AuthenticatedLayout>
 <div class="py-12">
  <div class="sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6">
        <!-- Header Section -->
        <div class="mb-6">
          <h1 class="text-2xl font-bold text-gray-800">
            সকল মারকাযের নিবন্ধন তালিকা
          </h1>
          <p class="text-gray-600">কেন্দ্রীয় মাদ্রাসা আবেদন ব্যবস্থাপনা সিস্টেম</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Global Search -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">সার্চ করুন</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <i class="fas fa-search text-gray-400"></i>
                </span>
                <input
                  v-model="globalSearchTerm"
                  type="text"
                  placeholder="মাদ্রাসার নাম, ইলহাক নং, কোড ইত্যাদি..."
                  class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-200"
                />
              </div>
            </div>

            <!-- Location Filters -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">অবস্থান</label>
              <div class="grid grid-cols-3 gap-2">
                <Dropdown
                  v-model="selectedDivision"
                  :options="divisionOptions"
                  optionLabel="Division"
                  placeholder="বিভাগ"
                  class="w-full"
                  @change="handleDivisionChange"
                />
                <Dropdown
                  v-model="selectedDistrict"
                  :options="filteredDistricts"
                  optionLabel="District"
                  placeholder="জেলা"
                  class="w-full"
                  @change="handleDistrictChange"
                />
                <Dropdown
                  v-model="selectedThana"
                  :options="filteredThanas"
                  optionLabel="Thana"
                  placeholder="থানা"
                  class="w-full"
                />
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end justify-end gap-2">
              <Button
                label="সার্চ করুন"
                icon="pi pi-search"
                @click="search"
                class="p-button"
              />
              <Button
                label="রিসেট"
                icon="pi pi-refresh"
                @click="resetFilters"
                class="p-button-outlined p-button-secondary"
              />
            </div>
          </div>
        </div>

        <!-- Results Table with PrimeVue DataTable -->
        <div class="bg-white rounded-lg shadow overflow-hidden border">
          <!-- হেডার সেকশন -->
          <div class="bg-gray-100 p-4 flex justify-between items-center border-b">
            <h2 class="text-gray-800 text-xl font-semibold">
              আবেদন তালিকা
            </h2>
            <div class="bg-white px-4 py-2 rounded border flex items-center">
              <span class="text-gray-700">মোট উপাত্ত:</span> <span class="font-bold ml-1">{{ students.length || 0 }}</span>
            </div>
          </div>

          <!-- Table Actions -->
          <div class="p-3 bg-gray-50 border-b flex justify-end gap-2">
            <Button icon="pi pi-file-excel" class="p-button-outlined p-button-secondary" />
            <Button icon="pi pi-file-pdf" class="p-button-outlined p-button-secondary" />
            <Button icon="pi pi-print" class="p-button-outlined p-button-secondary" />
          </div>

          <!-- ডাটা টেবিল -->
          <div class="p-4">
            <DataTable
              :value="filteredStudents"
              :loading="loading"
              dataKey="id"
              paginator
              :rows="perPage"
              :rowsPerPageOptions="[10, 20, 50, 100]"
              paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
              currentPageReportTemplate="দেখানো হচ্ছে {first} থেকে {last} মোট {totalRecords} এর মধ্যে"
              responsiveLayout="stack"
              breakpoint="960px"
              class="p-datatable-sm"
              :scrollable="true"
              scrollHeight="flex"
              :resizableColumns="true"
              columnResizeMode="fit"
              showGridlines
              stripedRows
            >
              <Column field="id" header="ক্রমিক নং" sortable style="min-width: 5rem" class="text-center">
                <template #body="{ index }">
                  <div class="text-gray-700">{{ index + 1 }}</div>
                </template>
              </Column>

              <Column field="id" header="আবেদন নং" sortable style="min-width: 8rem">
                <template #body="{ data }">
                  <span class="font-medium text-gray-800">{{ data.id }}</span>
                </template>
              </Column>

              <Column field="madrasha_Name" header="মাদ্রাসার নাম" sortable style="min-width: 12rem">
                <template #body="{ data }">
                  <Link
                    :href="route('nibondon_for_admin.abandon_stu_list', { markaz_id: data.id })"
                    class="text-blue-600 hover:underline"
                    title="View Details"
                  >
                    {{ data.madrasha_Name }}
                  </Link>
                </template>
              </Column>

              <Column field="Elhaq_no" header="ইলহাক নং" sortable style="min-width: 10rem">
                <template #body="{ data }">
                  <div>{{ data.Elhaq_no }}</div>
                </template>
              </Column>

              <Column field="exam_name_Bn" header="কেন্দ্রীয় পরীক্ষা" sortable style="min-width: 10rem">
                <template #body="{ data }">
                  <span>{{ data.exam_name_Bn }}</span>
                </template>
              </Column>

              <Column field="madrasha_code" header="কোড" sortable style="min-width: 8rem" class="text-center">
                <template #body="{ data }">
                  <span class="font-mono">{{ data.madrasha_code }}</span>
                </template>
              </Column>

              <Column field="student_count" header="ছাত্র সংখ্যা" sortable style="min-width: 8rem" class="text-center">
                <template #body="{ data }">
                  <span>{{ data.student_count }}</span>
                </template>
              </Column>

              <Column field="Mobile" header="ফোন নম্বর" sortable style="min-width: 10rem">
                <template #body="{ data }">
                  <div>{{ data.Mobile }}</div>
                </template>
              </Column>

              <Column field="division_name" header="বিভাগ" sortable style="min-width: 10rem">
                <template #body="{ data }">
                  <div>{{ data.division_name }}</div>
                </template>
              </Column>

              <Column field="district_name" header="জেলা" sortable style="min-width: 10rem">
                <template #body="{ data }">
                  <span>{{ data.district_name }}</span>
                </template>
              </Column>

              <Column field="thana_name" header="থানা/উপজেলা" sortable style="min-width: 10rem">
                <template #body="{ data }">
                  <span>{{ data.thana_name }}</span>
                </template>
              </Column>

              <Column field="board_status" header="বোর্ড স্ট্যটাস" sortable style="min-width: 10rem">
                <template #body="{ data }">
                  <span
                    :class="{
                      'text-green-600': data.board_status === 'অনুমোদিত',
                      'text-yellow-600': data.board_status === 'পেন্ডিং',
                      'text-red-600': data.board_status === 'বাতিল',
                      'text-blue-600': data.board_status === 'প্রক্রিয়াধীন',
                      'text-gray-600': !data.board_status
                    }"
                  >
                    {{ data.board_status || 'অনির্ধারিত' }}
                  </span>
                </template>
              </Column>

              <Column header="একশন" style="min-width: 8rem" class="text-center">
                <template #body="{ data }">
                  <div class="flex items-center justify-center gap-2">
                    <SplitButton
                      label="একশন"
                      icon="pi pi-cog"
                      :model="getActionItems(data)"
                      class="p-button-sm p-button-outlined"
                    />
                  </div>
                </template>
              </Column>

              <template #empty>
                <div class="text-center p-6">
                  <p class="text-gray-500 text-lg">কোন তথ্য পাওয়া যায়নি</p>
                </div>
              </template>

              <template #paginatorstart>
                <div class="flex items-center gap-2">
                  <button class="p-2 rounded-md border hover:bg-gray-100 transition-all duration-200">
                    <i class="pi pi-refresh"></i>
                  </button>
                </div>
              </template>

              <template #paginatorend>
                <div class="text-xs text-gray-500">
                  সর্বশেষ হালনাগাদ: {{ new Date().toLocaleString('bn-BD') }}
                </div>
              </template>

              <template #loading>
                <div class="flex flex-col items-center justify-center p-8">
                  <div class="w-16 h-16 border-4 border-gray-200 border-t-gray-600 rounded-full animate-spin mb-4"></div>
                  <p class="text-gray-700">তথ্য লোড হচ্ছে...</p>
                </div>
              </template>
            </DataTable>
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
import { Link } from '@inertiajs/vue3';
import { ref, reactive, computed, watch, onMounted } from 'vue';
import axios from 'axios';

// PrimeVue Components
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import SplitButton from 'primevue/splitbutton';

// Props definition
const props = defineProps({
  students: {
    type: Array,
    default: () => []
  },
  districts: {
    type: Array,
    default: () => []
  },
  upazilas: {
    type: Array,
    default: () => []
  },
  divisions: {
    type: Array,
    default: () => []
  }
});

// State variables
const students = ref([]);
const loading = ref(true);
const perPage = ref(10);
const divisions = ref([]);
const districts = ref([]);
const thanas = ref([]);

// Search and filter variables
const globalSearchTerm = ref('');
const selectedDivision = ref(null);
const selectedDistrict = ref(null);
const selectedThana = ref(null);
const filteredDistricts = ref([]);
const filteredThanas = ref([]);

// Computed properties
const divisionOptions = computed(() => {
  return divisions.value.map(div => ({ Division: div.Division, id: div.id }));
});

const filteredStudents = computed(() => {
  let result = [...students.value];

  // Apply global search if term exists
  if (globalSearchTerm.value) {
    const searchTerm = globalSearchTerm.value.toLowerCase();
    result = result.filter(student =>
      (student.madrasha_Name && student.madrasha_Name.toLowerCase().includes(searchTerm)) ||
      (student.Elhaq_no && student.Elhaq_no.toLowerCase().includes(searchTerm)) ||
      (student.madrasha_code && student.madrasha_code.toLowerCase().includes(searchTerm)) ||
      (student.Mobile && student.Mobile.toLowerCase().includes(searchTerm))
    );
  }

  // Apply location filters
  if (selectedDivision.value) {
    result = result.filter(student =>
      student.division_name === selectedDivision.value.Division
    );
  }

  if (selectedDistrict.value) {
    result = result.filter(student =>
      student.district_name === selectedDistrict.value.District
    );
  }

  if (selectedThana.value) {
    result = result.filter(student =>
      student.thana_name === selectedThana.value.Thana
    );
  }

  return result;
});

// Fetch data on component mount
onMounted(() => {
  fetchStudents();
  loadDivisions();
});

// Data fetching functions
const fetchStudents = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/markaz-students');
    students.value = response.data;
  } catch (error) {
    console.error('ডাটা লোড করতে সমস্যা হয়েছে:', error);
  } finally {
    loading.value = false;
  }
};

const loadDivisions = async () => {
  try {
    const response = await axios.get('/api/divisions');
    divisions.value = response.data;
  } catch (error) {
    console.error('Error loading divisions:', error);
  }
};

// Filter handling functions
const handleDivisionChange = async () => {
  if (selectedDivision.value) {
    try {
      const response = await axios.get(`/api/districts/${selectedDivision.value.id}`);
      filteredDistricts.value = response.data;
      // Reset district and thana selections
      selectedDistrict.value = null;
      selectedThana.value = null;
      filteredThanas.value = [];
    } catch (error) {
      console.error('Error loading districts:', error);
    }
  } else {
    filteredDistricts.value = [];
    filteredThanas.value = [];
  }
};

const handleDistrictChange = async () => {
  if (selectedDistrict.value) {
    try {
      const response = await axios.get(`/api/thanas/${selectedDistrict.value.DesID}`);
      filteredThanas.value = response.data;
      // Reset thana selection
      selectedThana.value = null;
    } catch (error) {
      console.error('Error loading thanas:', error);
    }
  } else {
    filteredThanas.value = [];
  }
};

// Search function
const search = () => {
  // The filtering is handled by the computed property
  // This function is kept for explicit search button functionality
  console.log('Searching with filters:', {
    searchTerm: globalSearchTerm.value,
    division: selectedDivision.value?.Division,
    district: selectedDistrict.value?.District,
    thana: selectedThana.value?.Thana
  });
};

// Reset filters
const resetFilters = () => {
  globalSearchTerm.value = '';
  selectedDivision.value = null;
  selectedDistrict.value = null;
  selectedThana.value = null;
  filteredDistricts.value = [];
  filteredThanas.value = [];

  // Fetch all data again
  fetchStudents();
};

// Action items for SplitButton
const getActionItems = (data) => {
  return [
    {
      label: 'মেসেজ',
      icon: 'pi pi-envelope',
      command: () => viewMessage(data)
    },
    {
      label: 'প্রিন্ট',
      icon: 'pi pi-print',
      command: () => printData(data)
    },
    {
      label: 'বিস্তারিত',
      icon: 'pi pi-eye',
      command: () => viewDetails(data)
    }
  ];
};

// Action functions
const viewMessage = (data) => {
  console.log('Viewing message for:', data);
  // Implement message view functionality
};

const printData = (data) => {
  console.log('Printing data for:', data);
  // Implement print functionality
};

const viewDetails = (data) => {
  console.log('Viewing details for:', data);
  // Implement details view functionality
};
</script>

<style scoped>
/* Custom styles for Islamic theme */
.bg-pattern-islamic {
  background-color: #f8f9fa;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e2e8f0' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.font-arabic {
  font-family: 'Noto Naskh Arabic', 'Amiri', serif;
}

/* PrimeVue DataTable customization */
:deep(.p-datatable .p-datatable-thead > tr > th) {
  background-color: #065f46;
  color: white;
  font-weight: 600;
  border-color: #064e3b;
}

:deep(.p-datatable .p-datatable-tbody > tr:nth-child(even)) {
  background-color: #f0fdf4;
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
  background-color: #d1fae5;
}

:deep(.p-paginator) {
  background-color: #f8f9fa;
  border-top: 1px solid #e2e8f0;
}

:deep(.p-dropdown-panel .p-dropdown-items .p-dropdown-item.p-highlight) {
  background-color: #10b981;
  color: white;
}

:deep(.p-button.p-button-success) {
  background-color: #059669;
  border-color: #047857;
}

:deep(.p-button.p-button-success:hover) {
  background-color: #047857;
  border-color: #065f46;
}
</style>
