import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';

export const useMarkazApplications = () => {
  const toast = useToast();
  const applications = ref([]);
  const loading = ref(true);
  let pollTimer = null;

  const fetchApplications = async () => {
    loading.value = true;
    try {
      const response = await axios.get('/api/markaz-agreements');
      applications.value = response.data;
    } catch (error) {
      console.error("Error fetching data:", error);
      toast.add({
        severity: 'error',
        summary: 'ত্রুটি',
        detail: 'আবেদন লোড করতে সমস্যা হয়েছে',
        life: 3000
      });
    } finally {
      loading.value = false;
    }
  };

  const setupRealtime = () => {
    // Real-time updates are disabled; you can enable polling if you want.
    // pollTimer = setInterval(fetchApplications, 30000); // 30 seconds
  };

  const initializeData = () => {
    fetchApplications();
    setupRealtime();
  };

  const cleanup = () => {
    if (pollTimer) {
      clearInterval(pollTimer);
      pollTimer = null;
    }
  };

  const deleteApplication = async (application) => {
    try {
      await axios.delete(`/api/markaz-agreements/${application.id}`);
      applications.value = applications.value.filter(app => app.id !== application.id);
      toast.add({
        severity: 'success',
        summary: 'সফল',
        detail: 'আবেদন সফলভাবে মুছে ফেলা হয়েছে',
        life: 3000
      });
    } catch (error) {
      toast.add({
        severity: 'error',
        summary: 'ত্রুটি',
        detail: 'আবেদন মুছতে সমস্যা হয়েছে',
        life: 3000
      });
    }
  };

  const updateApplicationStatus = (applicationId, newStatus) => {
    const idx = applications.value.findIndex(app => app.id === applicationId);
    if (idx !== -1) {
      applications.value[idx].status = newStatus;
    }
  };

  return {
    applications,
    loading,
    fetchApplications,
    initializeData,
    cleanup,
    deleteApplication,
    updateApplicationStatus
  };
};
