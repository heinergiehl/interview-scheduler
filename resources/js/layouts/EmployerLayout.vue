<script setup lang="ts">
import BaseSidebar from '@/components/AppSidebar.vue'; // your file
</script>
<template>
    <div class="flex">
        <BaseSidebar class="shrink-0" />
        <main class="flex-1 p-6">
            <slot />
        </main>
    </div>
</template>
