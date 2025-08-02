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
                      :class="{
                        'border-yellow-200': getStatusSeverity(data.status) === 'warning',
                        'border-green-200': getStatusSeverity(data.status) === 'success',
                        'border-red-200': getStatusSeverity(data.status) === 'danger',
                        'border-blue-200': getStatusSeverity(data.status) === 'info',
                        'border-purple-200': getStatusSeverity(data.status) === 'secondary'
                      }"
                    />
                  </template>
                </Column>

                <Column header="করণীয়" style="min-width: 10rem">
                  <template #body="{ data }">
                    <div class="flex gap-2">
                      <SplitButton
                        label="কার্যক্রম"
                        icon="pi pi-cog"
                        @click="viewApplication(data.id)"
                        :model="getActionItems(data)"
                        class="p-button-sm"
                        severity="secondary"
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
import { onMounted, onUnmounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Tag from 'primevue/tag';
import SplitButton from 'primevue/splitbutton';
import Toast from 'primevue/toast';

// Composables
import { useMarkazStatus } from '@/composables/admin/markaz/useMarkazStatus';
import { useMarkazFilters } from '@/composables/admin/markaz/useMarkazFilters';
import { useMarkazApplications } from '@/composables/admin/markaz/useMarkazApplications';
import { useMarkazActions } from '@/composables/admin/markaz/useMarkazActions';

// Initialize composables
const { statusOptions, getStatusSeverity, getStatusLabel } = useMarkazStatus();
const { tableFilters, markazTypes, clearFilter } = useMarkazFilters();
const {
  applications,
  loading,
  initializeData,
  cleanup,
  deleteApplication,
  updateApplicationStatus
} = useMarkazApplications();

const {
  viewApplication,
  getActionItems
} = useMarkazActions(updateApplicationStatus, deleteApplication);

// Lifecycle hooks
onMounted(() => {
  initializeData();
});

onUnmounted(() => {
  cleanup();
});
</script>
