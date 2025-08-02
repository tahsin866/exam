import { ref } from "vue";
import moment from 'moment/moment';

export function useMarkazDetails() {
    const formatDate = (timestamp) => {
        return moment(timestamp).format('DD/MM/YYYY');
    };

    const getStatusSeverity = (status) => {
      switch (status) {
        case 'অনুমোদন':
          return 'success';
        case 'বোর্ড দাখিল':
          return 'warning';
        case 'বোর্ড ফেরত':
          return 'danger';
        case 'বাতিল':
          return 'danger';
        default:
          return 'info';
      }
    };

    // Get user icon
    const getUserIcon = (activity) => {
      return activity.admin_name ? 'user-edit' : 'user';
    };

    return {
        formatDate,
        getStatusSeverity,
        getUserIcon
    };
}
