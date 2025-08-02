import { ref } from 'vue';

export const useMarkazStatus = () => {
  const statusOptions = ref([
    'অপেক্ষমান',
    'অনুমোদিত',
    'স্থগিত',
    'বাতিল',
    'বোর্ড ফেরত',
    'বোর্ড দাখিল',
    'প্রক্রিয়াধীন',
    'প্রত্যাখ্যাত',
    'ফেরত'
  ]);

  // Status Map (Bangla label + severity)
  const statusMap = {
    pending: { label: 'অপেক্ষমান', severity: 'warning' },
    approved: { label: 'অনুমোদিত', severity: 'success' },
    suspended: { label: 'স্থগিত', severity: 'danger' },
    cancelled: { label: 'বাতিল', severity: 'danger' },
    board_returned: { label: 'বোর্ড ফেরত', severity: 'danger' },
    board_submitted: { label: 'বোর্ড দাখিল', severity: 'info' },
    in_processing: { label: 'প্রক্রিয়াধীন', severity: 'secondary' },
    rejected: { label: 'প্রত্যাখ্যাত', severity: 'danger' },
    returned: { label: 'ফেরত', severity: 'warning' },

    // Bangla statuses (for fallback)
    'বোর্ড দাখিল': { label: 'বোর্ড দাখিল', severity: 'info' },
    'বোর্ড ফেরত': { label: 'বোর্ড ফেরত', severity: 'danger' },
    'অনুমোদন': { label: 'অনুমোদিত', severity: 'success' },
    'অনুমোদিত': { label: 'অনুমোদিত', severity: 'success' },
    'প্রক্রিয়াধীন': { label: 'প্রক্রিয়াধীন', severity: 'secondary' },
    'অপেক্ষমান': { label: 'অপেক্ষমান', severity: 'warning' },
    'স্থগিত': { label: 'স্থগিত', severity: 'danger' },
    'বাতিল': { label: 'বাতিল', severity: 'danger' },
    'প্রত্যাখ্যাত': { label: 'প্রত্যাখ্যাত', severity: 'danger' },
    'ফেরত': { label: 'ফেরত', severity: 'warning' }
  };

  const getStatusSeverity = (status) => {
    return statusMap[status]?.severity || 'info';
  };

  const getStatusLabel = (status) => {
    return statusMap[status]?.label || status || 'অনিশ্চিত';
  };

  // Enhanced status functions for details view
  const getStatusClass = (status) => {
    const statusClasses = {
      'pending': 'bg-yellow-100 text-yellow-800 border border-yellow-200',
      'approved': 'bg-green-100 text-green-800 border border-green-200',
      'suspended': 'bg-orange-100 text-orange-800 border border-orange-200',
      'cancelled': 'bg-red-100 text-red-800 border border-red-200',
      'board_returned': 'bg-pink-100 text-pink-800 border border-pink-200',
      'board_submitted': 'bg-blue-100 text-blue-800 border border-blue-200',
      'in_processing': 'bg-purple-100 text-purple-800 border border-purple-200',
      'rejected': 'bg-red-100 text-red-800 border border-red-200',
      'returned': 'bg-orange-100 text-orange-800 border border-orange-200'
    };
    return statusClasses[status] || 'bg-gray-100 text-gray-800 border border-gray-200';
  };

  // Activity status mapping functions for status logs
  const getActivityStatusLabel = (status) => {
    const statusMap = {
      // English status values
      'pending': 'অপেক্ষমান',
      'approved': 'অনুমোদিত',
      'suspended': 'স্থগিত',
      'cancelled': 'বাতিল',
      'board_returned': 'বোর্ড ফেরত',
      'board_submitted': 'বোর্ড দাখিল',
      'in_processing': 'প্রক্রিয়াধীন',
      'rejected': 'প্রত্যাখ্যাত',
      'returned': 'ফেরত',

      // Bangla status values (already converted)
      'বোর্ড দাখিল': 'বোর্ড দাখিল',
      'বোর্ড ফেরত': 'বোর্ড ফেরত',
      'অনুমোদন': 'অনুমোদিত',
      'অনুমোদিত': 'অনুমোদিত',
      'প্রক্রিয়াধীন': 'প্রক্রিয়াধীন',
      'পেন্ডিং': 'অপেক্ষমান',
      'অপেক্ষমান': 'অপেক্ষমান',
      'স্থগিত': 'স্থগিত',
      'বাতিল': 'বাতিল',
      'প্রত্যাখ্যাত': 'প্রত্যাখ্যাত',
      'ফেরত': 'ফেরত'
    };
    return statusMap[status] || status || 'অজানা';
  };

  const getActivityStatusClass = (status) => {
    const statusClasses = {
      // English status values
      'pending': 'bg-yellow-100 text-yellow-800 border border-yellow-200',
      'approved': 'bg-green-100 text-green-800 border border-green-200',
      'suspended': 'bg-orange-100 text-orange-800 border border-orange-200',
      'cancelled': 'bg-red-100 text-red-800 border border-red-200',
      'board_returned': 'bg-pink-100 text-pink-800 border border-pink-200',
      'board_submitted': 'bg-blue-100 text-blue-800 border border-blue-200',
      'in_processing': 'bg-purple-100 text-purple-800 border border-purple-200',
      'rejected': 'bg-red-100 text-red-800 border border-red-200',
      'returned': 'bg-orange-100 text-orange-800 border border-orange-200',

      // Bangla status values (already converted)
      'বোর্ড দাখিল': 'bg-blue-100 text-blue-800 border border-blue-200',
      'বোর্ড ফেরত': 'bg-pink-100 text-pink-800 border border-pink-200',
      'অনুমোদন': 'bg-green-100 text-green-800 border border-green-200',
      'অনুমোদিত': 'bg-green-100 text-green-800 border border-green-200',
      'প্রক্রিয়াধীন': 'bg-purple-100 text-purple-800 border border-purple-200',
      'পেন্ডিং': 'bg-yellow-100 text-yellow-800 border border-yellow-200',
      'অপেক্ষমান': 'bg-yellow-100 text-yellow-800 border border-yellow-200',
      'স্থগিত': 'bg-orange-100 text-orange-800 border border-orange-200',
      'বাতিল': 'bg-red-100 text-red-800 border border-red-200',
      'প্রত্যাখ্যাত': 'bg-red-100 text-red-800 border border-red-200',
      'ফেরত': 'bg-orange-100 text-orange-800 border border-orange-200'
    };
    return statusClasses[status] || 'bg-gray-100 text-gray-800 border border-gray-200';
  };

  return {
    statusOptions,
    statusMap,
    getStatusSeverity,
    getStatusLabel,
    getStatusClass,
    getActivityStatusLabel,
    getActivityStatusClass
  };
};
