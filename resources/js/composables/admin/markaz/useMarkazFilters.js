import { ref } from 'vue';

export const useMarkazFilters = () => {
  // PrimeVue FilterMatchMode
  const FilterMatchMode = {
    STARTS_WITH: 'startsWith',
    CONTAINS: 'contains',
    EQUALS: 'equals',
    DATE_IS: 'dateIs'
  };

  const tableFilters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    markaz_type: { value: null, matchMode: FilterMatchMode.EQUALS },
    status: { value: null, matchMode: FilterMatchMode.EQUALS }
  });

  const markazTypes = ref(['দরসিয়াত', 'হিফজ', 'কিরআত']);

  const clearFilter = () => {
    tableFilters.value = {
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      markaz_type: { value: null, matchMode: FilterMatchMode.EQUALS },
      status: { value: null, matchMode: FilterMatchMode.EQUALS }
    };
  };

  return {
    FilterMatchMode,
    tableFilters,
    markazTypes,
    clearFilter
  };
};
