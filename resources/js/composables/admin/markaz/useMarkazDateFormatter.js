import moment from 'moment';

export const useMarkazDateFormatter = () => {
  const formatDate = (date) => {
    if (!date) return 'N/A';
    return moment(date).format('DD/MM/YYYY');
  };

  const formatDateTime = (date) => {
    if (!date) return 'N/A';
    return moment(date).format('DD/MM/YYYY HH:mm');
  };

  const formatDateTimeWithTime = (date) => {
    if (!date) return 'N/A';
    return moment(date).format('DD/MM/YYYY, h:mm A');
  };

  return {
    formatDate,
    formatDateTime,
    formatDateTimeWithTime
  };
};
