<!-- resources/js/components/EmployerSidebar.vue -->
<script setup lang="ts">
/* ------------------------------------------------------------------ */
/* imports                                                            */
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import type { NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, CalendarPlus, Folder, LayoutGrid } from 'lucide-vue-next';
/* ------------------------------------------------------------------ */
/* navigation items                                                   */
const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('employer.home'),
        icon: LayoutGrid,
    },
    {
        title: 'My slots',
        href: route('employer.suggestions.index'),
        icon: CalendarPlus,
    },
];
const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>
<template>
    <Sidebar variant="inset" collapsible="icon" class="h-screen">
        <!-- ─── logo / top bar ──────────────────────────────────────────── -->
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('employer.home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <!-- ─── main navigation ─────────────────────────────────────────── -->
        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>
        <!-- ─── footer (external links + user dropdown) ─────────────────── -->
        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>
