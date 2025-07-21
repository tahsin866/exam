<template>
  <section
    class="relative py-28 overflow-hidden bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50"
    style="font-family: 'Amiri', 'Merriweather', 'SolaimanLipi', sans-serif;"
  >
    <!-- Islamic Mosque SVG Background (Full Height, Subtle, Right Side) -->
    <div class="absolute inset-0 w-full h-full opacity-10 pointer-events-none z-0">
      <svg class="absolute right-0 top-0 h-full w-1/2 md:w-1/3 lg:w-1/4 select-none" viewBox="0 0 400 800" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMax slice">
        <!-- Large Dome -->
        <ellipse cx="200" cy="500" rx="150" ry="80" fill="#059669" fill-opacity="0.13" />
        <ellipse cx="200" cy="520" rx="110" ry="60" fill="#059669" fill-opacity="0.18" />
        <!-- Minaret Left -->
        <rect x="80" y="350" width="18" height="160" rx="8" fill="#059669" fill-opacity="0.17"/>
        <rect x="76" y="340" width="26" height="30" rx="8" fill="#059669" fill-opacity="0.12"/>
        <!-- Minaret Right -->
        <rect x="302" y="350" width="18" height="160" rx="8" fill="#059669" fill-opacity="0.17"/>
        <rect x="298" y="340" width="26" height="30" rx="8" fill="#059669" fill-opacity="0.12"/>
        <!-- Main Gate -->
        <rect x="160" y="530" width="80" height="70" rx="20" fill="#059669" fill-opacity="0.13"/>
        <!-- Arch Door -->
        <path d="M200 600 Q220 580 220 550 Q220 540 200 540 Q180 540 180 550 Q180 580 200 600 Z" fill="#059669" fill-opacity="0.23"/>
        <!-- Crescent Moon -->
        <path d="M200 440 a30 30 0 1 0 0.01 0" fill="none" stroke="#059669" stroke-opacity="0.45" stroke-width="5"/>
        <circle cx="215" cy="435" r="8" fill="#059669" fill-opacity="0.25"/>
        <!-- Windows -->
        <ellipse cx="180" cy="570" rx="6" ry="10" fill="#059669" fill-opacity="0.18"/>
        <ellipse cx="220" cy="570" rx="6" ry="10" fill="#059669" fill-opacity="0.18"/>
      </svg>
    </div>

    <!-- Rest of the content (unchanged) -->
    <div class="container mx-auto px-4 relative z-10">
      <div class="flex flex-col items-center max-w-4xl mx-auto">
        <!-- Top Ornament -->
        <div class="mb-8 flex justify-center w-full">
          <div class="relative w-72 h-20">
            <svg class="w-full h-full text-emerald-700" viewBox="0 0 100 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10,20 L20,10 L30,20 L40,10 L50,20 L60,10 L70,20 L80,10 L90,20" stroke="currentColor" stroke-width="1" />
              <path d="M10,25 L20,15 L30,25 L40,15 L50,25 L60,15 L70,25 L80,15 L90,25" stroke="currentColor" stroke-width="1" />
              <circle cx="50" cy="5" r="4" fill="currentColor" />
              <circle cx="30" cy="5" r="2" fill="currentColor" />
              <circle cx="70" cy="5" r="2" fill="currentColor" />
            </svg>
          </div>
        </div>

        <!-- Arabic Bismillah -->
        <div class="mb-8 transform hover:scale-105 transition-transform duration-700"
             data-aos="fade-down" data-aos-delay="300">
          <h2 class="text-2xl md:text-3xl font-arabic text-emerald-900 tracking-wider">وفاق المدارس العربية بنغلا ديش</h2>
        </div>

        <!-- Main Title with Animation -->
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-emerald-900 mb-6 leading-tight tracking-wide"
            data-aos="fade-up" data-aos-delay="400">
          <span class="inline-block transform hover:scale-105 transition-transform duration-300">বেফাকুল</span>
          <span class="inline-block transform hover:scale-105 transition-transform duration-300 delay-100">মাদারিসিল</span>
          <span class="inline-block transform hover:scale-105 transition-transform duration-300 delay-200">আরাবিয়া</span>
          <span class="inline-block transform hover:scale-105 transition-transform duration-300 delay-300">বাংলাদেশ</span>
        </h1>

        <!-- Middle Ornamental Divider -->
        <div class="flex items-center justify-center my-8 w-full" data-aos="fade-up" data-aos-delay="500">
          <div class="h-0.5 w-24 bg-gradient-to-r from-transparent via-emerald-600 to-transparent"></div>
          <div class="mx-4 relative">
            <div class="absolute inset-0 rounded-full bg-emerald-100 animate-ping opacity-30"></div>
            <svg class="w-10 h-10 text-emerald-700 relative transform transition-transform duration-700 hover:rotate-180" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2L14.85 8.35L22 9.27L17 14.14L18.18 21L12 17.77L5.82 21L7 14.14L2 9.27L9.15 8.35L12 2Z"/>
            </svg>
          </div>
          <div class="h-0.5 w-24 bg-gradient-to-r from-transparent via-emerald-600 to-transparent"></div>
        </div>

        <!-- Subtitle with Fancy Border Animation -->
        <div class="relative group" data-aos="fade-up" data-aos-delay="600">
          <div class="absolute -inset-1 bg-gradient-to-r from-emerald-600 via-teal-500 to-emerald-600 rounded-lg blur opacity-25 group-hover:opacity-60 transition duration-1000 group-hover:duration-200"></div>
          <div class="relative px-8 py-4 bg-white/80 backdrop-blur-sm border border-emerald-200 rounded-lg shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
            <div class="absolute bottom-0 right-0 w-full h-1 bg-gradient-to-l from-emerald-400 to-teal-500"></div>
            <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-emerald-400 to-teal-500"></div>
            <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-t from-emerald-400 to-teal-500"></div>

            <h2 class="text-xl md:text-2xl text-emerald-800 font-semibold">
              (বাংলাদেশ কওমী মাদরাসা শিক্ষাবোর্ড)
            </h2>
          </div>
        </div>

        <!-- Bottom Information -->
        <div class="mt-12 flex flex-col md:flex-row items-center justify-center gap-8" data-aos="fade-up" data-aos-delay="700">
          <div class="flex items-center gap-3 text-emerald-700 bg-white/60 backdrop-blur-sm px-5 py-3 rounded-full shadow-sm hover:shadow-md transition-all duration-300">
            <i class="pi pi-map-marker text-xl"></i>
            <span class="text-lg font-medium">ঢাকা, বাংলাদেশ</span>
          </div>
          <div class="flex items-center gap-3 text-emerald-700 bg-white/60 backdrop-blur-sm px-5 py-3 rounded-full shadow-sm hover:shadow-md transition-all duration-300">
            <i class="pi pi-globe text-xl"></i>
            <span class="text-lg font-medium">প্রতিষ্ঠাঃ ১৯৭৮</span>
          </div>
          <div class="flex items-center gap-3 text-emerald-700 bg-white/60 backdrop-blur-sm px-5 py-3 rounded-full shadow-sm hover:shadow-md transition-all duration-300">
            <i class="pi pi-building text-xl"></i>
            <span class="text-lg font-medium">১৫,০০০+ মাদরাসা</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Wave Effect -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden">
      <svg class="w-full h-16 text-white" viewBox="0 0 1440 100" fill="currentColor" preserveAspectRatio="none">
        <path d="M0,50 C150,20 300,0 450,0 C600,0 750,20 900,50 C1050,80 1200,100 1440,100 L1440,100 L0,100 Z"></path>
      </svg>
    </div>
  </section>
</template>

<script setup>
import { onMounted } from 'vue';
// Use AOS for scroll animations if available
onMounted(() => {
  if (typeof AOS !== 'undefined') {
    AOS.init({ duration: 1000, once: true });
  }
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap');
.font-arabic {
  font-family: 'Amiri', serif;
}
@keyframes border-glow {
  0% {
    box-shadow: 0 0 5px rgba(16, 185, 129, 0.5);
  }
  50% {
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.7);
  }
  100% {
    box-shadow: 0 0 5px rgba(16, 185, 129, 0.5);
  }
}
@keyframes float {
  0% { transform: translateY(0px);}
  50% { transform: translateY(-10px);}
  100% { transform: translateY(0px);}
}
h1 {
  text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.9), 2px 4px 6px rgba(0, 0, 0, 0.1);
}
h1 span {
  display: inline-block;
  position: relative;
  margin: 0 2px;
}
h1 span:hover {
  color: #047857;
}
</style>
