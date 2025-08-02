import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

export const useMarkazDetailActions = (props) => {
  const toast = useToast();

  // Approval modal
  const showApprovalModal = ref(false);
  const openApprovalModal = () => { showApprovalModal.value = true; };

  const confirmApproval = () => {
    const id = props.application?.id || props.markazDetails.id;
    router.post(route('admin.markaz.approve', id), {}, {
      preserveScroll: true,
      onSuccess: () => {
        showApprovalModal.value = false;
        toast.add({ severity: 'success', summary: 'সফল', detail: 'আবেদন অনুমোদিত হয়েছে!', life: 3000 });
        setTimeout(() => window.location.reload(), 1500);
      },
      onError: (errors) => {
        toast.add({ severity: 'error', summary: 'ত্রুটি', detail: errors.error || 'কিছু সমস্যা হয়েছে!', life: 3000 });
      }
    });
  };

  // Return modal
  const isReturnModalOpen = ref(false);
  const returnReason = ref('');
  const returnImage = ref(null);
  const returnImagePreview = ref(null);

  const openReturnModal = () => { isReturnModalOpen.value = true; };

  const closeReturnModal = () => {
    isReturnModalOpen.value = false;
    returnReason.value = '';
    returnImage.value = null;
    returnImagePreview.value = null;
  };

  const handleReturnImageUpload = (event) => {
    const file = event.files?.[0];
    if (file) {
      returnImage.value = file;
      const reader = new FileReader();
      reader.onload = () => { returnImagePreview.value = reader.result; };
      reader.readAsDataURL(file);
    }
  };

  const removeReturnImage = () => {
    returnImage.value = null;
    returnImagePreview.value = null;
  };

  const submitReturnReason = () => {
    if (!returnReason.value.trim()) {
      toast.add({ severity: 'error', summary: 'ত্রুটি', detail: 'ফেরতের কারণ লিখুন!', life: 3000 });
      return;
    }
    const formData = new FormData();
    formData.append('message', returnReason.value.trim());
    if (returnImage.value) formData.append('image', returnImage.value);
    const id = props.application?.id || props.markazDetails.id;
    router.post(route('admin.markaz.return', id), formData, {
      onSuccess: () => {
        closeReturnModal();
        toast.add({ severity: 'success', summary: 'সফল', detail: 'আবেদন ফেরত পাঠানো হয়েছে!', life: 3000 });
        setTimeout(() => window.location.reload(), 1500);
      },
      onError: (errors) => {
        toast.add({ severity: 'error', summary: 'ত্রুটি', detail: errors.message || 'কিছু সমস্যা হয়েছে!', life: 3000 });
      }
    });
  };

  // Start processing function
  const startProcessing = () => {
    const id = props.application?.id || props.markazDetails.id;
    router.post(route('admin.markaz.processing', id), {}, {
      onSuccess: () => {
        toast.add({ severity: 'info', summary: 'প্রক্রিয়াকরণ শুরু', detail: 'আবেদন প্রক্রিয়াকরণ শুরু হয়েছে!', life: 3000 });
        setTimeout(() => window.location.reload(), 1500);
      },
      onError: (errors) => {
        toast.add({ severity: 'error', summary: 'ত্রুটি', detail: errors.error || 'প্রক্রিয়াকরণ শুরু করতে সমস্যা হয়েছে!', life: 3000 });
      }
    });
  };

  // Reject modal
  const isRejectModalOpen = ref(false);
  const rejectReason = ref('');
  const rejectImage = ref(null);
  const rejectImagePreview = ref(null);

  const openRejectModal = () => { isRejectModalOpen.value = true; };

  const closeRejectModal = () => {
    isRejectModalOpen.value = false;
    rejectReason.value = '';
    rejectImage.value = null;
    rejectImagePreview.value = null;
  };

  const handleRejectImageUpload = (event) => {
    const file = event.files?.[0];
    if (file) {
      rejectImage.value = file;
      const reader = new FileReader();
      reader.onload = () => { rejectImagePreview.value = reader.result; };
      reader.readAsDataURL(file);
    }
  };

  const removeRejectImage = () => {
    rejectImage.value = null;
    rejectImagePreview.value = null;
  };

  const submitRejectReason = () => {
    if (!rejectReason.value.trim()) {
      toast.add({ severity: 'error', summary: 'ত্রুটি', detail: 'রিজেক্টের কারণ লিখুন!', life: 3000 });
      return;
    }
    const formData = new FormData();
    formData.append('message', rejectReason.value.trim());
    if (rejectImage.value) formData.append('image', rejectImage.value);
    const id = props.application?.id || props.markazDetails.id;
    router.post(route('markaz.reject', id), formData, {
      onSuccess: () => {
        closeRejectModal();
        toast.add({ severity: 'success', summary: 'সফল', detail: 'আবেদন রিজেক্ট করা হয়েছে!', life: 3000 });
        setTimeout(() => window.location.reload(), 1500);
      },
      onError: (errors) => {
        toast.add({ severity: 'error', summary: 'ত্রুটি', detail: errors.message || 'কিছু সমস্যা হয়েছে!', life: 3000 });
      }
    });
  };

  return {
    // Approval
    showApprovalModal,
    openApprovalModal,
    confirmApproval,

    // Return
    isReturnModalOpen,
    returnReason,
    returnImage,
    returnImagePreview,
    openReturnModal,
    closeReturnModal,
    handleReturnImageUpload,
    removeReturnImage,
    submitReturnReason,

    // Processing
    startProcessing,

    // Reject
    isRejectModalOpen,
    rejectReason,
    rejectImage,
    rejectImagePreview,
    openRejectModal,
    closeRejectModal,
    handleRejectImageUpload,
    removeRejectImage,
    submitRejectReason
  };
};
