import { ref, onMounted, computed } from "vue";
import { useToast } from 'primevue/usetoast';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';

export function useMakajApply() {
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

    const filteredAgreements = computed(() => {
        if (!searchQuery.value) return agreements.value;
        return agreements.value.filter(agreement => {
            const statusLabel = getStatusLabel(agreement.status);
            return (
                agreement.id.toString().includes(searchQuery.value) ||
                agreement.main_madrasa.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                agreement.exam_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                agreement.status.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                statusLabel.toLowerCase().includes(searchQuery.value.toLowerCase())
            );
        });
    });

    const stats = computed(() => {
        return {
            total: agreements.value.length,
            pending: agreements.value.filter(a => a.status === 'অপেক্ষমান' || a.status === 'pending').length,
            submitted: agreements.value.filter(a => a.status === 'বোর্ড দাখিল' || a.status === 'board_submitted').length,
            approved: agreements.value.filter(a => a.status === 'অনুমোদন করা হয়েছে' || a.status === 'approved').length,
            rejected: agreements.value.filter(a => a.status === 'বোর্ড ফেরত' || a.status === 'board_returned').length,
            processing: agreements.value.filter(a => a.status === 'প্রক্রিয়াধীন' || a.status === 'in_processing').length
        };
    });

    // Status mapping function
    const getStatusLabel = (status) => {
        const statusMap = {
            'pending': 'অপেক্ষমান',
            'board_submitted': 'বোর্ড দাখিল',
            'board_returned': 'বোর্ড ফেরত',
            'approved': 'অনুমোদন করা হয়েছে',
            'suspended': 'স্থগিত করা হয়েছে',
            'cancelled': 'বাতিল করা হয়েছে',
            'in_processing': 'প্রক্রিয়াধীন'
        };
        return statusMap[status] || status;
    };

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
        items.push({
            label: 'পিডিএফ ডাউনলোড',
            icon: 'pi pi-file-pdf',
            command: () => downloadPdf(agreement.id)
        });
        // Only show edit/delete/submit if status is 'অপেক্ষমান'/'pending' or 'বোর্ড ফেরত'/'board_returned'
        if (agreement.status === 'অপেক্ষমান' || agreement.status === 'pending' ||
            agreement.status === 'বোর্ড ফেরত' || agreement.status === 'board_returned') {
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

    const downloadPdf = (id) => {
        window.open(route('markaz-agreements.pdf', id), '_blank');
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
                return 'bg-amber-100 text-amber-800 border border-amber-300';
            case 'বোর্ড ফেরত':
                return 'bg-red-100 text-red-800 border border-red-300';
            case 'অনুমোদন করা হয়েছে':
                return 'bg-emerald-100 text-emerald-800 border border-emerald-300';
            case 'অপেক্ষমান':
                return 'bg-blue-100 text-blue-700 border border-blue-300';
            case 'স্থগিত করা হয়েছে':
                return 'bg-gray-100 text-gray-700 border border-gray-300';
            case 'বাতিল করা হয়েছে':
                return 'bg-red-100 text-red-700 border border-red-300';
            case 'প্রক্রিয়াধীন':
            case 'in_processing':
                return 'bg-purple-100 text-purple-800 border border-purple-300';
            default:
                return 'bg-blue-100 text-blue-700 border border-blue-300';
        }
    };
    const getCurrentDateTime = () => {
        return "2025-07-18 01:27:36";
    };
    const getCurrentUser = () => {
        return "tahsin866";
    };

    return {
        agreements,
        activeMenu,
        deleteId,
        showDeleteModal,
        showSubmitModal,
        loading,
        searchQuery,
        op,
        selectedAgreement,
        filteredAgreements,
        stats,
        fetchAgreements,
        getMenuItems,
        downloadPdf,
        editAgreement,
        openDeleteModal,
        closeDeleteModal,
        deleteAgreement,
        openSubmitModal,
        closeSubmitModal,
        submitApplication,
        toggleMenu,
        isBottomRow,
        showAgreementDetails,
        getStatusClass,
        getStatusLabel,
        getCurrentDateTime,
        getCurrentUser
    };
}
