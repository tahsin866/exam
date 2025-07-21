<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <!-- Header -->
            <div class="mb-6">
              <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                ইউজার ম্যানেজমেন্ট সিস্টেম
              </h2>
            </div>

            <!-- Search and Add User Section -->
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
              <div class="flex-1">
                <div class="relative">
                  <span class="p-input-icon-left w-full">
                    <InputText
                      v-model="filters['global'].value"
                      placeholder="ফোন নম্বর দিয়ে সার্চ করুন"
                      class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    />
                  </span>
                </div>
              </div>
              <div>
                <Link
                  :href="route('user_create_for_admin.new_user_for_admin')"
                  class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                >
                  <i class="pi pi-plus mr-2"></i>
                  নতুন ইউজার
                </Link>
              </div>
            </div>

            <!-- PrimeVue DataTable -->
            <DataTable
              :value="admins"
              :paginator="true"
              :rows="20"
              :rowsPerPageOptions="[5, 10, 20, 50]"
              paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
              currentPageReportTemplate="মোট {totalRecords} জন ইউজারের মধ্যে {first}-{last} দেখাচ্ছে"
              responsiveLayout="scroll"
              :globalFilterFields="['name', 'phone', 'email']"
              v-model:filters="filters"
              filterDisplay="menu"
              class="p-datatable-sm"
            >
              <Column field="profile_image" header="ছবি">
                <template #body="slotProps">
                  <div class="h-10 w-10 rounded-full overflow-hidden border border-gray-200">
                    <img
                      :src="slotProps.data.profile_image ? '/storage/' + slotProps.data.profile_image : 'https://randomuser.me/api/portraits/men/1.jpg'"
                      :alt="slotProps.data.name"
                      class="h-full w-full object-cover"
                    />
                  </div>
                </template>
              </Column>
              <Column field="name" header="নাম" :sortable="true" :filter="true" filterPlaceholder="নাম খুঁজুন">
              </Column>
              <Column field="phone" header="ফোন নম্বর" :sortable="true" :filter="true" filterPlaceholder="ফোন নম্বর খুঁজুন">
              </Column>
              <Column field="email" header="ইমেইল" :sortable="true" :filter="true" filterPlaceholder="ইমেইল খুঁজুন">
              </Column>
              <Column field="designation" header="পদবি" :sortable="true" :filter="true" filterMatchMode="equals">
                <template #body="slotProps">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="getDesignationClass(slotProps.data.designation)">
                    {{ getDesignationText(slotProps.data.designation) }}
                  </span>
                </template>
                <template #filter="{ filterModel }">
                  <Dropdown
                    v-model="filterModel.value"
                    :options="designationOptions"
                    optionLabel="name"
                    optionValue="value"
                    placeholder="পদবি বাছাই করুন"
                    class="p-column-filter"
                    showClear
                  />
                </template>
              </Column>
              <Column header="একশন">
                <template #body="slotProps">
                  <div class="flex space-x-2">
                    <Toast />
                    <ConfirmPopup></ConfirmPopup>
                    <Dialog v-model:visible="showModal" :style="{width: '450px'}" header="এডমিন ডিলিট করুন" :modal="true">
                      <div class="flex items-start">
                        <i class="pi pi-exclamation-triangle mr-3 text-red-500" style="font-size: 2rem"></i>
                        <div>
                          <p class="text-sm text-gray-500">
                            আপনি কি নিশ্চিত যে আপনি এই এডমিন ডিলিট করতে চান? এই কাজটি অপরিবর্তনীয়।
                          </p>
                        </div>
                      </div>
                      <template #footer>
                        <Button label="বাতিল করুন" icon="pi pi-times" class="p-button-text" @click="showModal = false" />
                        <Button label="ডিলিট করুন" icon="pi pi-check" class="p-button-danger" @click="deleteAdmin" />
                      </template>
                    </Dialog>
                    <SplitButton
                      label="সংশোধন"
                      @click="editUser(slotProps.data.id)"
                      :model="getActionItems(slotProps.data.id)"
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
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

// PrimeVue Components
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import ConfirmPopup from 'primevue/confirmpopup';
import SplitButton from 'primevue/splitbutton';
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";

const toast = useToast();
const confirm = useConfirm();
const showModal = ref(false);
const selectedUserId = ref(null);

const editUser = (userId) => {
  router.visit(route('user_create_for_admin.admin_user_edit', userId));
};

const viewUser = (userId) => {
  // Implement view user functionality
  toast.add({ severity: 'info', summary: 'View', detail: 'Viewing user details', life: 3000 });
};

const confirmDelete = (userId) => {
  adminIdToDelete.value = userId;
  showModal.value = true;
};

const deleteAdmin = () => {
  // Inertia.js ব্যবহার করে ডিলিট রিকোয়েস্ট পাঠানো
  router.delete(route('user_create_for_admin.destroy', adminIdToDelete.value), {
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'সফল',
        detail: 'এডমিন সফলভাবে ডিলিট করা হয়েছে',
        life: 3000
      });
    },
    onError: () => {
      toast.add({
        severity: 'error',
        summary: 'ত্রুটি',
        detail: 'এডমিন ডিলিট করতে সমস্যা হয়েছে',
        life: 3000
      });
    }
  });
  showModal.value = false;
};

const checkHistory = (userId) => {
  // Implement check history functionality
  toast.add({ severity: 'info', summary: 'History', detail: 'Checking user history', life: 3000 });
};

const getActionItems = (userId) => {
  return [
    {
      label: 'ভিউ',
      icon: 'pi pi-eye',
      command: () => {
        viewUser(userId);
      }
    },
    {
      label: 'ডিলিট',
      icon: 'pi pi-trash',
      command: () => {
        confirmDelete(userId);
      }
    },
    {
      label: 'চেক হিস্টরি',
      icon: 'pi pi-history',
      command: () => {
        checkHistory(userId);
      }
    }
  ];
};

const FilterMatchMode = {
  STARTS_WITH: 'startswith',
  CONTAINS: 'contains',
  EQUALS: 'equals',
  DATE_IS: 'dateIs'
};

const admins = ref([]);

// Designation options for dropdown filter
const designationOptions = ref([
  { name: 'সুপার এডমিন', value: 1 },
  { name: 'সহ সুপার এডমিন', value: 2 },
  { name: 'বোর্ড এডমিন', value: 3 }
]);

// Initialize filters
const filters = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
  'name': { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  'phone': { value: null, matchMode: FilterMatchMode.CONTAINS },
  'email': { value: null, matchMode: FilterMatchMode.CONTAINS },
  'designation': { value: null, matchMode: FilterMatchMode.EQUALS }
});

onMounted(() => {
  fetchAdmins();
});

const fetchAdmins = async () => {
  try {
    const response = await axios.get('/api/admins');
    admins.value = response.data;
  } catch (error) {
    console.error('Error fetching admins:', error);
  }
};

const getDesignationText = (designation) => {
  if (designation == 1) {
    return 'সুপার এডমিন';
  } else if (designation == 2) {
    return 'সহ সুপার এডমিন';
  } else {
    return 'বোর্ড এডমিন';
  }
};

const getDesignationClass = (designation) => {
  if (designation == 1) {
    return 'bg-purple-100 text-purple-800';
  } else if (designation == 2) {
    return 'bg-gray-100 text-gray-800';
  } else {
    return 'bg-gray-100 text-gray-800';
  }
};

const adminIdToDelete = ref(null);
</script>

<style>
/* Laravel Breeze style customizations */
.p-datatable .p-datatable-thead > tr > th {
  @apply bg-gray-50 text-gray-700 text-sm font-medium px-6 py-3 border-b border-gray-200;
}

.p-datatable .p-datatable-tbody > tr {
  @apply border-b border-gray-200 hover:bg-gray-50;
}

.p-datatable .p-datatable-tbody > tr > td {
  @apply px-6 py-4 text-sm text-gray-500;
}

.p-datatable .p-paginator {
  @apply bg-white border-t border-gray-200 px-4 py-3;
}

.p-datatable .p-paginator .p-paginator-pages .p-paginator-page.p-highlight {
  @apply bg-gray-800 text-white;
}

.p-dropdown-panel .p-dropdown-items .p-dropdown-item.p-highlight {
  @apply bg-gray-100 text-gray-900;
}

.p-inputtext:enabled:focus {
  @apply border-indigo-300 ring ring-indigo-200 ring-opacity-50;
}

.p-button.p-button-sm {
  @apply text-xs px-2.5 py-1.5;
}

.p-splitbutton.p-button-sm > .p-button {
  @apply text-xs px-2.5 py-1.5;
}

.p-splitbutton-menubutton {
  @apply border-l border-gray-300;
}

/* Dark mode support */
.dark .p-datatable .p-datatable-thead > tr > th {
  @apply bg-gray-800 text-gray-200 border-gray-700;
}

.dark .p-datatable .p-datatable-tbody > tr {
  @apply border-gray-700 hover:bg-gray-700;
}

.dark .p-datatable .p-datatable-tbody > tr > td {
  @apply text-gray-300;
}

.dark .p-datatable .p-paginator {
  @apply bg-gray-800 border-gray-700;
}

.dark .p-dropdown-panel {
  @apply bg-gray-800 text-gray-200;
}

.dark .p-dropdown-item {
  @apply text-gray-200;
}

.dark .p-dropdown-item:hover {
  @apply bg-gray-700;
}

.dark .p-inputtext {
  @apply bg-gray-800 text-gray-200 border-gray-700;
}
</style>
