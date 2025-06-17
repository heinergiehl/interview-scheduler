<script setup lang="ts">
import { SidebarGroup, SidebarGroupContent, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { defineAsyncComponent } from 'vue';
interface Props {
    items: NavItem[];
    class?: string;
}
const iconCache = new Map<string, ReturnType<typeof defineAsyncComponent>>();
defineProps<Props>();
function resolveIcon(name: string | undefined) {
    if (!name) return console.warn('No icon name provided in NavFooter.vue'), null;
    if (!iconCache.has(name)) {
        iconCache.set(
            name,
            defineAsyncComponent(() =>
                // ① komplettes Paket laden  ② gewünschten Export herausziehen
                import('lucide-vue-next').then((m: any) => m[name]),
            ),
        );
    }
    return iconCache.get(name)!;
}
</script>
<template>
    <SidebarGroup :class="`group-data-[collapsible=icon]:p-0 ${$props.class || ''}`">
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                    <SidebarMenuButton class="text-neutral-600 hover:text-neutral-800 dark:text-neutral-300 dark:hover:text-neutral-100" as-child>
                        <a :href="item.href" target="_blank" rel="noopener noreferrer">
                            <component :is="resolveIcon(item.icon)" />
                            {{ item.title }}
                        </a>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
