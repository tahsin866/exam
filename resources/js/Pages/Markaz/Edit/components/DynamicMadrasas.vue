<script setup>
import { ref, defineProps, defineEmits, watch, computed } from 'vue'
import InputNumber from 'primevue/inputnumber'
import AutoComplete from 'primevue/autocomplete'
import Button from 'primevue/button'
import FileUpload from 'primevue/fileupload'

const props = defineProps({
  rows: Array,
  madrashas: {
    type: Array,
    default: () => []
  },
  markazType: {
    type: String,
    default: ''
  }
})
const emit = defineEmits(['update:rows'])

// For instant suggestion, keep a cache for current search
const suggestionCache = ref({})

// Computed properties to control field visibility based on markaz_type
const showDarsiyatFields = computed(() => props.markazType === 'দরসিয়াত');
const showHifzField = computed(() => props.markazType === 'তাহফিজুল কোরআন');
const showKiratField = computed(() => props.markazType === 'কিরাআত');

// Watch for madrashas data update, clear cache
watch(() => props.madrashas, () => {
  suggestionCache.value = {}
})

// Field label mapping
const fieldLabels = {
  fazilat: 'ফযীলত',
  sanabiya_ulya: 'সানাবিয়া উলইয়া',
  sanabiya: 'সানাবিয়া',
  mutawassita: 'মুতাওয়াসসিতা',
  ibtedaiyyah: 'ইবতেদাইয়্যাহ',
  hifzul_quran: 'হিফজুল কোরআন',
  qirat: 'ইলমুল কিরাআত'
}

// Fast suggestion: filter instantly and cache by query for session performance
const getSuggestions = (query) => {
  if (!props.madrashas || !Array.isArray(props.madrashas) || !query) return [];
  const q = query.toLowerCase().trim();
  if (suggestionCache.value[q]) return suggestionCache.value[q];
  // Filtering
  const filtered = props.madrashas.filter(madrasha => {
    const name = (madrasha.name || '').toLowerCase();
    const elhaqNo = (madrasha.ElhaqNo || '').toString().toLowerCase();
    return name.includes(q) || elhaqNo.includes(q);
  });
  suggestionCache.value[q] = filtered;
  return filtered;
};

const addRow = () => {
  const newRow = {
    fazilat: "",
    sanabiya_ulya: "",
    sanabiya: "",
    mutawassita: "",
    ibtedaiyyah: "",
    hifzul_quran: "",
    qirat: "",
    madrasa_Name: "",
    searchQuery: "",
    selectedMadrasha: null,
    files: {
      noc: null,
      nocPreview: null,
      resolution: null,
      resolutionPreview: null
    }
  }
  emit('update:rows', [...props.rows, newRow])
}

const removeRow = idx => {
  if (props.rows.length > 1) {
    const newRows = [...props.rows]
    newRows.splice(idx, 1)
    emit('update:rows', newRows)
  }
}

const onMadrashaSelect = (madrasha, row) => {
  row.selectedMadrasha = madrasha
  row.madrasa_Name = madrasha.name
  row.madrasa_id = madrasha.id // Make sure to set the madrasa_id for the backend
  row.searchQuery = `${madrasha.name} (ইলহাক: ${madrasha.ElhaqNo})`
}

const onFileSelect = (field, row, event) => {
  const file = event.files && event.files.length > 0 ? event.files[0] : null
  row.files[field] = file
  // For preview (if needed):
  if (file && file.type.startsWith("image/")) {
    const reader = new FileReader()
    reader.onload = e => {
      row.files[`${field}Preview`] = e.target.result
    }
    reader.readAsDataURL(file)
  } else {
    row.files[`${field}Preview`] = null
  }
}

// Helper function to check if a field has a value
const hasValue = (row, field) => {
  return row && row[field] !== undefined && row[field] !== null && row[field] !== '';
}

// Check if the required fields are filled based on markaz type
const isRequiredFieldEmpty = (row, field) => {
  if (!row.selectedMadrasha) return false; // Don't show error until madrasa is selected

  if (props.markazType === 'দরসিয়াত') {
    if ((field === 'fazilat' || field === 'sanabiya_ulya') && !hasValue(row, field)) {
      return true;
    }
  } else if (props.markazType === 'তাহফিজুল কোরআন') {
    if (field === 'hifzul_quran' && !hasValue(row, field)) {
      return true;
    }
  } else if (props.markazType === 'কিরাআত') {
    if (field === 'qirat' && !hasValue(row, field)) {
      return true;
    }
  }
  return false;
}

// Check if a field is required based on markaz type
const isFieldRequired = (field) => {
  if (props.markazType === 'দরসিয়াত') {
    return field === 'fazilat' || field === 'sanabiya_ulya';
  } else if (props.markazType === 'তাহফিজুল কোরআন') {
    return field === 'hifzul_quran';
  } else if (props.markazType === 'কিরাআত') {
    return field === 'qirat';
  }
  return false;
}

// Check if a field should be visible based on markaz type
const isFieldVisible = (field) => {
  // For fields that have existing data, always show them
  if (props.rows.some(row => hasValue(row, field))) {
    return true;
  }

  if (props.markazType === 'দরসিয়াত') {
    return ['fazilat', 'sanabiya_ulya', 'sanabiya', 'mutawassita', 'ibtedaiyyah'].includes(field);
  } else if (props.markazType === 'তাহফিজুল কোরআন') {
    return field === 'hifzul_quran';
  } else if (props.markazType === 'কিরাআত') {
    return field === 'qirat';
  }
  return true; // Default show all if no type selected
}
</script>

<template>
  <div class="mb-8 mt-6">
    <h3 class="text-xl font-bold text-black mb-4 arabic-font">
      আবেদনকৃত মাদরাসায় পরীক্ষা দিতে ইচ্ছুক মাদরাসার তালিকা ও তথ্য
    </h3>
    <div class="flex flex-col gap-6">
      <div v-for="(row, idx) in rows" :key="idx" class="bg-white p-6 rounded-md shadow-md border border-slate-300">
        <!-- Madrasa Search Field -->
        <div class="mb-6">
          <label class="block text-lg font-medium text-slate-700 mb-2 arabic-font">
            মাদরাসা নির্বাচন করুন <span class="text-red-500">*</span>
          </label>
          <AutoComplete
            v-model="row.searchQuery"
            :suggestions="getSuggestions(row.searchQuery)"
            field="name"
            placeholder="মাদরাসার নাম বা ইলহাক নম্বর দিয়ে খুঁজুন"
            class="w-full"
            dropdown
            @item-select="({ value }) => onMadrashaSelect(value, row)"
            optionLabel="name"
            :delay="0"
          >
            <template #item="slotProps">
              <div>
                <div class="font-medium">{{ slotProps.option.name }}</div>
                <div class="text-sm text-gray-500">ইলহাক: {{ slotProps.option.ElhaqNo }}</div>
              </div>
            </template>
          </AutoComplete>
          <!-- Selected Madrasha Info & Clear Option-->
          <div v-if="row.selectedMadrasha" class="mt-2 flex items-center gap-3 bg-gray-50 border border-gray-200 rounded px-3 py-2">
            <span class="font-bold text-blue-700">{{ row.selectedMadrasha.name }}</span>
            <span class="text-xs text-gray-500">ইলহাক: {{ row.selectedMadrasha.ElhaqNo }}</span>
            <Button
              icon="pi pi-times"
              class="p-1 text-sm"
              severity="secondary"
              rounded
              @click="() => { row.selectedMadrasha = null; row.searchQuery = ''; row.madrasa_Name = ''; row.madrasa_id = ''; }"
              aria-label="Clear"
            />
          </div>
          <div v-else class="mt-1 text-sm text-red-600">
            মাদরাসা নির্বাচন করুন
          </div>
        </div>

        <!-- Student numbers - Conditional display based on markaz type -->
        <div class="flex flex-wrap gap-6 items-end">
          <!-- Display fields based on markaz type -->
          <div
            v-for="field in Object.keys(fieldLabels)"
            :key="field"
            class="flex-1 min-w-[150px]"
            v-show="isFieldVisible(field)"
          >
            <label :for="`${field}-${idx}`" class="block text-lg font-medium text-slate-700 mb-2">
              {{ fieldLabels[field] }}
              <span v-if="isFieldRequired(field)" class="text-red-500">*</span>
            </label>
            <InputNumber
              :id="`${field}-${idx}`"
              v-model="row[field]"
              class="w-full"
              inputClass="w-full px-4 py-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-purple-600 focus:border-purple-600 bg-white shadow-sm"
              :placeholder="`${fieldLabels[field]} ছাত্র সংখ্যা লিখুন`"
              :min="0"
              showButtons
            />
            <div v-if="isRequiredFieldEmpty(row, field)" class="mt-1 text-sm text-red-600">
              {{ fieldLabels[field] }} ছাত্র সংখ্যা দিন
            </div>
          </div>

          <Button
            v-if="rows.length > 1"
            @click="removeRow(idx)"
            icon="pi pi-trash"
            severity="danger"
            class="px-4 py-2"
            label="সারি মুছুন"
          />
        </div>

        <!-- NOC & Resolution File Upload: New row, 2 columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
          <!-- NOC File Upload -->
          <div>
            <label class="block text-lg font-medium text-slate-700 mb-2">
              এনওসি (NOC) ফাইল <span class="text-red-500">*</span>
            </label>
            <FileUpload
              mode="basic"
              :auto="true"
              accept=".pdf,.jpg,.jpeg,.png"
              chooseLabel="ফাইল নির্বাচন করুন"
              @select="event => onFileSelect('noc', row, event)"
              :customUpload="true"
              class="w-full"
            />
            <div v-if="row.files.noc && typeof row.files.noc === 'object'" class="mt-2 text-sm text-green-700">
              ফাইল: {{ row.files.noc.name }}
            </div>
            <div v-else-if="row.selectedMadrasha && !row.files.noc" class="mt-1 text-sm text-red-600">
              NOC ফাইল আপলোড করুন
            </div>
            <div v-if="row.files.nocPreview" class="mt-2">
              <img :src="row.files.nocPreview" alt="NOC Preview" class="max-h-20 border rounded" />
            </div>
          </div>

          <!-- Resolution File Upload -->
          <div>
            <label class="block text-lg font-medium text-slate-700 mb-2">
              রেজল্যুশন (Resolution) ফাইল <span class="text-red-500">*</span>
            </label>
            <FileUpload
              mode="basic"
              :auto="true"
              accept=".pdf,.jpg,.jpeg,.png"
              chooseLabel="ফাইল নির্বাচন করুন"
              @select="event => onFileSelect('resolution', row, event)"
              :customUpload="true"
              class="w-full"
            />
            <div v-if="row.files.resolution && typeof row.files.resolution === 'object'" class="mt-2 text-sm text-green-700">
              ফাইল: {{ row.files.resolution.name }}
            </div>
            <div v-else-if="row.selectedMadrasha && !row.files.resolution" class="mt-1 text-sm text-red-600">
              রেজল্যুশন ফাইল আপলোড করুন
            </div>
            <div v-if="row.files.resolutionPreview" class="mt-2">
              <img :src="row.files.resolutionPreview" alt="Resolution Preview" class="max-h-20 border rounded" />
            </div>
          </div>
        </div>
      </div>
      <Button
        @click="addRow"
        icon="pi pi-plus"
        class="px-4 py-2 mt-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        label="নতুন সারি যোগ করুন +"
      />
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

:deep(.p-autocomplete-input) {
  width: 100%;
  padding: 0.5rem;
}

:deep(.p-fileupload-buttonbar) {
  padding: 0;
}

:deep(.p-fileupload-content) {
  display: none;
}

:deep(.p-button.p-component.p-fileupload-choose) {
  width: 100%;
}
</style>
