<script setup lang="ts">
import { type Column } from '@tanstack/vue-table';
import { ChevronDown, ChevronUp } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';
interface Props {
    label: string;
    column: Column<any, any>;
}
const props = defineProps<Props>();
</script>
<template>
    <div class="flex items-center gap-1">
        <span>{{ label }}</span>
        <div class="flex flex-col">
            <Button
                variant="ghost"
                size="sm"
                :class="['h-4 w-4 p-1', column.getIsSorted() === 'asc' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600']"
                @click="column.toggleSorting(false)"
            >
                <ChevronUp class="h-3 w-3" />
            </Button>
            <Button
                variant="ghost"
                size="sm"
                :class="['h-4 w-4 p-1', column.getIsSorted() === 'desc' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600']"
                @click="column.toggleSorting(true)"
            >
                <ChevronDown class="h-3 w-3" />
            </Button>
        </div>
    </div>
</template>
