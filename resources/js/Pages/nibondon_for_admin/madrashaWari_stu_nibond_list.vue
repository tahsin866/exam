<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-semibold text-gray-800 text-center">
              {{ students.length > 0 ? students[0].current_madrasha : '' }} : ছাত্র তালিকা
            </h1>
          </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-gray-500 text-sm">মোট ছাত্র</p>
                  <p class="text-2xl font-semibold text-gray-800">১০০</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-gray-500 text-sm">বোর্ড অনুমদিত</p>
                  <p class="text-2xl font-semibold text-gray-800">৭০</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-gray-500 text-sm">বোর্ড ফেরত</p>
                  <p class="text-2xl font-semibold text-gray-800">৩০</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-gray-500 text-sm">স্থগিত/বাতিল</p>
                  <p class="text-2xl font-semibold text-gray-800">৩০</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Filter Section -->
       <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
  <div class="p-6 bg-white border-b border-gray-200">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="col-span-1">
        <label class="block text-sm font-medium text-gray-700 mb-1">গ্লোবাল সার্চ</label>
        <span class="p-input-icon-left w-full">
          <InputText
            v-model="filters['global'].value"
            placeholder="সার্চ করুন..."
            class="w-full p-inputtext-sm"
          />
        </span>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">শ্রেণী ফিল্টার</label>
        <Dropdown
          v-model="filters['current_class'].value"
          :options="classOptions"
          placeholder="শ্রেণী সিলেক্ট করুন"
          class="w-full"
          showClear
        />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">স্ট্যাটাস ফিল্টার</label>
        <Dropdown
          v-model="filters['status'].value"
          :options="statusOptions"
          placeholder="স্ট্যাটাস সিলেক্ট করুন"
          class="w-full"
          showClear
        />
      </div>
    </div>
    <div class="mt-4 flex justify-end">
      <Button
        label="ফিল্টার রিসেট করুন"
        icon="pi pi-refresh"
        class="p-button-outlined p-button-secondary"
        @click="resetFilters"
      />
    </div>
  </div>
</div>


        <!-- Student Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <DataTable
              :value="students"
              :paginator="true"
              :rows="10"
              :rowsPerPageOptions="[5, 10, 20, 50]"
              paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
              currentPageReportTemplate="মোট {totalRecords} ছাত্রের মধ্যে {first} থেকে {last} পর্যন্ত দেখানো হচ্ছে"
              responsiveLayout="scroll"
              :globalFilterFields="['name_bn', 'current_class', 'id', 'father_name_bn', 'mother_name_bn', 'status']"
              v-model:filters="filters"
              stripedRows
              rowHover
              class="p-datatable-sm"
            >
              <Column field="index" header="#" :sortable="true" headerClass="text-left" bodyClass="text-center">
                <template #body="slotProps">
                  <span class="font-medium text-gray-700">{{ slotProps.index + 1 }}</span>
                </template>
              </Column>

              <Column field="student_image" header="ছবি" headerClass="text-left" bodyClass="text-center py-2">
                <template #body="slotProps">
                  <div class="flex justify-center">
                    <img
                      v-if="slotProps.data.student_image"
                      :src="'/storage/' + slotProps.data.student_image"
                      alt="Student"
                      class="w-12 h-12  border-2 border-gray-200 shadow-sm object-cover"
                    />
                    <div v-else class="w-12 h-12  bg-gray-200 flex items-center justify-center border-2 border-gray-300">
                      <i class="pi pi-user text-gray-400 text-xl"></i>
                    </div>
                  </div>
                </template>
              </Column>

              <Column field="name_bn" header="নাম" :sortable="true" headerClass="text-left">
                <template #body="slotProps">
                  <div class="font-medium text-gray-800">{{ slotProps.data.name_bn }}</div>
                </template>
              </Column>

              <Column field="current_class" header="শ্রেণি" :sortable="true" headerClass="text-left" bodyClass="text-center">
                <template #body="slotProps">
                  <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-md font-medium text-sm">
                    {{ slotProps.data.current_class }}
                  </span>
                </template>
              </Column>

              <Column field="id" header="রেজিস্ট্রেশন নাম্বার" :sortable="true" headerClass="text-left">
                <template #body="slotProps">
                  <div class="font-mono text-gray-700">{{ slotProps.data.id }}</div>
                </template>
              </Column>

              <Column field="Date_of_birth" header="জন্ম তারিখ" :sortable="true" headerClass="text-left">
                <template #body="slotProps">
                  <div class="text-gray-600">
                    <i class="pi pi-calendar mr-1 text-gray-400"></i>
                    {{ slotProps.data.Date_of_birth }}
                  </div>
                </template>
              </Column>

              <Column field="father_name_bn" header="পিতার নাম" :sortable="true" headerClass="text-left">
                <template #body="slotProps">
                  <div class="text-gray-700">{{ slotProps.data.father_name_bn }}</div>
                </template>
              </Column>

              <Column field="mother_name_bn" header="মাতার নাম" :sortable="true" headerClass="text-left">
                <template #body="slotProps">
                  <div class="text-gray-700">{{ slotProps.data.mother_name_bn }}</div>
                </template>
              </Column>

              <Column field="status" header="স্ট্যাটাস" :sortable="true" headerClass="text-left" bodyClass="text-center">
                <template #body="slotProps">
  <span
    :class="{
      'bg-yellow-100 text-yellow-800 px-2 py-1 rounded-md text-xs font-medium border border-yellow-200': slotProps.data.status === 'submitted',
      'bg-green-100 text-green-800 px-2 py-1 rounded-md text-xs font-medium border border-green-200': slotProps.data.status === 'approved',
      'bg-red-100 text-red-800 px-2 py-1 rounded-md text-xs font-medium border border-red-200': slotProps.data.status === 'returned',
      'bg-blue-100 text-blue-800 px-2 py-1 rounded-md text-xs font-medium border border-gray-200': slotProps.data.status === 'pending' || !slotProps.data.status
    }"
  >
    {{ getStatusInBangla(slotProps.data.status) }}
  </span>
                </template>
              </Column>

              <Column header="অ্যাকশন" headerClass="text-left" bodyClass="text-center">
                <template #body="slotProps">
                  <SplitButton
                    label="বিস্তারিত"
                    icon="pi pi-eye"
                    :model="getActionItems(slotProps.data)"
                    @click="viewDetails(slotProps.data)"
                    class="p-button-sm p-button-outlined"
                  />
                </template>
              </Column>

              <template #empty>
                <div class="text-center p-6">
                  <i class="pi pi-search text-gray-400 text-5xl mb-3"></i>
                  <p class="text-gray-500 text-xl">কোন ছাত্র তথ্য পাওয়া যায়নি</p>
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
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import { defineProps } from 'vue'
import axios from 'axios'

// PrimeVue Components
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import SplitButton from 'primevue/splitbutton'

const FilterMatchMode = {
  STARTS_WITH: 'startsWith',
  CONTAINS: 'contains',
  EQUALS: 'equals',
  DATE_IS: 'dateIs'
};

const props = defineProps({
  students: Array,
  madrasha_id: Number
})

// Options for dropdowns
const classOptions = ref([
  'ফযিলত',
  'সানাবিয়া উলইয়া',
  'সানাবিয়া',
  'মুতাওয়াসসিতা',
   'ইবতেদাইয়া',
  'হিফজুল কোরআন',
  'ইলমুল কিরাআত'
])

const statusOptions = ref([
  'অনুমোদন',
  'বোর্ড দাখিল',
  'বোর্ড ফেরত',
  'পেন্ডিং',
  'স্থগিত',
  'অনির্ধারিত'
])

// Initialize filters properly
const filters = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
  'name_bn': { value: null, matchMode: FilterMatchMode.CONTAINS },
  'current_class': { value: null, matchMode: FilterMatchMode.EQUALS },
  'id': { value: null, matchMode: FilterMatchMode.CONTAINS },
  'father_name_bn': { value: null, matchMode: FilterMatchMode.CONTAINS },
  'mother_name_bn': { value: null, matchMode: FilterMatchMode.CONTAINS },
  'status': { value: null, matchMode: FilterMatchMode.EQUALS }
})

// Reset filters function
const resetFilters = () => {
  filters.value = {
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'name_bn': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'current_class': { value: null, matchMode: FilterMatchMode.EQUALS },
    'id': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'father_name_bn': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'mother_name_bn': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'status': { value: null, matchMode: FilterMatchMode.EQUALS }
  };
}

// Action items for SplitButton
const getActionItems = (student) => {
  return [
    {
      label: 'পিডিএফ ডাউনলোড',
      icon: 'pi pi-file-pdf',
      command: () => downloadPdf(student)
    },
    {
      label: 'অনুমোদন',
      icon: 'pi pi-check-circle',
      command: () => approveStudent(student)
    },
    {
      label: 'স্থগিত',
      icon: 'pi pi-pause',
      command: () => suspendStudent(student)
    },
    {
      label: 'বোর্ড ফেরত',
      icon: 'pi pi-times-circle',
      command: () => rejectStudent(student)
    },
    {
      label: 'বোর্ড দাখিল',
      icon: 'pi pi-send',
      command: () => submitToBoard(student)
    }
  ];
};

// Action functions
const viewDetails = (student) => {
  window.location.href = route('nibondon_for_admin.student_detiles_For_nibondon', student.id);
}

const downloadPdf = (student) => {
  console.log('Download PDF for student:', student);
  // Implement PDF download functionality
}

const approveStudent = (student) => {
  console.log('Approve student:', student);
  // Implement approval functionality
}

const suspendStudent = (student) => {
  console.log('Suspend student:', student);
  // Implement suspension functionality
}

const rejectStudent = (student) => {
  console.log('Reject student:', student);
  // Implement rejection functionality
}

const submitToBoard = (student) => {
  console.log('Submit to board:', student);
  // Implement board submission functionality
}

// Export CSV function
const exportCSV = () => {
  console.log('Exporting CSV');
  // Implement CSV export functionality
}

// Refresh data function
const refreshData = () => {
  console.log('Refreshing data');
  // Implement data refresh functionality
}




const getStatusInBangla = (status) => {
  switch (status) {
    case 'submitted':
      return 'বোর্ড দাখিল';
    case 'approved':
      return 'অনুমোদিত';
    case 'returned':
      return 'বোর্ড ফেরত';
    case 'pending':
      return 'পেন্ডিং';
    default:
      return 'পেন্ডিং';
  }
};
</script>

<style scoped>



</style>

