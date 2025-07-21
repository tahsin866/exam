<script setup>
import { ref, onMounted } from 'vue';
import Chart from 'chart.js/auto';

const examAnalytics = ref({
  passRate: '৮৫.৭%',
  averageGrade: '৩.৮৫',
  topSubject: 'হাদীস শরীফ',
  lowestSubject: 'আরবি ভাষা'
});

onMounted(() => {
  // Pass Rate Chart
  const passRateCtx = document.getElementById('passRateChart');
  if (passRateCtx) {
    new Chart(passRateCtx, {
      type: 'doughnut',
      data: {
        labels: ['পাস', 'ফেল'],
        datasets: [{
          data: [85.7, 14.3],
          backgroundColor: ['#10b981', '#e5e7eb'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '75%',
        plugins: {
          legend: { display: false }
        }
      }
    });
  }

  // Subject Performance Chart
  const subjectCtx = document.getElementById('subjectPerformanceChart');
  if (subjectCtx) {
    new Chart(subjectCtx, {
      type: 'bar',
      data: {
        labels: ['হাদীস', 'ফিকাহ', 'তাফসীর', 'আরবি', 'ইসলামি ইতিহাস'],
        datasets: [{
          label: 'গড় নম্বর',
          data: [85, 78, 72, 65, 80],
          backgroundColor: [
            '#10b981', '#6366f1', '#a78bfa', '#f59e42', '#06b6d4'
          ],
          borderRadius: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: { beginAtZero: true, max: 100 }
        },
        plugins: {
          legend: { display: false }
        }
      }
    });
  }

  // Yearly Trend Chart
  const trendCtx = document.getElementById('yearlyTrendChart');
  if (trendCtx) {
    new Chart(trendCtx, {
      type: 'line',
      data: {
        labels: ['১৪৪২', '১৪৪৩', '১৪৪৪', '১৪৪৫', '১৪৪৬'],
        datasets: [{
          label: 'পাস রেট',
          data: [78, 80, 82, 84, 85.7],
          borderColor: '#10b981',
          backgroundColor: 'rgba(16,185,129,0.10)',
          tension: 0.3,
          fill: true,
          pointRadius: 5,
          pointBackgroundColor: '#10b981'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: { beginAtZero: false, min: 70, max: 100 }
        },
        plugins: {
          legend: { display: false }
        }
      }
    });
  }
});
</script>

<template>
  <div class="bg-white rounded-2xl shadow-xl border border-emerald-100 overflow-hidden mb-8 mt-6 relative">
    <!-- Decorative SVG -->
    <div class="absolute right-0 top-0 opacity-10 pointer-events-none z-0">
      <svg width="150" height="130" fill="none">
        <ellipse cx="110" cy="60" rx="100" ry="38" fill="#10b981" />
        <ellipse cx="120" cy="110" rx="45" ry="18" fill="#06b6d4" />
      </svg>
    </div>
    <div class="bg-gradient-to-r from-emerald-50 via-white to-emerald-50 px-8 py-7 border-b border-emerald-100">
      <h3 class="text-2xl font-extrabold text-emerald-900 flex items-center gap-2">
        <i class="fas fa-chart-pie text-emerald-500"></i>
        পরীক্ষার বিশ্লেষণ (Analytics)
      </h3>
      <p class="text-emerald-600 mt-1 text-sm">সমস্ত পরীক্ষার ডেটা ও ট্রেন্ড</p>
    </div>

    <div class="p-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Key Metrics -->
        <div class="bg-gradient-to-br from-emerald-100 via-white to-emerald-50 rounded-xl p-7 flex flex-col items-center shadow border border-emerald-50 relative">
          <h4 class="text-base font-semibold text-emerald-700 mb-4">মূল পরিসংখ্যান</h4>
          <div class="space-y-4 w-full">
            <div class="flex items-center justify-between">
              <span class="text-sm text-emerald-800">পাস রেট</span>
              <span class="text-sm font-bold text-emerald-900">{{ examAnalytics.passRate }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-emerald-800">গড় গ্রেড</span>
              <span class="text-sm font-bold text-emerald-900">{{ examAnalytics.averageGrade }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-emerald-800">সর্বোচ্চ বিষয়</span>
              <span class="text-sm font-bold text-emerald-900">{{ examAnalytics.topSubject }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-emerald-800">সর্বনিম্ন বিষয়</span>
              <span class="text-sm font-bold text-emerald-900">{{ examAnalytics.lowestSubject }}</span>
            </div>
          </div>
          <div class="mt-8 h-44 w-full flex items-center justify-center relative">
            <canvas id="passRateChart"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
              <span class="text-3xl font-extrabold text-emerald-600">{{ examAnalytics.passRate }}</span>
              <span class="text-xs text-emerald-800">পাস রেট</span>
            </div>
          </div>
        </div>

        <!-- Subject Performance -->
        <div class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 rounded-xl p-7 shadow border border-indigo-100 flex flex-col">
          <h4 class="text-base font-semibold text-indigo-700 mb-4">বিষয়ভিত্তিক পারফরম্যান্স</h4>
          <div class="h-64 relative z-10">
            <canvas id="subjectPerformanceChart"></canvas>
          </div>
        </div>

        <!-- Yearly Trend -->
        <div class="bg-gradient-to-br from-teal-50 via-white to-emerald-50 rounded-xl p-7 shadow border border-teal-100 flex flex-col">
          <h4 class="text-base font-semibold text-teal-700 mb-4">বার্ষিক ট্রেন্ড</h4>
          <div class="h-64 relative z-10">
            <canvas id="yearlyTrendChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Animations and scrollbars can be added for extra effect */
</style>
