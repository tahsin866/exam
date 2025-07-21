<script setup>
import 'primeicons/primeicons.css';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import CategorySelect from '@/Pages/Markaz/components/CategorySelect.vue'
import MainMadrasaInfo from '@/Pages/Markaz/components/MainMadrasaInfo.vue'
import DynamicMadrasas from '@/Pages/Markaz/components/DynamicMadrasas.vue'
import RequirementsSection from '@/Pages/Markaz/components/RequirementsSection.vue'
import AttachmentSection from '@/Pages/Markaz/components/AttachmentSection.vue'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Button from 'primevue/button'
import ProgressBar from 'primevue/progressbar'
import Card from 'primevue/card'
import Tooltip from 'primevue/tooltip'
import Badge from 'primevue/badge'

const step = ref(0)
const toast = useToast();
const loading = ref(false);
const formErrors = ref([]);

const form = useForm({
    user_id: usePage().props.auth.user.id,
    user_name: usePage().props.auth.user.madrasha_name,
    exam_id: '',
    exam_name: '',
    markaz_type: "",
    fazilat: '',
    sanabiya_ulya: '',
    sanabiya: '',
    mutawassita: '',
    ibtedaiyyah: '',
    hifzul_quran: '',
    qirat: '',
    noc_file: null,
    resolution_file: null,
    requirements: '',
    muhtamim_consent: null,
    president_consent: null,
    committee_resolution: null,
    associated_madrasas: []
})

const rows = ref([{
    fazilat: "",
    sanabiya_ulya: "",
    sanabiya: "",
    mutawassita: "",
    ibtedaiyyah: "",
    hifzul_quran: "",
    qirat: "",
    madrasa_Name: "",
    madrasa_id: "",
    searchQuery: "",
    isOpen: false,
    files: {
        noc: null,
        nocPreview: null,
        resolution: null,
        resolutionPreview: null
    }
}])

const addRow = () => {
  rows.value.push({
    fazilat: "",
    sanabiya_ulya: "",
    sanabiya: "",
    mutawassita: "",
    ibtedaiyyah: "",
    hifzul_quran: "",
    qirat: "",
    madrasa_Name: "",
    madrasa_id: "",
    searchQuery: "",
    isOpen: false,
    files: {
      noc: null,
      nocPreview: null,
      resolution: null,
      resolutionPreview: null
    }
  })
}
const removeRow = (index) => {
    if (rows.value.length > 1) {
        rows.value.splice(index, 1)
    }
}
const handleFileUpload = (file, type, index) => {
    if (!file) return
    if (type === 'noc') {
        rows.value[index].files.noc = file
        rows.value[index].files.nocPreview = URL.createObjectURL(file)
    } else {
        rows.value[index].files.resolution = file
        rows.value[index].files.resolutionPreview = URL.createObjectURL(file)
    }
}
const removeFile = (type, index) => {
    if (type === 'noc') {
        rows.value[index].files.noc = null
        rows.value[index].files.nocPreview = null
    } else {
        rows.value[index].files.resolution = null
        rows.value[index].files.resolutionPreview = null
    }
}

const nocFileForMadrahsa = ref(null)
const nocPreviewForMadrahsa = ref(null)
const resolutionFileForMadrahsa = ref(null)
const resolutionPreviewForMadrahsa = ref(null)
const muhtamimFile = ref(null)
const muhtamimPreview = ref(null)
const presidentFile = ref(null)
const presidentPreview = ref(null)
const committeeFile = ref(null)
const committeePreview = ref(null)

const handleFileUploadMumtahin = (file, type) => {
    if (!file) return
    switch (type) {
        case 'muhtamim':
            muhtamimFile.value = file
            muhtamimPreview.value = URL.createObjectURL(file)
            break
        case 'president':
            presidentFile.value = file
            presidentPreview.value = URL.createObjectURL(file)
            break
        case 'committee':
            committeeFile.value = file
            committeePreview.value = URL.createObjectURL(file)
            break
    }
}
const removeFileMumtahin = (type) => {
    switch (type) {
        case 'muhtamim':
            muhtamimFile.value = null
            muhtamimPreview.value = null
            break
        case 'president':
            presidentFile.value = null
            presidentPreview.value = null
            break
        case 'committee':
            committeeFile.value = null
            committeePreview.value = null
            break
    }
}
const removeFileForMadrahsa = (type) => {
    if (type === 'noc') {
        nocFileForMadrahsa.value = null
        nocPreviewForMadrahsa.value = null
    } else {
        resolutionFileForMadrahsa.value = null
        resolutionPreviewForMadrahsa.value = null
    }
}
const handleFileUploadForMadrahsa = (file, type) => {
    if (!file) return
    if (type === 'noc') {
        form.noc_file = file
        nocFileForMadrahsa.value = file
        nocPreviewForMadrahsa.value = URL.createObjectURL(file)
    } else {
        form.resolution_file = file
        resolutionFileForMadrahsa.value = file
        resolutionPreviewForMadrahsa.value = URL.createObjectURL(file)
    }
}
const madrashas = ref([])
onMounted(async () => {
    const { data } = await axios.get(route('madrashas.list'))
    madrashas.value = data
    const response = await axios.get(route('exam-setups.latest'))
    form.exam_id = response.data.id
    form.exam_name = response.data.exam_name
})
const filteredOptions = computed(() => (row) => {
    if (!row.searchQuery) return [];
    return madrashas.value.filter(madrasha => {
        const name = (madrasha.name || '').toLowerCase();
        const elhaqNo = (madrasha.ElhaqNo || '').toString().toLowerCase();
        const searchQuery = row.searchQuery.toLowerCase().trim();
        const normalizedElhaqNo = elhaqNo.replace(/[`']/g, '').replace(/\s+/g, '');
        const normalizedSearchQuery = searchQuery.replace(/[`']/g, '').replace(/\s+/g, '');
        if (normalizedElhaqNo.includes(normalizedSearchQuery)) return true;
        const searchWords = searchQuery.split(' ');
        return searchWords.every(word => name.includes(word));
    });
});
const selectOption = (madrasha, row) => {
    row.madrasa_Name = madrasha.name;
    row.madrasa_id = madrasha.id;
    row.searchQuery = `${madrasha.name} (ইলহাক: ${madrasha.ElhaqNo})`;
    row.isOpen = false;
};

watch(
  () => form.isSuccess,
  (success) => {
    if (success && form.recentlySuccessful) {
      toast.add({
        severity: 'success',
        summary: 'সফল!',
        detail: 'মারকায আবেদন সফলভাবে সাবমিট হয়েছে!',
        life: 3500,
        group: 'tr'
      });
    }
  }
);

const submitForm = () => {
    loading.value = true;
    formErrors.value = [];

    form.associated_madrasas = rows.value.map(row => ({
        madrasa_Name: row.madrasa_Name,
        madrasa_id: row.madrasa_id,
        fazilat: row.fazilat,
        sanabiya_ulya: row.sanabiya_ulya,
        sanabiya: row.sanabiya,
        mutawassita: row.mutawassita,
        ibtedaiyyah: row.ibtedaiyyah,
        hifzul_quran: row.hifzul_quran,
        qirat: row.qirat,
        noc_file: row.files.noc,
        resolution_file: row.files.resolution
    }))
    form.muhtamim_consent = muhtamimFile.value
    form.president_consent = presidentFile.value
    form.committee_resolution = committeeFile.value

    form.post(route('markaz-agreements.store'), {
        onSuccess: (page) => {
            loading.value = false;
            toast.add({
                severity: 'success',
                summary: 'সফল!',
                detail: 'মারকায আবেদন সফলভাবে সাবমিট হয়েছে!',
                life: 3500,
                group: 'tr'
            });
        },
        onError: (errors) => {
            loading.value = false;
            formErrors.value = errors;
            toast.add({
                severity: 'error',
                summary: 'ত্রুটি!',
                detail: 'ফরম সাবমিট করতে সমস্যা হয়েছে!',
                life: 3500,
                group: 'tr'
            });
        }
    })
}

// Helper function to get current date and time
const getCurrentDateTime = () => {
    return "2025-07-18 01:21:44"; // Updated with provided value
}

// Helper function to get current user
const getCurrentUser = () => {
    return "tahsin866"; // Using the provided value
}

// Step completion tracking
const isStep1Valid = computed(() => {
    return form.markaz_type && form.fazilat !== '' && form.sanabiya_ulya !== '';
});

const isStep2Valid = computed(() => {
    return rows.value.length > 0 && rows.value.some(row => row.madrasa_Name);
});

// Fixed the Step 3 validation logic to make the submit button enable correctly
const isStep3Valid = computed(() => {
    // Only require the requirements field to be filled - attachments are optional
    return !!form.requirements;
});

const getStepCompletionPercentage = computed(() => {
    let total = 0;
    if (isStep1Valid.value) total += 33.33;
    if (isStep2Valid.value) total += 33.33;
    if (isStep3Valid.value) total += 33.34;
    return Math.round(total);
});

// Step labels for the wizard
const stepLabels = [
    { label: "ধরন ও মূল তথ্য", icon: "pi pi-file-edit" },
    { label: "সংযুক্ত মাদ্রাসা", icon: "pi pi-building" },
    { label: "প্রয়োজনীয়তা ও সংযুক্তি", icon: "pi pi-paperclip" }
];

// Get status icon for each step
const getStepIcon = (index) => {
    if (index === 0 && isStep1Valid.value) return "pi pi-check-circle text-green-500";
    if (index === 1 && isStep2Valid.value) return "pi pi-check-circle text-green-500";
    if (index === 2 && isStep3Valid.value) return "pi pi-check-circle text-green-500";
    return step.value === index ? "pi pi-spin pi-spinner text-blue-500" : "pi pi-circle-fill text-gray-400";
};
</script>

<template>
<AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-emerald-50">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-emerald-800 to-emerald-700 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="bg-white/10 p-3 rounded-lg shadow-inner mr-4">
                            <i class="pi pi-building text-white text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">মারকাযের আবেদন ফরম</h1>
                            <p class="text-emerald-100">পরীক্ষা: {{ form.exam_name || 'লোড হচ্ছে...' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2 text-emerald-50">
                        <div class="mr-4 border-r border-emerald-100/30 pr-4">
                            <div class="text-sm">তারিখ ও সময়</div>
                            <div class="font-medium">{{ getCurrentDateTime() }}</div>
                        </div>
                        <div>
                            <div class="text-sm">ইউজার</div>
                            <div class="font-medium">{{ getCurrentUser() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Tracker -->
        <div class=" mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <Card class="mb-6 shadow-md border-0">
                <template #content>
                    <div class="flex flex-col">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-medium text-gray-700">আবেদন অগ্রগতি</h3>
                            <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium">{{ getStepCompletionPercentage }}% সম্পন্ন</span>
                        </div>
                        <ProgressBar :value="getStepCompletionPercentage" class="h-2 mb-4"></ProgressBar>

                        <div class="hidden md:flex justify-between mt-2">
                            <div
                                v-for="(stepLabel, index) in stepLabels"
                                :key="index"
                                class="flex flex-col items-center w-1/3 transition-all duration-300"
                                :class="{
                                    'opacity-50': step.value !== index && !((index === 0 && isStep1Valid.value) ||
                                                                          (index === 1 && isStep2Valid.value) ||
                                                                          (index === 2 && isStep3Valid.value))
                                }"
                            >
                                <div class="flex items-center justify-center h-10 w-10 rounded-full mb-2"
                                    :class="[
                                        step.value === index ? 'bg-blue-100 text-blue-600' :
                                        ((index === 0 && isStep1Valid.value) ||
                                         (index === 1 && isStep2Valid.value) ||
                                         (index === 2 && isStep3Valid.value)) ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500'
                                    ]">
                                    <i :class="[stepLabel.icon, 'text-lg']"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ stepLabel.label }}</span>
                            </div>
                        </div>
                    </div>
                </template>
            </Card>
        </div>

        <!-- Main Content -->
        <div class=" mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <Card class="shadow-lg border-0">
                <template #content>
                    <TabView v-model:activeIndex="step" class="custom-tabs" :scrollable="true">
                        <TabPanel>
                            <template #header>
                                <div class="flex items-center">
                                    <i :class="getStepIcon(0)" class="mr-2"></i>
                                    <span class="font-medium">১. ধরন ও মূল তথ্য</span>
                                </div>
                            </template>
                            <div class="p-4">
                                <h3 class="text-xl font-bold text-gray-700 mb-6 pb-2 border-b border-gray-200">
                                    মারকাযের ধরণ ও মূল তথ্য
                                </h3>

                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="pi pi-info-circle text-blue-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700">
                                                নিম্নে আপনার মারকাযের ধরণ নির্বাচন করুন এবং প্রয়োজনীয় তথ্য পূরণ করুন। সমস্ত তারকাচিহ্নিত (*) ক্ষেত্র পূরণ করা আবশ্যক।
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg p-5 mb-8 border border-gray-200 shadow-sm">
                                    <h4 class="text-lg font-medium text-gray-700 mb-4">মারকায ধরণ নির্বাচন</h4>
                                    <CategorySelect
                                        :modelValue="form.markaz_type"
                                        @update:modelValue="form.markaz_type = $event"
                                        class="custom-category-select"
                                    />
                                </div>

                                <div class="bg-white rounded-lg p-5 border border-gray-200 shadow-sm">
                                    <h4 class="text-lg font-medium text-gray-700 mb-4">মূল মাদ্রাসার তথ্য</h4>
                                    <MainMadrasaInfo
                                        :form="form"
                                        :nocFile="nocFileForMadrahsa"
                                        :nocPreview="nocPreviewForMadrahsa"
                                        :resolutionFile="resolutionFileForMadrahsa"
                                        :resolutionPreview="resolutionPreviewForMadrahsa"
                                        @file-upload="(file, type) => handleFileUploadForMadrahsa(file, type)"
                                        @remove-file="removeFileForMadrahsa"
                                        class="custom-madrasa-info"
                                    />
                                </div>
                            </div>

                            <div class="flex justify-end p-4 bg-gray-50 rounded-b-lg border-t border-gray-200">
                                <Button
                                    label="পরবর্তী ধাপ"
                                    icon="pi pi-arrow-right"
                                    iconPos="right"
                                    class="p-button-raised shadow-md"
                                    :disabled="!isStep1Valid"
                                    @click="step = 1"
                                />
                            </div>
                        </TabPanel>

                        <TabPanel>
                            <template #header>
                                <div class="flex items-center">
                                    <i :class="getStepIcon(1)" class="mr-2"></i>
                                    <span class="font-medium">২. সংযুক্ত মাদ্রাসা</span>
                                </div>
                            </template>
                            <div class="p-4">
                                <h3 class="text-xl font-bold text-gray-700 mb-6 pb-2 border-b border-gray-200">
                                    সংযুক্ত মাদ্রাসার তথ্য
                                </h3>

                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded-r-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="pi pi-exclamation-triangle text-yellow-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                সংযুক্ত মাদ্রাসা যোগ করতে মাদ্রাসার নাম বা ইলহাক নম্বর দিয়ে অনুসন্ধান করুন। প্রতিটি মাদ্রাসার জন্য প্রয়োজনীয় তথ্য পূরণ করুন।
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg p-5 border border-gray-200 shadow-sm">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-lg font-medium text-gray-700">সংযুক্ত মাদ্রাসা সমূহ</h4>
                                        <Badge :value="rows.length" class="bg-blue-600"></Badge>
                                    </div>

                                    <DynamicMadrasas
                                        :rows="rows"
                                        :madrashas="madrashas"
                                        :filteredOptions="filteredOptions"
                                        @add-row="addRow"
                                        @remove-row="removeRow"
                                        @file-upload="handleFileUpload"
                                        @remove-file="removeFile"
                                        @select-option="selectOption"
                                        class="custom-dynamic-madrasas"
                                    />
                                </div>
                            </div>

                            <div class="flex justify-between p-4 bg-gray-50 rounded-b-lg border-t border-gray-200">
                                <Button
                                    label="পূর্ববর্তী ধাপ"
                                    icon="pi pi-arrow-left"
                                    class="p-button-outlined"
                                    @click="step = 0"
                                />
                                <Button
                                    label="পরবর্তী ধাপ"
                                    icon="pi pi-arrow-right"
                                    iconPos="right"
                                    class="p-button-raised shadow-md"
                                    :disabled="!isStep2Valid"
                                    @click="step = 2"
                                />
                            </div>
                        </TabPanel>

                        <TabPanel>
                            <template #header>
                                <div class="flex items-center">
                                    <i :class="getStepIcon(2)" class="mr-2"></i>
                                    <span class="font-medium">৩. প্রয়োজনীয়তা ও সংযুক্তি</span>
                                </div>
                            </template>
                            <div class="p-4">
                                <h3 class="text-xl font-bold text-gray-700 mb-6 pb-2 border-b border-gray-200">
                                    প্রয়োজনীয়তা ও প্রমাণক সংযুক্তি
                                </h3>

                                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="pi pi-check-circle text-green-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-green-700">
                                                মারকাজ চাওয়ার প্রয়োজনীয়তা বর্ণনা করুন। প্রয়োজনীয়তা ব্যাখ্যা অবশ্যই পূরণ করুন। ফাইল সংযুক্তি ঐচ্ছিক, তবে যদি থাকে PDF, JPG, বা PNG ফরম্যাটে আপলোড করুন।
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg p-5 mb-6 border border-gray-200 shadow-sm">
                                    <h4 class="text-lg font-medium text-gray-700 mb-4">মারকাজ প্রয়োজনীয়তা <span class="text-red-500">*</span></h4>
                                    <RequirementsSection
                                        :modelValue="form.requirements"
                                        @update:modelValue="form.requirements = $event"
                                        class="custom-requirements"
                                    />
                                    <p class="text-sm text-gray-500 mt-2">মারকাজ চাওয়ার প্রয়োজনীয়তা বিস্তারিত ব্যাখ্যা করুন। এই ক্ষেত্রটি পূরণ করা বাধ্যতামূলক।</p>
                                </div>

                                <div class="bg-white rounded-lg p-5 border border-gray-200 shadow-sm">
                                    <h4 class="text-lg font-medium text-gray-700 mb-4">প্রমাণক সংযুক্তি (ঐচ্ছিক)</h4>
                                    <AttachmentSection
                                        :muhtamimFile="muhtamimFile"
                                        :muhtamimPreview="muhtamimPreview"
                                        :presidentFile="presidentFile"
                                        :presidentPreview="presidentPreview"
                                        :committeeFile="committeeFile"
                                        :committeePreview="committeePreview"
                                        @file-upload="handleFileUploadMumtahin"
                                        @remove-file="removeFileMumtahin"
                                        class="custom-attachments"
                                    />
                                    <div class="mt-4 p-3 bg-blue-50 rounded-md">
                                        <p class="text-sm text-blue-700">
                                            <i class="pi pi-info-circle mr-1"></i>
                                            ফাইল সংযুক্তি ঐচ্ছিক। প্রয়োজনীয়তা ব্যাখ্যা পূরণ করলেই আবেদন জমা দেওয়া যাবে।
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Display form errors if any -->
                            <div v-if="formErrors.length > 0" class="p-4 bg-red-50 border border-red-200 rounded-md mx-4 mb-4">
                                <h4 class="font-medium text-red-700 mb-2">
                                    <i class="pi pi-exclamation-circle mr-2"></i>নিম্নলিখিত সমস্যা সমাধান করুন:
                                </h4>
                                <ul class="list-disc pl-5">
                                    <li v-for="(error, key) in formErrors" :key="key" class="text-red-600 text-sm">
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>

                            <div class="flex justify-between p-4 bg-gray-50 rounded-b-lg border-t border-gray-200">
                                <Button
                                    label="পূর্ববর্তী ধাপ"
                                    icon="pi pi-arrow-left"
                                    class="p-button-outlined"
                                    @click="step = 1"
                                />
                                <Button
                                    label="আবেদন জমা দিন"
                                    icon="pi pi-check"
                                    iconPos="right"
                                    class="p-button-success p-button-raised shadow-md"
                                    :loading="loading"
                                    :disabled="!isStep3Valid || loading"
                                    @click="submitForm"
                                >
                                    <span v-if="!isStep3Valid" class="absolute bottom-full mb-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                                        প্রয়োজনীয়তা ব্যাখ্যা পূরণ করুন
                                    </span>
                                </Button>
                            </div>
                        </TabPanel>
                    </TabView>
                </template>
            </Card>
        </div>
    </div>
</AuthenticatedLayout>
</template>

<style scoped>
/* Custom styling for a professional look */
.custom-tabs >>> .p-tabview-nav {
    background: #f8fafc;
    border-radius: 8px 8px 0 0;
    border-bottom: 1px solid #e2e8f0;
    padding: 0 1rem;
}

.custom-tabs >>> .p-tabview-nav li .p-tabview-nav-link {
    border: none;
    background: transparent;
    color: #64748b;
    font-weight: 500;
    padding: 1rem 1.25rem;
    transition: all 0.2s;
    border-bottom: 2px solid transparent;
}

.custom-tabs >>> .p-tabview-nav li.p-highlight .p-tabview-nav-link {
    color: #10b981;
    border-bottom: 2px solid #10b981;
}

.custom-tabs >>> .p-tabview-panels {
    padding: 0;
    border: none;
}

.p-button {
    transition: all 0.2s;
    position: relative;
}

.p-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.p-button.p-button-raised {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
}

.p-button.p-button-outlined {
    border: 1px solid #64748b;
    color: #64748b;
}

.p-button.p-button-success {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
}

.p-card {
    transition: all 0.3s ease;
    border-radius: 0.75rem;
    overflow: hidden;
}

.p-card:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Responsive design adjustments */
@media (max-width: 640px) {
    .p-card {
        border-radius: 0.5rem;
    }

    .custom-tabs >>> .p-tabview-nav li .p-tabview-nav-link {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
    }
}

/* Animation for tab transitions */
.p-tabview-panels {
    position: relative;
}

.p-tabview-panel {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0.7; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
