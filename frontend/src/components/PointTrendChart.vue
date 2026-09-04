<template>
  <div class="point-chart">

    <!-- HEADER -->
    <div class="chart-header">
      <div>
        <span class="section-label">ANALISIS POIN</span>
        <h3>Tren Poin Siswa</h3>
        <p>
          Perbandingan perkembangan poin pelanggaran dan prestasi
        </p>
      </div>

      <select v-model="period" class="period-select">
        <option value="6">6 Bulan</option>
        <option value="12">12 Bulan</option>
      </select>
    </div>

    <!-- INDICATORS -->
    <div class="trend-indicators">

      <div class="trend-item">
        <div class="trend-icon violation">
          <TrendingDown :size="18" />
        </div>

        <div>
          <span>Pelanggaran</span>
          <strong>Menurun</strong>
        </div>
      </div>

      <div class="trend-item">
        <div class="trend-icon achievement">
          <TrendingUp :size="18" />
        </div>

        <div>
          <span>Prestasi</span>
          <strong>Meningkat</strong>
        </div>
      </div>

    </div>

    <!-- LEGEND -->
    <div class="chart-legend">

      <div class="legend-item">
        <span class="legend-line violation"></span>
        <span>Poin Pelanggaran</span>
      </div>

      <div class="legend-item">
        <span class="legend-line achievement"></span>
        <span>Poin Prestasi</span>
      </div>

    </div>

    <!-- CHART -->
    <div class="chart-wrapper">
      <Line
        :data="chartData"
        :options="chartOptions"
      />
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Tooltip,
  Filler
} from 'chart.js'

import { Line } from 'vue-chartjs'

import {
  TrendingDown,
  TrendingUp
} from 'lucide-vue-next'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Tooltip,
  Filler
)

const period = ref('6')

const allData = [
  {
    month: 'Jan',
    violation: 35,
    achievement: 18
  },
  {
    month: 'Feb',
    violation: 42,
    achievement: 22
  },
  {
    month: 'Mar',
    violation: 38,
    achievement: 25
  },
  {
    month: 'Apr',
    violation: 51,
    achievement: 29
  },
  {
    month: 'Mei',
    violation: 47,
    achievement: 34
  },
  {
    month: 'Jun',
    violation: 42,
    achievement: 38
  },
  {
    month: 'Jul',
    violation: 36,
    achievement: 43
  },
  {
    month: 'Agu',
    violation: 40,
    achievement: 46
  },
  {
    month: 'Sep',
    violation: 33,
    achievement: 51
  },
  {
    month: 'Okt',
    violation: 29,
    achievement: 54
  },
  {
    month: 'Nov',
    violation: 25,
    achievement: 58
  },
  {
    month: 'Des',
    violation: 21,
    achievement: 62
  }
]

const chartData = computed(() => {
  const data =
    period.value === '6'
      ? allData.slice(-6)
      : allData

  return {
    labels: data.map(item => item.month),

    datasets: [
      {
        label: 'Poin Pelanggaran',

        data: data.map(item => item.violation),

        borderColor: '#ef4444',

        backgroundColor: 'rgba(239, 68, 68, 0.07)',

        borderWidth: 3,

        pointRadius: 4,

        pointHoverRadius: 7,

        pointBorderWidth: 2,

        pointBackgroundColor: '#ffffff',

        tension: 0.4,

        fill: true
      },

      {
        label: 'Poin Prestasi',

        data: data.map(item => item.achievement),

        borderColor: '#2563eb',

        backgroundColor: 'rgba(37, 99, 235, 0.08)',

        borderWidth: 3,

        pointRadius: 4,

        pointHoverRadius: 7,

        pointBorderWidth: 2,

        pointBackgroundColor: '#ffffff',

        tension: 0.4,

        fill: true
      }
    ]
  }
})

const chartOptions = {
  responsive: true,

  maintainAspectRatio: false,

  interaction: {
    mode: 'index',
    intersect: false
  },

  plugins: {
    legend: {
      display: false
    },

    tooltip: {
      backgroundColor: '#0f172a',

      titleColor: '#ffffff',

      bodyColor: '#e2e8f0',

      padding: 12,

      cornerRadius: 10,

      displayColors: true
    }
  },

  scales: {
    y: {
      beginAtZero: true,

      border: {
        display: false
      },

      grid: {
        color: '#eef2f7'
      },

      ticks: {
        color: '#94a3b8',

        font: {
          size: 11
        },

        padding: 8
      }
    },

    x: {
      border: {
        display: false
      },

      grid: {
        display: false
      },

      ticks: {
        color: '#94a3b8',

        font: {
          size: 11
        }
      }
    }
  }
}
</script>

<style scoped>
.point-chart {
  background: #ffffff;
  border: 1px solid #e8edf5;
  border-radius: 18px;
  padding: 22px;
  box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
}

/* HEADER */

.chart-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 20px;
}

.section-label {
  display: inline-block;
  margin-bottom: 6px;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 1px;
  color: #2563eb;
}

.chart-header h3 {
  margin: 0;
  font-size: 19px;
  color: #0f172a;
}

.chart-header p {
  margin: 5px 0 0;
  font-size: 12px;
  color: #94a3b8;
}

/* PERIOD */

.period-select {
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  color: #334155;
  border-radius: 9px;
  padding: 8px 12px;
  font-size: 12px;
  outline: none;
  cursor: pointer;
}

.period-select:focus {
  border-color: #93c5fd;
}

/* INDICATORS */

.trend-indicators {
  display: flex;
  gap: 12px;
  margin-top: 20px;
}

.trend-item {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 9px 12px;
  border-radius: 11px;
  background: #f8fafc;
}

.trend-icon {
  width: 32px;
  height: 32px;
  border-radius: 9px;

  display: flex;
  align-items: center;
  justify-content: center;
}

.trend-icon.violation {
  background: #ecfdf5;
  color: #16a34a;
}

.trend-icon.achievement {
  background: #eff6ff;
  color: #2563eb;
}

.trend-item span {
  display: block;
  font-size: 10px;
  color: #94a3b8;
}

.trend-item strong {
  display: block;
  margin-top: 2px;
  font-size: 12px;
  color: #334155;
}

/* LEGEND */

.chart-legend {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-top: 20px;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 11px;
  color: #64748b;
}

.legend-line {
  width: 22px;
  height: 3px;
  border-radius: 99px;
}

.legend-line.violation {
  background: #ef4444;
}

.legend-line.achievement {
  background: #2563eb;
}

/* CHART */

.chart-wrapper {
  position: relative;
  height: 320px;
  margin-top: 15px;
}

/* RESPONSIVE */

@media (max-width: 700px) {
  .chart-header {
    flex-direction: column;
  }

  .period-select {
    width: 100%;
  }

  .trend-indicators {
    flex-direction: column;
  }

  .chart-wrapper {
    height: 260px;
  }
}
</style>