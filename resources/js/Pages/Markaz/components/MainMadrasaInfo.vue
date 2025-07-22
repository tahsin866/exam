<script setup>
import { defineProps, defineEmits, computed } from 'vue'
import InputNumber from 'primevue/inputnumber'
import FileUpload from 'primevue/fileupload'
import Button from 'primevue/button'

const props = defineProps({
    form: Object,
    nocFile: Object,
    nocPreview: String,
    resolutionFile: Object,
    resolutionPreview: String,
    errors: {
      type: Object,
      default: () => ({})
    }
})

const emit = defineEmits(['file-upload', 'remove-file'])

// Computed properties to control field visibility based on markaz_type
const showDarsiyatFields = computed(() => props.form.markaz_type === 'দরসিয়াত');
const showHifzField = computed(() => props.form.markaz_type === 'তাহফিজুল কোরআন');
const showKiratField = computed(() => props.form.markaz_type === 'কিরাআত');

function onNocFileSelect(e) {
    const file = e?.files?.[0] || null
    if (file) {
        emit('file-upload', file, 'noc')
    }
}

function onResolutionFileSelect(e) {
    const file = e?.files?.[0] || null
    if (file) {
        emit('file-upload', file, 'resolution')
    }
}

// Helper function to check if a file is an image
const isImageFile = (file) => {
  if (!file) return false;
  return ['image/jpeg', 'image/png', 'image/jpg'].includes(file.type);
};
</script>

<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">আবেদনকৃত মাদরাসার তথ্য</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Darsiyat Fields -->
                <div v-if="showDarsiyatFields" class="flex flex-col">
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                        ফযীলত <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center w-full">
                        <InputNumber
                            v-model="form.fazilat"
                            placeholder="ছাত্র সংখ্যা লিখুন"
                            :min="0"
                            showButtons
                            buttonLayout="horizontal"
                            decrementButtonClass="p-button-secondary"
                            incrementButtonClass="p-button-secondary"
                            inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            class="w-full"
                        />
                    </div>
                    <p v-if="errors.fazilat" class="text-sm text-red-500 mt-1">{{ errors.fazilat }}</p>
                </div>

                <div v-if="showDarsiyatFields" class="flex flex-col">
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                        সানাবিয়া ‍উলইয়া <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center w-full">
                        <InputNumber
                            v-model="form.sanabiya_ulya"
                            placeholder="ছাত্র সংখ্যা লিখুন"
                            :min="0"
                            showButtons
                            buttonLayout="horizontal"
                            decrementButtonClass="p-button-secondary"
                            incrementButtonClass="p-button-secondary"
                            inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            class="w-full"
                        />
                    </div>
                    <p v-if="errors.sanabiya_ulya" class="text-sm text-red-500 mt-1">{{ errors.sanabiya_ulya }}</p>
                </div>

                <div v-if="showDarsiyatFields" class="flex flex-col">
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                        সানাবিয়া
                    </label>
                    <div class="flex items-center w-full">
                        <InputNumber
                            v-model="form.sanabiya"
                            placeholder="ছাত্র সংখ্যা লিখুন"
                            :min="0"
                            showButtons
                            buttonLayout="horizontal"
                            decrementButtonClass="p-button-secondary"
                            incrementButtonClass="p-button-secondary"
                            inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            class="w-full"
                        />
                    </div>
                </div>

                <div v-if="showDarsiyatFields" class="flex flex-col">
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                        মুতাওয়াসসিতা
                    </label>
                    <div class="flex items-center w-full">
                        <InputNumber
                            v-model="form.mutawassita"
                            placeholder="ছাত্র সংখ্যা লিখুন"
                            :min="0"
                            showButtons
                            buttonLayout="horizontal"
                            decrementButtonClass="p-button-secondary"
                            incrementButtonClass="p-button-secondary"
                            inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            class="w-full"
                        />
                    </div>
                </div>

                <div v-if="showDarsiyatFields" class="flex flex-col">
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                        ইবতেদাইয়্যাহ
                    </label>
                    <div class="flex items-center w-full">
                        <InputNumber
                            v-model="form.ibtedaiyyah"
                            placeholder="ছাত্র সংখ্যা লিখুন"
                            :min="0"
                            showButtons
                            buttonLayout="horizontal"
                            decrementButtonClass="p-button-secondary"
                            incrementButtonClass="p-button-secondary"
                            inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            class="w-full"
                        />
                    </div>
                </div>

                <!-- Hifzul Quran Field -->
                <div v-if="showHifzField" class="flex flex-col">
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                        হিফজুল কোরান <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center w-full">
                        <InputNumber
                            v-model="form.hifzul_quran"
                            placeholder="ছাত্র সংখ্যা লিখুন"
                            :min="0"
                            showButtons
                            buttonLayout="horizontal"
                            decrementButtonClass="p-button-secondary"
                            incrementButtonClass="p-button-secondary"
                            inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            class="w-full"
                        />
                    </div>
                    <p v-if="errors.hifzul_quran" class="text-sm text-red-500 mt-1">{{ errors.hifzul_quran }}</p>
                </div>

                <!-- Kirat Field -->
                <div v-if="showKiratField" class="flex flex-col">
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                        ইলমুল কিরআত <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center w-full">
                        <InputNumber
                            v-model="form.qirat"
                            placeholder="ছাত্র সংখ্যা লিখুন"
                            :min="0"
                            showButtons
                            buttonLayout="horizontal"
                            decrementButtonClass="p-button-secondary"
                            incrementButtonClass="p-button-secondary"
                            inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            class="w-full"
                        />
                    </div>
                    <p v-if="errors.qirat" class="text-sm text-red-500 mt-1">{{ errors.qirat }}</p>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    প্রয়োজনীয় ডকুমেন্টস <span class="text-red-500">*</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- NOC File Upload -->
                    <div class="border border-gray-300 rounded-md p-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block font-medium text-sm text-gray-700">
                                পূর্বের মাদরাসার অনাপত্তিপত্র <span class="text-red-500">*</span>
                            </label>
                            <div v-if="nocFile" class="flex items-center space-x-2">
                                <a :href="nocPreview" target="_blank"
                                   class="inline-flex items-center px-2 py-1 bg-gray-100 border border-gray-300 rounded-md text-xs text-gray-700">
                                    দেখুন
                                </a>
                                <Button
                                    label="মুছুন"
                                    severity="danger"
                                    size="small"
                                    @click="$emit('remove-file', 'noc')"
                                    class="ml-2"
                                    icon="pi pi-times"
                                />
                            </div>
                        </div>
                        <FileUpload
                            mode="basic"
                            accept="application/pdf,image/*"
                            :auto="true"
                            chooseLabel="ফাইল নির্বাচন করুন"
                            @select="onNocFileSelect"
                            class="w-full"
                        />
                        <div v-if="!nocFile" class="text-sm text-red-500 mt-1">
                            অনাপত্তিপত্র আপলোড করুন
                        </div>

                        <!-- File preview if uploaded -->
                        <div v-if="nocPreview" class="mt-3">
                            <div class="border rounded-lg overflow-hidden bg-gray-100 p-2">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-medium text-gray-500">প্রিভিউ</span>
                                </div>
                                <img v-if="isImageFile(nocFile)" :src="nocPreview" class="max-h-32 mx-auto object-contain" />
                                <div v-else class="flex items-center justify-center h-32 bg-gray-50 border rounded">
                                    <i class="pi pi-file-pdf text-red-500 text-2xl mr-2"></i>
                                    <span class="text-sm text-gray-600">PDF ফাইল</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resolution File Upload -->
                    <div class="border border-gray-300 rounded-md p-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block font-medium text-sm text-gray-700">
                                সম্মতিপত্র <span class="text-red-500">*</span>
                            </label>
                            <div v-if="resolutionFile" class="flex items-center space-x-2">
                                <a :href="resolutionPreview" target="_blank"
                                   class="inline-flex items-center px-2 py-1 bg-gray-100 border border-gray-300 rounded-md text-xs text-gray-700">
                                    দেখুন
                                </a>
                                <Button
                                    label="মুছুন"
                                    severity="danger"
                                    size="small"
                                    @click="$emit('remove-file', 'resolution')"
                                    class="ml-2"
                                    icon="pi pi-times"
                                />
                            </div>
                        </div>
                        <FileUpload
                            mode="basic"
                            accept="application/pdf,image/*"
                            :auto="true"
                            chooseLabel="ফাইল নির্বাচন করুন"
                            @select="onResolutionFileSelect"
                            class="w-full"
                        />
                        <div v-if="!resolutionFile" class="text-sm text-red-500 mt-1">
                            সম্মতিপত্র আপলোড করুন
                        </div>

                        <!-- File preview if uploaded -->
                        <div v-if="resolutionPreview" class="mt-3">
                            <div class="border rounded-lg overflow-hidden bg-gray-100 p-2">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-medium text-gray-500">প্রিভিউ</span>
                                </div>
                                <img v-if="isImageFile(resolutionFile)" :src="resolutionPreview" class="max-h-32 mx-auto object-contain" />
                                <div v-else class="flex items-center justify-center h-32 bg-gray-50 border rounded">
                                    <i class="pi pi-file-pdf text-red-500 text-2xl mr-2"></i>
                                    <span class="text-sm text-gray-600">PDF ফাইল</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(.p-fileupload-buttonbar) {
    padding: 0;
}

:deep(.p-fileupload-content) {
    padding: 0;
}

:deep(.p-inputnumber-input) {
    width: 100%;
}
</style>
