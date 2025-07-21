<script setup>
import { defineProps, defineEmits } from 'vue'
import FileUpload from 'primevue/fileupload'
import Button from 'primevue/button'

const props = defineProps({ form: Object, markazData: Object })
const emit = defineEmits(['update:file'])

const getFileName = (field) => {
  // Show uploaded file name if available, otherwise show preview file name if available from markazData
  if (props.form[field] && props.form[field].name) return props.form[field].name
  if (props.markazData && props.markazData[field]) {
    // If markazData[field] is a filename or path, extract only the filename
    const path = props.markazData[field]
    return path.split('/').pop()
  }
  return ''
}
const previewUrl = field => props.markazData && props.markazData[field] ? `/storage/${props.markazData[field]}` : null

const handleFileChange = (field, files) => {
  // PrimeVue's FileUpload passes an array of files
  if (files && files.length > 0) {
    emit('update:file', field, files[0])
  }
}
</script>

<template>
  <div class="bg-white rounded-md shadow-md border border-slate-300 p-6">
    <h3 class="text-xl text-blue-800 font-bold mb-6 arabic-font">সংযুক্তি</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Muhatamim Consent -->
      <div class="space-y-3">
        <label class="block text-lg font-medium text-slate-700 arabic-font">মুহতামিমের সম্মতিপত্র</label>
        <FileUpload
          mode="basic"
          customUpload
          @select="e => handleFileChange('muhtamim_consent', e.files)"
          accept="application/pdf,image/*"
          :auto="true"
          chooseLabel="ফাইল আপলোড করুন"
          class="w-full"
        >
          <template #content>
            <div class="flex items-center justify-between border-2 border-dashed border-slate-300 rounded-md p-4 bg-slate-50">
              <span class="text-blue-700 arabic-font">
                {{ getFileName('muhtamim_consent') || 'ফাইল আপলোড করুন' }}
              </span>
              <div v-if="previewUrl('muhtamim_consent')" class="flex items-center space-x-2">
                <a :href="previewUrl('muhtamim_consent')" target="_blank" class="text-blue-600 hover:text-blue-800">প্রিভিউ</a>
              </div>
            </div>
          </template>
        </FileUpload>
      </div>
      <!-- President Consent -->
      <div class="space-y-3">
        <label class="block text-lg font-medium text-slate-700 arabic-font">জেলা সভাপতির সম্মতিপত্র</label>
        <FileUpload
          mode="basic"
          customUpload
          @select="e => handleFileChange('president_consent', e.files)"
          accept="application/pdf,image/*"
          :auto="true"
          chooseLabel="ফাইল আপলোড করুন"
          class="w-full"
        >
          <template #content>
            <div class="flex items-center justify-between border-2 border-dashed border-slate-300 rounded-md p-4 bg-slate-50">
              <span class="text-blue-700 arabic-font">
                {{ getFileName('president_consent') || 'ফাইল আপলোড করুন' }}
              </span>
              <div v-if="previewUrl('president_consent')" class="flex items-center space-x-2">
                <a :href="previewUrl('president_consent')" target="_blank" class="text-blue-600 hover:text-blue-800">প্রিভিউ</a>
              </div>
            </div>
          </template>
        </FileUpload>
      </div>
      <!-- Committee Resolution -->
      <div class="space-y-3">
        <label class="block text-lg font-medium text-slate-700 arabic-font">কমিটি রেজুলেশন</label>
        <FileUpload
          mode="basic"
          customUpload
          @select="e => handleFileChange('committee_resolution', e.files)"
          accept="application/pdf,image/*"
          :auto="true"
          chooseLabel="ফাইল আপলোড করুন"
          class="w-full"
        >
          <template #content>
            <div class="flex items-center justify-between border-2 border-dashed border-slate-300 rounded-md p-4 bg-slate-50">
              <span class="text-blue-700 arabic-font">
                {{ getFileName('committee_resolution') || 'ফাইল আপলোড করুন' }}
              </span>
              <div v-if="previewUrl('committee_resolution')" class="flex items-center space-x-2">
                <a :href="previewUrl('committee_resolution')" target="_blank" class="text-blue-600 hover:text-blue-800">প্রিভিউ</a>
              </div>
            </div>
          </template>
        </FileUpload>
      </div>
    </div>
  </div>
</template>
