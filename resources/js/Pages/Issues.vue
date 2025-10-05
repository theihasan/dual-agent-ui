<template>
  <div class="dual-agent-ui">
    <SidebarProvider>
      <AppSidebar />
      <SidebarInset>
        <header class="flex h-16 shrink-0 items-center justify-between border-b px-6">
          <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <Separator orientation="vertical" class="mr-2 h-4" />
            <h1 class="text-2xl font-semibold">Issues</h1>
          </div>
        </header>
        
        <div class="flex flex-1 flex-col">
          <!-- Filter Tabs -->
          <div class="border-b bg-background">
            <div class="flex items-center justify-between px-6 py-4">
              <Tabs :value="activeTab" @update:value="activeTab = $event">
                <TabsList class="grid w-auto grid-cols-5 bg-muted/20">
                  <TabsTrigger 
                    v-for="tab in filterTabs" 
                    :key="tab.value"
                    :value="tab.value"
                    class="relative"
                  >
                    {{ tab.label }}
                    <Badge 
                      v-if="tab.count" 
                      variant="secondary" 
                      class="ml-2 h-5 px-1.5 text-xs"
                    >
                      {{ tab.count }}
                    </Badge>
                  </TabsTrigger>
                </TabsList>
              </Tabs>
              
              <div class="flex items-center gap-4">
                <!-- Search -->
                <div class="relative">
                  <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                  <Input
                    v-model="searchQuery"
                    placeholder="Search by title"
                    class="w-64 pl-9"
                  />
                </div>
                
                <!-- View Options -->
                <div class="flex items-center gap-2">
                  <Button variant="ghost" size="sm" class="text-muted-foreground">View all</Button>
                  <Button variant="ghost" size="sm" class="text-red-600 font-medium">
                    Exceptions
                    <Badge variant="destructive" class="ml-2 bg-red-600">
                      {{ props.stats?.by_type?.EXCEPTION || 0 }}
                    </Badge>
                  </Button>
                  <Button variant="ghost" size="sm" class="text-muted-foreground">Performance</Button>
                </div>
              </div>
            </div>
          </div>

          <!-- Issues Table -->
          <div class="flex-1 overflow-auto">
            <Table>
              <TableHeader>
                <TableRow class="hover:bg-transparent border-none">
                  <TableHead class="w-16 text-xs font-medium text-muted-foreground uppercase tracking-wider">ID</TableHead>
                  <TableHead class="w-32 text-xs font-medium text-muted-foreground uppercase tracking-wider">TYPE</TableHead>
                  <TableHead class="w-12 text-xs font-medium text-muted-foreground uppercase tracking-wider"></TableHead>
                  <TableHead class="text-xs font-medium text-muted-foreground uppercase tracking-wider">TITLE</TableHead>
                  <TableHead class="w-40 text-xs font-medium text-muted-foreground uppercase tracking-wider">ENVIRONMENTS</TableHead>
                  <TableHead class="w-32 text-xs font-medium text-muted-foreground uppercase tracking-wider">FIRST SEEN</TableHead>
                  <TableHead class="w-32 text-xs font-medium text-muted-foreground uppercase tracking-wider cursor-pointer" @click="toggleSort">
                    LAST SEEN
                    <ChevronDown v-if="sortDirection === 'desc'" class="ml-1 h-3 w-3 inline" />
                    <ChevronUp v-else class="ml-1 h-3 w-3 inline" />
                  </TableHead>
                  <TableHead class="w-32 text-xs font-medium text-muted-foreground uppercase tracking-wider">ASSIGNED</TableHead>
                  <TableHead class="w-12"></TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow 
                  v-for="issue in filteredIssues" 
                  :key="issue.id"
                  class="border-b border-border/50 hover:bg-muted/30 cursor-pointer group"
                >
                  <TableCell class="font-medium">{{ issue.id }}</TableCell>
                  <TableCell>
                    <Badge variant="destructive" class="bg-red-600 hover:bg-red-700 text-white font-medium">
                      {{ issue.type }}
                    </Badge>
                  </TableCell>
                  <TableCell>
                    <div class="flex items-center">
                      <BarChart3 class="h-4 w-4 text-muted-foreground" />
                    </div>
                  </TableCell>
                  <TableCell class="max-w-md">
                    <div class="truncate font-medium" :title="issue.title">
                      {{ issue.title }}
                    </div>
                    <div class="text-xs text-muted-foreground mt-1">
                      Priority: {{ issue.priority }} • {{ issue.occurrence_count || 1 }} occurrences
                    </div>
                  </TableCell>
                  <TableCell>
                    <div class="flex items-center gap-2">
                      <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                      <span class="text-sm">{{ issue.environment }}</span>
                    </div>
                  </TableCell>
                  <TableCell class="text-muted-foreground">{{ formatRelativeTime(issue.first_seen) }}</TableCell>
                  <TableCell class="text-muted-foreground">{{ formatRelativeTime(issue.last_seen) }}</TableCell>
                  <TableCell>
                    <Button variant="ghost" size="sm" class="h-6 w-6 p-0">
                      <UserPlus class="h-3 w-3" />
                    </Button>
                  </TableCell>
                  <TableCell>
                    <Button variant="ghost" size="sm" class="h-6 w-6 p-0">
                      <ExternalLink class="h-3 w-3" />
                    </Button>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </div>
      </SidebarInset>
    </SidebarProvider>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { 
  Search, 
  BarChart3, 
  ChevronDown, 
  ChevronUp, 
  UserPlus, 
  ExternalLink 
} from 'lucide-vue-next'

import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Separator } from '@/components/ui/separator'
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { 
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow
} from '@/components/ui/table'
import { 
  SidebarProvider, 
  SidebarInset, 
  SidebarTrigger 
} from '@/components/ui/sidebar'
import AppSidebar from '@/components/AppSidebar.vue'

// Props
const props = defineProps({
  issues: Array,
  stats: Object,
  filters: Object,
  filterCounts: Object,
  meta: Object
})

// Reactive state
const activeTab = ref(props.filters?.status || 'open')
const searchQuery = ref(props.filters?.search || '')
const sortDirection = ref('desc')

// Filter tabs with real counts
const filterTabs = [
  { label: 'Open', value: 'open', count: props.filterCounts?.open || 0 },
  { label: 'Unassigned', value: 'unassigned', count: props.filterCounts?.unassigned || 0 },
  { label: 'Mine', value: 'mine', count: null },
  { label: 'Resolved', value: 'resolved', count: props.filterCounts?.resolved || 0 },
  { label: 'Ignored', value: 'ignored', count: props.filterCounts?.ignored || 0 }
]

// Computed filtered issues
const filteredIssues = computed(() => {
  let filtered = [...(props.issues || [])]

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(issue => 
      issue.title.toLowerCase().includes(query) ||
      issue.type.toLowerCase().includes(query) ||
      issue.description.toLowerCase().includes(query)
    )
  }

  // Sort by priority and updated_at
  filtered = filtered.sort((a, b) => {
    const priorityOrder = { 'critical': 4, 'high': 3, 'medium': 2, 'low': 1 }
    const aPriority = priorityOrder[a.priority] || 0
    const bPriority = priorityOrder[b.priority] || 0
    
    if (sortDirection.value === 'desc') {
      if (aPriority === bPriority) {
        return new Date(b.updated_at) - new Date(a.updated_at)
      }
      return bPriority - aPriority
    } else {
      if (aPriority === bPriority) {
        return new Date(a.updated_at) - new Date(b.updated_at)
      }
      return aPriority - bPriority
    }
  })

  return filtered
})

// Helper function to format dates
const formatRelativeTime = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now - date) / (1000 * 60 * 60))
  
  if (diffInHours < 24) {
    return `${diffInHours} hours ago`
  }
  
  const diffInDays = Math.floor(diffInHours / 24)
  if (diffInDays < 30) {
    return `${diffInDays} days ago`
  }
  
  const diffInMonths = Math.floor(diffInDays / 30)
  return `${diffInMonths} months ago`
}

// Methods
const toggleSort = () => {
  sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc'
}
</script>