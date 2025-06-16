<!-- resources/js/Pages/Employer/Suggestions/Index.vue -->
<template>
    <Head title="Interview Slots" />
    <Layout>
        <!-- ░░ top bar ░░ -->
        <div class="mb-6 flex flex-col items-start gap-2 select-none">
            <h1 class="text-2xl font-semibold">Interview Slots</h1>
        </div>
        <!-- ░░ suggestions list ░░ -->
        <div v-if="suggestions.data.length" class="space-y-2">
            <DataTable
                :columns="columns"
                :data="suggestions.data"
                :meta="suggestions.meta"
                routeName="applicant.home"
                :statusOptions="statusOptions"
                :isApplicant="true"
                :rowClass="(row) => (flashedRows.has(row.id) ? 'bg-primary transition-colors duration-500' : '')"
            />
        </div>
        <p v-else class="text-muted-foreground">No slots yet – hit “Add slot”.</p>
    </Layout>
</template>
<script setup lang="ts">
import DataTable from '@/components/custom-table/DataTable.vue';
import { columns } from '@/components/custom-table/columns';
import Layout from '@/layouts/AppLayout.vue';
import type { Suggestion } from '@/pages/types';
import type { User } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import dayjs from 'dayjs';
import { defineProps, ref } from 'vue';
import { toast } from 'vue-sonner';
// Props from Inertia
const props = defineProps<{
    auth: { user: User };
    suggestions: {
        data: Suggestion[];
        meta: {
            current_page: number;
            last_page: number;
            per_page: number;
            total: number;
        };
    };
}>();
// Status dropdown
const statusOptions = [
    { value: 'confirmed', label: 'Confirmed' },
    { value: 'accepted', label: 'Accepted' },
];
// Track which rows should flash
const flashedRows = ref<Set<number>>(new Set());
// Listen for the real‐time event,
// flash the new row, toast, then reload suggestions only,
// preserving this component’s state (including `flashedRows`).
useEcho(`employer.${props.auth.user.id}`, 'InterviewAppointmentConfirmed', ({ suggestion }: { suggestion: Suggestion }) => {
    // 1) Flash it
    flashedRows.value.add(suggestion.id);
    setTimeout(() => flashedRows.value.delete(suggestion.id), 2000);
    // 2) Immediate feedback
    toast.success('Interview confirmed!', {
        description: `Slot #${suggestion.id} was accepted at ${dayjs(suggestion.responded_at)}`,
    });
    // 3) Partially reload *only* the `suggestions` prop,
    //    and keep our local state alive so the flash sticks.
    router.reload({
        only: ['suggestions'],
    });
});
</script>
