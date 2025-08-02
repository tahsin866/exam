import { computed } from 'vue';

export const useMarkazStudentStats = (markazDetails) => {
  const totalStudents = computed(() => {
    const d = markazDetails.value;
    return (d.fazilat || 0) + (d.sanabiya_ulya || 0) + (d.sanabiya || 0) +
           (d.mutawassita || 0) + (d.ibtedaiyyah || 0) + (d.hifzul_quran || 0);
  });

  const associatedTotalStudents = computed(() => {
    if (!markazDetails.value.associated_madrasas) return 0;
    return markazDetails.value.associated_madrasas.reduce((total, m) => {
      return total + (m.fazilat || 0) + (m.sanabiya_ulya || 0) + (m.sanabiya || 0) +
             (m.mutawassita || 0) + (m.ibtedaiyyah || 0) + (m.hifzul_quran || 0);
    }, 0);
  });

  const studentCategories = [
    { key: 'fazilat', label: 'ফাযীলাত', color: 'blue-600' },
    { key: 'sanabiya_ulya', label: 'সানাবিয়া উলইয়া', color: 'green-600' },
    { key: 'sanabiya', label: 'সানাবিয়া', color: 'purple-600' },
    { key: 'mutawassita', label: 'মুতাওয়াসসিতা', color: 'orange-600' },
    { key: 'ibtedaiyyah', label: 'ইবতেদাইয়্যাহু', color: 'red-600' },
    { key: 'hifzul_quran', label: 'তাহফিজুল কোরআন', color: 'indigo-600' }
  ];

  const getStudentPercentage = (count) => {
    const total = Math.max(totalStudents.value, 1);
    return (count / total) * 100;
  };

  return {
    totalStudents,
    associatedTotalStudents,
    studentCategories,
    getStudentPercentage
  };
};
