<template>
  <div class="dual-agent-ui">
    <div class="container mx-auto p-6 space-y-6">
      <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <Card v-for="(value, key) in stats" :key="key">
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">
              {{ formatKey(key) }}
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">
              {{ value }}{{ key === 'errorRate' ? '%' : key === 'uptime' ? '%' : '' }}
            </div>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Recent Activities</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-4">
            <div 
              v-for="activity in recentActivities" 
              :key="activity.id"
              class="flex items-center justify-between p-3 rounded-lg border bg-muted/30"
            >
              <span class="font-medium">{{ activity.action }}</span>
              <span class="text-sm text-muted-foreground">{{ activity.timestamp }}</span>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'

defineProps({
  stats: Object,
  recentActivities: Array,
})

const formatKey = (key) => {
  return key.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())
}
</script>

