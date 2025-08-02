import { ref, onMounted, computed, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import axios from "axios";

export function useMarkazApplyEdit(props) {
  // Flash messages
  const page = usePage();
  const flashMessages = computed(() => page.props.flash || {});

  // Auto hide flash message after 5 seconds
  const autoHideFlash = () => {
    if (flashMessages.value.success || flashMessages.value.error) {
      setTimeout(() => {
        page.props.flash = {};
      }, 5000);
    }
  };

  // Ensure only one type of flash message is shown at a time
  const setFlash = ({ success = null, error = null }) => {
    if (success) {
      page.props.flash = { success };
    } else if (error) {
      page.props.flash = { error };
    } else {
      page.props.flash = {};
    }
  };

  onMounted(() => {
    autoHideFlash();
  });

  // Madrasha search list
  const madrashas = ref([]);
  (async () => {
    try {
      const { data } = await axios.get(route('madrashas.list'));
      madrashas.value = Array.isArray(data) ? data : (data.madrashas ?? []);
    } catch (e) {
      madrashas.value = [];
    }
  })();

  // Form
  const form = useForm({
    markaz_type: props.markazData.markaz_type || "",
    fazilat: props.markazData.fazilat ?? 0,
    sanabiya_ulya: props.markazData.sanabiya_ulya ?? 0,
    sanabiya: props.markazData.sanabiya ?? 0,
    mutawassita: props.markazData.mutawassita ?? 0,
    ibtedaiyyah: props.markazData.ibtedaiyyah ?? 0,
    hifzul_quran: props.markazData.hifzul_quran ?? 0,
    qirat: props.markazData.qirat ?? 0,
    requirements: props.markazData.requirements || "",
    noc_file: props.markazData.noc_file || null,
    resolution_file: props.markazData.resolution_file || null,
    muhtamim_consent: props.markazData.muhtamim_consent || null,
    president_consent: props.markazData.president_consent || null,
    committee_resolution: props.markazData.committee_resolution || null,
    associated_madrasas: []
  });

  // Rows
  const rows = ref(
    (props.associatedMadrasas ?? []).map(madrasa => ({
      id: madrasa.id,
      fazilat: madrasa.fazilat ?? 0,
      sanabiya_ulya: madrasa.sanabiya_ulya ?? 0,
      sanabiya: madrasa.sanabiya ?? 0,
      mutawassita: madrasa.mutawassita ?? 0,
      ibtedaiyyah: madrasa.ibtedaiyyah ?? 0,
      hifzul_quran: madrasa.hifzul_quran ?? 0,
      qirat: madrasa.qirat ?? 0,
      madrasa_Name: madrasa.madrasa_Name || "",
      searchQuery: madrasa.madrasa_Name || "",
      selectedMadrasha: null,
      files: {
        noc: null,
        nocPreview: madrasa.noc_file_url || null,
        resolution: null,
        resolutionPreview: madrasa.resolution_file_url || null
      }
    }))
  );

  // Watch markaz_type changes to reset irrelevant fields
  watch(() => form.markaz_type, (newType, oldType) => {
    if (newType !== oldType && oldType) {
      if (newType === 'দরসিয়াত') {
        if (!props.markazData.hifzul_quran) form.hifzul_quran = '';
        if (!props.markazData.qirat) form.qirat = '';
      } else if (newType === 'তাহফিজুল কোরআন') {
        if (!props.markazData.fazilat) form.fazilat = '';
        if (!props.markazData.sanabiya_ulya) form.sanabiya_ulya = '';
        if (!props.markazData.sanabiya) form.sanabiya = '';
        if (!props.markazData.mutawassita) form.mutawassita = '';
        if (!props.markazData.ibtedaiyyah) form.ibtedaiyyah = '';
        if (!props.markazData.qirat) form.qirat = '';
      } else if (newType === 'কিরাআত') {
        if (!props.markazData.fazilat) form.fazilat = '';
        if (!props.markazData.sanabiya_ulya) form.sanabiya_ulya = '';
        if (!props.markazData.sanabiya) form.sanabiya = '';
        if (!props.markazData.mutawassita) form.mutawassita = '';
        if (!props.markazData.ibtedaiyyah) form.ibtedaiyyah = '';
        if (!props.markazData.hifzul_quran) form.hifzul_quran = '';
      }
    }
  });

  // Submit
  const submitForm = () => {
    form.associated_madrasas = rows.value.map(row => ({
      id: row.id || null,
      madrasa_Name: row.madrasa_Name,
      fazilat: row.fazilat,
      sanabiya_ulya: row.sanabiya_ulya,
      sanabiya: row.sanabiya,
      mutawassita: row.mutawassita,
      ibtedaiyyah: row.ibtedaiyyah,
      hifzul_quran: row.hifzul_quran,
      qirat: row.qirat,
      noc_file: row.files.noc,
      resolution_file: row.files.resolution
    }));
    form.post(route("markaz-agreements.update", props.markazData.id), {
      preserveScroll: true,
      onSuccess: () => {
        setFlash({ success: 'মারকায চুক্তি সফলভাবে আপডেট হয়েছে' });
        autoHideFlash();
      },
      onError: () => {
        setFlash({ error: 'আপডেট করতে ব্যর্থ হয়েছে' });
        autoHideFlash();
      }
    });
  };

  return {
    page,
    flashMessages,
    autoHideFlash,
    madrashas,
    form,
    rows,
    submitForm
  };
}
