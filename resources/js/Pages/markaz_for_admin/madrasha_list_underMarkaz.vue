<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <!-- Header Section -->
            <div class="mb-6">
              <h1 class="text-2xl font-semibold text-gray-800">
                মারকাজ ভিত্তিক মাদরাসার তালিকা
              </h1>
            </div>

            <!-- Results Table -->
            <div class="mt-6 bg-white rounded-md overflow-hidden">
              <div class="bg-gray-50 border-b border-gray-200 p-4 flex justify-between items-center">
                <h2 class="text-gray-700 text-lg font-medium flex items-center gap-2">
                  <i class="fas fa-list"></i>
                  মাদরাসা তালিকা
                </h2>
                <div class="text-gray-600">মোট উপাত্ত: {{ madrashas?.length || 0 }}</div>
              </div>

              <div class="p-4">
                <DataTable
                  :value="madrashas"
                  v-model:filters="filters"
                  filterDisplay="menu"
                  :paginator="true"
                  :rows="10"
                  :rowsPerPageOptions="[10, 20, 50, 100]"
                  paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                  currentPageReportTemplate="দেখানো হচ্ছে {first} থেকে {last} মোট {totalRecords} এর মধ্যে"
                  responsiveLayout="stack"
                  breakpoint="960px"
                  class="p-datatable-sm"
                  showGridlines
                  stripedRows
                  :globalFilterFields="['name', 'Elhaq_no', 'id', 'Mobile_no']"
                  dataKey="id"
                >
                  <template #header>
                    <div class="flex justify-between items-center">
                      <div>
                        <span class="p-input-icon-left">

                          <InputText v-model="filters['global'].value" placeholder="সার্চ করুন..." class="p-inputtext-sm" />
                        </span>
                      </div>
                    </div>
                  </template>

                  <Column field="id" header="ক্রমিক" sortable style="min-width: 5rem">
                    <template #body="{ index }">
                      {{ index + 1 }}
                    </template>
                  </Column>

                  <Column field="name" header="মাদরাসার নাম" sortable style="min-width: 12rem">
                  </Column>

                  <Column field="Elhaq_no" header="এলহাক নম্বর" sortable style="min-width: 10rem">
                  </Column>

                  <Column field="id" header="আইডি" sortable style="min-width: 8rem">
                  </Column>

                  <Column field="Mobile_no" header="মোবাইল নম্বর" sortable style="min-width: 10rem">
                  </Column>

                  <Column header="করনীয়" style="min-width: 10rem">
                    <template #body="{ data }">
                      <SplitButton
                        label="বিস্তারিত"
                        icon="fas fa-eye"
                        size="small"
                        class="p-button-sm p-button-primary"
                        @click="viewApplication(data)"
                        :model="getActionOptions(data)"
                      />
                    </template>
                  </Column>

                  <template #empty>
                    <div class="text-center p-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                      <p class="text-gray-500 text-lg">কোন তথ্য পাওয়া যায়নি</p>
                    </div>
                  </template>

                  <template #loading>
                    <div class="flex flex-col items-center justify-center p-8">
                      <div class="w-16 h-16 border-4 border-gray-200 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
                      <p class="text-indigo-700">তথ্য লোড হচ্ছে...</p>
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
import { ref, reactive, onMounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import SplitButton from 'primevue/splitbutton';

const FilterMatchMode = {
  STARTS_WITH: 'startswith',
  CONTAINS: 'contains',
  EQUALS: 'equals',
  DATE_IS: 'dateIs'
};

// Initialize only global filter
const filters = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS }
});

// Table Headers
const tableHeaders = [
  { id: 1, label: "ক্রমিক" },
  { id: 2, label: "মাদরাসার নাম" },
  { id: 3, label: "এলহাক নম্বর" },
  { id: 6, label: "আইডি" },
  { id: 7, label: "মোবাইল নম্বর" },
  { id: 11, label: "অ্যাকশন" }
];

// Props
const props = defineProps({
  madrashas: {
    type: Array,
    required: true
  },
  markazId: Number
});

const getActionOptions = (data) => {
  return [
    {
      label: 'মেসেজিং',
      icon: 'fas fa-envelope',
      command: () => editApplication(data)
    },
    {
      label: 'স্থগিত',
      icon: 'fas fa-pause-circle',
      command: () => suspendApplication(data)
    },
    {
      label: 'এলার্ট মেসেজ',
      icon: 'fas fa-exclamation-triangle',
      command: () => sendAlertMessage(data)
    },
    {
      label: 'বাতিল',
      icon: 'fas fa-times-circle',
      command: () => cancelApplication(data)
    }
  ];
};

// Action functions
const viewApplication = (data) => {
  console.log('View application:', data);
  // Implement view functionality
};

const editApplication = (data) => {
  console.log('Edit application:', data);
  // Implement edit functionality
};

const suspendApplication = (data) => {
  console.log('Suspend application:', data);
  // Implement suspend functionality
};

const sendAlertMessage = (data) => {
  console.log('Send alert message:', data);
  // Implement alert message functionality
};

const cancelApplication = (data) => {
  console.log('Cancel application:', data);
  // Implement cancel functionality
};
</script>
