<template>
  <div class="dual-agent-ui">
    <SidebarProvider>
      <AppSidebar />
      <SidebarInset>
        <header class="flex h-16 shrink-0 items-center gap-2 border-b px-4">
          <SidebarTrigger class="-ml-1" />
          <Separator orientation="vertical" class="mr-2 h-4" />
          <div class="flex items-center gap-2">
            <span class="font-semibold">Welcome</span>
          </div>
        </header>
        
        <div class="flex flex-1 flex-col gap-4 p-4">
          <div class="space-y-6">
            <h1 class="text-3xl font-bold tracking-tight">Welcome to Dual Agent UI</h1>
            <p class="text-lg text-muted-foreground">This is a Vue 3 + Inertia.js setup with shadcn-vue components.</p>
            
            <Card>
              <CardHeader>
                <CardTitle>Getting Started</CardTitle>
              </CardHeader>
              <CardContent>
                <p>Navigate through the sidebar to explore different sections of the dashboard.</p>
              </CardContent>
            </Card>
          </div>
        </div>
      </SidebarInset>
    </SidebarProvider>
  </div>
</template>

<script setup>
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Separator } from '@/components/ui/separator'
import { 
  SidebarProvider, 
  SidebarInset, 
  SidebarTrigger 
} from '@/components/ui/sidebar'
import AppSidebar from '@/components/AppSidebar.vue'
</script>