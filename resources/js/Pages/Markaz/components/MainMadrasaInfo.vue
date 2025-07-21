<script setup>
import { defineProps, defineEmits } from 'vue'
import InputNumber from 'primevue/inputnumber'
import FileUpload from 'primevue/fileupload'
import Button from 'primevue/button'

const props = defineProps({
    form: Object,
    nocFile: Object,
    nocPreview: String,
    resolutionFile: Object,
    resolutionPreview: String
})

const fields = [
    { key: 'fazilat', label: 'ফযীলত', placeholder: 'ছাত্র সংখ্যা লিখুন' },
    { key: 'sanabiya_ulya', label: 'সানাবিয়া ‍উলইয়া', placeholder: 'ছাত্র সংখ্যা লিখুন' },
    { key: 'sanabiya', label: 'সানাবিয়া', placeholder: 'ছাত্র সংখ্যা লিখুন' },
    { key: 'mutawassita', label: 'মুতাওয়াসসিতা', placeholder: 'ছাত্র সংখ্যা লিখুন' },
    { key: 'ibtedaiyyah', label: 'ইবতেদাইয়্যাহ', placeholder: 'ছাত্র সংখ্যা লিখুন' },
    { key: 'hifzul_quran', label: 'হিফজুল কোরান', placeholder: 'ছাত্র সংখ্যা লিখুন' },
    { key: 'qirat', label: 'ইলমুল কিরআত', placeholder: 'ছাত্র সংখ্যা লিখুন' }
]

const emit = defineEmits(['file-upload', 'remove-file'])

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
</script>

<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">আবেদনকৃত মাদরাসার তথ্য</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div v-for="field in fields" :key="field.key" class="flex flex-col">
                    <label class="block font-medium text-sm text-gray-700 mb-2">{{ field.label }}</label>
                    <div class="flex items-center w-full">
                        <InputNumber
                            v-model="form[field.key]"
                            :placeholder="field.placeholder"
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
            </div>
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">প্রয়োজনীয় ডকুমেন্টস</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- NOC File Upload -->
                    <div class="border border-gray-300 rounded-md p-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block font-medium text-sm text-gray-700">পূর্বের মাদরাসার অনাপত্তিপত্র</label>
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
                            :auto="false"
                            chooseLabel="ফাইল নির্বাচন করুন"
                            @select="onNocFileSelect"
                            class="w-full"
                        />
                    </div>
                    <!-- Resolution File Upload -->
                    <div class="border border-gray-300 rounded-md p-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block font-medium text-sm text-gray-700">সম্মতিপত্র</label>
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
                            :auto="false"
                            chooseLabel="ফাইল নির্বাচন করুন"
                            @select="onResolutionFileSelect"
                            class="w-full"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
