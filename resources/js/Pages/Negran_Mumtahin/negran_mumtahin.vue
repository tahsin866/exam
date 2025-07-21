<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';
import SplitButton from 'primevue/splitbutton';
import axios from 'axios';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';

const toast = useToast();
const applications = ref([]);
const filteredApplications = ref([]);
const loading = ref(true);
const openModal = ref(false);
const searchMobile = ref('');
const searchCode = ref('');
const searchTerm = ref('');
const application_type = ref('');
const searchStatus = ref('');
const showDeleteConfirm = ref(false);
const applicationToDelete = ref(null);

const getActionItems = (rowData) => {
  return [
    {
      label: 'বিস্তারিত',
      icon: 'pi pi-eye',
      command: () => {
        toast.add({ severity: 'info', summary: 'বিস্তারিত', detail: 'আবেদন বিস্তারিত দেখা হচ্ছে', life: 3000 });
        // viewDetails(rowData) function call
      }
    },
    {
      label: 'সম্পাদনা',
      icon: 'pi pi-pencil',
      command: () => {
        router.visit(route('Negran_Mumtahin.neg-mumtahin-eidt', { id: rowData.id }));
      }
    },
    {
      separator: true
    },
    {
      label: 'মুছুন',
      icon: 'pi pi-trash',
      command: () => {
        applicationToDelete.value = rowData;
        showDeleteConfirm.value = true;
      }
    }
  ];
};

const deleteApplication = () => {
  if (!applicationToDelete.value) return;

  axios.delete(route('Negran_Mumtahin.delete', { id: applicationToDelete.value.id }))
    .then(response => {
      toast.add({
        severity: 'success',
        summary: 'সফল',
        detail: 'আবেদন সফলভাবে মুছে ফেলা হয়েছে',
        life: 3000
      });

      // Remove from both arrays
      applications.value = applications.value.filter(app => app.id !== applicationToDelete.value.id);
      filteredApplications.value = filteredApplications.value.filter(app => app.id !== applicationToDelete.value.id);

      showDeleteConfirm.value = false;
      applicationToDelete.value = null;
    })
    .catch(error => {
      console.error('Delete error:', error);
      toast.add({
        severity: 'error',
        summary: 'ব্যর্থ',
        detail: 'আবেদন মুছতে ব্যর্থ হয়েছে',
        life: 3000
      });
    });
};

const exportOptions = ref([
  {
    label: 'বোর্ড দাখিল',
    icon: 'ri-send-plane-line',
    command: () => submitToBoard()
  },
  {
    label: 'CSV ডাউনলোড',
    icon: 'ri-file-excel-line',
    command: () => exportCSV()
  },
  {
    label: 'এক্সেল ডাউনলোড',
    icon: 'ri-file-excel-2-line',
    command: () => exportExcel()
  },
  {
    label: 'বাতিল',
    icon: 'ri-close-circle-line',
    command: () => cancelApplications()
  }
]);

// Add these new refs to store unique dropdown options
const applicationTypes = ref([]);
const statusOptions = ref([]);

// Update the onMounted function to extract unique values for dropdowns
onMounted(async () => {
  try {
    const response = await fetch('/api/neg-mumtahin-applications');
    applications.value = await response.json();
    filteredApplications.value = [...applications.value]; // Initialize filtered applications

    // Extract unique application types for dropdown
    const uniqueTypes = [...new Set(applications.value.map(app => app.application_type))];
    applicationTypes.value = [
      { label: 'সকল', value: '' },
      ...uniqueTypes.map(type => ({ label: type, value: type }))
    ];

    // Extract unique statuses for dropdown
    const uniqueStatuses = [...new Set(applications.value.map(app => app.status))];
    statusOptions.value = [
      { label: 'সকল', value: '' },
      ...uniqueStatuses.map(status => ({ label: status, value: status }))
    ];
  } catch (error) {
    console.error('Error fetching applications:', error);
  } finally {
    loading.value = false;
  }
});


const getSeverity = (status) => {
  switch (status) {
    case 'pending':
      return 'warning';
    case 'approved':
      return 'success';
    case 'rejected':
      return 'danger';
    default:
      return 'info';
  }
};

const searchTeachers = async () => {
  if (!searchMobile.value && !searchCode.value) {
    alert('অনুগ্রহ করে মোবাইল নম্বর অথবা কোড নম্বর দিন');
    return;
  }

  try {
    const response = await axios.get('/api/search-teacher', {
      params: {
        mobile: searchMobile.value,
        code: searchCode.value
      }
    });

    localStorage.setItem('teacherSearchResults', JSON.stringify(response.data));
    window.location.href = '/negran-view-detailes';
  } catch (error) {
    console.error('Error searching teachers:', error);
    alert('অনুসন্ধান করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
  }
};

const exportPDF = () => {
  console.log('Exporting PDF');
};

const exportCSV = () => {
  console.log('Exporting CSV');
};

const exportExcel = () => {
  console.log('Exporting Excel');
};

const cancelApplications = () => {
  console.log('Cancelling applications');
};

const submitToBoard = async (data) => {
  try {
    toast.add({
      severity: 'info',
      summary: 'Processing',
      detail: 'বোর্ড দাখিল প্রক্রিয়াধীন...',
      life: 3000
    });

    const response = await axios.post(`/mumtahin/submit-to-board/${data.id}`);

    if (response.data.success) {
      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'আবেদনটি সফলভাবে বোর্ডে দাখিল করা হয়েছে',
        life: 3000
      });
    } else {
      throw new Error(response.data.message || 'Failed to submit');
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: error.response?.data?.message || error.message || 'আবেদন দাখিল করতে সমস্যা হয়েছে',
      life: 3000
    });
    console.error('Submit to board error:', error);
  }
};



const searchApplications = () => {
  loading.value = true;

  // Filter the applications based on search criteria
  filteredApplications.value = applications.value.filter(app => {
    // Check if application matches the search term
    const termMatch = !searchTerm.value ||
      app.name?.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      app.mobile?.includes(searchTerm.value) ||
      app.email?.toLowerCase().includes(searchTerm.value.toLowerCase());

    // Check if application matches the application type
    // Corrected to match exactly with the dropdown value
    const typeMatch = !application_type.value ||
      app.application_type === application_type.value;

    // Check if application matches the status
    const statusMatch = !searchStatus.value ||
      app.status === searchStatus.value;

    // Return true if all conditions match
    return termMatch && typeMatch && statusMatch;
  });

  loading.value = false;

  // Show toast with search results
  toast.add({
    severity: 'info',
    summary: 'অনুসন্ধান সম্পন্ন',
    detail: `${filteredApplications.value.length} টি আবেদন পাওয়া গেছে`,
    life: 3000
  });
};

// Watch for changes in search inputs to automatically update results
watch([searchTerm, application_type, searchStatus], () => {
  if (searchTerm.value || application_type.value || searchStatus.value) {
    searchApplications();
  } else {
    // If all search fields are empty, show all applications
    filteredApplications.value = [...applications.value];
  }
}, { debounce: 300 });
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        নেগরান মুমতাহিন আবেদন তালিকা
      </h2>
    </template>
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <Toast />
        <!-- Action Buttons -->
        <div class="mb-6 flex justify-between">
          <div>
            <h4 class="text-lg font-medium text-gray-700">
              মোট নেগরান মুমতাহিন আবেদন সংখ্যা
            </h4>
          </div>
          <div class="flex gap-3">
            <Link
              :href="route('Negran_Mumtahin.negran_mumtahin_apply')"
              class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
            >
              <i class="ri-history-line mr-1.5"></i>
              নতুন
            </Link>
            <button
              @click="openModal = true"
              class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
              <i class="ri-add-circle-line mr-1.5"></i>
              পুরাতন
            </button>
          </div>
        </div>

        <!-- Search Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-700 mb-4">অনুসন্ধান করুন</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label for="searchTerm" class="block text-sm font-medium text-gray-700 mb-1">
                  সার্চ টার্ম
                </label>
                <InputText
                  id="searchTerm"
                  v-model="searchTerm"
                  class="w-full"
                  placeholder="নাম, মোবাইল, ইত্যাদি"
                />
              </div>
              <div>
                <label for="application_type" class="block text-sm font-medium text-gray-700 mb-1">
                  আবেদনের ধরন
                </label>
             <Dropdown
  id="application_type"
  v-model="application_type"
  :options="applicationTypes"
  optionLabel="label"
  optionValue="value"
  class="w-full"
/>
              </div>
              <div>
                <label for="searchStatus" class="block text-sm font-medium text-gray-700 mb-1">
                  আবেদন অবস্থা
                </label>
             <Dropdown
  id="searchStatus"
  v-model="searchStatus"
  :options="statusOptions"
  optionLabel="label"
  optionValue="value"
  class="w-full"
/>
              </div>
            </div>
            <div class="mt-4 flex justify-end">
              <Button
                @click="searchApplications"
                icon="pi pi-search"
                label="অনুসন্ধান করুন"
                class="p-button-primary"
              />
            </div>
          </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
              <h4 class="text-lg font-medium text-gray-700">
                মোট নেগরান মুমতাহিন আবেদন সংখ্যা
              </h4>
              <div class="flex gap-4">
                <SplitButton
                  label="PDF ডাউনলোড"
                  icon="ri-file-pdf-line"
                  severity="green"
                  @click="exportPDF"
                  :model="exportOptions"
                />
              </div>
            </div>
            <!-- Table Section -->
            <DataTable
              :value="filteredApplications"
              paginator
              showGridlines
              :rows="10"
              dataKey="id"
              :loading="loading"
              class="p-datatable-sm"
            >
              <template #empty> কোন আবেদন পাওয়া যায়নি </template>
              <template #loading> লোড হচ্ছে... </template>
              <Column field="id" header="ক্রমিক নং" style="min-width: 5rem">
                <template #body="{ index }">
                  {{ index + 1 }}
                </template>
              </Column>
              <Column header="ছবি" style="min-width: 6rem">
                <template #body="{ data }">
                  <img v-if="data.image" :src="`/storage/${data.image}`" alt="Profile Image" class="w-10 h-10 rounded-full object-cover">
                  <div v-else class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">N/A</span>
                  </div>
                </template>
              </Column>
              <Column field="name" header="নাম" style="min-width: 12rem"></Column>
              <Column field="mobile" header="মোবাইল নম্বর" style="min-width: 10rem"></Column>
              <Column field="birth_date" header="জন্ম-তারিখ" style="min-width: 10rem"></Column>
              <Column field="application_type" header="আবেদনের ধরন" style="min-width: 10rem"></Column>
              <Column field="status" header="আবেদন অবস্থা" style="min-width: 10rem">
                <template #body="{ data }">
                  <Tag :value="data.status" :severity="getSeverity(data.status)" />
                </template>
              </Column>
              <Column header="করণীয়" style="min-width: 12rem">
                <template #body="{ data }">
                  <SplitButton
                    label="বোর্ড দাখিল"
                    icon="pi pi-send-plane"
                    severity="success"
                    @click="submitToBoard(data)"
                    :model="getActionItems(data)"
                    class="p-button-sm"
                  />
                </template>
              </Column>
            </DataTable>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for searching old teachers (Laravel style) -->
    <div v-if="openModal" class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="openModal = false"></div>
        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  নেগরান মুমতাহিন সার্চ
                </h3>
                <div class="mt-4 space-y-4">
                  <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700">মোবাইল নম্বর</label>
                    <input
                      id="mobile"
                      v-model="searchMobile"
                      type="text"
                      placeholder="মোবাইল নম্বর দিন"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                  </div>
                  <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">কোড নম্বর</label>
                    <input
                      id="code"
                      v-model="searchCode"
                      type="text"
                      placeholder="কোড নম্বর দিন"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="searchTeachers"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              সার্চ করুন
            </button>
            <button
              type="button"
              @click="openModal = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              বাতিল
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal (Laravel style) -->
    <div v-if="showDeleteConfirm" class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  আবেদন মুছে ফেলুন
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    আপনি কি নিশ্চিত যে আপনি এই আবেদনটি মুছতে চান? এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="deleteApplication"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              মুছুন
            </button>
            <button
              type="button"
              @click="showDeleteConfirm = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              বাতিল
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
body {
  font-family: 'SolaimanLipi', Arial, sans-serif;
}
</style>
