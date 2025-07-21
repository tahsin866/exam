<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <!-- Form Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">নেগরান ভাতা</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="(field, index) in allowanceFields" :key="index" class="mb-4">
                <label :for="field.id" class="block text-sm font-medium text-gray-700 mb-1">
                  <span class="flex items-center">
                    <i :class="field.icon" class="mr-2 text-gray-500"></i>
                    {{ field.label }}
                  </span>
                </label>
                <div class="relative mt-1">
                  <input
                    v-model="formData[field.id]"
                    :id="field.id"
                    type="number"
                    min="0"
                    :placeholder="field.placeholder"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    @input="validateInput(field.id)"
                  >

                </div>
                <p v-if="errors[field.id]" class="mt-1 text-sm text-red-600">{{ errors[field.id] }}</p>
              </div>
            </div>

            <div class="mt-6 flex gap-4">
              <button
                @click="submitForm"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                :disabled="isSubmitting"
              >
                <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <i v-else class="fas fa-save mr-2"></i>
                {{ isSubmitting ? 'সংরক্ষণ করা হচ্ছে...' : 'সংরক্ষণ করুন' }}
              </button>
              <button
                @click="resetForm"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <i class="fas fa-undo mr-2"></i>
                রিসেট করুন
              </button>
            </div>
          </div>
        </div>

        <!-- Display Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-lg font-medium text-gray-900">বর্তমান ভাতা তালিকা</h2>
              <span class="text-sm text-gray-500">
                আপডেট: {{ lastUpdated }}
              </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="(field, index) in allowanceFields" :key="index"
                   class="bg-gray-50 p-4 rounded-md border border-gray-200">
                <p class="text-sm font-medium text-gray-700 flex items-center">
                  <i :class="field.icon" class="mr-2 text-gray-500"></i>
                  {{ field.label }}
                </p>
                <p class="mt-2 text-lg font-medium text-gray-900">
                  {{ formatCurrency(formData[field.id]) }} ৳
                </p>
              </div>
            </div>

            <div class="mt-6 p-4 bg-gray-50 rounded-md border border-gray-200">
              <p class="flex items-center text-sm font-medium text-gray-700">
                <i class="fas fa-calculator mr-2 text-gray-500"></i>
                মোট মাসিক ভাতা: <span class="ml-2 font-semibold">{{ formatCurrency(calculateTotal()) }} ৳</span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { format } from 'date-fns';
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';

const isSubmitting = ref(false);
const lastUpdated = ref(format(new Date(), 'PPP'));
const errors = reactive({});

const allowanceFields = [
  {
    id: 'chiefAllowance',
    label: 'প্রধান নেগরান ভাতা',
    placeholder: 'পরিমাণ লিখুন',

  },
  {
    id: 'assistantChiefAllowance',
    label: 'সহকারী প্রধান নেগরান ভাতা',
    placeholder: 'পরিমাণ লিখুন',

  },
  {
    id: 'assistantAllowance',
    label: 'সহকারী নেগরান ভাতা',
    placeholder: 'পরিমাণ লিখুন',

  },
  {
    id: 'staffAllowance',
    label: 'নেগরান অফিস স্টাফ ভাতা',
    placeholder: 'পরিমাণ লিখুন',

  },
  {
    id: 'khademAllowance',
    label: 'খাদেম ভাতা',
    placeholder: 'পরিমাণ লিখুন',

  }
];

const formData = reactive({
  chiefAllowance: '',
  assistantChiefAllowance: '',
  assistantAllowance: '',
  staffAllowance: '',
  khademAllowance: ''
});

const validateInput = (fieldId) => {
  const value = formData[fieldId];
  if (value < 0) {
    errors[fieldId] = 'নেগেটিভ মান গ্রহণযোগ্য নয়';
    formData[fieldId] = '';
  } else {
    delete errors[fieldId];
  }
};

const calculateTotal = () => {
  return Object.values(formData).reduce((sum, value) => sum + (Number(value) || 0), 0);
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('bn-BD').format(value || 0);
};

const submitForm = async () => {
  try {
    isSubmitting.value = true;
    // Add your API call here
    await new Promise(resolve => setTimeout(resolve, 1000)); // Simulated API delay
    lastUpdated.value = format(new Date(), 'PPP');
    // Show success toast
  } catch (error) {
    // Show error toast
  } finally {
    isSubmitting.value = false;
  }
};

const resetForm = () => {
  Object.keys(formData).forEach(key => formData[key] = '');
};
</script>
