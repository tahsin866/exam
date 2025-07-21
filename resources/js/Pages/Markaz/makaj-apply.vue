<script setup>
import { ref, onMounted, computed } from "vue";
import { useToast } from 'primevue/usetoast';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
// PrimeVue components
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import SplitButton from 'primevue/splitbutton';
import Toast from 'primevue/toast';
import Card from 'primevue/card';
import ProgressBar from 'primevue/progressbar';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Badge from 'primevue/badge';
import InputText from 'primevue/inputtext';
import OverlayPanel from 'primevue/overlaypanel';

const toast = useToast();
const agreements = ref([]);
const activeMenu = ref(null);
const deleteId = ref(null);
const showDeleteModal = ref(false);
const showSubmitModal = ref(false);
const loading = ref(true);
const searchQuery = ref('');
const op = ref();
const selectedAgreement = ref(null);
let submitId = null;

// Filter agreements based on search query
const filteredAgreements = computed(() => {
    if (!searchQuery.value) return agreements.value;

    return agreements.value.filter(agreement => {
        return (
            agreement.id.toString().includes(searchQuery.value) ||
            agreement.main_madrasa.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            agreement.exam_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            agreement.status.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    });
});

// Stats for dashboard cards
const stats = computed(() => {
    return {
        total: agreements.value.length,
        pending: agreements.value.filter(a => a.status === 'খসড়া').length,
        submitted: agreements.value.filter(a => a.status === 'বোর্ড দাখিল').length,
        approved: agreements.value.filter(a => a.status === 'অনুমোদন').length,
        rejected: agreements.value.filter(a => a.status === 'বোর্ড ফেরত').length
    };
});

onMounted(async () => {
    await fetchAgreements();
});

const fetchAgreements = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/markaz/get-table-data');
        agreements.value = response.data.agreements;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching data:', error);
        loading.value = false;
        toast.add({
            severity: 'error',
            summary: 'ত্রুটি',
            detail: 'তথ্য লোড করতে সমস্যা হয়েছে',
            life: 3000
        });
    }
};

const getMenuItems = (agreement) => {
    const items = [];
    if (agreement.status !== 'অনুমোদন') {
        items.push({
            label: 'সম্পাদনা করুন',
            icon: 'pi pi-pencil',
            command: () => editAgreement(agreement.id)
        });
        items.push({
            label: 'মুছে ফেলুন',
            icon: 'pi pi-trash',
            command: () => openDeleteModal(agreement.id)
        });
        items.push({
            label: 'বোর্ড দাখিল করুন',
            icon: 'pi pi-upload',
            command: () => openSubmitModal(agreement.id)
        });
    }
    return items;
};

const editAgreement = (id) => {
    router.get(route('markaz-agreements.edit', id));
};

const openDeleteModal = (id) => {
    deleteId.value = id;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

const deleteAgreement = () => {
    router.delete(route('markaz.delete', { id: deleteId.value }), {
        onSuccess: () => {
            agreements.value = agreements.value.filter(a => a.id !== deleteId.value);
            toast.add({
                severity: 'success',
                summary: 'সফল',
                detail: 'আবেদন সফলভাবে মুছে ফেলা হয়েছে!',
                life: 3000
            });
            showDeleteModal.value = false;
        },
    });
};

const openSubmitModal = (id) => {
    submitId = id;
    showSubmitModal.value = true;
};

const closeSubmitModal = () => {
    showSubmitModal.value = false;
};

const submitApplication = () => {
    router.post(route('markaz.submit', submitId), {}, {
        preserveScroll: true,
        onSuccess: (page) => {
            // agreements array-তে status update এবং রঙ পরিবর্তন
            const idx = agreements.value.findIndex(a => a.id === submitId);
            if (idx !== -1) {
                agreements.value[idx].status = 'বোর্ড দাখিল';
            }
            toast.add({
                severity: 'success',
                summary: 'সফল',
                detail: 'আবেদন বোর্ডে দাখিল হয়েছে!',
                life: 3000
            });
            showSubmitModal.value = false;
        },
        onError: (errors) => {
            if (errors.error) {
                toast.add({
                    severity: 'error',
                    summary: 'ত্রুটি',
                    detail: errors.error,
                    life: 4000
                });
            }
        }
    });
};

const toggleMenu = (event, agreement) => {
    const tableHeight = event.target.closest('table').offsetHeight;
    const rowPosition = event.target.closest('tr').offsetTop;
    if (activeMenu.value && activeMenu.value !== agreement.id) {
        agreements.value.forEach(a => a.showMenu = false);
    }
    agreement.isBottom = (tableHeight - rowPosition) < 200;
    agreement.showMenu = !agreement.showMenu;
    activeMenu.value = agreement.showMenu ? agreement.id : null;
};

const isBottomRow = (agreement) => {
    return agreement.isBottom;
};

const showAgreementDetails = (event, agreement) => {
    selectedAgreement.value = agreement;
    op.value.toggle(event);
};

const getStatusClass = (status) => {
    switch(status) {
        case 'বোর্ড দাখিল':
            return 'bg-amber-100 text-amber-800 border-amber-300';
        case 'বোর্ড ফেরত':
            return 'bg-red-100 text-red-800 border-red-300';
        case 'অনুমোদন':
            return 'bg-emerald-100 text-emerald-800 border-emerald-300';
        default: // খসড়া or any other status
            return 'bg-blue-100 text-blue-700 border-blue-300';
    }
};

// Helper function to get current date and time
const getCurrentDateTime = () => {
    return "2025-07-18 01:27:36"; // Using the provided value
};

// Helper function to get current user
const getCurrentUser = () => {
    return "tahsin866"; // Using the provided value
};

const emit = defineEmits(['deleted']);
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-emerald-50 mt-10">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-emerald-800 to-emerald-700 shadow-md">
                <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="flex items-center mb-4 md:mb-0">
                            <div class="bg-white/10 p-3 rounded-lg shadow-inner mr-4">
                                <i class="pi pi-list text-white text-3xl"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">মারকাজের আবেদন তালিকা</h1>
                                <p class="text-emerald-100">আপনার সমস্ত মারকাজ আবেদন এখানে দেখুন</p>
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

            <!-- Stats Cards -->
            <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <Card class="shadow-md border-0 bg-white hover:shadow-lg transition-all">
                        <template #content>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                    <i class="pi pi-file-o text-blue-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-700">{{ stats.total }}</h3>
                                    <p class="text-sm text-gray-500">মোট আবেদন</p>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <Card class="shadow-md border-0 bg-white hover:shadow-lg transition-all">
                        <template #content>
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                    <i class="pi pi-pencil text-blue-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-700">{{ stats.pending }}</h3>
                                    <p class="text-sm text-gray-500">খসড়া</p>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <Card class="shadow-md border-0 bg-white hover:shadow-lg transition-all">
                        <template #content>
                            <div class="flex items-center">
                                <div class="bg-amber-100 p-3 rounded-lg mr-4">
                                    <i class="pi pi-send text-amber-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-700">{{ stats.submitted }}</h3>
                                    <p class="text-sm text-gray-500">দাখিলকৃত</p>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <Card class="shadow-md border-0 bg-white hover:shadow-lg transition-all">
                        <template #content>
                            <div class="flex items-center">
                                <div class="bg-emerald-100 p-3 rounded-lg mr-4">
                                    <i class="pi pi-check-circle text-emerald-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-700">{{ stats.approved }}</h3>
                                    <p class="text-sm text-gray-500">অনুমোদিত</p>
                                </div>
                            </div>
                        </template>
                    </Card>

                    <Card class="shadow-md border-0 bg-white hover:shadow-lg transition-all">
                        <template #content>
                            <div class="flex items-center">
                                <div class="bg-red-100 p-3 rounded-lg mr-4">
                                    <i class="pi pi-times-circle text-red-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-700">{{ stats.rejected }}</h3>
                                    <p class="text-sm text-gray-500">ফেরত</p>
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>

            <!-- Main Content -->
            <div class=" mx-auto px-4 sm:px-6 lg:px-8 pb-12">
                <Card class="shadow-lg border-0">
                    <template #content>
                        <!-- Header with search and new application button -->
                        <div class="flex flex-col sm:flex-row justify-between items-center pb-4 mb-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-700 mb-3 sm:mb-0">আবেদন তালিকা</h2>

                            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                                <div class="p-input-icon-left w-full sm:w-auto mb-2 sm:mb-0">
                                    <i class="pi pi-search"></i>
                                    <InputText v-model="searchQuery" placeholder="খুঁজুন..." class="p-inputtext-sm w-full sm:w-64" />
                                </div>

                                <Link :href="route('Markaz.markaz_agreement_form')" class="w-full sm:w-auto">
                                    <Button label="নতুন আবেদন করুন" icon="pi pi-plus" class="p-button-raised p-button-success shadow-sm w-full" />
                                </Link>
                            </div>
                        </div>

                        <!-- Loading state -->
                        <div v-if="loading" class="flex justify-center py-8">
                            <div class="flex flex-col items-center">
                                <i class="pi pi-spin pi-spinner text-emerald-500 text-4xl mb-4"></i>
                                <p class="text-gray-600">তথ্য লোড হচ্ছে...</p>
                            </div>
                        </div>

                        <!-- Empty state -->
                        <div v-else-if="agreements.length === 0" class="flex justify-center py-16">
                            <div class="flex flex-col items-center max-w-md text-center">
                                <i class="pi pi-file-excel text-gray-300 text-6xl mb-4"></i>
                                <h3 class="text-xl font-medium text-gray-700 mb-2">কোন আবেদন পাওয়া যায়নি</h3>
                                <p class="text-gray-500 mb-6">আপনি এখনও কোন মারকাজ আবেদন করেননি। নতুন আবেদন করতে নিচের বাটনে ক্লিক করুন।</p>
                                <Link :href="route('Markaz.markaz_agreement_form')">
                                    <Button label="আবেদন করুন" icon="pi pi-plus" class="p-button-raised" />
                                </Link>
                            </div>
                        </div>

                        <!-- Search empty state -->
                        <div v-else-if="filteredAgreements.length === 0" class="flex justify-center py-16">
                            <div class="flex flex-col items-center max-w-md text-center">
                                <i class="pi pi-search text-gray-300 text-6xl mb-4"></i>
                                <h3 class="text-xl font-medium text-gray-700 mb-2">কোন ফলাফল পাওয়া যায়নি</h3>
                                <p class="text-gray-500 mb-6">আপনার অনুসন্ধানের সাথে মিল রেখে কোন আবেদন পাওয়া যায়নি।</p>
                                <Button label="সব দেখুন" icon="pi pi-list" class="p-button-outlined" @click="searchQuery = ''" />
                            </div>
                        </div>

                        <!-- DataTable with applications -->
                        <div v-else class="overflow-x-auto">
                            <DataTable
                                :value="filteredAgreements"
                                stripedRows
                                class="custom-datatable"
                                responsiveLayout="scroll"
                                paginator
                                :rows="10"
                                :rowsPerPageOptions="[5, 10, 20, 50]"
                                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                currentPageReportTemplate="{first} থেকে {last} এর মধ্যে {totalRecords} টি আবেদন"
                            >
                                <Column field="id" header="আবেদন নং" style="width: 90px" class="text-center">
                                    <template #body="slotProps">
                                        <span class="font-semibold">{{ slotProps.data.id }}</span>
                                    </template>
                                </Column>

                                <Column field="application_date" header="আবেদনের তারিখ" style="width: 150px">
                                    <template #body="slotProps">
                                        <span>{{ slotProps.data.application_date }}</span>
                                    </template>
                                </Column>

                                <Column header="মাদরাসার নাম">
                                    <template #body="slotProps">
                                        <div class="flex flex-col">
                                            <div class="font-medium">{{ slotProps.data.main_madrasa }}</div>
                                            <div v-if="slotProps.data.associated_madrasas && slotProps.data.associated_madrasas.length">
                                                <Badge :value="slotProps.data.associated_madrasas.length" severity="info" class="mr-2"></Badge>
                                                <span
                                                    class="text-sm text-blue-600 cursor-pointer hover:underline"
                                                    @click="showAgreementDetails($event, slotProps.data)"
                                                >
                                                    সংযুক্ত মাদরাসা দেখুন
                                                </span>
                                            </div>
                                        </div>
                                    </template>
                                </Column>

                                <Column field="exam_name" header="কেন্দ্রীয় পরীক্ষা">
                                    <template #body="slotProps">
                                        <span>{{ slotProps.data.exam_name }}</span>
                                    </template>
                                </Column>

                                <Column header="ছাত্র সংখ্যা" style="width: 150px">
                                    <template #body="slotProps">
                                        <div class="flex flex-col">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">মূল মাদরাসা:</span>
                                                <span class="font-semibold">{{ slotProps.data.main_total_students }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">সংযুক্ত:</span>
                                                <span class="font-semibold">{{ slotProps.data.associated_total_students }}</span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm border-t border-gray-200 mt-1 pt-1">
                                                <span class="text-gray-700">মোট:</span>
                                                <span class="font-bold">{{ slotProps.data.main_total_students + slotProps.data.associated_total_students }}</span>
                                            </div>
                                        </div>
                                    </template>
                                </Column>

                                <Column header="স্ট্যাটাস" style="width: 130px">
                                    <template #body="slotProps">
                                        <span
                                            class="px-3 py-1.5 text-xs font-medium rounded-full border inline-block text-center"
                                            :class="getStatusClass(slotProps.data.status)"
                                        >
                                            {{ slotProps.data.status }}
                                        </span>
                                    </template>
                                </Column>

                                <Column header="করনীয়" style="width: 140px" class="text-center">
                                    <template #body="slotProps">
                                        <SplitButton
                                            label="বিস্তারিত"
                                            icon="pi pi-eye"
                                            @click="router.get(route('Markaz.view', slotProps.data.id))"
                                            :model="getMenuItems(slotProps.data)"
                                            class="p-button-sm custom-split-button"
                                        />
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </template>
                </Card>
            </div>

            <!-- Details Overlay Panel -->
            <OverlayPanel ref="op" class="w-80 md:w-96">
                <div v-if="selectedAgreement" class="p-3">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 pb-2 border-b border-gray-200">সংযুক্ত মাদরাসা সমূহ</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li v-for="(madrasa, index) in selectedAgreement.associated_madrasas" :key="index" class="text-gray-700">
                            {{ madrasa }}
                        </li>
                    </ul>
                </div>
            </OverlayPanel>

            <!-- Toast -->
            <Toast />

            <!-- Delete Modal -->
            <Dialog v-model:visible="showDeleteModal" modal header="সতর্কীকরণ!" :style="{ width: '450px' }" :closable="false" class="p-fluid">
                <div class="flex flex-col items-center p-4">
                    <i class="pi pi-exclamation-triangle text-5xl text-red-500 mb-4"></i>
                    <p class="text-center mb-2">আপনি কি নিশ্চিত যে এই আবেদনটি মুছে ফেলতে চান?</p>
                    <p class="text-center text-sm text-red-600">এই কাজটি অপরিবর্তনীয়!</p>
                </div>
                <template #footer>
                    <div class="flex justify-end">
                        <Button label="বাতিল করুন" icon="pi pi-times" class="p-button-text" @click="closeDeleteModal" />
                        <Button label="মুছে ফেলুন" icon="pi pi-trash" class="p-button-danger" @click="deleteAgreement" />
                    </div>
                </template>
            </Dialog>

            <!-- Submit Modal -->
            <Dialog v-model:visible="showSubmitModal" modal header="বোর্ডে দাখিল করুন" :style="{ width: '450px' }" :closable="false" class="p-fluid">
                <div class="flex flex-col items-center p-4">
                    <i class="pi pi-send text-5xl text-amber-500 mb-4"></i>
                    <p class="text-center">আপনি কি নিশ্চিত যে এই আবেদনটি বোর্ডে দাখিল করতে চান?</p>
                </div>
                <template #footer>
                    <div class="flex justify-end">
                        <Button label="বাতিল করুন" icon="pi pi-times" class="p-button-text" @click="closeSubmitModal" />
                        <Button label="দাখিল করুন" icon="pi pi-send" class="p-button-warning" @click="submitApplication" />
                    </div>
                </template>
            </Dialog>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.custom-datatable {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.custom-datatable >>> .p-datatable-header {
    background: #f8fafc;
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.custom-datatable >>> .p-datatable-thead > tr > th {
    background: #f1f5f9;
    padding: 1rem 0.75rem;
    border-color: #e2e8f0;
    font-weight: 600;
    color: #334155;
    white-space: nowrap;
}

.custom-datatable >>> .p-datatable-tbody > tr {
    transition: all 0.2s ease;
}

.custom-datatable >>> .p-datatable-tbody > tr:nth-child(even) {
    background-color: #f8fafc;
}

.custom-datatable >>> .p-datatable-tbody > tr:hover {
    background-color: #eef2ff;
}

.custom-datatable >>> .p-datatable-tbody > tr > td {
    padding: 0.75rem;
    border-color: #e2e8f0;
}

.custom-datatable >>> .p-paginator {
    background: #f8fafc;
    padding: 0.75rem;
    border-top: 1px solid #e2e8f0;
}

.custom-datatable >>> .p-paginator .p-paginator-current {
    margin-left: auto;
    color: #64748b;
}

.custom-split-button >>> .p-splitbutton-defaultbutton {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
}

.custom-split-button >>> .p-splitbutton-menubutton {
    background: linear-gradient(135deg, #0d9668, #047857);
    border: none;
}

.p-button {
    transition: all 0.2s;
}

.p-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.p-button.p-button-raised {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
}

.p-card {
    transition: all 0.3s ease;
    border-radius: 0.75rem;
    overflow: hidden;
}

.p-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

@keyframes fadeIn {
    from { opacity: 0.7; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Dialog animations */
.p-dialog-enter-from {
    opacity: 0;
    transform: scale(0.95);
}

.p-dialog-enter-active {
    transition: all 0.2s ease-out;
}

.p-dialog-enter-to {
    opacity: 1;
    transform: scale(1);
}

.p-dialog-leave-from {
    opacity: 1;
    transform: scale(1);
}

.p-dialog-leave-active {
    transition: all 0.2s ease-in;
}

.p-dialog-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>
