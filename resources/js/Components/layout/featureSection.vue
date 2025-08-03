<template>
  <section
    class="py-20 relative overflow-hidden"
    style="font-family: 'Merriweather', 'SolaimanLipi', sans-serif;"
  >
    <!-- Background with Islamic Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-teal-50/80 to-emerald-50">
      <div class="absolute inset-0 opacity-10">
        <svg class="h-full w-full" preserveAspectRatio="none">
          <pattern id="islamic-pattern" x="0" y="0" width="56" height="56" patternUnits="userSpaceOnUse">
            <path d="M28 0l28 28-28 28L0 28z" stroke="currentColor" stroke-width="0.5" class="text-emerald-900" fill="none"/>
            <circle cx="28" cy="28" r="10" stroke="currentColor" stroke-width="0.5" class="text-emerald-900" fill="none"/>
            <circle cx="28" cy="28" r="6" stroke="currentColor" stroke-width="0.5" class="text-emerald-900" fill="none"/>
          </pattern>
          <rect width="100%" height="100%" fill="url(#islamic-pattern)"/>
        </svg>
      </div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-300/20 rounded-full filter blur-3xl"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-teal-300/20 rounded-full filter blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10">
      <!-- Section Title with Decoration -->
      <div class="flex flex-col items-center mb-12 text-center" v-motion
           :initial="{ opacity: 0, y: 20 }"
           :enter="{ opacity: 1, y: 0, transition: { delay: 200 } }">
        <div class="flex items-center mb-3">
          <div class="h-px w-12 bg-emerald-500 mr-4"></div>
          <h2 class="text-emerald-600 font-medium uppercase tracking-wider text-lg">আমাদের পরিসংখ্যান</h2>
          <div class="h-px w-12 bg-emerald-500 ml-4"></div>
        </div>
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">বেফাকুল মাদারিসিল আরাবিয়া বাংলাদেশ</h2>
        <p class="text-gray-600 max-w-2xl">বাংলাদেশের সবচেয়ে বড় কওমী মাদরাসা শিক্ষাবোর্ড এর সর্বশেষ পরিসংখ্যান এবং সেবাসমূহ</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Statistics Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 relative overflow-hidden group"
             v-motion
             :initial="{ opacity: 0, x: -50 }"
             :enter="{ opacity: 1, x: 0, transition: { delay: 400 } }">
          <!-- Card Pattern Background -->
          <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-white opacity-80"></div>
          <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 via-teal-500 to-emerald-400"></div>

          <div class="relative">
            <div class="flex items-center mb-8">
              <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-emerald-200/50 transform group-hover:rotate-6 transition-transform duration-500">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-800">পরিসংখ্যান</h3>
                <p class="text-gray-500 text-sm">সর্বশেষ আপডেট: {{ formattedDate }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div v-for="(stat, index) in stats" :key="stat.title"
                class="bg-gradient-to-br from-emerald-50/80 to-teal-50/80 p-6 rounded-xl border border-emerald-100 shadow-sm hover:shadow-md transform hover:translate-y-[-5px] transition-all duration-300 group/stat"
                v-motion
                :initial="{ opacity: 0, y: 20 }"
                :enter="{ opacity: 1, y: 0, transition: { delay: 500 + (index * 100) } }">
                <div class="flex items-center justify-between mb-4">
                  <h4 class="text-lg font-semibold text-gray-700">{{ stat.title }}</h4>
                  <div class="w-10 h-10 rounded-full flex items-center justify-center bg-emerald-100/50 group-hover/stat:bg-emerald-200 transition-colors duration-300">
                    <component :is="stat.icon" class="w-5 h-5 text-emerald-600"/>
                  </div>
                </div>
                <div class="flex items-end">
                  <p class="text-3xl font-bold text-emerald-600 transition-all duration-300 group-hover/stat:text-emerald-700" ref="countRefs">
                    <animated-number :value="stat.value" />
                  </p>
                  <span class="text-sm text-gray-500 ml-1 mb-1">{{ stat.unit }}</span>
                </div>
                <div class="mt-1">
                  <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-400 to-teal-500 h-full rounded-full"
                        :class="stat.class"
                        :style="{width: `${stat.percentage}%`, transition: 'all 1.5s ease-out'}"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Madrasa Signup Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 flex flex-col relative overflow-hidden group"
             v-motion
             :initial="{ opacity: 0, x: 50 }"
             :enter="{ opacity: 1, x: 0, transition: { delay: 400 } }">
          <!-- Card Pattern Background -->
          <div class="absolute inset-0 bg-gradient-to-br from-teal-50/40 to-white/80 opacity-80"></div>
          <div class="absolute top-0 right-0 w-full h-1 bg-gradient-to-r from-teal-500 via-emerald-400 to-teal-500"></div>

          <div class="relative flex flex-col h-full">
            <div class="flex items-center mb-8">
              <div class="w-14 h-14 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-xl flex items-center justify-center mr-4 shadow-lg shadow-teal-200/50 transform group-hover:rotate-6 transition-transform duration-500">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-800">মাদরাসা সাইন-আপ</h3>
                <p class="text-gray-500 text-sm">মাদরাসা রেজিস্ট্রেশন এবং অংশগ্রহণ</p>
              </div>
            </div>

            <div class="bg-gradient-to-br from-white to-emerald-50 rounded-lg p-6 mb-8 border border-emerald-100/60 shadow-inner">
              <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                সুবিধাসমূহ
              </h4>
              <ul class="space-y-2.5">
                <li v-for="(benefit, index) in benefits" :key="index"
                    class="flex items-start"
                    v-motion
                    :initial="{ opacity: 0, x: 20 }"
                    :enter="{ opacity: 1, x: 0, transition: { delay: 600 + (index * 100) } }">
                  <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  <span class="text-gray-600">{{ benefit }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-auto">
              <Link
                :href="route('madrasha_check_for_user')"
                class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 flex items-center justify-center group hover:shadow-lg hover:shadow-emerald-500/30 hover:scale-[1.02]"
              >
                সাইন-আপ করুন
                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </Link>
              <p class="text-center text-gray-500 text-sm mt-4">ইতিমধ্যে রেজিস্টার্ড? <a href="#" class="text-emerald-600 font-medium hover:underline">লগইন করুন</a></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Testimonial Row -->
      <div class="mt-12 bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg p-6 overflow-hidden"
           v-motion
           :initial="{ opacity: 0, y: 20 }"
           :enter="{ opacity: 1, y: 0, transition: { delay: 800 } }">
        <div class="relative">
          <svg class="absolute top-0 left-0 text-emerald-100 w-12 h-12 transform translate-x-1 -translate-y-3" fill="currentColor" viewBox="0 0 32 32">
            <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
          </svg>
          <div class="px-8 py-4">
            <div class="swiper-container" ref="swiperContainer">
              <div class="swiper-wrapper">
                <div class="swiper-slide flex flex-col md:flex-row items-center gap-6" v-for="(testimonial, index) in testimonials" :key="index">
                  <div class="flex-shrink-0">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-emerald-50">
                      <img :src="testimonial.image" class="w-full h-full object-cover" :alt="testimonial.name">
                    </div>
                  </div>
                  <div>
                    <p class="text-gray-600 italic mb-4">{{ testimonial.quote }}</p>
                    <div>
                      <p class="font-semibold text-gray-800">{{ testimonial.name }}</p>
                      <p class="text-sm text-emerald-600">{{ testimonial.title }}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-pagination mt-6"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
// import { Swiper } from 'swiper/bundle'; // Temporarily disabled



// Define icons as components
const StudentIcon = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
  `
};

const MarkazIcon = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
    </svg>
  `
};

const NegranIcon = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
    </svg>
  `
};

const MumtahinIcon = {
  template: `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
    </svg>
  `
};

// Define component for animated counter
const AnimatedNumber = {
  props: {
    value: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      displayValue: 0
    }
  },
  mounted() {
    this.animateValue(0, this.value, 1500);
  },
  methods: {
    animateValue(start, end, duration) {
      let startTimestamp = null;
      const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        this.displayValue = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
          window.requestAnimationFrame(step);
        }
      };
      window.requestAnimationFrame(step);
    }
  },
  template: `<span>{{ displayValue.toLocaleString() }}</span>`
};

// Current formatted date
const formattedDate = computed(() => {
  const date = new Date();
  return new Intl.DateTimeFormat('bn-BD', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date);
});

// Statistics data with real number values and appropriate icons
const stats = [
  {
    title: 'পরীক্ষার্থীর সংখ্যা',
    value: 53543,
    unit: 'জন',
    icon: StudentIcon,
    percentage: 85,
    class: 'animate-pulse'
  },
  {
    title: 'মারকায সংখ্যা',
    value: 3000,
    unit: '',
    icon: MarkazIcon,
    percentage: 70,
    class: ''
  },
  {
    title: 'নেগরান সংখ্যা',
    value: 4500,
    unit: 'জন',
    icon: NegranIcon,
    percentage: 65,
    class: ''
  },
  {
    title: 'মুমতাহিন সংখ্যা',
    value: 7800,
    unit: 'জন',
    icon: MumtahinIcon,
    percentage: 90,
    class: 'animate-pulse'
  },
];

// Benefits for madrasa signup
const benefits = [
  'অনলাইনে পরীক্ষার রেজিস্ট্রেশন করার সুবিধা',
  'মাদরাসার সকল ছাত্র-ছাত্রীদের তথ্য ডিজিটাল পদ্ধতিতে সংরক্ষণ',
  'রেজাল্ট অনলাইনে প্রকাশ এবং নিরীক্ষণ',
  'সার্টিফিকেট অনলাইন ভেরিফিকেশন সিস্টেম',
  'মাদরাসার সকল নোটিশ ও পরীক্ষার রুটিন সরাসরি পাওয়া'
];

// Testimonials
const testimonials = [
  {
    quote: "অনলাইন ম্যানেজমেন্ট সিস্টেমের কারণে আমাদের মাদরাসার অ্যাডমিনিস্ট্রেটিভ কাজের চাপ অনেক কমেছে এবং সময় বেঁচেছে।",
    name: "মাওলানা আব্দুল হামিদ",
    title: "মুহতামিম, জামিয়া আরাবিয়া ইসলামিয়া",
    image: "https://randomuser.me/api/portraits/men/1.jpg"
  },
  {
    quote: "পরীক্ষার ফলাফল এখন দ্রুত পাওয়া যায়, এছাড়া সার্টিফিকেট ভেরিফিকেশন সিস্টেম আমাদের অনেক উপকার করেছে।",
    name: "মাওলানা রফিকুল ইসলাম",
    title: "প্রিন্সিপাল, দারুল উলুম মাদরাসা",
    image: "https://randomuser.me/api/portraits/men/2.jpg"
  }
];

const swiperContainer = ref(null);
let swiper = null;

onMounted(() => {
  // Swiper temporarily disabled for debugging
  console.log('FeatureSection mounted successfully');
  
  // TODO: Re-implement testimonials slider without Swiper dependency
  // For now, testimonials will be displayed as static cards
});
</script>

<style scoped>
.swiper-container {
  width: 100%;
  overflow: hidden;
}

.swiper-pagination-bullet {
  background-color: #059669;
  opacity: 0.5;
}

.swiper-pagination-bullet-active {
  opacity: 1;
  background-color: #059669;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
