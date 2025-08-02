import { router } from '@inertiajs/vue3';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

export const useMarkazActions = (updateApplicationStatus, deleteApplication) => {
  const confirm = useConfirm();
  const toast = useToast();

  const viewApplication = (id) => {
    router.get(route('admin.markaz.view', id));
  };

  const confirmDelete = (application) => {
    confirm.require({
      message: 'আপনি কি এই আবেদনটি মুছে ফেলতে চান?',
      header: 'নিশ্চিতকরণ',
      icon: 'pi pi-exclamation-triangle',
      acceptLabel: 'হ্যাঁ',
      rejectLabel: 'না',
      accept: () => {
        deleteApplication(application);
      }
    });
  };

  const startProcessing = async (application) => {
    try {
      await router.post(route('admin.markaz.processing', application.id), {}, {
        onSuccess: () => {
          updateApplicationStatus(application.id, 'in_processing');
          toast.add({
            severity: 'info',
            summary: 'প্রক্রিয়াকরণ শুরু',
            detail: 'আবেদন প্রক্রিয়াকরণ শুরু হয়েছে',
            life: 3000
          });
        },
        onError: (errors) => {
          toast.add({
            severity: 'error',
            summary: 'ত্রুটি',
            detail: errors?.error || 'প্রক্রিয়াকরণ শুরু করতে সমস্যা হয়েছে',
            life: 3000
          });
        }
      });
    } catch (error) {
      toast.add({
        severity: 'error',
        summary: 'ত্রুটি',
        detail: 'প্রক্রিয়াকরণ শুরু করতে সমস্যা হয়েছে',
        life: 3000
      });
    }
  };

  const returnApplication = async (application) => {
    confirm.require({
      message: 'আপনি কি এই আবেদনটি ফেরত পাঠাতে চান?',
      header: 'নিশ্চিতকরণ',
      icon: 'pi pi-exclamation-triangle',
      acceptLabel: 'হ্যাঁ',
      rejectLabel: 'না',
      accept: async () => {
        try {
          await router.post(route('admin.markaz.return', application.id), {
            message: 'আবেদনটি ফেরত পাঠানো হয়েছে'
          }, {
            onSuccess: () => {
              updateApplicationStatus(application.id, 'board_returned');
              toast.add({
                severity: 'warn',
                summary: 'ফেরত পাঠানো হয়েছে',
                detail: 'আবেদন সফলভাবে ফেরত পাঠানো হয়েছে',
                life: 3000
              });
            },
            onError: (errors) => {
              toast.add({
                severity: 'error',
                summary: 'ত্রুটি',
                detail: errors?.error || 'আবেদন ফেরত পাঠাতে সমস্যা হয়েছে',
                life: 3000
              });
            }
          });
        } catch (error) {
          toast.add({
            severity: 'error',
            summary: 'ত্রুটি',
            detail: 'আবেদন ফেরত পাঠাতে সমস্যা হয়েছে',
            life: 3000
          });
        }
      }
    });
  };

  const approveApplication = async (application) => {
    confirm.require({
      message: 'আপনি কি এই আবেদনটি অনুমোদন করতে চান?',
      header: 'নিশ্চিতকরণ',
      icon: 'pi pi-check-circle',
      acceptLabel: 'হ্যাঁ',
      rejectLabel: 'না',
      accept: async () => {
        try {
          await router.post(route('admin.markaz.approve', application.id), {}, {
            onSuccess: () => {
              updateApplicationStatus(application.id, 'approved');
              toast.add({
                severity: 'success',
                summary: 'অনুমোদিত',
                detail: 'আবেদন সফলভাবে অনুমোদন করা হয়েছে',
                life: 3000
              });
            },
            onError: (errors) => {
              toast.add({
                severity: 'error',
                summary: 'ত্রুটি',
                detail: errors?.error || 'আবেদন অনুমোদন করতে সমস্যা হয়েছে',
                life: 3000
              });
            }
          });
        } catch (error) {
          toast.add({
            severity: 'error',
            summary: 'ত্রুটি',
            detail: 'আবেদন অনুমোদন করতে সমস্যা হয়েছে',
            life: 3000
          });
        }
      }
    });
  };

  const getActionItems = (data) => [
    {
      label: 'বিস্তারিত দেখুন',
      icon: 'pi pi-eye',
      command: () => viewApplication(data.id)
    },
    {
      label: 'অনুমোদন করুন',
      icon: 'pi pi-check',
      command: () => approveApplication(data)
    },
    {
      label: 'প্রক্রিয়াকরণ শুরু',
      icon: 'pi pi-cog',
      command: () => startProcessing(data)
    },
    {
      label: 'ফেরত পাঠান',
      icon: 'pi pi-undo',
      command: () => returnApplication(data)
    },
    { separator: true },
    {
      label: 'স্থগিত করুন',
      icon: 'pi pi-ban',
      command: () => {
        toast.add({ severity: 'warn', summary: 'স্থগিত', detail: 'আবেদন স্থগিত করা হয়েছে', life: 3000 });
      }
    },
    {
      label: 'বাতিল করুন',
      icon: 'pi pi-times-circle',
      command: () => {
        toast.add({ severity: 'warn', summary: 'বাতিল', detail: 'আবেদন বাতিল করা হয়েছে', life: 3000 });
      }
    },
    { separator: true },
    {
      label: 'মুছে ফেলুন',
      icon: 'pi pi-trash',
      command: () => confirmDelete(data)
    }
  ];

  return {
    viewApplication,
    confirmDelete,
    startProcessing,
    returnApplication,
    approveApplication,
    getActionItems
  };
};
