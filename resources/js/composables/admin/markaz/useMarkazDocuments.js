import { computed } from 'vue';

export const useMarkazDocuments = (markazDetails) => {
  // মূল মাদরাসার ডকুমেন্ট
  const documentList = computed(() => {
    const d = markazDetails.value;
    return [
      { key: 'muhtamim_consent', label: 'মহতামিমের সম্মতি', file: d.muhtamim_consent, type: 'pdf' },
      { key: 'committee_resolution', label: 'কমিটির রেজুলেশন', file: d.committee_resolution, type: 'pdf' },
      { key: 'president_consent', label: 'সভাপতির সম্মতি', file: d.president_consent, type: 'pdf' },
      { key: 'noc_file', label: 'NOC ফাইল', file: d.noc_file, type: 'pdf' },
      { key: 'resolution_file', label: 'রেজুলেশন ফাইল', file: d.resolution_file, type: 'pdf' }
    ].filter(doc => !!doc.file);
  });

  // সংযুক্ত মাদরাসার ডকুমেন্টের নাম ও key map
  const associatedDocKeys = [
    { key: 'consent_file', label: 'সম্মতিপত্র' },
    { key: 'noc_file', label: 'অনাপত্তিপত্র' },
    { key: 'resolution_file', label: 'রেজুলেশন' }
  ];

  const viewDocument = (documentPath) => {
    if (documentPath) {
      window.open(`/storage/${documentPath}`, '_blank');
    }
  };

  const downloadDocument = (documentPath) => {
    if (documentPath) {
      const link = document.createElement('a');
      link.href = `/storage/${documentPath}`;
      link.download = documentPath.split('/').pop();
      link.click();
    }
  };

  return {
    documentList,
    associatedDocKeys,
    viewDocument,
    downloadDocument
  };
};
