<!-- resources/js/Pages/Employer/Suggestions/Index.vue -->
<script setup lang="ts">
/* ── imports ───────────────────────────────────────────────────────── */
import { columns } from '@/components/custom-table/columns';
import DataTable from '@/components/custom-table/DataTable.vue';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Dialog, DialogContent, DialogTrigger } from '@/components/ui/dialog';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import { Input } from '@/components/ui/input';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import Layout from '@/layouts/AppLayout.vue';
import { Suggestion, User } from '@/pages/types';
import { Head, useForm } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import dayjs from 'dayjs';
import { Plus } from 'lucide-vue-next';
import { nextTick, ref, watch } from 'vue';
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
const form = useForm({
    suggested_at: '',
    candidate_id: null,
});
const today = dayjs();
const date = ref<Date | null>(null);
const time = ref('09:00');
const open = ref(false);
/* ── submit handler ─────────────────────────────────────────────────── */
function save() {
    if (!date.value) return; // extra guard
    const dt = dayjs(date.value).hour(+time.value.split(':')[0]).minute(+time.value.split(':')[1]).second(0);
    form.suggested_at = dt.format('YYYY-MM-DD HH:mm:ss');
    form.post(route('employer.suggestions.store'), {
        onSuccess: () => {
            form.reset();
            date.value = null; // reset date picker
            time.value = '09:00';
            open.value = false; // close modal
            toast.success('Success!', {
                description: 'Your slot suggestion has been saved.',
                duration: 2000,
                style: {
                    backgroundColor: '#d4edda',
                    color: '#155724',
                },
            });
        },
        onError: () => {
            // keep modal open & focus first error
            nextTick(() => {
                const firstErr = document.querySelector('[aria-invalid="true"]');
                (firstErr as HTMLElement | null)?.focus();
                toast.error('Error!', {
                    description: 'There are errors in your submission. Please check the form.',
                    duration: 2000,
                    style: {
                        backgroundColor: '#f8d7da',
                        color: '#721c24',
                    },
                });
            });
        },
    });
}
/* ── reactive copy of suggestions.data ──────────────────────────── */
const suggestionsList = ref<Suggestion[]>([...props.suggestions.data]);
// keep it in sync if Inertia re-fetches the page
watch(
    () => props.suggestions.data,
    (v) => {
        suggestionsList.value = [...v];
    },
);
const ACCEPTED_CLASS = 'bg-green-500 transition-colors duration-500';
const DECLINED_CLASS = 'bg-red-500   transition-colors duration-500';
/** Return the flash colour (or empty string) for a given row */
function flashRowClass(row: Suggestion): string {
    if (!flashedRows.value.has(row.id)) return '';
    return declined.value ? DECLINED_CLASS : ACCEPTED_CLASS;
}
/* ── real-time update via Echo ─────────────────────────────────────── */
const flashedRows = ref<Set<number>>(new Set());
const userId = props.auth.user.id;
useEcho(`employer.${userId}`, 'InterviewAppointmentAccepted', (payload: { suggestion: Suggestion }) => {
    const updated: Suggestion = payload.suggestion;
    // replace the old suggestion in our reactive list
    suggestionsList.value = suggestionsList.value.map((s) => (s.id === updated.id ? { ...s, ...updated } : s));
    // flash it…
    flashedRows.value.add(updated.id);
    // …then clear the flash after 1s
    setTimeout(() => {
        flashedRows.value.delete(updated.id);
    }, 1000);
    toast.success('Interview accepted!', {
        description: `Slot #${updated.id} was accepted at ${dayjs(updated.responded_at).format('Pp')}`,
    });
});
const declined = ref(false);
useEcho(`employer.${userId}`, 'InterviewAppointmentDeclined', (payload: { suggestion: Suggestion }) => {
    const updated: Suggestion = payload.suggestion;
    console.log('InterviewAppointmentDeclined', updated);
    // replace the old suggestion in our reactive list
    suggestionsList.value = suggestionsList.value.map((s) => (s.id === updated.id ? { ...s, ...updated } : s));
    // flash it…
    flashedRows.value.add(updated.id);
    // …then clear the flash after 1s
    declined.value = true;
    setTimeout(() => {
        flashedRows.value.delete(updated.id);
        declined.value = false;
    }, 1000);
    toast.error('Interview declined!', {
        description: `Slot #${updated.id} was accepted at ${dayjs(updated.responded_at)}`,
    });
});
const statusOptions = [
    { value: 'draft', label: 'Draft' },
    { value: 'confirmed', label: 'Confirmed' },
];
</script>
<template>
    <Head title="Interview Slots" />
    <Layout>
        <!-- ░░ top bar ░░ -->
        <div class="mb-6 flex flex-col items-start gap-2 select-none">
            <h1 class="text-2xl font-semibold">Interview slots</h1>
            <!-- modal trigger -->
            <Dialog v-model:open="open">
                <TooltipProvider>
                    <Tooltip>
                        <DialogTrigger as-child>
                            <TooltipTrigger>
                                <Button>
                                    <Plus />
                                </Button>
                            </TooltipTrigger>
                        </DialogTrigger>
                        <TooltipContent> Add your Appointment suggestion. You can add multiple ones.</TooltipContent>
                    </Tooltip>
                </TooltipProvider>
                <!-- ░░ modal content ░░ -->
                <DialogContent class="w-80 space-y-4 select-none">
                    <DialogTitle class="text-lg font-medium">Propose a slot</DialogTitle>
                    <DialogDescription>
                        <p class="text-sm text-muted-foreground">
                            Suggest a date and time for the interview. You can add multiple slots, and the candidate can choose one.
                        </p>
                    </DialogDescription>
                    <!-- real form: Enter key works, native validation too -->
                    <form class="space-y-4" @submit.prevent="save">
                        <!-- ↓ shadcn-vue Select for candidates ↓ -->
                        <div>
                            <Select v-model="form.candidate_id">
                                <SelectTrigger aria-label="Candidate">
                                    <SelectValue placeholder="Select candidate" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="cand in candidates" :key="cand.id" :value="cand.id">
                                        {{ cand.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.candidate_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.candidate_id }}
                            </p>
                        </div>
                        <!-- date -->
                        <div>
                            <label for="slot-date" class="mb-1 block text-sm">Date</label>
                            <Calendar
                                @click.prevent
                                id="slot-date"
                                v-model="date"
                                :from="today.toDate()"
                                aria-required="true"
                                class="rounded-md border"
                            />
                            <p v-if="form.errors.suggested_at" class="mt-1 text-sm text-red-600">
                                {{ form.errors.suggested_at }}
                            </p>
                        </div>
                        <!-- time -->
                        <div>
                            <label for="slot-time" class="mb-1 block text-sm">Time</label>
                            <Input :aria-invalid="!!form.errors.suggested_at" id="slot-time" type="time" v-model="time" required />
                        </div>
                        <!-- submit -->
                        <Button type="submit" class="w-full" :disabled="form.processing || !date">
                            {{ form.processing ? 'Saving…' : 'Save' }}
                        </Button>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
        <!-- ░░ suggestions list ░░ -->
        <!-- select with items per page -->
        <div v-if="suggestions.data?.length" class="space-y-2">
            <DataTable
                :statusOptions="statusOptions"
                :columns="columns"
                :data="suggestionsList"
                :meta="suggestions.meta"
                routeName="employer.home"
                :isApplicant="false"
                :rowClass="flashRowClass"
            />
        </div>
        <p v-else class="text-muted-foreground">No slots yet – hit “Add slot”.</p>
    </Layout>
</template>
