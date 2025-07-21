<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import StudentStatsCards from '@/Pages/students_registration/components/StudentStatsCards.vue'
import StudentSearchWizard from '@/Pages/students_registration/components/StudentSearchWizard.vue'
import StudentsDataTable from '@/Pages/students_registration/components/StudentsDataTable.vue'

const students = ref([]);
const loading = ref(true);
const error = ref(null);
const filters = ref({
  global: { value: null },
  id: { value: null },
  name_bn: { value: null },
  father_name_bn: { value: null },
  current_madrasha: { value: null },
  exam_name_Bn: { value: null },
  current_class: { value: null },
  Date_of_birth: { value: null },
  student_type: { value: null },
  payment_status: { value: null },
  status: { value: null },
  marhala: { value: null }
});
const studentStats = ref({
  totalStudents: 0,
  boardSubmittedStudents: 0,
  approvedStudents: 0,
  boardReturnedStudents: 0
});

const fetchData = async () => {
  try {
    loading.value = true;
    const [studentsRes, statsRes] = await Promise.all([
      axios.get('/api/students-registration'),
      axios.get('/api/dashboard/student-stats')
    ]);
    students.value = studentsRes.data.map(student => ({
      ...student,
      showMenu: false
    }));
    studentStats.value = statsRes.data;
  } catch (err) {
    error.value = 'ডাটা লোড করতে সমস্যা হয়েছে।';
  } finally {
    loading.value = false;
  }
};

onMounted(fetchData);

const refresh = () => fetchData()
</script>

<template>
  <AuthenticatedLayout>
    <div class="p-4 mt-5 mx-auto">
      <StudentStatsCards :studentStats="studentStats" />
      <StudentSearchWizard v-model:filters="filters" />
    <StudentsDataTable
  :students="students"
  :filters="filters"
  :loading="loading"
  @update:filters="val => filters = val"
  @refresh="refresh"
/>
    </div>
  </AuthenticatedLayout>
</template>
