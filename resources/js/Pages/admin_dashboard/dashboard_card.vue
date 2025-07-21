<script setup>
import { ref, computed } from 'vue';

// Fake analytics chart data (sparkline)
function getRandomData(len = 20, base = 1000, variation = 80) {
  let val = base;
  return Array.from({ length: len }, () => {
    val += Math.floor(Math.random() * variation - variation / 2);
    return Math.max(val, 0);
  });
}

const stats = ref([
  {
    title: 'মোট মাদরাসা',
    value: '১৫,৪৮৯',
    change: '+৩.৫%',
    icon: 'university',
    color: 'bg-blue-500',
    trend: getRandomData(20, 15000, 200),
    trendUp: true,
    tooltip: 'দেশব্যাপী অনুমোদিত কওমী মাদরাসা'
  },
  {
    title: 'মোট ছাত্র',
    value: '৮৭,৩৪৫',
    change: '+৫.২%',
    icon: 'users',
    color: 'bg-green-500',
    trend: getRandomData(20, 80000, 800),
    trendUp: true,
    tooltip: 'সর্বমোট ছাত্র সংখ্যা'
  },
  {
    title: 'পরীক্ষার্থী',
    value: '৭৫,৬৭৮',
    change: '+৪.৮%',
    icon: 'user-graduate',
    color: 'bg-purple-500',
    trend: getRandomData(20, 70000, 600),
    trendUp: true,
    tooltip: 'চলতি বছর পরীক্ষার্থী সংখ্যা'
  },
  {
    title: 'মোট মারকায',
    value: '৩৪৫',
    change: '+২.১%',
    icon: 'mosque',
    color: 'bg-orange-500',
    trend: getRandomData(20, 320, 8),
    trendUp: true,
    tooltip: 'বিভিন্ন বিভাগীয় মারকাযের সংখ্যা'
  }
]);

// Tooltip visibility
const tooltipIndex = ref(null);
const showTooltip = (idx) => tooltipIndex.value = idx;
const hideTooltip = () => tooltipIndex.value = null;

function getTrendColor(trendUp) {
  return trendUp ? 'text-green-500' : 'text-red-500';
}
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div
      v-for="(stat, index) in stats"
      :key="index"
      class="relative group bg-white rounded-2xl shadow-xl border border-emerald-100 hover:shadow-2xl hover:-translate-y-1 transition-transform duration-300 p-6 overflow-hidden"
    >
      <!-- Floating colored glow -->
      <div
        :class="`${stat.color}`"
        class="absolute -top-8 -right-10 w-28 h-28 rounded-full opacity-15 blur-2xl z-0"
      ></div>
      <div class="flex items-center justify-between z-10 relative">
        <div>
          <div class="flex items-center gap-1 mb-1">
            <p class="text-emerald-700 text-sm font-semibold tracking-wide">{{ stat.title }}</p>
            <button
              @mouseenter="showTooltip(index)"
              @mouseleave="hideTooltip"
              @focus="showTooltip(index)"
              @blur="hideTooltip"
              class="ml-1 focus:outline-none"
              tabindex="0"
              aria-label="Help"
            >
              <i class="fas fa-info-circle text-emerald-400 text-xs"></i>
            </button>
            <span
              v-if="tooltipIndex === index"
              class="absolute z-20 left-1/2 transform -translate-x-1/2 top-10 min-w-[180px] bg-white border border-emerald-100 shadow-md px-3 py-1.5 rounded text-emerald-700 text-xs"
            >
              {{ stat.tooltip }}
            </span>
          </div>
          <div class="flex items-end gap-2">
            <h3 class="text-3xl md:text-4xl font-extrabold text-emerald-900">{{ stat.value }}</h3>
            <span
              class="ml-2 text-xs font-semibold px-3 py-0.5 rounded-full transition"
              :class="stat.change.includes('+') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
            >
              <span class="inline-block align-middle">
                <svg v-if="stat.change.includes('+')" class="h-3 w-3 inline" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                <svg v-else class="h-3 w-3 inline" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
              </span>
              {{ stat.change }}
            </span>
          </div>
          <p class="text-emerald-500 text-xs mt-1 font-medium">গত মাসের তুলনায়</p>
        </div>
        <div :class="`${stat.color} text-white p-3 rounded-full shadow-md border-2 border-white flex items-center justify-center text-xl transition-transform duration-300 group-hover:scale-110`">
          <i :class="`fas fa-${stat.icon}`"></i>
        </div>
      </div>
      <!-- Analytics Sparkline (inline SVG) -->
      <div class="mt-4 h-10 flex items-center z-10 relative">
        <svg
          :key="index"
          viewBox="0 0 80 28"
          class="w-full h-10"
          preserveAspectRatio="none"
        >
          <polyline
            :points="stat.trend.map((v,i) => `${i * 4},${28-(v-stat.trend.reduce((a,b)=>Math.min(a,b),Infinity))/(Math.max(...stat.trend)-Math.min(...stat.trend)||1)*22-3}`).join(' ')"
            fill="none"
            :stroke="stat.color.replace('bg-', 'text-').replace('-500', '-600')"
            stroke-width="2"
            class="transition-all duration-300"
          />
          <circle
            :cx="76"
            :cy="28-(stat.trend[stat.trend.length-1]-Math.min(...stat.trend))/(Math.max(...stat.trend)-Math.min(...stat.trend)||1)*22-3"
            r="2.5"
            :fill="stat.color.replace('bg-', '').replace('-500', '')"
            fill-opacity="0.85"
          />
        </svg>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* For tooltip fade-in */
.group [aria-label="Help"]:focus + span,
.group [aria-label="Help"]:hover + span {
  display: block;
}
</style>
