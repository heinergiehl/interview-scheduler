<script setup lang="ts">
/* ── imports ── */
import { Button } from '@/components/ui/button';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import { Select, SelectContent, SelectTrigger, SelectValue } from '@/components/ui/select';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import type { Suggestion } from '@/pages/types';
import { router, useForm } from '@inertiajs/vue3';
import type { ColumnDef, PaginationState, SortingState } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table';
import { format } from 'date-fns';
import { Check, CircleOff, Edit, Trash, Trash2 } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { route } from 'ziggy-js';
/* ── props ─────────────────────────────────────────────────────── */
const props = defineProps<{
    columns: ColumnDef<Suggestion, any>[];
    data: Suggestion[];
    meta: { current_page: number; last_page: number; per_page: number };
    routeName: string;
    isApplicant?: boolean; // optional prop for applicant-specific features
    /** Options for the inline-status select. E.g. [{value:'draft',label:'Draft'}] */
    statusOptions: { value: string; label: string }[];
    rowClass?: (row: Suggestion) => string;
}>();
/* ── inline edit ──────────────────────────────────────────────── */
const editingRowId = ref<number | null>(null);
const form = useForm<{ suggested_at: string; appointment_status: string }>({
    suggested_at: '',
    appointment_status: '',
});
function startEdit(row: Suggestion) {
    table.resetRowSelection();
    editingRowId.value = row.id;
    form.suggested_at = row.suggested_date_time;
    form.appointment_status = row.appointment_status;
}
function cancelEdit() {
    editingRowId.value = null;
    form.reset();
}
function submitEdit(id: number) {
    if (!form.suggested_at || !form.appointment_status) {
        // Handle validation error, e.g. show a notification
        console.error('Please fill in all fields before saving.');
        return;
    }
    if (!props.isApplicant) {
        form.put(`/employer/suggestions/${id}`, {
            preserveScroll: true,
            onSuccess: () => (editingRowId.value = null),
            onError: (err) => {
                // Handle error, e.g. show a notification
                console.error('Failed to update suggestion:');
            },
        });
    } else {
        form.put(route('applicant.suggestions.accept', id), {
            preserveScroll: true,
            onSuccess: () => (editingRowId.value = null),
            onError: (err) => {
                // Handle error, e.g. show a notification
                console.error('Failed to update suggestion:', err);
            },
        });
    }
}
/* ── pagination / sorting ─────────────────────────────────────── */
const pagination = ref<PaginationState>({
    pageIndex: props.meta.current_page - 1,
    pageSize: props.meta.per_page,
});
const sorting = ref<SortingState>([]);
watch(
    () => props.meta.current_page,
    (v) => (pagination.value.pageIndex = v - 1),
);
watch(
    () => props.meta.per_page,
    (v) => (pagination.value.pageSize = v),
);
/* ── row selection ─────────────────────────────────────────────── */
const rowSelection = ref<Record<string, boolean>>({});
/* ── table instance ────────────────────────────────────────────── */
const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    manualPagination: true,
    get pageCount() {
        return props.meta.last_page;
    },
    enableRowSelection: true,
    getRowId: (row) => row.id.toString(),
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
    state: {
        get pagination() {
            return pagination.value;
        },
        get sorting() {
            return sorting.value;
        },
        get rowSelection() {
            return rowSelection.value;
        },
    },
    onPaginationChange: (up) => (pagination.value = typeof up === 'function' ? up(pagination.value) : up),
    onSortingChange: (up) => (sorting.value = typeof up === 'function' ? up(sorting.value) : up),
    onRowSelectionChange: (up) => (rowSelection.value = typeof up === 'function' ? up(rowSelection.value) : up),
});
/* ── selection helpers ────────────────────────────────────────── */
const selectedIds = computed<number[]>(() => table.getSelectedRowModel().rows.map((r) => r.original.id));
const multiSelected = computed(() => selectedIds.value.length > 1);
const anySelected = computed(() => !!selectedIds.value.length);
/* ── bulk delete ──────────────────────────────────────────────── */
function bulkDelete() {
    if (!anySelected.value) return;
    if (!confirm(`Delete ${selectedIds.value.length} slot(s)?`)) return;
    router.delete(route('employer.suggestions.bulkDestroy'), {
        data: { ids: selectedIds.value },
        preserveState: true,
        onSuccess: () => table.resetRowSelection(),
    });
}
/* ── refetch on page/per-page change ──────────────────────────── */
watch(
    pagination,
    (p) => {
        router.get(route(props.routeName), { page: p.pageIndex + 1, per_page: p.pageSize }, { preserveState: true });
    },
    { deep: true },
);
</script>
<template>
    <div>
        <!-- toolbar -->
        <div class="flex items-center justify-between px-4 py-2">
            <Button v-if="anySelected" size="sm" variant="destructive" class="flex items-center gap-1" @click="bulkDelete">
                <Trash2 class="h-4 w-4" /> Delete&nbsp;{{ selectedIds.length }}
            </Button>
        </div>
        <!-- items-per-page selector -->
        <div class="flex items-end space-x-2 p-4">
            <span class="text-sm text-gray-600">Items per page:</span>
            <Select v-model="pagination.pageSize" class="w-24 text-sm">
                <SelectTrigger><SelectValue :placeholder="`${pagination.pageSize}`" /></SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="n in [5, 10, 15, 20]" :key="n" :value="`${n}`">{{ n }}</SelectItem>
                </SelectContent>
            </Select>
        </div>
        <!-- table -->
        <Table class="select-none">
            <TableHeader>
                <TableRow v-for="hg in table.getHeaderGroups()" :key="hg.id">
                    <TableHead class="w-4">
                        <Checkbox
                            :model-value="table.getIsAllPageRowsSelected()"
                            :indeterminate="table.getIsSomePageRowsSelected()"
                            aria-label="Select all rows"
                            @update:modelValue="(v) => table.toggleAllPageRowsSelected(!!v)"
                        />
                    </TableHead>
                    <TableHead v-for="h in hg.headers" :key="h.id">
                        <FlexRender v-if="!h.isPlaceholder" :render="h.column.columnDef.header" :props="h.getContext()" />
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow
                    v-for="row in table.getRowModel().rows"
                    :key="row.id"
                    :data-state="row.getIsSelected() && 'selected'"
                    class="h-[60px] data-[state=selected]:bg-primary/5"
                    :class="props.rowClass?.(row.original) ?? ''"
                >
                    <!-- checkbox -->
                    <TableCell class="w-4">
                        <Checkbox
                            :model-value="row.getIsSelected()"
                            :indeterminate="row.getIsSomeSelected()"
                            aria-label="Select row"
                            @update:modelValue="(v) => row.toggleSelected(!!v)"
                        />
                    </TableCell>
                    <TableCell>{{ row.original.id }}</TableCell>
                    <TableCell>{{ row.original.employer.name }}</TableCell>
                    <TableCell>
                        <span v-if="editingRowId !== row.original.id">
                            {{ format(new Date(row.original.suggested_date_time), 'Pp') }}
                        </span>
                        <input v-else type="datetime-local" v-model="form.suggested_at" class="rounded border px-2 py-1 text-sm" />
                    </TableCell>
                    <TableCell>
                        <span v-if="editingRowId !== row.original.id">{{ row.original.appointment_status }}</span>
                        <Select v-else v-model="form.appointment_status" class="text-sm">
                            <SelectTrigger><SelectValue placeholder="Status" /></SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="opt in props.statusOptions" :key="opt.value" :value="opt.value">
                                    {{ opt.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </TableCell>
                    <TableCell>
                        {{ row.original.responded_at ? format(new Date(row.original.responded_at), 'Pp') : '-' }}
                    </TableCell>
                    <!-- 
                    show edit/delete buttons if not in edit mode and not applicant
                    -->
                    <TableCell v-if="!multiSelected" class="whitespace-nowrap">
                        <template v-if="editingRowId !== row.original.id && !props.isApplicant">
                            <div class="flex items-center space-x-2">
                                <Button v-if="row.original.canUpdate" size="icon" variant="outline" @click="startEdit(row.original)">
                                    <Edit />
                                </Button>
                                <Button
                                    v-if="row.original.canDelete"
                                    size="icon"
                                    variant="destructive"
                                    @click="
                                        () => {
                                            table.resetRowSelection();
                                            router.delete(`/employer/suggestions/${row.original.id}`, { preserveState: true });
                                        }
                                    "
                                >
                                    <Trash />
                                </Button>
                            </div>
                        </template>
                        <!-- show accept and decline button for the case that not being in edit mode and user is participant -->
                        <template v-else-if="editingRowId !== row.original.id && props.isApplicant">
                            <div class="flex items-center space-x-2">
                                <Button
                                    v-if="row.original.canAccept"
                                    size="icon"
                                    @click="() => router.put(route('applicant.suggestions.accept', row.original.id), { preserveState: true })"
                                >
                                    <Check />
                                </Button>
                                <Button
                                    v-if="row.original.canDecline"
                                    size="icon"
                                    variant="destructive"
                                    @click="() => router.put(route('applicant.suggestions.decline', row.original.id), { preserveState: true })"
                                >
                                    <CircleOff />
                                </Button>
                            </div>
                        </template>
                        <!-- show save/cancel buttons if in edit mode -->
                        <template v-else-if="editingRowId === row.original.id">
                            <Button size="sm" :disabled="form.processing" @click="submitEdit(row.original.id)">Save</Button>
                            <Button size="sm" variant="ghost" @click="cancelEdit">Cancel</Button>
                        </template>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
        <!-- pager -->
        <div class="flex items-center justify-center space-x-2 py-4">
            <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">Previous</Button>
            <span class="text-sm text-gray-500">Page {{ pagination.pageIndex + 1 }} / {{ table.getPageCount() }}</span>
            <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()">Next</Button>
        </div>
    </div>
</template>
