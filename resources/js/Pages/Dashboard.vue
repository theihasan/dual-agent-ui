<template>
  <div class="dashboard">
    <h1>Dashboard</h1>

    <div class="stats-grid">
      <div class="stat-card" v-for="(value, key) in stats" :key="key">
        <h3>{{ formatKey(key) }}</h3>
        <p>{{ value }}{{ key === 'errorRate' ? '%' : key === 'uptime' ? '%' : '' }}</p>
      </div>
    </div>

    <div class="recent-activities">
      <h2>Recent Activities</h2>
      <ul>
        <li v-for="activity in recentActivities" :key="activity.id">
          {{ activity.action }} - {{ activity.timestamp }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'

defineProps({
  stats: Object,
  recentActivities: Array,
})

const formatKey = (key) => {
  return key.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())
}
</script>

<style scoped>
.dashboard {
  padding: 20px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.stat-card {
  border: 1px solid #ddd;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
}

.stat-card h3 {
  margin: 0 0 10px 0;
  color: #666;
}

.stat-card p {
  font-size: 24px;
  font-weight: bold;
  margin: 0;
}

.recent-activities h2 {
  margin-bottom: 20px;
}

.recent-activities ul {
  list-style: none;
  padding: 0;
}

.recent-activities li {
  padding: 10px;
  border-bottom: 1px solid #eee;
}
</style>