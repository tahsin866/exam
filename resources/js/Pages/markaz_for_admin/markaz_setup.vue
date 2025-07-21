<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <!-- Header Section -->
            <div class="mb-6">
              <h1 class="text-2xl font-semibold text-gray-800">
                মারকায আবেদন ব্যবস্থাপনা
              </h1>
              <p class="text-gray-600">কেন্দ্রীয় মাদ্রাসা আবেদন ব্যবস্থাপনা সিস্টেম</p>
            </div>

            <!-- Search Panel -->
            <div class="mb-6 p-4 bg-gray-50 rounded-md border border-gray-200">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">সার্চ করুন</label>
                  <span class="p-input-icon-left w-full">

                    <InputText v-model="tableFilters['global'].value" placeholder="সার্চ করুন..." class="w-full" />
                  </span>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">আবেদনকারীর ধরন</label>
                  <Dropdown
                    v-model="tableFilters['markaz_type'].value"
                    :options="markazTypes"
                    placeholder="ধরন নির্বাচন করুন"
                    class="w-full"
                    showClear
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">বোর্ড স্ট্যাটাস</label>
                  <Dropdown
                    v-model="tableFilters['status'].value"
                    :options="statusOptions"
                    placeholder="স্ট্যাটাস নির্বাচন করুন"
                    class="w-full"
                    showClear
                  />
                </div>
              </div>
              <div class="mt-4 flex justify-end">
                <Button type="button" icon="pi pi-filter-slash" label="ফিল্টার মুছুন" outlined @click="clearFilter()" />
              </div>
            </div>

            <!-- Results Table -->
            <div class="bg-white rounded-md shadow-sm border border-gray-200 overflow-hidden">
              <div class="bg-gray-50 p-4 flex justify-between items-center border-b border-gray-200">
                <h2 class="text-gray-700 text-lg font-medium flex items-center gap-2">
                  <i class="fas fa-list"></i>
                  আবেদন তালিকা
                </h2>
                <div class="text-gray-600">
                  মোট আবেদন: {{ applications.length }}
                </div>
              </div>

              <div class="p-4">
                <DataTable
                  v-model:filters="tableFilters"
                  :value="applications"
                  paginator
                  showGridlines
                  :rows="10"
                  dataKey="id"
                  filterDisplay="menu"
                  :loading="loading"
                  :globalFilterFields="['number', 'madrasha_Name', 'Elhaq_no', 'madrasha_code', 'markaz_type', 'status']"
                  class="p-datatable-sm"
                  sortMode="multiple"
                >
                  <template #empty>
                    <div class="text-center p-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                      <p class="text-gray-500 text-lg">কোন আবেদন পাওয়া যায়নি</p>
                    </div>
                  </template>

                  <template #loading>
                    <div class="flex flex-col items-center justify-center p-8">
                      <div class="w-16 h-16 border-4 border-gray-200 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
                      <p class="text-indigo-700">লোড হচ্ছে...</p>
                    </div>
                  </template>

                  <Column field="id" header="ক্রমিক নং" style="min-width: 5rem" sortable>
                    <template #body="{ index }">
                      {{ index + 1 }}
                    </template>
                  </Column>

                  <Column field="number" header="নম্বর" style="min-width: 8rem" sortable>
                  </Column>

                  <Column field="madrasha_Name" header="মাদ্রাসার নাম" style="min-width: 12rem" sortable>
                  </Column>

                  <Column field="Elhaq_no" header="এলহাক নং" style="min-width: 8rem" sortable>
                  </Column>

                  <Column field="madrasha_code" header="মাদ্রাসা কোড" style="min-width: 8rem" sortable>
                  </Column>

                  <Column field="studentNumber" header="ছাত্র সংখ্যা" style="min-width: 8rem" sortable>
                  </Column>

                  <Column field="markaz_type" header="আবেদনকারীর ধরন" style="min-width: 10rem" sortable>
                  </Column>

                  <Column field="madrasahNumber" header="মাদ্রাসা নম্বর" style="min-width: 8rem" sortable>
                  </Column>

                  <Column field="status" header="বোর্ড স্ট্যাটাস" style="min-width: 10rem" sortable>
                    <template #body="{ data }">
                      <Tag :severity="getStatusSeverity(data.status || '')" :value="data.status || 'অনিশ্চিত'" />
                    </template>
                  </Column>

                  <Column header="করণীয়" style="min-width: 10rem">
                    <template #body="{ data }">
                      <div class="flex gap-2">
                        <SplitButton
                          label="Action"
                          icon="pi pi-cog"
                          @click="viewApplication(data.id)"
                          :model="getActionItems(data)"
                          class="p-button-sm"
                        />
                      </div>
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Toast />
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { Link, router } from '@inertiajs/vue3';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Tag from 'primevue/tag';
import SplitButton from 'primevue/splitbutton';
import Toast from 'primevue/toast';

const confirm = useConfirm();
const toast = useToast();

const FilterMatchMode = {
  STARTS_WITH: 'startsWith',
  CONTAINS: 'contains',
  EQUALS: 'equals',
  DATE_IS: 'dateIs'
};

// PrimeVue DataTable filters
const tableFilters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  markaz_type: { value: null, matchMode: FilterMatchMode.EQUALS },
  status: { value: null, matchMode: FilterMatchMode.EQUALS }
});

// Sample applications data
const applications = ref([]);
const loading = ref(true);

// Options for dropdown filters
const markazTypes = ref(['দরসিয়াত', 'হিফজ', 'কিরআত']);
const statusOptions = ref(['বোর্ড দাখিল', 'বোর্ড ফেরত', 'অনুমোদন']);

// Fetch applications data
const fetchApplications = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/markaz-agreements');
    applications.value = response.data;
  } catch (error) {
    console.error("Error fetching data:", error);
    toast.add({
      severity: 'error',
      summary: 'ত্রুটি',
      detail: 'আবেদন লোড করতে সমস্যা হয়েছে',
      life: 3000
    });
  } finally {
    loading.value = false;
  }
};

onMounted(fetchApplications);

// Clear all filters
const clearFilter = () => {
  tableFilters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    markaz_type: { value: null, matchMode: FilterMatchMode.EQUALS },
    status: { value: null, matchMode: FilterMatchMode.EQUALS }
  };
};

// Get severity for status tag
const getStatusSeverity = (status) => {
  switch (status) {
    case 'বোর্ড দাখিল':
      return 'warning';
    case 'বোর্ড ফেরত':
      return 'danger';
    case 'অনুমোদন':
      return 'success';
    default:
      return 'info';
  }
};

// Get action items for split button
const getActionItems = (data) => {
  return [
    {
      label: 'View',
      icon: 'pi pi-eye',
      command: () => {
        viewApplication(data.id);
      }
    },
    {
      label: 'Delete',
      icon: 'pi pi-trash',
      command: () => {
        confirmDelete(data);
      }
    },
    {
      separator: true
    },
    {
      label: 'Update',
      icon: 'pi pi-refresh',
      command: () => {
        toast.add({ severity: 'success', summary: 'Updated', detail: 'Data Updated', life: 3000 });
      }
    },
    {
      label: 'Quit',
      icon: 'pi pi-sign-out',
      command: () => {
        window.location.href = 'https://vuejs.org/';
      }
    }
  ];
};

// View application details
const viewApplication = (id) => {
  router.get(route('AdminMarkaz.view', id));
};

// Delete application
const confirmDelete = (application) => {
  confirm.require({
    message: 'আপনি কি এই আবেদনটি মুছে ফেলতে চান?',
    header: 'নিশ্চিতকরণ',
    icon: 'pi pi-exclamation-triangle',
    acceptLabel: 'হ্যাঁ',
    rejectLabel: 'না',
    accept: () => {
      deleteApplication(application);
    }
  });
};

const deleteApplication = (application) => {
  try {
    // Here you would typically make an API call to delete the application
    // axios.delete(`/api/markaz-agreements/${application.id}`);
    // For now, just filter it out from the local array
    applications.value = applications.value.filter(app => app.id !== application.id);
    toast.add({
      severity: 'success',
      summary: 'সফল',
      detail: 'আবেদন সফলভাবে মুছে ফেলা হয়েছে',
      life: 3000
    });
  } catch (error) {
    console.error("Error deleting application:", error);
    toast.add({
      severity: 'error',
      summary: 'ত্রুটি',
      detail: 'আবেদন মুছতে সমস্যা হয়েছে',
      life: 3000
    });
  }
};

const save = () => {
  toast.add({ severity: 'success', summary: 'Success', detail: 'Data Saved', life: 3000 });
};
</script>
