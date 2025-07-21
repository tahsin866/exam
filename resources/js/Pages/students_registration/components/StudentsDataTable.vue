<script setup>
import { ref, watch, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import SplitButton from 'primevue/splitbutton';
import axios from 'axios';

// Table columns configuration (user can toggle visibility)
const allColumns = [
  { label: 'রেজিস্ট্রেশন নং', field: 'id', fixed: true },
  { label: 'ছবি', field: 'student_image', fixed: false },
  { label: 'নাম', field: 'name_bn', fixed: false },
  { label: 'পিতার নাম', field: 'father_name_bn', fixed: false },
  { label: 'মাদরাসার নাম', field: 'current_madrasha', fixed: false },
  { label: 'পরীক্ষার নাম', field: 'exam_name_Bn', fixed: false },
  { label: 'মারহালা', field: 'current_class', fixed: false },
  { label: 'জন্ম-তারিখ', field: 'Date_of_birth', fixed: false },
  { label: 'পরীক্ষার্থীর ধরন', field: 'student_type', fixed: false },
  { label: 'পেমেন্ট স্ট্যাটাস', field: 'payment_status', fixed: false },
  { label: 'আবেদন অবস্থা', field: 'status', fixed: false },
  { label: 'করনীয়', field: 'actions', fixed: true }
];

const props = defineProps({
  students: Array,
  filters: Object,
  loading: Boolean
});
const emit = defineEmits(['refresh', 'update:filters']);

const localFilters = ref({ ...props.filters });
watch(() => props.filters, (val) => { localFilters.value = { ...val }; }, { deep: true });
watch(localFilters, (val) => { emit('update:filters', val); }, { deep: true });

const confirm = useConfirm();
const toast = useToast();
const submitId = ref(null);
const deleteId = ref(null);

// Column visibility state
const visibleColumns = ref(allColumns.map(col => col.fixed || true)); // all columns visible by default

const displayedColumns = computed(() =>
  allColumns.filter((col, idx) => col.fixed || visibleColumns.value[idx])
);

const getStatusInBangla = (status) => {
  switch (status) {
    case 'submitted': return 'বোর্ড দাখিল';
    case 'approved': return 'অনুমোদিত';
    case 'returned': return 'বোর্ড ফেরত';
    case 'pending': return 'পেন্ডিং';
    default: return 'পেন্ডিং';
  }
};

const openSubmitConfirm = (event, id) => {
  submitId.value = id;
  confirmDialog.value = {
    show: true,
    type: 'submit',
    header: 'বোর্ড দাখিল করুন',
    message: 'আপনি কি নিশ্চিত যে এই আবেদনটি সাবমিট করতে চান?',
    accept: () => { submitApplication(); confirmDialog.value.show = false; }
  };
};
const openDeleteConfirm = (event, id) => {
  deleteId.value = id;
  confirmDialog.value = {
    show: true,
    type: 'delete',
    header: 'সতর্কীকরণ!',
    message: 'আপনি কি নিশ্চিত যে এই আবেদনটি মুছে ফেলতে চান? এই কাজটি অপরিবর্তনীয়!',
    accept: () => { deleteStudent(); confirmDialog.value.show = false; }
  };
};
const getDropdownItems = (data) => [
  { label: 'এডিট', icon: 'pi pi-pencil', command: () => router.visit(route('students_registration.student_registration_edit', data.id)) },
  { label: 'বিস্তারিত দেখুন', icon: 'pi pi-info-circle', command: () => router.visit(route('students_registration.student_registraion_view', data.id)) },
  { separator: true },
  { label: 'মুছে ফেলুন', icon: 'pi pi-trash', command: (event) => openDeleteConfirm(event, data.id) }
];

const confirmDialog = ref({ show: false, type: '', header: '', message: '', accept: null });
const submitApplication = () => {
  router.post(route('student_reg.submit', submitId.value), {}, {
    preserveScroll: true,
    onSuccess: () => {
      toast.add({ severity: 'success', summary: 'সফল', detail: 'আবেদন সফলভাবে সাবমিট করা হয়েছে', life: 3000 });
      emit('refresh');
    },
    onError: (errors) => {
      if (errors.error) {
        toast.add({ severity: 'error', summary: 'ত্রুটি', detail: errors.error, life: 3000 });
      }
    }
  });
};
const deleteStudent = () => {
  router.delete(route('students.delete', { id: deleteId.value }), {
    preserveScroll: true,
    onSuccess: () => {
      toast.add({ severity: 'success', summary: 'সফল', detail: 'আবেদন সফলভাবে মুছে ফেলা হয়েছে', life: 3000 });
      emit('refresh');
    },
    onError: (errors) => {
      if (errors.error) {
        toast.add({ severity: 'error', summary: 'ত্রুটি', detail: errors.error, life: 3000 });
      }
    }
  });
};
const submitAllApplications = () => {
  confirmDialog.value = {
    show: true,
    type: 'submitall',
    header: 'নিশ্চিতকরণ',
    message: 'আপনি কি নিশ্চিত যে আপনি সমস্ত আবেদন বোর্ডে দাখিল করতে চান?',
    accept: () => {
      toast.add({ severity: 'info', summary: 'প্রক্রিয়াকরণ', detail: 'আপনার সমস্ত আবেদন বোর্ডে দাখিল করা হচ্ছে...', life: 3000 });
      axios.post(route('submit.all.applications'))
        .then(response => {
          if (response.data.success) {
            toast.add({ severity: 'success', summary: 'সফল', detail: response.data.success, life: 5000 });
            emit('refresh');
          } else if (response.data.info) {
            toast.add({ severity: 'info', summary: 'তথ্য', detail: response.data.info, life: 5000 });
          }
        })
        .catch(error => {
          let errorMessage = 'আবেদন দাখিল করতে সমস্যা হয়েছে। পরে আবার চেষ্টা করুন।';
          if (error.response && error.response.data.error) errorMessage = error.response.data.error;
          toast.add({ severity: 'error', summary: 'ত্রুটি', detail: errorMessage, life: 5000 });
        });
      confirmDialog.value.show = false;
    }
  };
};

// CSV Export
const exportCSV = () => {
  const header = displayedColumns.value.map(col => col.label).join(',');
  const rows = props.students.map(student => displayedColumns.value.map(col => {
    if (col.field === 'student_image') return student.student_image ? 'Yes' : 'No';
    if (col.field === 'actions') return '';
    return `"${(student[col.field] ?? '').toString().replace(/"/g, '""')}"`;
  }).join(','));
  const csvContent = [header, ...rows].join('\n');
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.setAttribute('download', 'students.csv');
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

// Pagination state
const page = ref(1);
const rowsPerPage = ref(10);
const paginatedStudents = computed(() => {
  const start = (page.value - 1) * rowsPerPage.value;
  const end = start + rowsPerPage.value;
  return props.students.slice(start, end);
});
const totalPages = computed(() => Math.ceil(props.students.length / rowsPerPage.value));
const goToPage = (p) => { if (p > 0 && p <= totalPages.value) page.value = p; };
watch(() => props.students, () => { page.value = 1; });

// Column toggler modal
const showColumnToggler = ref(false);

</script>

<template>
  <div class="p-0 rounded-md overflow-hidden shadow-xl border border-gray-200 bg-gradient-to-br from-white via-slate-50 to-green-50">
    <!-- Toolbar -->
    <div class="px-5 py-4 bg-gradient-to-r from-green-800 via-green-700 to-green-600 border-b border-green-200 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
      <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" viewBox="0 0 20 20" fill="currentColor">
          <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
        </svg>
        <h3 class="text-white text-2xl font-bold tracking-tight">শিক্ষার্থী তালিকা</h3>
      </div>
      <div class="flex gap-2 flex-wrap justify-end">
        <button @click="showColumnToggler = true" class="inline-flex items-center px-4 py-2 bg-green-100 border border-green-300 rounded-lg font-semibold text-xs text-green-900 uppercase tracking-widest shadow hover:bg-green-200 transition">
          <i class="pi pi-table mr-2" /> কলাম কাস্টমাইজ
        </button>
        <button @click="exportCSV" class="inline-flex items-center px-4 py-2 bg-blue-100 border border-blue-300 rounded-lg font-semibold text-xs text-blue-900 uppercase tracking-widest shadow hover:bg-blue-200 transition">
          <i class="pi pi-file-excel mr-2" /> এক্সেল/CSV
        </button>
        <button class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-yellow-300 rounded-lg font-semibold text-xs text-yellow-900 uppercase tracking-widest shadow hover:bg-yellow-200 transition">
          <i class="pi pi-file-pdf mr-2" /> PDF ডাউনলোড
        </button>
        <button
          class="inline-flex items-center px-4 py-2 bg-green-700 border border-green-800 rounded-lg font-semibold text-xs text-white uppercase tracking-widest shadow hover:bg-green-900 transition"
          @click="submitAllApplications"
        >
          <i class="pi pi-paper-plane mr-2" /> বোর্ড দাখিল (একসাথে)
        </button>
      </div>
    </div>

    <!-- Column Toggler Modal -->
    <div v-if="showColumnToggler" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
      <div class="bg-white rounded-xl shadow-2xl border border-green-200 p-6 w-full max-w-md relative animate-fade-in">
        <div class="font-bold text-lg text-green-900 mb-3">কলাম কাস্টমাইজ করুন</div>
        <div class="flex flex-wrap gap-3 mb-4">
          <label v-for="(col, idx) in allColumns" :key="col.field" class="flex items-center gap-2">
            <input
              type="checkbox"
              :disabled="col.fixed"
              v-model="visibleColumns[idx]"
              class="rounded border-green-400 focus:ring-green-600"
            />
            <span :class="col.fixed ? 'font-bold text-green-800' : ''">{{ col.label }}</span>
          </label>
        </div>
        <div class="flex gap-2 justify-end">
          <button class="px-4 py-2 rounded font-semibold bg-gray-100 hover:bg-gray-200" @click="showColumnToggler = false">বন্ধ করুন</button>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div class="p-6 overflow-x-auto">
      <table class="min-w-full text-sm text-left font-[SolaimanLipi,Inter,sans-serif] border-collapse">
        <thead>
          <tr class="bg-green-100">
            <th v-for="col in displayedColumns" :key="col.field" class="px-4 py-2 border-b border-green-200 font-bold whitespace-nowrap">
              {{ col.label }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in paginatedStudents" :key="student.id" class="hover:bg-green-50 transition">
            <td v-for="col in displayedColumns" :key="col.field" class="px-4 py-2 border-b border-gray-100 whitespace-nowrap"
              :class="col.field === 'actions' ? 'text-center' : ''">
              <!-- Column content rendering -->
              <template v-if="col.field === 'id'">
                <span class="font-mono text-green-900">{{ student.id }}</span>
              </template>
              <template v-else-if="col.field === 'student_image'">
                <div class="flex justify-center">
                  <img
                    v-if="student.student_image"
                    :src="'/storage/' + student.student_image"
                    alt="Student"
                    class="w-12 h-12 border-2 border-green-200 shadow-sm object-cover rounded-lg"
                  />
                  <div v-else class="w-12 h-12 bg-green-100 flex items-center justify-center border-2 border-green-200 rounded-lg">
                    <i class="pi pi-user text-green-400 text-xl"></i>
                  </div>
                </div>
              </template>
              <template v-else-if="col.field === 'current_class'">
                <span class="px-2 py-1 bg-green-50 text-green-800 rounded-md font-medium text-xs border border-green-200">
                  {{ student.current_class }}
                </span>
              </template>
              <template v-else-if="col.field === 'Date_of_birth'">
                <span class="text-green-700">
                  <i class="pi pi-calendar mr-1 text-green-400"></i>
                  {{ student.Date_of_birth }}
                </span>
              </template>
              <template v-else-if="col.field === 'payment_status'">
                <span
                  :class="student.is_paid
                    ? 'bg-green-100 text-green-800 px-2 py-1 rounded-md text-xs font-medium border border-green-200'
                    : 'bg-red-100 text-red-800 px-2 py-1 rounded-md text-xs font-medium border border-red-200'"
                >
                  <i
                    :class="student.is_paid
                      ? 'pi pi-check-circle mr-1'
                      : 'pi pi-times-circle mr-1'"
                  ></i>
                  {{ student.payment_status }}
                </span>
              </template>
              <template v-else-if="col.field === 'status'">
                <span
                  :class="{
                    'bg-yellow-100 text-yellow-800 px-2 py-1 rounded-md text-xs font-medium border border-yellow-200': student.status === 'submitted',
                    'bg-green-100 text-green-800 px-2 py-1 rounded-md text-xs font-medium border border-green-200': student.status === 'approved',
                    'bg-red-100 text-red-800 px-2 py-1 rounded-md text-xs font-medium border border-red-200': student.status === 'returned',
                    'bg-blue-100 text-blue-800 px-2 py-1 rounded-md text-xs font-medium border border-gray-200': student.status === 'pending' || !student.status
                  }"
                >
                  {{ getStatusInBangla(student.status) }}
                </span>
              </template>
              <template v-else-if="col.field === 'actions'">
                <SplitButton
                  label="বোর্ড দাখিল"
                  icon="pi pi-upload"
                  :model="getDropdownItems(student)"
                  @click="openSubmitConfirm($event, student.id)"
                  class="p-button-sm p-button-outlined"
                />
              </template>
              <template v-else>
                <span class="text-green-800">{{ student[col.field] }}</span>
              </template>
            </td>
          </tr>
          <tr v-if="!props.students || props.students.length === 0">
            <td :colspan="displayedColumns.length" class="text-center py-10 text-gray-400 text-xl">
              <i class="pi pi-search text-5xl mb-3 block"></i>
              কোন ছাত্র তথ্য পাওয়া যায়নি
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination Controls -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mt-6">
        <div class="flex items-center gap-2">
          <span>প্রতি পৃষ্ঠায়:</span>
          <select v-model="rowsPerPage" class="border border-green-200 rounded px-2 py-1">
            <option v-for="opt in [5, 10, 20, 50, 100]" :value="opt" :key="opt">{{ opt }}</option>
          </select>
        </div>
        <div class="flex items-center gap-2">
          <button @click="goToPage(page-1)" :disabled="page === 1" class="px-2 py-1 rounded bg-green-50 border border-green-200 text-green-800 hover:bg-green-100">Prev</button>
          <span>পৃষ্ঠা {{ page }} / {{ totalPages }}</span>
          <button @click="goToPage(page+1)" :disabled="page === totalPages" class="px-2 py-1 rounded bg-green-50 border border-green-200 text-green-800 hover:bg-green-100">Next</button>
        </div>
      </div>
    </div>

    <!-- Custom Confirm Dialog -->
    <div v-if="confirmDialog.show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
      <div class="bg-white rounded-xl shadow-2xl border border-green-200 p-7 w-full max-w-md relative animate-fade-in">
        <div class="flex items-center gap-3 mb-4">
          <span v-if="confirmDialog.type === 'submit'" class="bg-green-100 text-green-600 rounded-full p-2">
            <i class="pi pi-paper-plane text-lg"></i>
          </span>
          <span v-if="confirmDialog.type === 'delete'" class="bg-red-100 text-red-600 rounded-full p-2">
            <i class="pi pi-trash text-lg"></i>
          </span>
          <span v-if="confirmDialog.type === 'submitall'" class="bg-blue-100 text-blue-600 rounded-full p-2">
            <i class="pi pi-upload text-lg"></i>
          </span>
          <h4 class="font-bold text-lg text-green-900">{{ confirmDialog.header }}</h4>
        </div>
        <div class="mb-6 text-green-900 text-base">
          {{ confirmDialog.message }}
        </div>
        <div class="flex justify-end gap-2">
          <button
            class="px-5 py-2 bg-gray-100 hover:bg-gray-200 transition text-green-900 rounded-lg font-semibold"
            @click="confirmDialog.show = false"
          >
            বাতিল করুন
          </button>
          <button
            class="px-5 py-2 bg-green-700 hover:bg-green-900 transition text-white rounded-lg font-semibold"
            @click="confirmDialog.accept"
          >
            নিশ্চিত করুন
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: scale(.97);}
  to { opacity: 1; transform: scale(1);}
}
.animate-fade-in {
  animation: fade-in .22s;
}
</style>
