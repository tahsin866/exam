<script setup>
import 'primeicons/primeicons.css';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import MarkazConditions from '@/Pages/Markaz/components/MarkazConditions.vue'
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
const validationErrors = ref({});
const conditionsAgreed = ref(false);

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
    try {
        if (file instanceof File || file instanceof Blob) {
            if (type === 'noc') {
                rows.value[index].files.noc = file;
                rows.value[index].files.nocPreview = URL.createObjectURL(file);
            } else {
                rows.value[index].files.resolution = file;
                rows.value[index].files.resolutionPreview = URL.createObjectURL(file);
            }
            return;
        }

        let extractedFile = null;

        if (file && file.files && file.files.length > 0) {
            extractedFile = file.files[0];
        } else if (file && file.target && file.target.files && file.target.files.length > 0) {
            extractedFile = file.target.files[0];
        } else if (file && file.originalEvent && file.originalEvent.target &&
                  file.originalEvent.target.files && file.originalEvent.target.files.length > 0) {
            extractedFile = file.originalEvent.target.files[0];
        }

        if (extractedFile) {
            if (type === 'noc') {
                rows.value[index].files.noc = extractedFile;
                rows.value[index].files.nocPreview = URL.createObjectURL(extractedFile);
            } else {
                rows.value[index].files.resolution = extractedFile;
                rows.value[index].files.resolutionPreview = URL.createObjectURL(extractedFile);
            }
        } else {
            console.error('No file found in event:', file);
        }
    } catch (error) {
        console.error('Error in handleFileUpload:', error);
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

const handleFileUploadMumtahin = (event, type) => {
    try {
        if (event instanceof File || event instanceof Blob) {
            switch (type) {
                case 'muhtamim':
                    muhtamimFile.value = event;
                    muhtamimPreview.value = URL.createObjectURL(event);
                    break;
                case 'president':
                    presidentFile.value = event;
                    presidentPreview.value = URL.createObjectURL(event);
                    break;
                case 'committee':
                    committeeFile.value = event;
                    committeePreview.value = URL.createObjectURL(event);
                    break;
            }
            return;
        }

        let file = null;

        if (event && event.files && event.files.length > 0) {
            file = event.files[0];
        } else if (event && event.target && event.target.files && event.target.files.length > 0) {
            file = event.target.files[0];
        } else if (event && event.originalEvent && event.originalEvent.target &&
                   event.originalEvent.target.files && event.originalEvent.target.files.length > 0) {
            file = event.originalEvent.target.files[0];
        }

        if (!file) {
            console.error('No file found in event:', event);
            return;
        }

        switch (type) {
            case 'muhtamim':
                muhtamimFile.value = file;
                muhtamimPreview.value = URL.createObjectURL(file);
                break;
            case 'president':
                presidentFile.value = file;
                presidentPreview.value = URL.createObjectURL(file);
                break;
            case 'committee':
                committeeFile.value = file;
                committeePreview.value = URL.createObjectURL(file);
                break;
        }
    } catch (error) {
        console.error('Error in handleFileUploadMumtahin:', error);
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

const handleFileUploadForMadrahsa = (file, type) => {
    try {
        if (file instanceof File || file instanceof Blob) {
            if (type === 'noc') {
                form.noc_file = file;
                nocFileForMadrahsa.value = file;
                nocPreviewForMadrahsa.value = URL.createObjectURL(file);
            } else {
                form.resolution_file = file;
                resolutionFileForMadrahsa.value = file;
                resolutionPreviewForMadrahsa.value = URL.createObjectURL(file);
            }
            return;
        }

        let extractedFile = null;

        if (file && file.files && file.files.length > 0) {
            extractedFile = file.files[0];
        } else if (file && file.target && file.target.files && file.target.files.length > 0) {
            extractedFile = file.target.files[0];
        } else if (file && file.originalEvent && file.originalEvent.target &&
                  file.originalEvent.target.files && file.originalEvent.target.files.length > 0) {
            extractedFile = file.originalEvent.target.files[0];
        }

        if (extractedFile) {
            if (type === 'noc') {
                form.noc_file = extractedFile;
                nocFileForMadrahsa.value = extractedFile;
                nocPreviewForMadrahsa.value = URL.createObjectURL(extractedFile);
            } else {
                form.resolution_file = extractedFile;
                resolutionFileForMadrahsa.value = extractedFile;
                resolutionPreviewForMadrahsa.value = URL.createObjectURL(extractedFile);
            }
        } else {
            console.error('No file found in event:', file);
        }
    } catch (error) {
        console.error('Error in handleFileUploadForMadrahsa:', error);
    }
}

const removeFileForMadrahsa = (type) => {
    if (type === 'noc') {
        nocFileForMadrahsa.value = null
        nocPreviewForMadrahsa.value = null
        form.noc_file = null
    } else {
        resolutionFileForMadrahsa.value = null
        resolutionPreviewForMadrahsa.value = null
        form.resolution_file = null
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

watch(() => form.markaz_type, (newType) => {
  if (newType === 'দরসিয়াত') {
    form.hifzul_quran = '';
    form.qirat = '';
  } else if (newType === 'তাহফিজুল কোরআন') {
    form.fazilat = '';
    form.sanabiya_ulya = '';
    form.sanabiya = '';
    form.mutawassita = '';
    form.ibtedaiyyah = '';
    form.qirat = '';
  } else if (newType === 'কিরাআত') {
    form.fazilat = '';
    form.sanabiya_ulya = '';
    form.sanabiya = '';
    form.mutawassita = '';
    form.ibtedaiyyah = '';
    form.hifzul_quran = '';
  }

  rows.value.forEach(row => {
    if (newType === 'দরসিয়াত') {
      row.hifzul_quran = '';
      row.qirat = '';
    } else if (newType === 'তাহফিজুল কোরআন') {
      row.fazilat = '';
      row.sanabiya_ulya = '';
      row.sanabiya = '';
      row.mutawassita = '';
      row.ibtedaiyyah = '';
      row.qirat = '';
    } else if (newType === 'কিরাআত') {
      row.fazilat = '';
      row.sanabiya_ulya = '';
      row.sanabiya = '';
      row.mutawassita = '';
      row.ibtedaiyyah = '';
      row.hifzul_quran = '';
    }
  });
});

const submitForm = () => {
  loading.value = true;
  formErrors.value = [];
  validationErrors.value = {};

  if (!form.requirements) {
    loading.value = false;
    toast.add({
      severity: 'error',
      summary: 'ত্রুটি!',
      detail: 'মারকাজ প্রয়োজনীয়তা ব্যাখ্যা করুন',
      life: 3500,
      group: 'tr'
    });
    return;
  }

  if (!form.markaz_type) {
    loading.value = false;
    toast.add({
      severity: 'error',
      summary: 'ত্রুটি!',
      detail: 'মারকাযের স্তর নির্বাচন করুন',
      life: 3500,
      group: 'tr'
    });
    return;
  }

  if (form.markaz_type === 'দরসিয়াত') {
    if (form.fazilat === '' || form.fazilat === null) {
      loading.value = false;
      toast.add({
        severity: 'error',
        summary: 'ত্রুটি!',
        detail: 'ফাযীলাত ফিল্ড পূরণ করুন',
        life: 3500,
        group: 'tr'
      });
      return;
    }
    if (form.sanabiya_ulya === '' || form.sanabiya_ulya === null) {
      loading.value = false;
      toast.add({
        severity: 'error',
        summary: 'ত্রুটি!',
        detail: 'সানাবিয়া উলইয়া ফিল্ড পূরণ করুন',
        life: 3500,
        group: 'tr'
      });
      return;
    }
  } else if (form.markaz_type === 'তাহফিজুল কোরআন') {
    if (form.hifzul_quran === '' || form.hifzul_quran === null) {
      loading.value = false;
      toast.add({
        severity: 'error',
        summary: 'ত্রুটি!',
        detail: 'হিফযুল কোরআন ফিল্ড পূরণ করুন',
        life: 3500,
        group: 'tr'
      });
      return;
    }
  } else if (form.markaz_type === 'কিরাআত') {
    if (form.qirat === '' || form.qirat === null) {
      loading.value = false;
      toast.add({
        severity: 'error',
        summary: 'ত্রুটি!',
        detail: 'কিরাআত ফিল্ড পূরণ করুন',
        life: 3500,
        group: 'tr'
      });
      return;
    }
  }

  if (!nocFileForMadrahsa.value || !resolutionFileForMadrahsa.value) {
    loading.value = false;
    toast.add({
      severity: 'error',
      summary: 'ত্রুটি!',
      detail: 'মূল মাদ্রাসার সমস্ত প্রয়োজনীয় ফাইল আপলোড করুন',
      life: 3500,
      group: 'tr'
    });
    return;
  }

  const invalidRows = rows.value.filter(row => {
    if (!row.madrasa_id) return true;
    if (!row.files.noc || !row.files.resolution) return true;

    if (form.markaz_type === 'দরসিয়াত') {
      if (row.fazilat === '' || row.fazilat === null) return true;
      if (row.sanabiya_ulya === '' || row.sanabiya_ulya === null) return true;
    } else if (form.markaz_type === 'তাহফিজুল কোরআন') {
      if (row.hifzul_quran === '' || row.hifzul_quran === null) return true;
    } else if (form.markaz_type === 'কিরাআত') {
      if (row.qirat === '' || row.qirat === null) return true;
    }

    return false;
  });

  if (invalidRows.length > 0) {
    loading.value = false;
    toast.add({
      severity: 'error',
      summary: 'ত্রুটি!',
      detail: `${invalidRows.length}টি সংযুক্ত মাদ্রাসার তথ্য অসম্পূর্ণ`,
      life: 3500,
      group: 'tr'
    });
    return;
  }

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
    onSuccess: () => {
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

const getCurrentDateTime = () => {
    return "2025-07-22 09:17:27";
}

const getCurrentUser = () => {
    return "tahsin866";
}

const handleConditionsAccepted = () => {
    conditionsAgreed.value = true;
}

const isConditionsAccepted = computed(() => {
    return conditionsAgreed.value;
});

const isStep1Valid = computed(() => {
  try {
    if (!form || !form.markaz_type) return false;

    if (form.markaz_type === 'দরসিয়াত') {
      return form.fazilat !== '' && form.fazilat !== null &&
             form.sanabiya_ulya !== '' && form.sanabiya_ulya !== null &&
             !!nocFileForMadrahsa.value && !!resolutionFileForMadrahsa.value;
    } else if (form.markaz_type === 'তাহফিজুল কোরআন') {
      return form.hifzul_quran !== '' && form.hifzul_quran !== null &&
             !!nocFileForMadrahsa.value && !!resolutionFileForMadrahsa.value;
    } else if (form.markaz_type === 'কিরাআত') {
      return form.qirat !== '' && form.qirat !== null &&
             !!nocFileForMadrahsa.value && !!resolutionFileForMadrahsa.value;
    }

    return false;
  } catch (error) {
    console.error('Error in isStep1Valid:', error);
    return false;
  }
});

const isStep2Valid = computed(() => {
  try {
    if (!rows?.value || rows.value.length === 0) return false;

    return rows.value.every(row => {
      if (!row.madrasa_id) return false;
      if (!row.files?.noc || !row.files?.resolution) return false;

      if (form?.markaz_type === 'দরসিয়াত') {
        return row.fazilat !== '' && row.fazilat !== null &&
               row.sanabiya_ulya !== '' && row.sanabiya_ulya !== null;
      } else if (form?.markaz_type === 'তাহফিজুল কোরআন') {
        return row.hifzul_quran !== '' && row.hifzul_quran !== null;
      } else if (form?.markaz_type === 'কিরাআত') {
        return row.qirat !== '' && row.qirat !== null;
      }

      return false;
    });
  } catch (error) {
    console.error('Error in isStep2Valid:', error);
    return false;
  }
});

const isStep3Valid = computed(() => {
  try {
    return !!form?.requirements;
  } catch (error) {
    console.error('Error in isStep3Valid:', error);
    return false;
  }
});

const canAccessStep = computed(() => {
  try {
    return {
      0: true,
      1: !!isConditionsAccepted.value,
      2: (!!isConditionsAccepted.value && !!isStep1Valid.value),
      3: (!!isConditionsAccepted.value && !!isStep1Valid.value && !!isStep2Valid.value)
    };
  } catch (error) {
    console.error('Error in canAccessStep:', error);
    return { 0: true, 1: false, 2: false, 3: false };
  }
});

const getStepCompletionPercentage = computed(() => {
    try {
        let total = 0;
        if (isConditionsAccepted?.value) total += 25;
        if (isStep1Valid?.value) total += 25;
        if (isStep2Valid?.value) total += 25;
        if (isStep3Valid?.value) total += 25;
        return Math.round(total);
    } catch (error) {
        console.error('Error in getStepCompletionPercentage:', error);
        return 0;
    }
});

const stepLabels = [
    { label: "শর্তাবলী", icon: "pi pi-info-circle" },
    { label: "ধরন ও মূল তথ্য", icon: "pi pi-file-edit" },
    { label: "সংযুক্ত মাদ্রাসা", icon: "pi pi-building" },
    { label: "প্রয়োজনীয়তা ও সংযুক্তি", icon: "pi pi-paperclip" }
];

const getStepIcon = (index) => {
    if (index === 0 && isConditionsAccepted.value) return "pi pi-check-circle text-gray-700";
    if (index === 1 && isStep1Valid.value) return "pi pi-check-circle text-gray-700";
    if (index === 2 && isStep2Valid.value) return "pi pi-check-circle text-gray-700";
    if (index === 3 && isStep3Valid.value) return "pi pi-check-circle text-gray-700";
    return step.value === index ? "pi pi-spin pi-spinner text-gray-700" : "pi pi-circle-fill text-gray-400";
};

const handleTabChange = (event) => {
    const targetIndex = event.index;

    if (!canAccessStep.value[targetIndex]) {
        event.preventDefault();

        let message = '';
        if (targetIndex === 1 && !isConditionsAccepted.value) {
            message = 'অনুগ্রহ করে শর্তাবলী পড়ুন এবং সম্মতি দিন';
        } else if (targetIndex === 2 && !isStep1Valid.value) {
            message = 'ধরন ও মূল তথ্য প্রদান করুন';
        } else if (targetIndex === 3 && !isStep2Valid.value) {
            message = 'সংযুক্ত মাদ্রাসার তথ্য সম্পূর্ণ করুন';
        }

        toast.add({
            severity: 'warn',
            summary: 'সতর্কতা!',
            detail: message,
            life: 3000
        });
        return;
    }

    step.value = targetIndex;
};

const goToNextStep = () => {
  const nextStep = step.value + 1;
  if (canAccessStep.value[nextStep]) {
    step.value = nextStep;
  }
};
</script>

<template>
<AuthenticatedLayout>
    <div class="min-h-screen bg-gray-50 mt-8">
        <!-- Header Banner -->
        <div class="bg-gray-800 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="bg-gray-700 p-3 rounded-lg mr-4">
                            <i class="pi pi-building text-white text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">মারকাযের আবেদন ফরম</h1>
                            <p class="text-gray-300">পরীক্ষা: {{ form.exam_name || 'লোড হচ্ছে...' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center bg-gray-700 rounded-lg px-4 py-2 text-white">
                        <div class="mr-4 border-r border-gray-500 pr-4">
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <Card class="mb-6 shadow-sm border border-gray-200">
                <template #content>
                    <div class="flex flex-col">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-medium text-gray-700">আবেদন অগ্রগতি</h3>
                            <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">{{ getStepCompletionPercentage }}% সম্পন্ন</span>
                        </div>
                        <ProgressBar :value="getStepCompletionPercentage" class="h-2 mb-4"></ProgressBar>

                        <div class="hidden md:flex justify-between mt-2">
                            <div
                                v-for="(stepLabel, index) in stepLabels"
                                :key="index"
                                class="flex flex-col items-center w-1/4 transition-all duration-300"
                                :class="{
                                    'opacity-50': !canAccessStep[index] && index !== step
                                }"
                            >
                                <div class="flex items-center justify-center h-10 w-10 rounded-full mb-2"
                                    :class="[
                                        step === index ? 'bg-gray-200 text-gray-800' :
                                        ((index === 0 && isConditionsAccepted) ||
                                         (index === 1 && isStep1Valid) ||
                                         (index === 2 && isStep2Valid) ||
                                         (index === 3 && isStep3Valid)) ? 'bg-gray-200 text-gray-800' : 'bg-gray-100 text-gray-500'
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <Card class="shadow-md border border-gray-200">
                <template #content>
                    <TabView v-model:activeIndex="step" :scrollable="true" @tab-change="handleTabChange">
                        <!-- Conditions Tab -->
                        <TabPanel>
                            <template #header>
                                <div class="flex items-center">
                                    <i :class="getStepIcon(0)" class="mr-2"></i>
                                    <span class="font-medium">১. শর্তাবলী</span>
                                </div>
                            </template>
                            <div class="p-6">
                                <MarkazConditions @continue="handleConditionsAccepted" />

                                <!-- Special message when conditions are not agreed -->
                                <div v-if="!conditionsAgreed" class="mt-6 p-4 bg-gray-100 border-l-4 border-gray-500 rounded-r">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="pi pi-exclamation-triangle text-gray-600 text-xl"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-600">
                                                শর্তাবলী সম্মত হলে চেকবক্সে টিক দিন এবং পরবর্তী ধাপে যেতে "পরবর্তী ধাপে যান" বাটনে ক্লিক করুন।
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Next button -->
                                <div class="flex justify-end p-4 bg-gray-50 rounded-b-lg border-t border-gray-200 mt-4">
                                    <Button
                                        label="পরবর্তী ধাপে যান"
                                        icon="pi pi-arrow-right"
                                        iconPos="right"
                                        class="p-button-secondary"
                                        :disabled="!conditionsAgreed"
                                        @click="goToNextStep"
                                    />
                                </div>
                            </div>
                        </TabPanel>

                        <TabPanel :disabled="!canAccessStep[1]">
                            <template #header>
                                <div class="flex items-center" :class="{ 'opacity-50': !canAccessStep[1] }">
                                    <i :class="getStepIcon(1)" class="mr-2"></i>
                                    <span class="font-medium">২. ধরন ও মূল তথ্য</span>
                                    <i v-if="!canAccessStep[1]" class="pi pi-lock ml-2 text-gray-500" v-tooltip.top="'প্রথমে শর্তাবলী সম্মত হন'"></i>
                                </div>
                            </template>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-700 mb-6 pb-2 border-b border-gray-200">
                                    মারকাযের ধরণ ও মূল তথ্য
                                </h3>

                                <div class="bg-gray-100 border-l-4 border-gray-500 p-4 mb-6 rounded-r-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="pi pi-info-circle text-gray-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-600">
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
                                    />
                                </div>

                                <div class="flex justify-between p-4 bg-gray-50 rounded-b-lg border-t border-gray-200 mt-6">
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
                                        class="p-button-secondary"
                                        :disabled="!isStep1Valid"
                                        @click="goToNextStep"
                                    />
                                </div>
                            </div>
                        </TabPanel>

                        <TabPanel :disabled="!canAccessStep[2]">
                            <template #header>
                                <div class="flex items-center" :class="{ 'opacity-50': !canAccessStep[2] }">
                                    <i :class="getStepIcon(2)" class="mr-2"></i>
                                    <span class="font-medium">৩. সংযুক্ত মাদ্রাসা</span>
                                    <i v-if="!canAccessStep[2]" class="pi pi-lock ml-2 text-gray-500"
                                       v-tooltip.top="!isConditionsAccepted ? 'প্রথমে শর্তাবলী সম্মত হন' : 'ধরন ও মূল তথ্য সম্পূর্ণ করুন'"></i>
                                </div>
                            </template>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-700 mb-6 pb-2 border-b border-gray-200">
                                    সংযুক্ত মাদ্রাসার তথ্য
                                </h3>

                                <div class="bg-gray-100 border-l-4 border-gray-500 p-4 mb-6 rounded-r-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="pi pi-exclamation-triangle text-gray-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-600">
                                                সংযুক্ত মাদ্রাসা যোগ করতে মাদ্রাসার নাম বা ইলহাক নম্বর দিয়ে অনুসন্ধান করুন। প্রতিটি মাদ্রাসার জন্য প্রয়োজনীয় তথ্য পূরণ করুন।
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg p-5 border border-gray-200 shadow-sm">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-lg font-medium text-gray-700">সংযুক্ত মাদ্রাসা সমূহ</h4>
                                        <Badge :value="rows.length" class="bg-gray-600"></Badge>
                                    </div>

                                    <DynamicMadrasas
                                        :rows="rows"
                                        :madrashas="madrashas"
                                        :filteredOptions="filteredOptions"
                                        :markazType="form.markaz_type"
                                        @add-row="addRow"
                                        @remove-row="removeRow"
                                        @file-upload="handleFileUpload"
                                        @remove-file="removeFile"
                                        @select-option="selectOption"
                                    />
                                </div>

                                <div class="flex justify-between p-4 bg-gray-50 rounded-b-lg border-t border-gray-200 mt-6">
                                    <Button
                                        label="পূর্ববর্তী ধাপ"
                                        icon="pi pi-arrow-left"
                                        class="p-button-outlined"
                                        @click="step = 1"
                                    />
                                    <Button
                                        label="পরবর্তী ধাপ"
                                        icon="pi pi-arrow-right"
                                        iconPos="right"
                                        class="p-button-secondary"
                                        :disabled="!isStep2Valid"
                                        @click="goToNextStep"
                                    />
                                </div>
                            </div>
                        </TabPanel>

                        <TabPanel :disabled="!canAccessStep[3]">
                            <template #header>
                                <div class="flex items-center" :class="{ 'opacity-50': !canAccessStep[3] }">
                                    <i :class="getStepIcon(3)" class="mr-2"></i>
                                    <span class="font-medium">৪. প্রয়োজনীয়তা ও সংযুক্তি</span>
                                    <i v-if="!canAccessStep[3]" class="pi pi-lock ml-2 text-gray-500"
                                       v-tooltip.top="!isConditionsAccepted ? 'প্রথমে শর্তাবলী সম্মত হন' : !isStep1Valid ? 'ধরন ও মূল তথ্য সম্পূর্ণ করুন' : 'সংযুক্ত মাদ্রাসার তথ্য সম্পূর্ণ করুন'"></i>
                                </div>
                            </template>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-700 mb-6 pb-2 border-b border-gray-200">
                                    প্রয়োজনীয়তা ও প্রমাণক সংযুক্তি
                                </h3>

                                <div class="bg-gray-100 border-l-4 border-gray-500 p-4 mb-6 rounded-r-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="pi pi-check-circle text-gray-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-600">
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
                                    />
                                    <div class="mt-4 p-3 bg-gray-100 rounded-md">
                                        <p class="text-sm text-gray-600">
                                            <i class="pi pi-info-circle mr-1"></i>
                                            ফাইল সংযুক্তি ঐচ্ছিক। প্রয়োজনীয়তা ব্যাখ্যা পূরণ করলেই আবেদন জমা দেওয়া যাবে।
                                        </p>
                                    </div>
                                </div>

                                <!-- Display form errors if any -->
                                <div v-if="formErrors.length > 0" class="p-4 bg-gray-100 border border-red-200 rounded-md mb-4">
                                    <h4 class="font-medium text-red-700 mb-2">
                                        <i class="pi pi-exclamation-circle mr-2"></i>নিম্নলিখিত সমস্যা সমাধান করুন:
                                    </h4>
                                    <ul class="list-disc pl-5">
                                        <li v-for="(error, key) in formErrors" :key="key" class="text-red-600 text-sm">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>

                                <div class="flex justify-between p-4 bg-gray-50 rounded-b-lg border-t border-gray-200 mt-6">
                                    <Button
                                        label="পূর্ববর্তী ধাপ"
                                        icon="pi pi-arrow-left"
                                        class="p-button-outlined"
                                        @click="step = 2"
                                    />
                                    <Button
                                        label="আবেদন জমা দিন"
                                        icon="pi pi-check"
                                        iconPos="right"
                                        class="p-button-success"
                                        :loading="loading"
                                        :disabled="!isStep3Valid || loading"
                                        @click="submitForm"
                                    >
                                        <span v-if="!isStep3Valid" class="absolute bottom-full mb-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                                            প্রয়োজনীয়তা ব্যাখ্যা পূরণ করুন
                                        </span>
                                    </Button>
                                </div>
                            </div>
                        </TabPanel>
                    </TabView>
                </template>
            </Card>
        </div>
    </div>
</AuthenticatedLayout>
</template>