<template>
  <AuthenticatedLayout>
    <ConfirmDialog />
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <!-- Header Section -->
            <div class="mb-6">
              <h1 class="text-2xl font-semibold text-gray-800">
                মারকাজ পরিবর্তনের আবেদন তালিকা
              </h1>
            </div>

            <!-- Search Panel -->
            <div class="mb-6 p-4 bg-gray-50 rounded-md border border-gray-200">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">সার্চ করুন</label>
                  <span class="p-input-icon-left w-full">

                    <InputText v-model="filters.global.value" placeholder="গ্লোবাল সার্চ..." class="w-full" />
                  </span>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">মারকায টাইপ</label>
                  <Dropdown
                    v-model="filters.markaz_type.value"
                    :options="markazTypes"
                    placeholder="সিলেক্ট করুন"
                    class="w-full"
                    showClear
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">আবেদনের সময়</label>
                  <Calendar
                    v-model="filters.created_at.value"
                    dateFormat="dd/mm/yy"
                    placeholder="তারিখ দিয়ে ফিল্টার করুন"
                    class="w-full"
                  />
                </div>
              </div>
              <div class="mt-4 flex justify-end">
                <Button
                  type="button"
                  icon="pi pi-filter-slash"
                  label="রিসেট ফিল্টার"
                  outlined
                  @click="resetFilters"
                />
              </div>
            </div>

            <!-- Results Table -->
            <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">
              <div class="bg-gray-50 p-4 flex justify-between items-center border-b border-gray-200">
                <h2 class="text-gray-700 text-lg font-medium flex items-center gap-2">
                  <i class="pi pi-book"></i>
                  আবেদন তালিকা
                </h2>
                <div class="text-gray-600">
                  মোট আবেদন: {{ markazChanges.length }}
                </div>
              </div>

              <div class="p-4">
                <DataTable
                  :value="markazChanges"
                  :paginator="true"
                  :rows="10"
                  :rowsPerPageOptions="[5, 10, 20, 50]"
                  dataKey="id"
                  :filters="filters"
                  filterDisplay="menu"
                  :loading="loading"
                  paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                  currentPageReportTemplate="দেখানো হচ্ছে {first} থেকে {last} মোট {totalRecords} এর মধ্যে"
                  responsiveLayout="scroll"
                  class="p-datatable-sm"
                  stripedRows
                  showGridlines
                >
                  <Column field="madrasha_name" header="মাদরাসার নাম" :sortable="true">
                  </Column>

                  <Column field="asking_madrasha" header="কাঙ্খিত মারকাযের নাম" :sortable="true">
                  </Column>

                  <Column field="madrasha_code" header="মাদরাসার কোড" :sortable="true">
                  </Column>

                  <Column field="markaz_type" header="মারকায টাইপ" :sortable="true">
                    <template #body="slotProps">
                      <span
                        :class="{
                          'bg-blue-100 text-blue-800 px-3 py-1.5 rounded-full text-xs font-medium shadow-sm border border-blue-200': slotProps.data.markaz_type === 'কিরাআত',
                          'bg-green-100 text-green-800 px-3 py-1.5 rounded-full text-xs font-medium shadow-sm border border-green-200': slotProps.data.markaz_type === 'দারসিয়াত',
                          'bg-purple-100 text-purple-800 px-3 py-1.5 rounded-full text-xs font-medium shadow-sm border border-purple-200': slotProps.data.markaz_type === 'হিফজুল কোরাআন',
                          'bg-gray-100 text-gray-800 px-3 py-1.5 rounded-full text-xs font-medium shadow-sm border border-gray-200': !slotProps.data.markaz_type
                        }"
                      >
                        <i
                          :class="{
                            'pi pi-book mr-1': slotProps.data.markaz_type === 'কিরাআত',
                            'pi pi-bookmark mr-1': slotProps.data.markaz_type === 'দারসিয়াত',
                            'pi pi-file-edit mr-1': slotProps.data.markaz_type === 'হিফজুল কোরাআন',
                            'pi pi-question-circle mr-1': !slotProps.data.markaz_type
                          }"
                        ></i>
                        {{ slotProps.data.markaz_type || 'অনির্ধারিত' }}
                      </span>
                    </template>
                  </Column>

                  <Column field="created_at" header="আবেদনের সময়" :sortable="true">
                    <template #body="slotProps">
                      <span class="text-green-700">{{ formatDate(slotProps.data.created_at) }}</span>
                    </template>
                  </Column>

                  <Column header="বিস্তারিত দেখুন" style="min-width: 8rem">
                    <template #body="slotProps">
                      <SplitButton
                        label="বিস্তারিত দেখুন"
                        icon="pi pi-cog"
                        :model="getActionItems(slotProps.data)"
                        class="p-button-sm"
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
import { ref, reactive, onMounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import SplitButton from 'primevue/splitbutton';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';

const confirm = useConfirm();

const FilterMatchMode = {
  STARTS_WITH: 'startsWith',
  CONTAINS: 'contains',
  EQUALS: 'equals',
  DATE_IS: 'dateIs'
};

// State definitions
const markazChanges = ref([]);
const loading = ref(true);

// Markaz type options
const markazTypes = ref([
  'কিরাআত',
  'দারসিয়াত',
  'হিফজুল কোরাআন'
]);

// Filter data - simplified to only include global, markaz_type, and created_at
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  markaz_type: { value: null, matchMode: FilterMatchMode.EQUALS },
  created_at: { value: null, matchMode: FilterMatchMode.DATE_IS }
});

// Reset filter function
const resetFilters = () => {
  filters.value.global.value = null;
  filters.value.markaz_type.value = null;
  filters.value.created_at.value = null;
};

// Date formatting
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('bn-BD', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  }).format(date);
};

// Action button options
const getActionItems = (data) => {
  return [
    {
      label: 'অনুমোদন',
      icon: 'pi pi-check',
      command: () => handleAction('accept', data.id)
    },
    {
      label: 'ফেরত',
      icon: 'pi pi-reply',
      command: () => handleAction('return', data.id)
    },
    {
      label: 'বাতিল',
      icon: 'pi pi-times',
      command: () => handleAction('reject', data.id)
    },
    {
      label: 'স্থগিত',
      icon: 'pi pi-pause',
      command: () => handleAction('hold', data.id)
    }
  ];
};

// Action handler
const handleAction = (action, id) => {
  if (action === 'accept') {
    confirm.require({
      message: 'আপনি কি এই আবেদনটি অনুমোদন করতে চান?',
      header: 'অনুমোদন নিশ্চিতকরণ',
      icon: 'pi pi-info-circle',
      acceptLabel: 'হ্যাঁ',
      rejectLabel: 'না',
      accept: () => {
        approveMarkazChange(id);
      },
      reject: () => {
        // Do nothing or show a message
        console.log('অনুমোদন বাতিল করা হয়েছে');
      }
    });
  } else if (action === 'return') {
    // Handle return action
    console.log(`Return action for ID: ${id}`);
  } else if (action === 'reject') {
    // Handle reject action
    console.log(`Reject action for ID: ${id}`);
  } else if (action === 'hold') {
    // Handle hold action
    console.log(`Hold action for ID: ${id}`);
  }
};

// Function to approve markaz change
const approveMarkazChange = async (id) => {
  try {
    const response = await axios.post(`/api/markaz-changes/${id}/approve`);
    if (response.data.success) {
      console.log(response.data.message || 'মারকাজ পরিবর্তন সফলভাবে অনুমোদিত হয়েছে');
      // Refresh data
      loadMarkazChanges();
    } else {
      console.error(response.data.message || 'অনুমোদন প্রক্রিয়ায় সমস্যা হয়েছে');
    }
  } catch (error) {
    console.error('Error approving markaz change:', error);
    console.error('অনুমোদন প্রক্রিয়ায় সমস্যা হয়েছে');
  }
};

// Load data from database
const loadMarkazChanges = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/markaz-changes');
    markazChanges.value = response.data;
  } catch (error) {
    console.error('Error loading markaz changes:', error);
  } finally {
    loading.value = false;
  }
};

// Lifecycle hook
onMounted(() => {
  loadMarkazChanges();
});
</script>
