<template>
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            আবেদনকৃত মাদরাসায় পরীক্ষা দিতে ইচ্ছুক মাদরাসার তালিকা ও তথ্য
        </h3>
        <div class="space-y-4">
            <div v-for="(row, index) in rows" :key="index"
                class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Improved AutoComplete with Immediate Results -->
                    <div class="mb-4 w-full">
                        <label class="block font-medium text-sm text-gray-700 mb-2">
                            মাদরাসা নির্বাচন করুন <span class="text-red-500">*</span>
                        </label>
                        <AutoComplete
                            v-model="row.searchQuery"
                            :suggestions="getSuggestions(row)"
                            @complete="searchMadrasas($event, row)"
                            @item-select="event => $emit('select-option', event.value, row)"
                            @focus="preloadSuggestions(row)"
                            :delay="0"
                            :minLength="0"
                            :showEmptyMessage="true"
                            emptyMessage="কোন মাদরাসা পাওয়া যায়নি"
                            placeholder="মাদরাসার নাম বা ইলহাক নম্বর দিয়ে খুঁজুন"
                            class="w-full"
                        >
                            <template #option="slotProps">
                                <div>
                                    <div class="font-medium">{{ slotProps.option.name }}</div>
                                    <div class="text-sm text-gray-600">ইলহাক: {{ slotProps.option.ElhaqNo }}</div>
                                </div>
                            </template>
                            <template #empty>
                                <div class="p-2 text-gray-500">কোন মাদরাসা পাওয়া যায়নি</div>
                            </template>
                        </AutoComplete>
                        <div v-if="!row.madrasa_id" class="text-sm text-red-500 mt-1">
                            মাদরাসা নির্বাচন করুন
                        </div>
                    </div>

                    <!-- Student Numbers with InputNumber - Conditional Display Based on Markaz Type -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Darsiyat Fields -->
                        <div v-if="showDarsiyatFields" class="flex flex-col">
                            <label class="block font-medium text-sm text-gray-700 mb-2">
                                ফযীলত <span class="text-red-500">*</span>
                            </label>
                            <InputNumber
                                v-model="row.fazilat"
                                placeholder="ছাত্র সংখ্যা লিখুন"
                                :min="0"
                                showButtons
                                buttonLayout="horizontal"
                                decrementButtonClass="p-button-secondary"
                                incrementButtonClass="p-button-secondary"
                                inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                class="w-full"
                            />
                            <div v-if="!row.fazilat && row.madrasa_id" class="text-sm text-red-500 mt-1">
                                ফযীলত ছাত্র সংখ্যা লিখুন
                            </div>
                        </div>

                        <div v-if="showDarsiyatFields" class="flex flex-col">
                            <label class="block font-medium text-sm text-gray-700 mb-2">
                                সানাবিয়া ‍উলইয়া <span class="text-red-500">*</span>
                            </label>
                            <InputNumber
                                v-model="row.sanabiya_ulya"
                                placeholder="ছাত্র সংখ্যা লিখুন"
                                :min="0"
                                showButtons
                                buttonLayout="horizontal"
                                decrementButtonClass="p-button-secondary"
                                incrementButtonClass="p-button-secondary"
                                inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                class="w-full"
                            />
                            <div v-if="!row.sanabiya_ulya && row.madrasa_id" class="text-sm text-red-500 mt-1">
                                সানাবিয়া ‍উলইয়া ছাত্র সংখ্যা লিখুন
                            </div>
                        </div>

                        <div v-if="showDarsiyatFields" class="flex flex-col">
                            <label class="block font-medium text-sm text-gray-700 mb-2">
                                সানাবিয়া
                            </label>
                            <InputNumber
                                v-model="row.sanabiya"
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

                        <div v-if="showDarsiyatFields" class="flex flex-col">
                            <label class="block font-medium text-sm text-gray-700 mb-2">
                                মুতাওয়াসসিতা
                            </label>
                            <InputNumber
                                v-model="row.mutawassita"
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

                        <div v-if="showDarsiyatFields" class="flex flex-col">
                            <label class="block font-medium text-sm text-gray-700 mb-2">
                                ইবতেদাইয়্যাহ
                            </label>
                            <InputNumber
                                v-model="row.ibtedaiyyah"
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

                        <!-- Hifzul Quran Field -->
                        <div v-if="showHifzField" class="flex flex-col">
                            <label class="block font-medium text-sm text-gray-700 mb-2">
                                হিফজুল কোরান <span class="text-red-500">*</span>
                            </label>
                            <InputNumber
                                v-model="row.hifzul_quran"
                                placeholder="ছাত্র সংখ্যা লিখুন"
                                :min="0"
                                showButtons
                                buttonLayout="horizontal"
                                decrementButtonClass="p-button-secondary"
                                incrementButtonClass="p-button-secondary"
                                inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                class="w-full"
                            />
                            <div v-if="!row.hifzul_quran && row.madrasa_id" class="text-sm text-red-500 mt-1">
                                হিফজুল কোরান ছাত্র সংখ্যা লিখুন
                            </div>
                        </div>

                        <!-- Kirat Field -->
                        <div v-if="showKiratField" class="flex flex-col">
                            <label class="block font-medium text-sm text-gray-700 mb-2">
                                ইলমুল কিরআত <span class="text-red-500">*</span>
                            </label>
                            <InputNumber
                                v-model="row.qirat"
                                placeholder="ছাত্র সংখ্যা লিখুন"
                                :min="0"
                                showButtons
                                buttonLayout="horizontal"
                                decrementButtonClass="p-button-secondary"
                                incrementButtonClass="p-button-secondary"
                                inputClass="w-full h-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                class="w-full"
                            />
                            <div v-if="!row.qirat && row.madrasa_id" class="text-sm text-red-500 mt-1">
                                ইলমুল কিরআত ছাত্র সংখ্যা লিখুন
                            </div>
                        </div>

                        <div v-if="rows.length > 1" class="flex items-end">
                            <Button
                                @click="$emit('remove-row', index)"
                                label="সারি মুছুন"
                                icon="pi pi-trash"
                                severity="danger"
                                class="w-full"
                            />
                        </div>
                    </div>

                    <!-- File Uploads for NOC & Resolution -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">প্রয়োজনীয় ডকুমেন্টস <span class="text-red-500">*</span></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border border-gray-300 rounded-md p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block font-medium text-sm text-gray-700">
                                        পূর্বের মাদরাসার অনাপত্তিপত্র <span class="text-red-500">*</span>
                                    </label>
                                    <div v-if="row.files.nocPreview" class="flex items-center space-x-2">
                                        <a :href="row.files.nocPreview" target="_blank"
                                            class="inline-flex items-center px-2 py-1 bg-gray-100 border border-gray-300 rounded-md text-xs text-gray-700">
                                            প্রিভিউ
                                        </a>
                                        <Button
                                            label="মুছুন"
                                            icon="pi pi-times"
                                            severity="danger"
                                            size="small"
                                            @click="$emit('remove-file', 'noc', index)"
                                            class="ml-2"
                                        />
                                    </div>
                                </div>
                                <FileUpload
                                    mode="basic"
                                    accept="application/pdf,image/*"
                                    :auto="true"
                                    chooseLabel="ফাইল নির্বাচন করুন"
                                    @select="(e) => handleFileSelect(e, 'noc', index)"
                                    class="w-full"
                                />
                                <div v-if="!row.files.noc && row.madrasa_id" class="text-sm text-red-500 mt-1">
                                    অনাপত্তিপত্র আপলোড করুন
                                </div>
                            </div>
                            <div class="border border-gray-300 rounded-md p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block font-medium text-sm text-gray-700">
                                        বর্তমান মাদরাসার সম্মতিপত্র <span class="text-red-500">*</span>
                                    </label>
                                    <div v-if="row.files.resolutionPreview" class="flex items-center space-x-2">
                                        <a :href="row.files.resolutionPreview" target="_blank"
                                            class="inline-flex items-center px-2 py-1 bg-gray-100 border border-gray-300 rounded-md text-xs text-gray-700">
                                            প্রিভিউ
                                        </a>
                                        <Button
                                            label="মুছুন"
                                            icon="pi pi-times"
                                            severity="danger"
                                            size="small"
                                            @click="$emit('remove-file', 'resolution', index)"
                                            class="ml-2"
                                        />
                                    </div>
                                </div>
                                <FileUpload
                                    mode="basic"
                                    accept="application/pdf,image/*"
                                    :auto="true"
                                    chooseLabel="ফাইল নির্বাচন করুন"
                                    @select="(e) => handleFileSelect(e, 'resolution', index)"
                                    class="w-full"
                                />
                                <div v-if="!row.files.resolution && row.madrasa_id" class="text-sm text-red-500 mt-1">
                                    সম্মতিপত্র আপলোড করুন
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add New Row Button -->
            <div>
                <Button
                    @click="$emit('add-row')"
                    label="নতুন সারি যোগ করুন"
                    icon="pi pi-plus"
                    class="p-button-raised p-button-secondary"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, ref, computed, defineEmits } from 'vue'
import AutoComplete from 'primevue/autocomplete'
import InputNumber from 'primevue/inputnumber'
import FileUpload from 'primevue/fileupload'
import Button from 'primevue/button'

const props = defineProps({
    rows: Array,
    madrashas: Array,
    filteredOptions: Function,
    markazType: {
        type: String,
        default: ''
    }
});

// Define emits
const emit = defineEmits(['add-row', 'remove-row', 'file-upload', 'remove-file', 'select-option']);

// Track suggestions for each row
const suggestionCache = ref({});

// Search madrasa with immediate results
const searchMadrasas = (event, row) => {
    const query = event.query.toLowerCase().trim();

    // Store filtered suggestions in the cache for this row
    if (!suggestionCache.value[row]) {
        suggestionCache.value[row] = {};
    }

    // Use the existing filteredOptions function but with immediate execution
    suggestionCache.value[row].suggestions = props.madrashas.filter(madrasha => {
        const name = (madrasha.name || '').toLowerCase();
        const elhaqNo = (madrasha.ElhaqNo || '').toString().toLowerCase();
        const normalizedElhaqNo = elhaqNo.replace(/[`']/g, '').replace(/\s+/g, '');
        const normalizedQuery = query.replace(/[`']/g, '').replace(/\s+/g, '');

        if (normalizedElhaqNo.includes(normalizedQuery)) return true;

        const searchWords = query.split(' ');
        return searchWords.every(word => name.includes(word));
    });

    row.isOpen = true;
}

// Get suggestions for a row
const getSuggestions = (row) => {
    return suggestionCache.value[row]?.suggestions || [];
}

// Preload some suggestions when focusing on the field
const preloadSuggestions = (row) => {
    // Show all madrasas when focused (limited to 30 for performance)
    if (!suggestionCache.value[row]) {
        suggestionCache.value[row] = {};
    }
    suggestionCache.value[row].suggestions = props.madrashas.slice(0, 30);
    row.isOpen = true;
}

// সম্পূর্ণ পুনর্লিখিত handleFileSelect ফাংশন
const handleFileSelect = (event, type, index) => {
    try {
        // ১। সরাসরি ফাইল অবজেক্ট চেক - যদি event নিজেই File অবজেক্ট হয়
        if (event instanceof File || event instanceof Blob) {
            emit('file-upload', event, type, index);
            return;
        }

        // ২। যদি event.files সরাসরি থাকে (PrimeVue FileUpload)
        if (event && event.files && event.files.length > 0) {
            emit('file-upload', event.files[0], type, index);
            return;
        }

        // ৩। যদি event স্ট্যান্ডার্ড input[type="file"] থেকে আসে
        if (event && event.target && event.target.files && event.target.files.length > 0) {
            emit('file-upload', event.target.files[0], type, index);
            return;
        }

        // ৪। যদি event.originalEvent থাকে (PrimeVue এবং অন্যান্য লাইব্রেরিতে থাকে)
        if (event && event.originalEvent && event.originalEvent.target &&
            event.originalEvent.target.files && event.originalEvent.target.files.length > 0) {
            emit('file-upload', event.originalEvent.target.files[0], type, index);
            return;
        }

        // ৫। যদি event অবজেক্ট এর ভিতর কোথাও File অবজেক্ট লুকিয়ে থাকে
        if (event && typeof event === 'object') {
            // সমস্ত properties চেক করি
            for (const key in event) {
                const value = event[key];

                // যদি সরাসরি File অবজেক্ট হয়
                if (value instanceof File || value instanceof Blob) {
                    emit('file-upload', value, type, index);
                    return;
                }

                // যদি files অ্যারে হয়
                if (Array.isArray(value) && value.length > 0 && value[0] instanceof File) {
                    emit('file-upload', value[0], type, index);
                    return;
                }
            }
        }

        // কোন ফাইল পাওয়া যায়নি
        console.error('ফাইল খুঁজে পাওয়া যায়নি, ইভেন্ট অবজেক্ট:', event);

    } catch (error) {
        console.error('ফাইল সিলেকশনে সমস্যা হয়েছে:', error);
    }
}

// Computed properties to control field visibility based on markaz_type
const showDarsiyatFields = computed(() => props.markazType === 'দরসিয়াত');
const showHifzField = computed(() => props.markazType === 'তাহফিজুল কোরআন');
const showKiratField = computed(() => props.markazType === 'কিরাআত');
</script>

<style scoped>
:deep(.p-autocomplete) {
    width: 100%;
}

:deep(.p-autocomplete-input) {
    width: 100%;
    height: 40px;
}

:deep(.p-autocomplete-panel) {
    z-index: 1000;
}

:deep(.p-fileupload-buttonbar) {
    padding: 0;
}

:deep(.p-fileupload-content) {
    padding: 0;
}
</style>
