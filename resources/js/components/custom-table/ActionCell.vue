<!-- ActionCell.vue -->
<template>
    <div class="flex items-center space-x-2">
        <!-- view mode -->
        <template v-if="!isEditing">
            <Button v-if="suggestion.canUpdate" size="sm" variant="outline" :disabled="form.processing" @click="startEdit"> Edit </Button>
            <Button v-if="suggestion.canDelete" size="sm" variant="destructive" :disabled="form.processing" @click="confirmDelete"> Delete </Button>
        </template>
        <!-- edit mode -->
        <template v-else>
            <!-- date/time field -->
            <input type="datetime-local" v-model="form.suggested_at" class="rounded border px-2 py-1 text-sm" />
            <!-- status dropdown -->
            <select v-model="form.appointment_status" class="rounded border px-2 py-1 text-sm">
                <option v-for="opt in statusOptions" :key="opt" :value="opt">
                    {{ opt }}
                </option>
            </select>
            <Button size="sm" :disabled="form.processing" @click="submitEdit"> Save </Button>
            <Button size="sm" variant="ghost" @click="cancelEdit"> Cancel </Button>
        </template>
    </div>
</template>
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import Button from '../ui/button/Button.vue';
interface Suggestion {
    id: number;
    suggested_date_time: string;
    appointment_status: string;
    canUpdate: boolean;
    canDelete: boolean;
}
const props = defineProps<{ suggestion: Suggestion }>();
const suggestion = props.suggestion;
const isEditing = ref(false);
// initialize form with both fields
const form = useForm<{ suggested_at: string; appointment_status: string }>({
    suggested_at: suggestion.suggested_date_time,
    appointment_status: suggestion.appointment_status,
});
// your allowed statuses — adjust as needed
const statusOptions = ['pending', 'confirmed', 'declined'];
function startEdit() {
    isEditing.value = true;
}
function cancelEdit() {
    form.reset(); // resets both fields
    isEditing.value = false;
}
function submitEdit() {
    form.put(`/employer/suggestions/${suggestion.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            isEditing.value = false;
        },
    });
}
function confirmDelete() {
    if (!confirm('Delete this slot?')) return;
    form.delete(`/employer/suggestions/${suggestion.id}`, {
        preserveScroll: true,
    });
}
</script>
