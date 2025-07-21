<script setup>
import { defineProps, defineEmits } from 'vue'
import InputNumber from 'primevue/inputnumber'
import FileUpload from 'primevue/fileupload'

const props = defineProps({ form: Object })
const emit = defineEmits(['update:file'])

const fieldLabels = {
  fazilat: 'ফযীলত',
  sanabiya_ulya: 'সানাবিয়া উলইয়া',
  sanabiya: 'সানাবিয়া',
  mutawassita: 'মুতাওয়াসসিতা',
  ibtedaiyyah: 'ইবতেদাইয়্যাহ',
  hifzul_quran: 'হিফজুল কোরআন',
  qirat: 'ইলমুল কিরাআত'
}

function onFileSelect(field, event) {
  const file = event.files && event.files.length > 0 ? event.files[0] : null
  emit('update:file', field, file)
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
        </label>
        <InputNumber
          :id="field"
          v-model="form[field]"
          class="w-full"
          inputClass="w-full px-4 py-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-purple-600 focus:border-purple-600 bg-white shadow-sm"
          :placeholder="`${fieldLabels[field]} ছাত্র সংখ্যা লিখুন`"
          :min="0"
        />
      </div>
    </div>

    <!-- নিচে নতুন রো, দুই কলামে ফাইল ফিল্ড -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
      <!-- NOC File Upload -->
      <div>
        <label class="block text-lg font-medium text-slate-700 mb-2">
          এনওসি (NOC) ফাইল
        </label>
        <FileUpload
          mode="basic"
          :auto="true"
          accept=".pdf,.jpg,.jpeg,.png"
          chooseLabel="ফাইল নির্বাচন করুন"
          @select="onFileSelect('noc_file', $event)"
          :customUpload="true"
          class="w-full"
        />
        <div v-if="form.noc_file && typeof form.noc_file === 'string'" class="mt-2 text-sm text-green-700">
          বর্তমান ফাইল: <a :href="form.noc_file" target="_blank" class="underline">{{ form.noc_file.split('/').pop() }}</a>
        </div>
        <div v-else-if="form.noc_file && typeof form.noc_file === 'object'" class="mt-2 text-sm text-green-700">
          নতুন ফাইল: {{ form.noc_file.name }}
        </div>
      </div>

      <!-- Resolution File Upload -->
      <div>
        <label class="block text-lg font-medium text-slate-700 mb-2">
          রেজল্যুশন (Resolution) ফাইল
        </label>
        <FileUpload
          mode="basic"
          :auto="true"
          accept=".pdf,.jpg,.jpeg,.png"
          chooseLabel="ফাইল নির্বাচন করুন"
          @select="onFileSelect('resolution_file', $event)"
          :customUpload="true"
          class="w-full"
        />
        <div v-if="form.resolution_file && typeof form.resolution_file === 'string'" class="mt-2 text-sm text-green-700">
          বর্তমান ফাইল: <a :href="form.resolution_file" target="_blank" class="underline">{{ form.resolution_file.split('/').pop() }}</a>
        </div>
        <div v-else-if="form.resolution_file && typeof form.resolution_file === 'object'" class="mt-2 text-sm text-green-700">
          নতুন ফাইল: {{ form.resolution_file.name }}
        </div>
      </div>
    </div>
  </div>
</template>
