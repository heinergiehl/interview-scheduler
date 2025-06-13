import type { Suggestion } from '@/pages/types';
import { createColumnHelper } from '@tanstack/vue-table';
import { format } from 'date-fns';
import { h } from 'vue';
import SortableHeader from './SortableHeader.vue';
const helper = createColumnHelper<Suggestion>();
export const columns = [
    helper.accessor('id', {
        header: (info) => h(SortableHeader, { label: 'ID', column: info.column }),
        cell: (info) => info.getValue()?.toString(),
        enableSorting: true,
    }),
    helper.accessor((row) => row.user?.name ?? '', {
        id: 'userName',
        header: (info) => h(SortableHeader, { label: 'Name', column: info.column }),
        cell: (info) => info.getValue() || 'N/A',
        enableSorting: true,
    }),
    helper.accessor('suggested_date_time', {
        header: (info) => h(SortableHeader, { label: 'Suggested Date', column: info.column }),
        cell: (info) => format(new Date(info.getValue()), 'Pp'),
        enableSorting: true,
    }),
    helper.accessor('appointment_status', {
        header: (info) => h(SortableHeader, { label: 'Status', column: info.column }),
        cell: (info) => info.getValue(),
        enableSorting: true,
    }),
    helper.accessor('responded_at', {
        header: (info) => h(SortableHeader, { label: 'Responded At', column: info.column }),
        cell: (info) => {
            const v = info.getValue();
            return v ? format(new Date(v), 'Pp') : '—';
        },
        enableSorting: true,
    }),
    helper.display({ id: 'actions', header: 'Actions' }),
];
