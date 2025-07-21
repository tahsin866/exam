<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { Link, useForm } from '@inertiajs/vue3';

import EditStudentPersonalInfo from '@/Pages/students_registration/components/EditStudentPersonalInfo.vue';
import EditStudentAddressInfo from '@/Pages/students_registration/components/EditStudentAddressInfo.vue';
import EditStudentAttachments from '@/Pages/students_registration/components/EditStudentAttachments.vue';

const props = defineProps({
  student: Object,
  marhalas: Array,
  errors: Object
});

const form = useForm({
  name_bn: props.student.name_bn,
  name_en: props.student.name_en,
  name_ar: props.student.name_ar,
  father_name_bn: props.student.father_name_bn,
  father_name_en: props.student.father_name_en,
  father_name_ar: props.student.father_name_ar,
  mother_name_bn: props.student.mother_name_bn,
  mother_name_en: props.student.mother_name_en,
  mother_name_ar: props.student.mother_name_ar,
  board_name: props.student.board_name,
  present_division_name: props.student.present_division_name,
  present_district_name: props.student.present_district_name,
  present_thana_name: props.student.present_thana_name,
  presernt_DID: props.student.presernt_DID,
  present_desId: props.student.present_desId,
  present_TID: props.student.present_TID,
  parmanent_division_name: props.student.parmanent_division_name,
  parmanent_district_name: props.student.parmanent_district_name,
  parmanent_thana_name: props.student.parmanent_thana_name,
  parmanent_DID: props.student.parmanent_DID,
  parmanent_desId: props.student.parmanent_desId,
  parmanent_TID: props.student.parmanent_TID,
  BRN_no: props.student.BRN_no,
  NID_no: props.student.NID_no,
  mobile_no: props.student.mobile_no,
  Date_of_birth: props.student.Date_of_birth,
  student_type: props.student.student_type || 'নিয়মিত',
  marhala_id: props.student.marhala_id,
  student_image: null,
  NID_attach: null,
  _method: 'PUT',
});

const studentPhotoFile = ref(null);
const birthCertificateFile = ref(null);
const marksheetFile = ref(null);

const studentPhotoPreview = ref(null);
const birthCertificatePreview = ref(null);
const marksheetPreview = ref(null);

const updateStudent = () => {
  if (studentPhotoFile.value) form.student_image = studentPhotoFile.value;
  if (birthCertificateFile.value) form.NID_attach = birthCertificateFile.value;
  form.processing = true;
  form.submit('post', route('students_registration.student_registration_update', props.student.id), {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => { window.location.reload(); },
  });
};

const handleFileUpload = (event, fileType) => {
  const file = event.target.files[0];
  if (!file) return;
  if (fileType === 'studentPhoto') {
    studentPhotoFile.value = file;
    createFilePreview(file, 'studentPhoto');
  } else if (fileType === 'birthCertificate') {
    birthCertificateFile.value = file;
    createFilePreview(file, 'birthCertificate');
  } else if (fileType === 'marksheet') {
    marksheetFile.value = file;
    createFilePreview(file, 'marksheet');
  }
};

const createFilePreview = (file, fileType) => {
  const previewUrl = URL.createObjectURL(file);
  if (fileType === 'studentPhoto') studentPhotoPreview.value = previewUrl;
  else if (fileType === 'birthCertificate') birthCertificatePreview.value = previewUrl;
  else if (fileType === 'marksheet') marksheetPreview.value = previewUrl;
};

const removeFile = (fileType) => {
  if (fileType === 'studentPhoto') {
    if (studentPhotoPreview.value) URL.revokeObjectURL(studentPhotoPreview.value);
    studentPhotoFile.value = null;
    studentPhotoPreview.value = null;
  } else if (fileType === 'birthCertificate') {
    if (birthCertificatePreview.value) URL.revokeObjectURL(birthCertificatePreview.value);
    birthCertificateFile.value = null;
    birthCertificatePreview.value = null;
  } else if (fileType === 'marksheet') {
    if (marksheetPreview.value) URL.revokeObjectURL(marksheetPreview.value);
    marksheetFile.value = null;
    marksheetPreview.value = null;
  }
};

const marhalaName = ref('');
const divisions = ref([]);
const presentDistricts = ref([]);
const presentThanas = ref([]);
const districts = ref([]);
const thanas = ref([]);

const presentFilters = ref({
  division: '',
  district: '',
  Thana: ''
});
const permanentFilters = ref({
  division: '',
  district: '',
  Thana: ''
});

const initializeFilters = () => {
  if (props.student && props.student.presernt_DID) presentFilters.value.division = props.student.presernt_DID;
  if (props.student && props.student.present_desId) presentFilters.value.district = props.student.present_desId;
  if (props.student && props.student.present_TID) presentFilters.value.Thana = props.student.present_TID;
  if (props.student && props.student.parmanent_DID) permanentFilters.value.division = props.student.parmanent_DID;
  if (props.student && props.student.parmanent_desId) permanentFilters.value.district = props.student.parmanent_desId;
  if (props.student && props.student.parmanent_TID) permanentFilters.value.Thana = props.student.parmanent_TID;
};

watch(() => props.student, (newValue) => { if (newValue) initializeFilters(); }, { immediate: true });

watch(presentFilters, async (newValues) => {
  if (newValues.division) {
    const selectedDivision = divisions.value.find(d => d.id == newValues.division);
    if (selectedDivision) { form.present_division_name = selectedDivision.Division; form.presernt_DID = selectedDivision.id; }
  }
  if (newValues.district) {
    const selectedDistrict = presentDistricts.value.find(d => d.DesID == newValues.district);
    if (selectedDistrict) { form.present_district_name = selectedDistrict.District; form.present_desId = selectedDistrict.DesID; }
  }
  if (newValues.Thana) {
    const selectedThana = presentThanas.value.find(t => t.Thana_ID == newValues.Thana);
    if (selectedThana) { form.present_thana_name = selectedThana.Thana; form.present_TID = selectedThana.Thana_ID; }
  }
}, { deep: true });

watch(permanentFilters, async (newValues) => {
  if (newValues.division) {
    const selectedDivision = divisions.value.find(d => d.id == newValues.division);
    if (selectedDivision) { form.parmanent_division_name = selectedDivision.Division; form.parmanent_DID = selectedDivision.id; }
  }
  if (newValues.district) {
    const selectedDistrict = districts.value.find(d => d.DesID == newValues.district);
    if (selectedDistrict) { form.parmanent_district_name = selectedDistrict.District; form.parmanent_desId = selectedDistrict.DesID; }
  }
  if (newValues.Thana) {
    const selectedThana = thanas.value.find(t => t.Thana_ID == newValues.Thana);
    if (selectedThana) { form.parmanent_thana_name = selectedThana.Thana; form.parmanent_TID = selectedThana.Thana_ID; }
  }
}, { deep: true });

onMounted(async () => {
  await loadDivisions();
  initializeFilters();
  if (presentFilters.value.division) {
    await presentHandleDivisionChange();
    if (presentFilters.value.district) await presentHandleDistrictChange();
  }
  if (permanentFilters.value.division) {
    await handleDivisionChange();
    if (permanentFilters.value.district) await handleDistrictChange();
  }
});

const loadDivisions = async () => {
  try {
    const response = await axios.get('/api/divisions');
    divisions.value = response.data;
    return true;
  } catch (error) {
    console.error('Error loading divisions:', error);
    return false;
  }
};

const presentHandleDivisionChange = async () => {
  if (!presentFilters.value.division) {
    presentFilters.value.district = '';
    presentFilters.value.Thana = '';
    presentDistricts.value = [];
    presentThanas.value = [];
    return;
  }
  try {
    const response = await axios.get(`/api/districts/${presentFilters.value.division}`);
    presentDistricts.value = response.data;
    if (props.student && props.student.present_desId) presentFilters.value.district = props.student.present_desId;
  } catch (error) { console.error('Error loading districts:', error); }
};

const presentHandleDistrictChange = async () => {
  if (!presentFilters.value.district) {
    presentFilters.value.Thana = '';
    presentThanas.value = [];
    return;
  }
  try {
    const response = await axios.get(`/api/thanas/${presentFilters.value.district}`);
    presentThanas.value = response.data;
    if (props.student && props.student.present_TID) presentFilters.value.Thana = props.student.present_TID;
  } catch (error) { console.error('Error loading thanas:', error); }
};

const handleDivisionChange = async () => {
  if (!permanentFilters.value.division) {
    permanentFilters.value.district = '';
    permanentFilters.value.Thana = '';
    districts.value = [];
    thanas.value = [];
    return;
  }
  try {
    const response = await axios.get(`/api/districts/${permanentFilters.value.division}`);
    districts.value = response.data;
    if (props.student && props.student.parmanent_desId) permanentFilters.value.district = props.student.parmanent_desId;
  } catch (error) { console.error('Error loading districts:', error); }
};

const handleDistrictChange = async () => {
  if (!permanentFilters.value.district) {
    permanentFilters.value.Thana = '';
    thanas.value = [];
    return;
  }
  try {
    const response = await axios.get(`/api/thanas/${permanentFilters.value.district}`);
    thanas.value = response.data;
    if (props.student && props.student.parmanent_TID) permanentFilters.value.Thana = props.student.parmanent_TID;
  } catch (error) { console.error('Error loading thanas:', error); }
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="mb-5 mt-5 mx-5 space-y-6 text-xl">
      <EditStudentPersonalInfo
        :form="form"
        :student="props.student"
        :marhalaName="marhalaName"
      />

      <EditStudentAddressInfo
        :divisions="divisions"
        :presentFilters="presentFilters"
        :permanentFilters="permanentFilters"
        :presentDistricts="presentDistricts"
        :presentThanas="presentThanas"
        :districts="districts"
        :thanas="thanas"
        @present-division-change="presentHandleDivisionChange"
        @present-district-change="presentHandleDistrictChange"
        @permanent-division-change="handleDivisionChange"
        @permanent-district-change="handleDistrictChange"
      />


          <EditStudentAttachments
        :student="props.student"
        :studentPhotoFile="studentPhotoFile"
        :birthCertificateFile="birthCertificateFile"
        :marksheetFile="marksheetFile"
        :studentPhotoPreview="studentPhotoPreview"
        :birthCertificatePreview="birthCertificatePreview"
        :marksheetPreview="marksheetPreview"
        @file-upload="handleFileUpload"
        @remove-file="removeFile"
        @update-student="updateStudent"
      />
    </div>
  </AuthenticatedLayout>
</template>
