<template>
  <div class="dual-agent-ui">
    <SidebarProvider>
      <AppSidebar />
      <SidebarInset>
        <header class="flex h-16 shrink-0 items-center justify-between border-b px-6">
          <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <Separator orientation="vertical" class="mr-2 h-4" />
            <h1 class="text-2xl font-semibold">Dashboard</h1>
          </div>
          
          <!-- Time Period Selector -->
          <div class="flex items-center gap-2">
            <Badge variant="outline" class="bg-blue-50 text-blue-700 border-blue-200">1H</Badge>
            <Badge variant="outline" class="text-muted-foreground">24H</Badge>
            <Badge variant="outline" class="text-muted-foreground">7D</Badge>
            <Badge variant="outline" class="text-muted-foreground">14D</Badge>
            <Badge variant="outline" class="text-muted-foreground">30D</Badge>
            <Button variant="outline" size="sm" class="ml-2">
              <Calendar class="h-4 w-4 mr-1" />
            </Button>
          </div>
        </header>
        
        <div class="flex flex-1 flex-col gap-6 p-6">
          <!-- Activity Section -->
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <Activity class="h-5 w-5" />
                <h2 class="text-lg font-semibold">Activity</h2>
              </div>
              <Button variant="ghost" size="sm" class="text-muted-foreground">
                Requests
                <ExternalLink class="h-3 w-3 ml-1" />
              </Button>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Requests Card -->
              <Card>
                <CardHeader class="pb-3">
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <p class="text-sm font-medium text-muted-foreground">REQUESTS</p>
                      <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                          <span class="text-sm">1/2/3XX</span>
                          <span class="text-lg font-semibold">0</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                          <span class="text-sm">4XX</span>
                          <span class="text-lg font-semibold">0</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                          <span class="text-sm">5XX</span>
                          <span class="text-lg font-semibold">0</span>
                        </div>
                      </div>
                    </div>
                    <div class="text-right">
                      <p class="text-sm font-medium text-muted-foreground">DURATION</p>
                      <div class="flex items-center gap-4 mt-1">
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-gray-600 rounded-full"></div>
                          <span class="text-sm">AVG</span>
                          <span class="text-lg font-semibold">—</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                          <span class="text-sm">P95</span>
                          <span class="text-lg font-semibold">—</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </CardHeader>
                <CardContent>
                  <!-- Chart Placeholder -->
                  <div class="h-32 bg-muted/30 rounded-md flex items-center justify-center">
                    <p class="text-sm text-muted-foreground">No data available</p>
                  </div>
                  <div class="flex justify-between items-center mt-4 text-sm text-muted-foreground">
                    <span>Oct 5, 2025, 16:59:30 UTC</span>
                    <span>Oct 5, 2025, 17:59:30 UTC</span>
                  </div>
                </CardContent>
              </Card>

              <!-- Duration Card -->
              <Card>
                <CardHeader class="pb-3">
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <p class="text-sm font-medium text-muted-foreground">JOB DURATION</p>
                      <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-gray-600 rounded-full"></div>
                          <span class="text-sm">AVG</span>
                          <span class="text-lg font-semibold">—</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                          <span class="text-sm">P95</span>
                          <span class="text-lg font-semibold">—</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </CardHeader>
                <CardContent>
                  <!-- Chart Placeholder -->
                  <div class="h-32 bg-muted/30 rounded-md flex items-center justify-center">
                    <p class="text-sm text-muted-foreground">No data available</p>
                  </div>
                  <div class="flex justify-between items-center mt-4 text-sm text-muted-foreground">
                    <span>Oct 5, 2025, 16:59:30 UTC</span>
                    <span>Oct 5, 2025, 17:59:30 UTC</span>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>

          <!-- Application Section -->
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <Monitor class="h-5 w-5" />
                <h2 class="text-lg font-semibold">Application</h2>
              </div>
              <Button variant="ghost" size="sm" class="text-muted-foreground">
                Jobs
                <ExternalLink class="h-3 w-3 ml-1" />
              </Button>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Exceptions Card -->
              <Card>
                <CardHeader>
                  <Badge variant="secondary" class="w-fit">EXCEPTIONS</Badge>
                  <div class="mt-4">
                    <h3 class="text-lg font-semibold">No exceptions reported in the last hour.</h3>
                  </div>
                </CardHeader>
                <CardContent>
                  <!-- Chart Circle Placeholder -->
                  <div class="flex items-center justify-center py-8">
                    <div class="relative w-32 h-32">
                      <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                        <path
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none"
                          stroke="#e5e7eb"
                          stroke-width="2"
                          stroke-dasharray="100, 100"
                        />
                      </svg>
                      <div class="absolute inset-0 flex items-center justify-center">
                        <Check class="h-6 w-6 text-green-500" />
                      </div>
                    </div>
                  </div>
                  <div class="text-center">
                    <div class="flex items-center justify-center gap-2 text-sm text-green-600 font-medium">
                      <CheckCircle class="h-4 w-4" />
                      NO ACTIONS
                    </div>
                  </div>
                </CardContent>
              </Card>

              <!-- Jobs Card -->
              <Card>
                <CardHeader class="pb-3">
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <p class="text-sm font-medium text-muted-foreground">JOBS</p>
                      <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                          <span class="text-sm">FAILED</span>
                          <span class="text-2xl font-bold">0</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                          <span class="text-sm">PROCESSED</span>
                          <span class="text-2xl font-bold">0</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                          <span class="text-sm">RELEASED</span>
                          <span class="text-2xl font-bold">0</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </CardHeader>
                <CardContent>
                  <div class="mt-6">
                    <div class="text-center py-8">
                      <div class="w-16 h-16 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-4">
                        <Settings class="h-8 w-8 text-blue-500" />
                      </div>
                      <h3 class="text-lg font-semibold mb-2">Setup thresholds</h3>
                      <p class="text-sm text-muted-foreground mb-4">
                        Configure your performance thresholds to start monitoring.
                      </p>
                      <Button class="bg-blue-600 hover:bg-blue-700">
                        + Add Threshold
                      </Button>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>

          <!-- Users Section -->
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <Users class="h-5 w-5" />
                <h2 class="text-lg font-semibold">Users</h2>
              </div>
              <Button variant="ghost" size="sm" class="text-muted-foreground">
                Users
                <ExternalLink class="h-3 w-3 ml-1" />
              </Button>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <!-- User Exceptions Card -->
              <Card>
                <CardHeader>
                  <Badge variant="secondary" class="w-fit">EXCEPTIONS</Badge>
                  <div class="mt-4">
                    <h3 class="text-lg font-semibold">No users impacted by exceptions in the last hour.</h3>
                  </div>
                </CardHeader>
                <CardContent>
                  <div class="flex items-center justify-center py-8">
                    <div class="relative w-32 h-32">
                      <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                        <path
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none"
                          stroke="#e5e7eb"
                          stroke-width="2"
                          stroke-dasharray="100, 100"
                        />
                      </svg>
                      <div class="absolute inset-0 flex items-center justify-center">
                        <Check class="h-6 w-6 text-green-500" />
                      </div>
                    </div>
                  </div>
                  <div class="text-center">
                    <div class="flex items-center justify-center gap-2 text-sm text-green-600 font-medium">
                      <CheckCircle class="h-4 w-4" />
                      NO ACTIONS
                    </div>
                  </div>
                </CardContent>
              </Card>

              <!-- Active Users Card -->
              <Card>
                <CardHeader>
                  <Badge variant="secondary" class="w-fit">REQUESTS</Badge>
                  <div class="mt-4">
                    <h3 class="text-lg font-semibold">No active users in the last hour.</h3>
                  </div>
                </CardHeader>
                <CardContent>
                  <div class="flex items-center justify-center py-8">
                    <div class="relative w-32 h-32">
                      <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                        <path
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none"
                          stroke="#e5e7eb"
                          stroke-width="2"
                          stroke-dasharray="100, 100"
                        />
                      </svg>
                      <div class="absolute inset-0 flex items-center justify-center">
                        <Check class="h-6 w-6 text-green-500" />
                      </div>
                    </div>
                  </div>
                  <div class="text-center">
                    <div class="flex items-center justify-center gap-2 text-sm text-green-600 font-medium">
                      <CheckCircle class="h-4 w-4" />
                      NO ACTIONS
                    </div>
                  </div>
                </CardContent>
              </Card>

              <!-- Authentication Stats Card -->
              <Card>
                <CardHeader class="pb-3">
                  <div class="text-center">
                    <p class="text-sm font-medium text-muted-foreground">AUTHENTICATED USERS</p>
                    <p class="text-3xl font-bold mt-1">0</p>
                  </div>
                </CardHeader>
                <CardContent>
                  <div class="space-y-4">
                    <div class="text-center">
                      <p class="text-sm font-medium text-muted-foreground">REQUESTS</p>
                      <div class="flex items-center justify-center gap-6 mt-2">
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                          <span class="text-sm">AUTHENTICATED</span>
                          <span class="text-xl font-bold">0</span>
                        </div>
                        <div class="flex items-center gap-2">
                          <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                          <span class="text-sm">GUEST</span>
                          <span class="text-xl font-bold">0</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </SidebarInset>
    </SidebarProvider>
  </div>
</template>

<script setup>
import { 
  Activity, 
  Calendar, 
  ExternalLink, 
  Monitor, 
  Users, 
  Check, 
  CheckCircle, 
  Settings 
} from 'lucide-vue-next'

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Separator } from '@/components/ui/separator'
import { 
  SidebarProvider, 
  SidebarInset, 
  SidebarTrigger 
} from '@/components/ui/sidebar'
import AppSidebar from '@/components/AppSidebar.vue'

defineProps({
  stats: Object,
  recentActivities: Array,
})

const formatKey = (key) => {
  return key.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())
}
</script>

