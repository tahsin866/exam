<script setup>
import { ref, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/admin/AuthenticatedLayout.vue";
import { Head } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Textarea from 'primevue/textarea';
import FileUpload from 'primevue/fileupload';
import Toast from 'primevue/toast';
import Chip from 'primevue/chip';
import Card from 'primevue/card';
import Divider from 'primevue/divider';
import ScrollPanel from 'primevue/scrollpanel';

// Composables
import { useMarkazStatus } from '@/composables/admin/markaz/useMarkazStatus';
import { useMarkazTabs } from '@/composables/admin/markaz/useMarkazTabs';
import { useMarkazDocuments } from '@/composables/admin/markaz/useMarkazDocuments';
import { useMarkazStudentStats } from '@/composables/admin/markaz/useMarkazStudentStats';
import { useMarkazDetailActions } from '@/composables/admin/markaz/useMarkazDetailActions';
import { useMarkazScroll } from '@/composables/admin/markaz/useMarkazScroll';
import { useMarkazDateFormatter } from '@/composables/admin/markaz/useMarkazDateFormatter';

const props = defineProps({
  markazDetails: { type: Object, required: true },
  application: { type: Object, required: false, default: () => ({}) }
});

// Convert props to refs for composables
const markazDetailsRef = computed(() => props.markazDetails);

// Initialize composables
const { getStatusLabel, getStatusClass, getActivityStatusLabel, getActivityStatusClass } = useMarkazStatus();
const { activeTab, setActiveTab, tabs } = useMarkazTabs();
const { documentList, associatedDocKeys, viewDocument, downloadDocument } = useMarkazDocuments(markazDetailsRef);
const { totalStudents, associatedTotalStudents, studentCategories, getStudentPercentage } = useMarkazStudentStats(markazDetailsRef);
const { scrollY, scrollToTop } = useMarkazScroll();
const { formatDate } = useMarkazDateFormatter();

// Detail actions
const {
  showApprovalModal,
  openApprovalModal,
  confirmApproval,
  isReturnModalOpen,
  returnReason,
  returnImage,
  returnImagePreview,
  openReturnModal,
  closeReturnModal,
  handleReturnImageUpload,
  removeReturnImage,
  submitReturnReason,
  startProcessing,
  isRejectModalOpen,
  rejectReason,
  rejectImage,
  rejectImagePreview,
  openRejectModal,
  closeRejectModal,
  handleRejectImageUpload,
  removeRejectImage,
  submitRejectReason
} = useMarkazDetailActions(props);
</script>

<template>
  <Head title="মারকায মাদরাসার বিস্তারিত" />
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-blue-50 mt-5">
      <!-- Sticky Header -->
      <div class="sticky top-0 z-50 backdrop-blur-lg bg-white/80 border-b border-gray-200 shadow-sm">
        <div class=" mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                <i class="pi pi-building text-white text-sm"></i>
              </div>
              <div>
                <h1 class="text-lg font-semibold text-gray-900">মারকায আবেদন বিস্তারিত</h1>
                <p class="text-xs text-gray-500">{{ props.markazDetails.madrasha_Name }}</p>
              </div>
            </div>
            <div class="flex items-center space-x-3">
              <Chip :label="getStatusLabel(props.application.status)"
                    :class="getStatusClass(props.application.status)"
                    class="font-medium px-3 py-1 rounded-full text-sm shadow-sm"/>
              <span class="text-sm text-gray-500 font-medium">ID: #{{ props.markazDetails.id }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <Card><template #content>
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="pi pi-users text-blue-600 text-lg"></i>
              </div>
              <div>
                <p class="text-2xl font-bold text-gray-900">{{ totalStudents }}</p>
                <p class="text-sm text-gray-500">মূল মাদরাসার ছাত্র</p>
              </div>
            </div>
          </template></Card>
          <Card><template #content>
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="pi pi-sitemap text-green-600 text-lg"></i>
              </div>
              <div>
                <p class="text-2xl font-bold text-gray-900">{{ associatedTotalStudents }}</p>
                <p class="text-sm text-gray-500">সংযুক্ত মাদরাসার ছাত্র</p>
              </div>
            </div>
          </template></Card>
          <Card><template #content>
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="pi pi-building text-purple-600 text-lg"></i>
              </div>
              <div>
                <p class="text-2xl font-bold text-gray-900">{{ (props.markazDetails.associated_madrasas?.length || 0) + 1 }}</p>
                <p class="text-sm text-gray-500">মোট মাদরাসা</p>
              </div>
            </div>
          </template></Card>
          <Card><template #content>
            <div class="flex items-center space-x-3">
              <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="pi pi-calendar text-orange-600 text-lg"></i>
              </div>
              <div>
                <p class="text-lg font-bold text-gray-900">{{ formatDate(props.markazDetails.created_at) }}</p>
                <p class="text-sm text-gray-500">আবেদনের তারিখ</p>
              </div>
            </div>
          </template></Card>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-8">
          <nav class="flex space-x-1 bg-gray-100 p-1 rounded-lg w-fit">
            <button @click="activeTab = 'basic'"
              :class="activeTab === 'basic' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
              class="px-4 py-2 text-lg font-medium rounded-md transition-all duration-200">মূল তথ্য</button>
            <button @click="activeTab = 'students'"
              :class="activeTab === 'students' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
              class="px-4 py-2 text-lg font-medium rounded-md transition-all duration-200">ছাত্র তথ্য</button>
            <button @click="activeTab = 'documents'"
              :class="activeTab === 'documents' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
              class="px-4 py-2 text-lg font-medium rounded-md transition-all duration-200">ডকুমেন্ট</button>
            <button @click="activeTab = 'associated'"
              :class="activeTab === 'associated' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
              class="px-4 py-2 text-lg font-medium rounded-md transition-all duration-200">সংযুক্ত মাদরাসা</button>
            <button @click="activeTab = 'timeline'"
              :class="activeTab === 'timeline' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
              class="px-4 py-2 text-lg font-medium rounded-md transition-all duration-200">আবেদন অবস্থা পর্যবেক্ষণ</button>
          </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Main -->
          <div class="lg:col-span-2">
            <!-- Basic Info Tab -->
            <div v-show="activeTab === 'basic'" class="space-y-6">
              <Card class="border-0 shadow-sm">
                <template #title>
                  <div class="flex items-center space-x-2">
                    <i class="pi pi-info-circle text-indigo-600"></i>
                    <span>মূল মাদরাসার তথ্য</span>
                  </div>
                </template>
                <template #content>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                      <div class="border-l-4 border-indigo-500 pl-4">
                        <p class="text-sm font-medium text-gray-500">মাদরাসার নাম</p>
                        <p class="text-lg font-semibold text-gray-900">{{ props.markazDetails.madrasha_Name }}</p>
                      </div>
                      <div class="border-l-4 border-green-500 pl-4">
                        <p class="text-sm font-medium text-gray-500">মারকায টাইপ</p>
                        <p class="text-lg font-semibold text-gray-900">{{ props.markazDetails.markaz_type }}</p>
                      </div>
                    </div>
                    <div class="space-y-4">
                      <div class="border-l-4 border-purple-500 pl-4">
                        <p class="text-sm font-medium text-gray-500">পরীক্ষার নাম</p>
                        <p class="text-lg font-semibold text-gray-900">{{ props.markazDetails.exam_name }}</p>
                      </div>
                      <div class="border-l-4 border-orange-500 pl-4">
                        <p class="text-sm font-medium text-gray-500">আবেদনের তারিখ</p>
                        <p class="text-lg font-semibold text-gray-900">{{ formatDate(props.markazDetails.created_at) }}</p>
                      </div>
                    </div>
                  </div>
                  <Divider />
                  <div>
                    <p class="text-sm font-medium text-gray-500 mb-3">প্রয়োজনীয়তা</p>
                    <ScrollPanel style="width: 100%; height: 120px" class="bg-gray-50 rounded-lg p-4">
                      <p class="text-gray-700 leading-relaxed">{{ props.markazDetails.requirements }}</p>
                    </ScrollPanel>
                  </div>
                </template>
              </Card>
            </div>
            <!-- Students Tab -->
            <div v-show="activeTab === 'students'" class="space-y-6">
              <Card class="border-0 shadow-sm">
                <template #title>
                  <div class="flex items-center space-x-2">
                    <i class="pi pi-users text-blue-600"></i>
                    <span>শ্রেণিভিত্তিক ছাত্র সংখ্যা</span>
                  </div>
                </template>
                <template #content>
                  <div class="overflow-x-auto">
                    <table class="min-w-full">
                      <thead>
                        <tr class="border-b border-gray-200">
                          <th class="text-left py-3 px-4 font-medium text-gray-700">শ্রেণী</th>
                          <th class="text-center py-3 px-4 font-medium text-gray-700">ছাত্র সংখ্যা</th>
                          <th class="text-center py-3 px-4 font-medium text-gray-700">অগ্রগতি</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-100">
                        <tr v-for="(label, key, color) in [
                          ['ফাযীলাত', 'fazilat', 'blue-600'],
                          ['সানাবিয়া উলইয়া', 'sanabiya_ulya', 'green-600'],
                          ['সানাবিয়া', 'sanabiya', 'purple-600'],
                          ['মুতাওয়াসসিতা', 'mutawassita', 'orange-600'],
                          ['ইবতেদাইয়্যাহু', 'ibtedaiyyah', 'red-600'],
                          ['তাহফিজুল কোরআন', 'hifzul_quran', 'indigo-600']
                        ]"
                        :key="key"
                        class="hover:bg-gray-50">
                          <td class="py-3 px-4">{{ label[0] }}</td>
                          <td class="text-center py-3 px-4 font-semibold">{{ props.markazDetails[label[1]] || 0 }}</td>
                          <td class="text-center py-3 px-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                              <div :class="`bg-${label[2]} h-2 rounded-full`"
                                   :style="{ width: ((props.markazDetails[label[1]] || 0) / Math.max(totalStudents, 1)) * 100 + '%' }"></div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </template>
              </Card>
            </div>
            <!-- Documents Tab -->
            <div v-show="activeTab === 'documents'" class="space-y-6">
              <Card class="border-0 shadow-sm">
                <template #title>
                  <div class="flex items-center space-x-2">
                    <i class="pi pi-file text-green-600"></i>
                    <span>মূল মাদরাসার ডকুমেন্টস</span>
                  </div>
                </template>
                <template #content>
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th class="px-6 py-3 text-left text-lg font-semibold text-gray-500 uppercase tracking-wider">ডকুমেন্টের নাম</th>
                          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">অ্যাকশন</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="doc in documentList" :key="doc.key">
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <i class="pi pi-file-pdf text-red-500 mr-2"></i>
                              <span class="text-sm text-gray-900">{{ doc.label }}</span>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <Button size="small" outlined class="mr-2" @click="viewDocument(doc.file)">
                              <i class="pi pi-eye mr-1"></i> ভিউ
                            </Button>
                            <Button size="small" severity="secondary" @click="downloadDocument(doc.file)">
                              <i class="pi pi-download mr-1"></i> ডাউনলোড
                            </Button>
                          </td>
                        </tr>
                        <tr v-if="documentList.length === 0">
                          <td colspan="2" class="text-center py-10 text-gray-400">কোনো ডকুমেন্ট পাওয়া যায়নি</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </template>
              </Card>
            </div>
            <!-- Associated Madrasas Tab -->
            <div v-show="activeTab === 'associated'" class="space-y-6">
              <Card class="border-0 shadow-sm" v-if="props.markazDetails.associated_madrasas && props.markazDetails.associated_madrasas.length">
                <template #title>
                  <div class="flex items-center space-x-2">
                    <i class="pi pi-sitemap text-purple-600"></i>
                    <span>সংযুক্ত মাদরাসা সমূহ</span>
                  </div>
                </template>
                <template #content>
                  <div class="space-y-6">
                    <div v-for="(madrasa, index) in props.markazDetails.associated_madrasas"
                         :key="madrasa.id"
                         class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-all">
                      <div class="flex items-start justify-between mb-3">
                        <div>
                          <h3 class="font-semibold text-gray-900">{{ madrasa.madrasa_Name }}</h3>
                          <p class="text-sm text-gray-500">মাদরাসা #{{ index + 1 }}</p>
                        </div>
                        <Chip :label="`${(madrasa.fazilat || 0) + (madrasa.sanabiya_ulya || 0) + (madrasa.sanabiya || 0) + (madrasa.mutawassita || 0) + (madrasa.ibtedaiyyah || 0) + (madrasa.hifzul_quran || 0)} জন ছাত্র`"
                              class="bg-blue-100 text-blue-800"/>
                      </div>
                      <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm mb-6">
                        <div class="flex justify-between"><span class="text-gray-600">ফাযীলাত:</span><span class="font-medium">{{ madrasa.fazilat || 0 }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">সানাবিয়া উলইয়া:</span><span class="font-medium">{{ madrasa.sanabiya_ulya || 0 }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">সানাবিয়া:</span><span class="font-medium">{{ madrasa.sanabiya || 0 }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">মুতাওয়াসসিতা:</span><span class="font-medium">{{ madrasa.mutawassita || 0 }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">ইবতেদাইয়্যাহু:</span><span class="font-medium">{{ madrasa.ibtedaiyyah || 0 }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">হিফযুল কুরআন:</span><span class="font-medium">{{ madrasa.hifzul_quran || 0 }}</span></div>
                      </div>
                      <div>
                        <p class="font-semibold text-gray-800 mb-1">সংযুক্ত ডকুমেন্টস</p>
                        <table class="min-w-full divide-y divide-gray-200 border rounded">
                          <thead class="bg-gray-50">
                            <tr>
                              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ডকুমেন্টের নাম</th>
                              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">অ্যাকশন</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="doc in associatedDocKeys" :key="doc.key">
                              <td class="px-4 py-2">
                                <span class="text-gray-700">{{ doc.label }}</span>
                              </td>
                              <td class="px-4 py-2">
                                <template v-if="madrasa[doc.key]">
                                  <Button size="small" outlined class="mr-2" @click="viewDocument(madrasa[doc.key])">
                                    <i class="pi pi-eye mr-1"></i> ভিউ
                                  </Button>
                                  <Button size="small" severity="secondary" @click="downloadDocument(madrasa[doc.key])">
                                    <i class="pi pi-download mr-1"></i> ডাউনলোড
                                  </Button>
                                </template>
                                <template v-else>
                                  <span class="text-gray-400">নাই</span>
                                </template>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </template>
              </Card>
              <Card class="border-0 shadow-sm" v-else>
                <template #content>
                  <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                      <i class="pi pi-building text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500">কোন সংযুক্ত মাদরাসা নেই</p>
                  </div>
                </template>
              </Card>
            </div>
            <!-- Timeline Tab -->
            <div v-show="activeTab === 'timeline'" class="space-y-6">
              <Card class="border-0 shadow-sm">
                <template #title>
                  <div class="flex items-center space-x-2">
                    <i class="pi pi-clock text-indigo-600"></i>
                    <span>আবেদন অবস্থা পর্যবেক্ষণ</span>
                  </div>
                </template>
                <template #content>
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">
                            সিদ্ধান্ত গ্রহণকারী
                          </th>
                          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">
                            পদবি
                          </th>
                          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">
                            তারিখ ও সময়
                          </th>
                          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">
                            অবস্থা
                          </th>
                          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">
                            মন্তব্য
                          </th>
                          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">
                            ফিডব্যাক ইমেজ
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(activity, idx) in props.markazDetails.status_logs" :key="idx" class="hover:bg-gray-50">
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div class="text-sm font-medium text-gray-900">{{ activity.admin_name || activity.user_name || 'N/A' }}</div>
                              <span v-if="activity.user_type"
                                    :class="activity.user_type === 'admin' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-blue-100 text-blue-800 border border-blue-200'"
                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium shadow-sm">
                                {{ activity.user_type === 'admin' ? 'এডমিন' : 'মাদরাসা' }}
                              </span>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="activity.user_type === 'admin' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-green-100 text-green-800 border border-green-200'"
                                  class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full shadow-sm">
                              {{ activity.admin_position || activity.designation || 'N/A' }}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-sm text-gray-500">
                              <i class="pi pi-calendar mr-1 text-gray-400"></i>
                              {{ formatDate(activity.processed_at || activity.created_at) }}
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="getActivityStatusClass(activity.status)"
                                  class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm">
                              {{ getActivityStatusLabel(activity.status) }}
                            </span>
                          </td>
                          <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 max-h-24 overflow-y-auto pr-4">
                              {{ activity.admin_message || activity.comments || 'কোন মন্তব্য নাই' }}
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div v-if="activity.admin_feedback_image || activity.feedback_image">
                              <a :href="'/storage/' + (activity.admin_feedback_image || activity.feedback_image)" target="_blank">
                                <img :src="'/storage/' + (activity.admin_feedback_image || activity.feedback_image)"
                                     alt="Feedback Image"
                                     class="h-12 w-12 object-cover rounded shadow hover:shadow-lg transition-shadow" />
                              </a>
                            </div>
                            <div v-else class="text-xs text-gray-400">N/A</div>
                          </td>
                        </tr>
                        <tr v-if="!props.markazDetails.status_logs || props.markazDetails.status_logs.length === 0">
                          <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                              <i class="pi pi-info-circle text-gray-300 text-2xl mb-2"></i>
                              <span>এখনো কোন কার্যক্রম রেকর্ড করা হয়নি</span>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </template>
              </Card>
            </div>
          </div>
          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <Card class="border-0 shadow-sm mb-6 sticky top-24">
              <template #title>
                <div class="flex items-center space-x-2">
                  <i class="pi pi-cog text-gray-600"></i>
                  <span>দ্রুত কার্যক্রম</span>
                </div>
              </template>
              <template #content>
                <div class="space-y-3">
                  <Button @click="openApprovalModal" severity="success" class="w-full justify-start" size="large">
                    <i class="pi pi-check mr-2"></i> অনুমোদন প্রদান
                  </Button>
                  <Button @click="startProcessing" severity="info" class="w-full justify-start" size="large">
                    <i class="pi pi-cog mr-2"></i> প্রক্রিয়াকরণ শুরু
                  </Button>
                  <Button @click="openReturnModal" severity="warning" outlined class="w-full justify-start" size="large">
                    <i class="pi pi-undo mr-2"></i> ফেরত পাঠান
                  </Button>
                  <Button @click="openRejectModal" severity="danger" outlined class="w-full justify-start" size="large">
                    <i class="pi pi-times mr-2"></i> প্রত্যাখ্যান
                  </Button>
                  <Divider />
                  <Button outlined class="w-full justify-start" size="large">
                    <i class="pi pi-download mr-2"></i> PDF ডাউনলোড
                  </Button>
                  <Button outlined class="w-full justify-start" size="large">
                    <i class="pi pi-print mr-2"></i> প্রিন্ট করুন
                  </Button>
                </div>
              </template>
            </Card>
          </div>
        </div>
      </div>
      <!-- Scroll to Top Button -->
      <transition name="fade">
        <button v-show="scrollY > 400" @click="scrollToTop"
          class="fixed bottom-6 right-6 z-40 w-12 h-12 bg-white border border-gray-200 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group">
          <i class="pi pi-arrow-up text-gray-600 group-hover:text-gray-900 transition-colors"></i>
        </button>
      </transition>
      <!-- Approval Modal -->
      <Dialog v-model:visible="showApprovalModal" modal header="অনুমোদন নিশ্চিতকরণ"
        :style="{width: '30vw'}" :closable="false">
        <div class="text-center mb-6">
          <div class="w-16 h-16 mx-auto mb-4 bg-emerald-100 rounded-full flex items-center justify-center">
            <i class="pi pi-check text-emerald-600 text-2xl"></i>
          </div>
          <h4 class="text-xl font-semibold text-gray-800">অনুমোদন নিশ্চিতকরণ</h4>
          <p class="text-gray-600 mt-2">আপনি কি এই অনুমোদন প্রদান করতে নিশ্চিত?</p>
        </div>
        <template #footer>
          <Button label="না, ফিরে যান" icon="pi pi-times" @click="showApprovalModal = false" class="p-button-text"/>
          <Button label="হ্যাঁ, নিশ্চিত" icon="pi pi-check" @click="confirmApproval" class="p-button-success" autofocus/>
        </template>
      </Dialog>
      <!-- Return Modal -->
      <Dialog v-model:visible="isReturnModalOpen" modal header="ফেরতের কারণ"
        :style="{width: '550px'}" :closable="false" class="return-modal">
        <div class="p-fluid w-full">
          <div class="field w-full">
            <Textarea v-model="returnReason" placeholder="ফেরতের কারণ লিখুন..." rows="5" class="text-2xl w-full"/>
          </div>
          <div class="field mt-5 w-full">
            <label class="block text-sm font-semibold text-emerald-700 mb-2">ছবি সংযুক্ত করুন</label>
            <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" @select="handleReturnImageUpload" :auto="true" chooseLabel="ছবি নির্বাচন করুন" class="w-full"/>
          </div>
          <div v-if="returnImagePreview" class="mt-4 relative w-full">
            <img :src="returnImagePreview" class="w-full h-48 object-cover rounded-lg border border-emerald-200">
            <Button icon="pi pi-times" @click="removeReturnImage" class="p-button-rounded p-button-danger absolute top-2 right-2"/>
          </div>
        </div>
        <template #footer>
          <Button label="বাতিল" icon="pi pi-times" @click="closeReturnModal" class="p-button-text"/>
          <Button label="সাবমিট" icon="pi pi-check" @click="submitReturnReason" class="p-button-success"/>
        </template>
      </Dialog>
      <!-- Reject Modal -->
      <Dialog v-model:visible="isRejectModalOpen" modal header="রিজেক্টের কারণ"
        :style="{width: '550px'}" :closable="false">
        <div class="p-fluid">
          <div class="field">
            <Textarea v-model="rejectReason" placeholder="রিজেক্টের কারণ লিখুন..." rows="5" class="text-2xl w-full"/>
          </div>
          <div class="field mt-5">
            <label class="block text-sm font-semibold text-red-700 mb-2">ছবি সংযুক্ত করুন</label>
            <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" @select="handleRejectImageUpload" :auto="true" chooseLabel="ছবি নির্বাচন করুন"/>
          </div>
          <div v-if="rejectImagePreview" class="mt-4 relative">
            <img :src="rejectImagePreview" class="w-full h-48 object-cover rounded-lg border border-red-200">
            <Button icon="pi pi-times" @click="removeRejectImage" class="p-button-rounded p-button-danger absolute top-2 right-2"/>
          </div>
        </div>
        <template #footer>
          <Button label="বাতিল" icon="pi pi-times" @click="closeRejectModal" class="p-button-text"/>
          <Button label="সাবমিট" icon="pi pi-check" @click="submitRejectReason" class="p-button-danger"/>
        </template>
      </Dialog>
      <Toast position="top-right"/>
    </div>
  </AuthenticatedLayout>
</template>
