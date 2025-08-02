import { ref } from 'vue';

export const useMarkazTabs = () => {
  const activeTab = ref('basic');

  const setActiveTab = (tab) => {
    activeTab.value = tab;
  };

  const tabs = [
    { key: 'basic', label: 'মূল তথ্য', icon: 'pi pi-info-circle' },
    { key: 'students', label: 'ছাত্র তথ্য', icon: 'pi pi-users' },
    { key: 'documents', label: 'ডকুমেন্ট', icon: 'pi pi-file' },
    { key: 'associated', label: 'সংযুক্ত মাদরাসা', icon: 'pi pi-sitemap' },
    { key: 'timeline', label: 'আবেদন অবস্থা পর্যবেক্ষণ', icon: 'pi pi-clock' }
  ];

  return {
    activeTab,
    setActiveTab,
    tabs
  };
};
