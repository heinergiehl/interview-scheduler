<!-- resources/js/Pages/Employer/Suggestions/Index.vue -->
<script setup lang="ts">
/* ── imports ───────────────────────────────────────────────────────── */
import { columns } from '@/components/custom-table/columns';
import DataTable from '@/components/custom-table/DataTable.vue';
import Layout from '@/layouts/AppLayout.vue';
import { Suggestion } from '@/pages/types';
import { User } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import dayjs from 'dayjs';
import { ref, watch } from 'vue';
import { toast } from 'vue-sonner';
/* ── props from Inertia ─────────────────────────────────────────────── */
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
    candidates: { id: number; name: string }[];
}>();
/* ── form state ─────────────────────────────────────────────────────── */
const form = useForm({ suggested_at: '' });
const today = dayjs();
const date = ref<Date | null>(null);
const time = ref('09:00');
const open = ref(false);
/* status list kept in the parent */
const statusOptions = [
    { value: 'confirmed', label: 'Confirmed' },
    { value: 'accepted', label: 'Accepted' },
];
/* ── reactive copy of suggestions.data ──────────────────────────── */
const suggestionsList = ref<Suggestion[]>([...props.suggestions.data]);
// keep it in sync if Inertia re-fetches the page
watch(
    () => props.suggestions.data,
    (v) => {
        suggestionsList.value = [...v];
    },
);
/* ── real-time update via Echo ─────────────────────────────────────── */
const userId = props.auth.user.id;
useEcho(`employer.${userId}`, 'InterviewAppointmentConfirmed', (payload: { suggestion: Suggestion }) => {
    const updated: Suggestion = payload.suggestion;
    console.log('Received real-time update:', updated);
    // add the new suggestion or update existing one
    const newSuggestion = payload.suggestion;
    const index = suggestionsList.value.findIndex((s) => s.id === updated.id);
    if (index === -1) {
        suggestionsList.value.push(newSuggestion);
    } else {
        suggestionsList.value[index] = newSuggestion;
    }
    console.log('Updated suggestions list:', suggestionsList.value);
    toast.success('Interview confirmed!', {
        description: `Slot #${updated.id} was accepted at ${dayjs(updated.responded_at).format('Pp')}`,
    });
});
</script>
<template>
    <Head title="Interview Slots" />
    <Layout>
        <!-- ░░ top bar ░░ -->
        <div class="mb-6 flex flex-col items-start gap-2 select-none">
            <h1 class="text-2xl font-semibold">Interview slots</h1>
        </div>
        <!-- ░░ suggestions list ░░ -->
        <!-- select with items per page -->
        <div v-if="suggestionsList.length" class="space-y-2">
            <DataTable
                :columns="columns"
                :data="suggestionsList"
                :meta="suggestions.meta"
                routeName="applicant.suggestions.index"
                :statusOptions="statusOptions"
                :isApplicant="true"
            />
        </div>
        <p v-else class="text-muted-foreground">No slots yet – hit “Add slot”.</p>
    </Layout>
</template>
