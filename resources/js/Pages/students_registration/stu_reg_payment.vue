<template>
  <AuthenticatedLayout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
      <!-- Main Card -->
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <!-- Header -->
        <div class="bg-yellow-500 p-4 text-center">
          <h2 class="text-xl font-bold text-gray-800">
            ৪৮তম কেন্দ্রীয় পরীক্ষা: নিবন্ধন পেমেন্ট সিস্টেম
          </h2>
        </div>

        <!-- Content -->
        <div class="p-6">
          <!-- Loading indicator -->
          <div v-if="isLoading" class="flex flex-col items-center justify-center py-8">
            <div class="w-12 h-12 border-4 border-gray-200 border-t-blue-500 rounded-full animate-spin"></div>
            <p class="mt-4 text-lg text-gray-700">লোড হচ্ছে...</p>
          </div>

          <!-- Table -->
          <div v-else class="overflow-x-auto">
            <DataTable
              :value="desserts"
              stripedRows
              responsiveLayout="scroll"
              class="p-datatable-sm"
            >
              <Column field="Marhala" header="মারহালা" style="min-width: 120px"></Column>
              <Column field="regularCount" header="নিয়মিত ছাত্র">
                <template #body="slotProps">
                  {{ slotProps.data.regularCount }} জন
                </template>
              </Column>
              <Column field="regularFee" header="নিয়মিত ফি">
                <template #body="slotProps">
                  {{ slotProps.data.regularFee }} টাকা
                </template>
              </Column>
              <Column field="regularTotalFee" header="নিয়মিত মোট ফি">
                <template #body="slotProps">
                  {{ slotProps.data.regularTotalFee }} টাকা
                </template>
              </Column>
              <Column field="irregularCount" header="অনিয়মিত ছাত্র">
                <template #body="slotProps">
                  {{ slotProps.data.irregularCount }} জন
                </template>
              </Column>
              <Column field="irregularFee" header="অনিয়মিত ফি">
                <template #body="slotProps">
                  {{ slotProps.data.irregularFee }} টাকা
                </template>
              </Column>
              <Column field="isPaid" header="পেমেন্ট স্ট্যাটাস">
                <template #body="slotProps">
                  <span
                    v-if="slotProps.data.isPaid"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                  >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    পরিশোধিত
                  </span>
                  <span
                    v-else
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                  >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    অপরিশোধিত
                  </span>
                </template>
              </Column>
              <Column header="একশন">
                <template #body="slotProps">
                  <button
                    v-if="!slotProps.data.isPaid"
                    @click="openPaymentDialog(slotProps.data.Marhala, slotProps.data.regularTotalFee + slotProps.data.irregularTotalFee)"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                  >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    পেমেন্ট করুন
                  </button>
                  <span
                    v-else
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                  >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    সম্পন্ন
                  </span>
                </template>
              </Column>
            </DataTable>
          </div>
        </div>
      </div>

      <!-- Laravel-style Modal -->
      <div v-if="isDialogVisible" class="fixed inset-0 overflow-y-auto z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <!-- Background overlay -->
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

          <!-- Modal panel -->
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Header -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                  <h3 class="text-lg leading-6 font-medium text-gray-900 border-b pb-3 mb-4" id="modal-title">
                    পেমেন্ট নিশ্চিতকরণ
                  </h3>

                  <div class="flex flex-col items-center w-full">
                    <h3 class="text-lg font-semibold text-center text-green-800 mb-3">
                      {{ selectedMarhala }} - পেমেন্ট নিশ্চিতকরণ
                    </h3>

                    <!-- Loading indicator -->
                    <div v-if="isLoading" class="flex flex-col items-center justify-center py-4">
                      <div class="w-12 h-12 border-4 border-gray-200 border-t-blue-500 rounded-full animate-spin"></div>
                      <p class="mt-3 text-gray-700">লোড হচ্ছে...</p>
                    </div>

                    <div v-else class="w-full">
                      <div class="mt-3 text-center">
                        <div class="flex justify-between items-center p-3 border-b border-gray-200">
                          <span class="text-gray-700">নতুন ছাত্র সংখ্যা:</span>
                          <span class="font-semibold">{{ newStudentsCount }} জন</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-green-50">
                          <span class="text-gray-700">মোট পরিমাণ:</span>
                          <span class="text-xl font-bold text-green-700">{{ totalAmount }} টাকা</span>
                        </div>
                      </div>

                      <!-- Success Message -->
                      <div v-if="successMessage" class="mt-4 bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex">
                          <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                          </div>
                          <div class="ml-3">
                            <p class="text-sm text-green-700">{{ successMessage }}</p>
                          </div>
                        </div>
                      </div>

                      <!-- Error Message -->
                      <div v-if="errorMessage" class="mt-4 bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="flex">
                          <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                          </div>
                          <div class="ml-3">
                            <p class="text-sm text-red-700">{{ errorMessage }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                @click="confirmPayment"
                :disabled="isProcessing || isLoading || newStudentsCount === 0"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <svg v-if="isProcessing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                নিশ্চিত করুন
              </button>
              <button
                type="button"
                @click="isDialogVisible = false"
                :disabled="isProcessing || isLoading"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50
                disabled:cursor-not-allowed"
              >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                বাতিল
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

const desserts = ref([]);
const payments = ref({});
const isDialogVisible = ref(false);
const selectedMarhala = ref('');
const totalAmount = ref(0);
const newStudentsCount = ref(0);
const isLoading = ref(true);
const isProcessing = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

onMounted(async () => {
  try {
    isLoading.value = true;
    const response = await axios.get('/api/payment-stats');
    desserts.value = response.data;
    // পেমেন্ট স্ট্যাটাস চেক করা
    await checkPaymentStatus();
    isLoading.value = false;
  } catch (error) {
    console.error('Error fetching payment stats:', error);
    isLoading.value = false;
  }
});

// পেমেন্ট স্ট্যাটাস চেক করার ফাংশন
const checkPaymentStatus = async () => {
  try {
    const response = await axios.get('/api/check-payment-status');
    // পেমেন্ট স্ট্যাটাস আপডেট করা
    if (response.data && response.data.length > 0) {
      response.data.forEach(item => {
        payments.value[item.class] = item.is_paid ? 'paid' : null;
        // Update the isPaid property in desserts array
        const dessertIndex = desserts.value.findIndex(d => d.Marhala === item.class);
        if (dessertIndex !== -1) {
          desserts.value[dessertIndex].isPaid = item.is_paid;
        }
      });
    }
  } catch (error) {
    console.error('Error checking payment status:', error);
  }
};

const openPaymentDialog = async (marhala, fee) => {
  try {
    isLoading.value = true;
    // শুধু নতুন ছাত্রদের তথ্য নিয়ে আসা - exam_id পারামিটার যোগ করা
    const response = await axios.get(`/api/unpaid-students-count?class=${marhala}`);
    // সঠিক ছাত্র সংখ্যা এবং মোট পরিমাণ নেওয়া
    newStudentsCount.value = response.data.count;
    totalAmount.value = response.data.total_amount;
    selectedMarhala.value = marhala;
    isDialogVisible.value = true;
    // Reset messages
    successMessage.value = '';
    errorMessage.value = '';
    isLoading.value = false;
  } catch (error) {
    console.error('Error fetching unpaid students:', error);
    isLoading.value = false;
    errorMessage.value = 'ছাত্রদের তথ্য লোড করতে সমস্যা হয়েছে।';
  }
};

const confirmPayment = async () => {
  try {
    isProcessing.value = true;
    // পেমেন্ট ডাটা সার্ভারে পাঠানো
    const response = await axios.post('/api/store-payment', {
      class: selectedMarhala.value,
      total_amount: totalAmount.value
    });
    // সফল হলে
    payments.value[selectedMarhala.value] = 'paid';
    successMessage.value = response.data.message;
    // Update the desserts array to mark the current marhala as paid
    const marhalaToPay = selectedMarhala.value;
    desserts.value = desserts.value.map(item => {
      if (item.Marhala === marhalaToPay) {
        return { ...item, isPaid: true };
      }
      return item;
    });
    // 3 সেকেন্ড পর ডায়ালগ বন্ধ করা
    setTimeout(() => {
      isDialogVisible.value = false;
      successMessage.value = '';
    }, 3000);
  } catch (error) {
    console.error('Payment error:', error);
    errorMessage.value = 'পেমেন্ট প্রক্রিয়াকরণে সমস্যা হয়েছে। আবার চেষ্টা করুন।';
  } finally {
    isProcessing.value = false;
  }
};
</script>

<style scoped>


</style>
