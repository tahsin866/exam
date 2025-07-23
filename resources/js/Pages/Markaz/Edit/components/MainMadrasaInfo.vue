<script setup>
import { defineProps, defineEmits, computed } from 'vue'
import InputNumber from 'primevue/inputnumber'
import FileUpload from 'primevue/fileupload'

const props = defineProps({ form: Object, markazData: Object })
const emit = defineEmits(['update:file'])

const fieldLabels = {
  fazilat: 'ফযীলত',
  sanabiya_ulya: 'সানাবিয়া উলইয়া',
  sanabiya: 'সানাবিয়া',
  mutawassita: 'মুতাওয়াসসিতা',
  ibtedaiyyah: 'ইবতেদাইয়্যাহ',
  hifzul_quran: 'হিফজুল কোরআন',
  qirat: 'ইলমুল কিরাআত'
}

function onFileSelect(field, event) {
  const file = event.files && event.files.length > 0 ? event.files[0] : null
  emit('update:file', field, file)
}

// Check if the field has a value from the database
const hasValueFromDatabase = (field) => {
  return props.form && props.form[field] !== undefined && props.form[field] !== null && props.form[field] !== '';
}

// Determine if a field is editable based on whether it has a database value
// Only fields with existing data from the database can be edited
const isFieldEditable = (field) => {
  return hasValueFromDatabase(field);
}

// Get required fields based on markaz_type
const requiredFields = computed(() => {
  const markazType = props.form.markaz_type;

  if (markazType === 'দরসিয়াত') {
    return ['fazilat', 'sanabiya_ulya'];
  } else if (markazType === 'তাহফিজুল কোরআন') {
    return ['hifzul_quran'];
  } else if (markazType === 'কিরাআত') {
    return ['qirat'];
  }

  return [];
});

// Check if a field is required
const isRequired = (field) => {
  return requiredFields.value.includes(field);
}

// Check if field should be visible based on its relevance to markaz type
const shouldDisplayField = (field) => {
  return true; // Display all fields in edit form for consistency
}
</script>

<template>
  <div class="p-6 bg-white rounded-md shadow-md border border-slate-300">
    <h2 class="text-2xl font-bold text-black mb-6">আবেদনকৃত মাদরাসার তথ্য</h2>
    <div class="flex flex-wrap gap-6">
      <div
        v-for="field in Object.keys(fieldLabels)"
        :key="field"
        class="flex-1 min-w-[150px]"
      >
        <label :for="field" class="block text-lg font-medium text-slate-700 mb-2">
          {{ fieldLabels[field] }}
          <span v-if="isRequired(field)" class="text-red-500">*</span>
          <span v-if="!isFieldEditable(field)" class="ml-2 text-sm text-red-500">

          </span>
        </label>
        <InputNumber
          :id="field"
          v-model="form[field]"
          class="w-full"
          inputClass="w-full px-4 py-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-purple-600 focus:border-purple-600 bg-white shadow-sm"
          :class="{ 'non-editable-field': !isFieldEditable(field) }"
          :placeholder="isFieldEditable(field) ? `${fieldLabels[field]} ছাত্র সংখ্যা লিখুন` : 'ডাটা নেই'"
          :min="0"
          :disabled="!isFieldEditable(field)"
          :tabindex="!isFieldEditable(field) ? -1 : 0"
        />
        <div v-if="isRequired(field) && !form[field] && isFieldEditable(field)" class="mt-1 text-sm text-red-600">
          এই ফিল্ডটি পূরণ করা আবশ্যক
        </div>
      </div>
    </div>

    <!-- নিচে নতুন রো, দুই কলামে ফাইল ফিল্ড -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
      <!-- NOC File Upload -->
      <div>
        <label class="block text-lg font-medium text-slate-700 mb-2">
          এনওসি (NOC) ফাইল <span class="text-red-500">*</span>
        </label>
        <div class="flex items-center">
          <FileUpload
            mode="basic"
            :auto="true"
            accept=".pdf,.jpg,.jpeg,.png"
            chooseLabel="ফাইল নির্বাচন করুন"
            @select="onFileSelect('noc_file', $event)"
            :customUpload="true"
            class="w-full"
          />
        </div>

        <!-- Show existing file from database -->
        <div v-if="markazData && markazData.noc_file_url" class="mt-3 p-3 bg-gray-50 border rounded-md flex items-center">
          <i class="pi pi-file-pdf text-red-500 text-xl mr-2"></i>
          <div>
            <div class="font-medium">বর্তমান ফাইল:</div>
            <a :href="markazData.noc_file_url" target="_blank" class="text-blue-600 hover:underline text-sm">
              {{ markazData.noc_file_url ? markazData.noc_file_url.split('/').pop() : 'ফাইল দেখুন' }}
            </a>
          </div>
        </div>
        
        <!-- Show newly uploaded file -->
        <div v-if="form.noc_file && typeof form.noc_file === 'object'" class="mt-3 p-3 bg-gray-50 border rounded-md flex items-center">
          <i class="pi pi-upload text-green-500 text-xl mr-2"></i>
          <div>
            <div class="font-medium">নতুন ফাইল:</div>
            <span class="text-sm">{{ form.noc_file.name }}</span>
          </div>
        </div>
      </div>

      <!-- Resolution File Upload -->
      <div>
        <label class="block text-lg font-medium text-slate-700 mb-2">
          রেজল্যুশন (Resolution) ফাইল <span class="text-red-500">*</span>
        </label>
        <div class="flex items-center">
          <FileUpload
            mode="basic"
            :auto="true"
            accept=".pdf,.jpg,.jpeg,.png"
            chooseLabel="ফাইল নির্বাচন করুন"
            @select="onFileSelect('resolution_file', $event)"
            :customUpload="true"
            class="w-full"
          />
        </div>

        <!-- Show existing file from database -->
        <div v-if="markazData && markazData.resolution_file_url" class="mt-3 p-3 bg-gray-50 border rounded-md flex items-center">
          <i class="pi pi-file-pdf text-red-500 text-xl mr-2"></i>
          <div>
            <div class="font-medium">বর্তমান ফাইল:</div>
            <a :href="markazData.resolution_file_url" target="_blank" class="text-blue-600 hover:underline text-sm">
              {{ markazData.resolution_file_url ? markazData.resolution_file_url.split('/').pop() : 'ফাইল দেখুন' }}
            </a>
          </div>
        </div>
        
        <!-- Show newly uploaded file -->
        <div v-if="form.resolution_file && typeof form.resolution_file === 'object'" class="mt-3 p-3 bg-gray-50 border rounded-md flex items-center">
          <i class="pi pi-upload text-green-500 text-xl mr-2"></i>
          <div>
            <div class="font-medium">নতুন ফাইল:</div>
            <span class="text-sm">{{ form.resolution_file.name }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
:deep(.p-inputnumber) {
  width: 100%;
}

:deep(.p-inputnumber-input) {
  width: 100% !important;
}

:deep(.p-fileupload-buttonbar) {
  display: flex;
  padding: 0;
}

:deep(.p-fileupload-content) {
  display: none;
}

:deep(.p-button) {
  width: 100%;
}

:deep(.p-button.p-disabled) {
  opacity: 0.7;
  cursor: not-allowed;
}

.non-editable-field :deep(.p-inputnumber-input) {
  background-color: #f3f4f6;
  cursor: not-allowed;
  color: #6b7280;
  border-color: #d1d5db;
}
</style>
