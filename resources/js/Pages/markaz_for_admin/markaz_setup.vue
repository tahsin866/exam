<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <!-- Header Section -->
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
                    <Tag
                      :severity="getStatusSeverity(data.status)"
                      :value="getStatusLabel(data.status)"
                      class="px-3 py-1 text-sm font-medium rounded-full border shadow-sm"
                    />
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
    <Toast />
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
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

// PrimeVue FilterMatchMode
const FilterMatchMode = {
  STARTS_WITH: 'startsWith',
  CONTAINS: 'contains',
  EQUALS: 'equals',
  DATE_IS: 'dateIs'
};

// State
const confirm = useConfirm();
const toast = useToast();
const tableFilters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  markaz_type: { value: null, matchMode: FilterMatchMode.EQUALS },
  status: { value: null, matchMode: FilterMatchMode.EQUALS }
});
const applications = ref([]);
const loading = ref(true);

const markazTypes = ref(['দরসিয়াত', 'হিফজ', 'কিরআত']);
const statusOptions = ref([
  'অপেক্ষমান',
  'অনুমোদিত',
  'স্থগিত',
  'বাতিল',
  'বোর্ড ফেরত',
  'বোর্ড দাখিল',
  'প্রক্রিয়াধীন',
  'প্রত্যাখ্যাত',
  'ফেরত'
]);

let pollTimer = null;

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

const setupRealtime = () => {
  // Real-time updates are disabled; you can enable polling if you want.
  // pollTimer = setInterval(fetchApplications, 30000); // 30 seconds
};

onMounted(() => {
  fetchApplications();
  setupRealtime();
});
onUnmounted(() => {
  if (pollTimer) {
    clearInterval(pollTimer);
    pollTimer = null;
  }
});

const clearFilter = () => {
  tableFilters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    markaz_type: { value: null, matchMode: FilterMatchMode.EQUALS },
    status: { value: null, matchMode: FilterMatchMode.EQUALS }
  };
};

// Status Map (Bangla label + severity)
const statusMap = {
  pending: { label: 'অপেক্ষমান', severity: 'warning' },
  approved: { label: 'অনুমোদিত', severity: 'success' },
  suspended: { label: 'স্থগিত', severity: 'danger' },
  cancelled: { label: 'বাতিল', severity: 'danger' },
  board_returned: { label: 'বোর্ড ফেরত', severity: 'danger' },
  board_submitted: { label: 'বোর্ড দাখিল', severity: 'info' },
  in_processing: { label: 'প্রক্রিয়াধীন', severity: 'secondary' },
  rejected: { label: 'প্রত্যাখ্যাত', severity: 'danger' },
  returned: { label: 'ফেরত', severity: 'warning' },

  // Bangla statuses (for fallback)
  'বোর্ড দাখিল': { label: 'বোর্ড দাখিল', severity: 'info' },
  'বোর্ড ফেরত': { label: 'বোর্ড ফেরত', severity: 'danger' },
  'অনুমোদন': { label: 'অনুমোদিত', severity: 'success' },
  'অনুমোদিত': { label: 'অনুমোদিত', severity: 'success' },
  'প্রক্রিয়াধীন': { label: 'প্রক্রিয়াধীন', severity: 'secondary' },
  'অপেক্ষমান': { label: 'অপেক্ষমান', severity: 'warning' },
  'স্থগিত': { label: 'স্থগিত', severity: 'danger' },
  'বাতিল': { label: 'বাতিল', severity: 'danger' },
  'প্রত্যাখ্যাত': { label: 'প্রত্যাখ্যাত', severity: 'danger' },
  'ফেরত': { label: 'ফেরত', severity: 'warning' }
};

const getStatusSeverity = (status) => {
  return statusMap[status]?.severity || 'info';
};

const getStatusLabel = (status) => {
  return statusMap[status]?.label || status || 'অনিশ্চিত';
};

const getActionItems = (data) => [
  {
    label: 'বিস্তারিত দেখুন',
    icon: 'pi pi-eye',
    command: () => viewApplication(data.id)
  },
  {
    label: 'মুছে ফেলুন',
    icon: 'pi pi-trash',
    command: () => confirmDelete(data)
  },
  {
    label: 'আপডেট',
    icon: 'pi pi-refresh',
    command: () => {
      toast.add({ severity: 'success', summary: 'আপডেটেড', detail: 'ডেটা আপডেট হয়েছে', life: 3000 });
    }
  },
  {
    label: 'স্থগিত করুন',
    icon: 'pi pi-ban',
    command: () => {
      toast.add({ severity: 'warn', summary: 'স্থগিত', detail: 'আবেদন স্থগিত করা হয়েছে', life: 3000 });
    }
  },
  {
    label: 'প্রক্রিয়াকরণ শুরু',
    icon: 'pi pi-cog',
    command: () => {
      startProcessing(data);
    }
  },
  {
    label: 'ফেরত পাঠান',
    icon: 'pi pi-undo',
    command: () => {
      returnApplication(data);
    }
  },
  {
    label: 'বাতিল করুন',
    icon: 'pi pi-times-circle',
    command: () => {
      toast.add({ severity: 'warn', summary: 'বাতিল', detail: 'আবেদন বাতিল করা হয়েছে', life: 3000 });
    }
  },
  { separator: true },
  {
    label: 'প্রস্থান',
    icon: 'pi pi-sign-out',
    command: () => {
      window.location.href = 'https://vuejs.org/';
    }
  }
];

const viewApplication = (id) => {
  router.get(route('admin.markaz.view', id));
};

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

const deleteApplication = async (application) => {
  try {
    await axios.delete(`/api/markaz-agreements/${application.id}`);
    applications.value = applications.value.filter(app => app.id !== application.id);
    toast.add({
      severity: 'success',
      summary: 'সফল',
      detail: 'আবেদন সফলভাবে মুছে ফেলা হয়েছে',
      life: 3000
    });
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'ত্রুটি',
      detail: 'আবেদন মুছতে সমস্যা হয়েছে',
      life: 3000
    });
  }
};

const startProcessing = async (application) => {
  try {
    await router.post(route('admin.markaz.processing', application.id), {}, {
      onSuccess: () => {
        const idx = applications.value.findIndex(app => app.id === application.id);
        if (idx !== -1) {
          applications.value[idx].status = 'in_processing';
        }
        toast.add({
          severity: 'info',
          summary: 'প্রক্রিয়াকরণ শুরু',
          detail: 'আবেদন প্রক্রিয়াকরণ শুরু হয়েছে',
          life: 3000
        });
      },
      onError: (errors) => {
        toast.add({
          severity: 'error',
          summary: 'ত্রুটি',
          detail: errors?.error || 'প্রক্রিয়াকরণ শুরু করতে সমস্যা হয়েছে',
          life: 3000
        });
      }
    });
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'ত্রুটি',
      detail: 'প্রক্রিয়াকরণ শুরু করতে সমস্যা হয়েছে',
      life: 3000
    });
  }
};

const returnApplication = async (application) => {
  confirm.require({
    message: 'আপনি কি এই আবেদনটি ফেরত পাঠাতে চান?',
    header: 'নিশ্চিতকরণ',
    icon: 'pi pi-exclamation-triangle',
    acceptLabel: 'হ্যাঁ',
    rejectLabel: 'না',
    accept: async () => {
      try {
        await router.post(route('admin.markaz.return', application.id), {
          message: 'আবেদনটি ফেরত পাঠানো হয়েছে'
        }, {
          onSuccess: () => {
            const idx = applications.value.findIndex(app => app.id === application.id);
            if (idx !== -1) {
              applications.value[idx].status = 'board_returned';
            }
            toast.add({
              severity: 'warn',
              summary: 'ফেরত পাঠানো হয়েছে',
              detail: 'আবেদন সফলভাবে ফেরত পাঠানো হয়েছে',
              life: 3000
            });
          },
          onError: (errors) => {
            toast.add({
              severity: 'error',
              summary: 'ত্রুটি',
              detail: errors?.error || 'আবেদন ফেরত পাঠাতে সমস্যা হয়েছে',
              life: 3000
            });
          }
        });
      } catch (error) {
        toast.add({
          severity: 'error',
          summary: 'ত্রুটি',
          detail: 'আবেদন ফেরত পাঠাতে সমস্যা হয়েছে',
          life: 3000
        });
      }
    }
  });
};

const save = () => {
  toast.add({ severity: 'success', summary: 'Success', detail: 'Data Saved', life: 3000 });
};
</script>
