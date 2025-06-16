<script setup lang="ts">
import { ScrollArea } from '@/components/ui/scroll-area';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
// ───────────────────────────────────────────────────────────
// Component Props
// ───────────────────────────────────────────────────────────
const props = defineProps<{
    nav: Record<string, any>;
}>();
// ───────────────────────────────────────────────────────────
// Reactive State
// ───────────────────────────────────────────────────────────
const showStartMenu = ref(false);
const currentTime = computed(() => new Date().toLocaleTimeString([], { hour: 'numeric', minute: 'numeric' }));
// ───────────────────────────────────────────────────────────
// Navigation Helpers
// ───────────────────────────────────────────────────────────
const openDashboard = () => {
    if (props.nav?.main?.length) {
        const suggestionsRoute = props.nav.main.find((i) => i.title === 'Suggestions');
        const dashboardRoute = props.nav.main.find((i) => i.title === 'Dashboard');
        router.visit((suggestionsRoute ?? dashboardRoute)?.href ?? route('login'));
    } else {
        router.visit(route('login'));
    }
};
// ───────────────────────────────────────────────────────────
// UI Helpers
// ───────────────────────────────────────────────────────────
const toggleStartMenu = () => (showStartMenu.value = !showStartMenu.value);
const closeStartMenu = () => (showStartMenu.value = false);
</script>
<template>
    <Head title="Portfolio: InterviewScheduler" />
    <!-- Desktop (teal for light, darker teal for dark) -->
    <div @click="closeStartMenu" class="font-vt323 relative min-h-screen bg-[#008080] p-4 md:p-8 dark:bg-[#003f3f]">
        <!-- Desktop Icon ─────────────────────────────────── -->
        <div
            @dblclick="openDashboard"
            class="group absolute top-8 left-8 flex w-24 cursor-pointer flex-col items-center text-center"
            title="Double-click to open Dashboard"
        >
            <img src="dashboard.png" alt="Dashboard Icon" class="h-16 w-16 group-hover:bg-blue-800/50 dark:group-hover:bg-blue-600/50" />
            <span class="mt-1 p-0.5 text-white select-none group-hover:bg-blue-800/50 dark:group-hover:bg-blue-600/50"> Dashboard </span>
        </div>
        <!-- Main Window ──────────────────────────────────── -->
        <div class="window-style mx-auto mt-24 flex max-w-4xl flex-col bg-[#C0C0C0] dark:bg-[#2b2b2b]" style="height: 600px">
            <!-- Title Bar -->
            <div class="flex flex-shrink-0 items-center justify-between bg-[#000080] p-1 text-white select-none dark:bg-[#000040]">
                <h1 class="text-lg font-bold tracking-wider">InterviewScheduler - Project Overview</h1>
                <div class="flex items-center space-x-1">
                    <button class="btn-style flex h-5 w-5 items-center justify-center font-bold">_</button>
                    <button class="btn-style flex h-5 w-5 items-center justify-center">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0H10V1H0V0Z" fill="currentColor" />
                            <path d="M0 1H1V9H0V1Z" fill="currentColor" />
                            <path d="M9 1H10V9H9V1Z" fill="currentColor" />
                            <path d="M0 9H10V10H0V9Z" fill="currentColor" />
                            <path d="M1 1H9V2H1V1Z" fill="currentColor" />
                        </svg>
                    </button>
                    <button class="btn-style flex h-5 w-5 items-center justify-center font-bold">X</button>
                </div>
            </div>
            <!-- Menu Bar -->
            <div class="flex flex-shrink-0 space-x-4 border-b-2 border-b-[#404040] px-2 py-0.5 select-none dark:border-b-[#666]">
                <p><span class="underline">F</span>ile</p>
                <p><span class="underline">E</span>dit</p>
                <p><span class="underline">V</span>iew</p>
                <p><span class="underline">H</span>elp</p>
            </div>
            <!-- Window Content -->
            <ScrollArea class="sunken-style-inner h-full w-full">
                <!-- Project Details -->
                <div class="space-y-6 p-4 text-lg md:p-6">
                    <section>
                        <h3 class="text-xl font-bold tracking-wider">Project Concept: Interview Scheduler</h3>
                        <p class="mt-2">
                            A simple application for scheduling job or internship interviews. It defines two user roles: the
                            <strong>Employer</strong> (you) and the <strong>Applicant</strong> (e.g., Heiner).
                        </p>
                        <p class="mt-2">
                            The process is straightforward: an Employer selects a date to schedule an interview. When the status is set to
                            <em>"confirm"</em>, the Applicant receives the suggestion in their dashboard. Both parties are notified via email. The
                            Applicant can then accept the appointment, which updates the Employer's dashboard in <strong>real-time</strong>,
                            triggering another round of email notifications.
                        </p>
                    </section>
                    <section>
                        <h3 class="text-xl font-bold tracking-wider">Tech Stack</h3>
                        <div class="mt-2 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="window-style bg-[#C0C0C0] p-3 dark:bg-[#3b3b3b]">
                                <h4 class="font-bold">Laravel & Vue</h4>
                                <p>Robust PHP backend with a modern, reactive JavaScript frontend.</p>
                            </div>
                            <div class="window-style bg-[#C0C0C0] p-3 dark:bg-[#3b3b3b]">
                                <h4 class="font-bold">Inertia.js</h4>
                                <p>The glue that connects Laravel and Vue, creating a seamless SPA experience.</p>
                            </div>
                            <div class="window-style bg-[#C0C0C0] p-3 dark:bg-[#3b3b3b]">
                                <h4 class="font-bold">Laravel Reverb</h4>
                                <p>Powers the real-time updates via a first-party WebSocket server.</p>
                            </div>
                            <div class="window-style bg-[#C0C0C0] p-3 dark:bg-[#3b3b3b]">
                                <h4 class="font-bold">Resend & Queued Jobs</h4>
                                <p>Handles asynchronous email sending for instant UI feedback.</p>
                            </div>
                        </div>
                    </section>
                    <section>
                        <h3 class="text-xl font-bold tracking-wider">Key Laravel Features Implemented</h3>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            <li><strong>Authentication:</strong> Laravel Breeze starter kit.</li>
                            <li><strong>Authorization:</strong> Gates and Policies protect routes and actions.</li>
                            <li><strong>Events & Listeners:</strong> Trigger email notifications on interview status changes.</li>
                            <li><strong>Queued Jobs:</strong> Offload email sending to a worker.</li>
                            <li><strong>WebSockets (Reverb):</strong> Private channels for secure, real-time broadcasts.</li>
                        </ul>
                    </section>
                </div>
            </ScrollArea>
        </div>
        <!-- Start Menu ────────────────────────────────────── -->
        <div v-if="showStartMenu" @click.stop class="start-menu-style absolute bottom-12 left-3 z-20 w-56 bg-[#c0c0c0] p-1 dark:bg-[#2b2b2b]">
            <div class="flex">
                <div class="flex w-8 items-end justify-center bg-[#000080] p-1 dark:bg-[#000040]">
                    <h2 class="vertical-text text-2xl font-bold text-white">
                        <span>Scheduler</span><span class="text-[#c0c0c0] dark:text-[#888]">95</span>
                    </h2>
                </div>
                <ul class="flex-1">
                    <li class="menu-item">
                        <a href="https://heinerdevelops.tech" target="_blank" class="flex w-full items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" class="mr-2">
                                <path
                                    fill="currentColor"
                                    d="M12 12q-1.65 0-2.825-1.175T8 8q0-1.65 1.175-2.825T12 4q1.65 0 2.825 1.175T16 8q0 1.65-1.175 2.825T12 12m-8 8v-2.8q0-.85.438-1.562T5.6 14.55q1.55-.775 3.15-1.162T12 13q1.65 0 3.25.388t3.15 1.162q.725.375 1.163 1.088T20 17.2V20z"
                                />
                            </svg>
                            <span class="underline">P</span>ortfolio
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="https://github.com/heinergiehl" target="_blank" class="flex w-full items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" class="mr-2">
                                <path
                                    fill="currentColor"
                                    d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5c.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34c-.46-1.16-1.11-1.47-1.11-1.47c-.91-.62.07-.6.07-.6c1 .07 1.53 1.03 1.53 1.03c.87 1.5 2.3.9 2.87.69c.08-.54.34-.9.62-1.11c-2.19-.25-4.5-1.1-4.5-4.88c0-1.08.38-1.96 1.03-2.65c-.1-.25-.45-1.26.1-2.61c0 0 .83-.27 2.75 1.02A9.58 9.58 0 0 1 12 6.82a9.58 9.58 0 0 1 2.5-.33c1.92-1.3 2.75-1.02 2.75-1.02c.55 1.35.2 2.36.1 2.61c.65.7 1.03 1.57 1.03 2.65c0 3.79-2.31 4.63-4.5 4.88c.36.31.69.92.69 1.85V21c0 .27.16.58.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2"
                                />
                            </svg>
                            <span class="underline">G</span>itHub
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Taskbar ───────────────────────────────────────── -->
        <div class="window-style fixed right-0 bottom-0 left-0 z-10 flex h-10 items-center bg-[#C0C0C0] px-2 select-none dark:bg-[#2b2b2b]">
            <button
                @click.stop="toggleStartMenu"
                class="btn-style flex items-center bg-[#C0C0C0] px-2 py-0.5 dark:bg-[#3b3b3b]"
                :class="{ 'btn-active': showStartMenu }"
            >
                <img
                    src="https://www.windows93.net/c/sys/skins/w93/start.png"
                    alt="Start Icon"
                    class="mr-1 h-6 w-6"
                    onerror="this.style.display='none'"
                />
                <span class="text-lg font-bold">Start</span>
            </button>
            <div class="sunken-style ml-auto px-2 py-0.5 text-lg">
                <span>{{ currentTime }}</span>
            </div>
        </div>
    </div>
</template>
<style>
@import url('https://fonts.googleapis.com/css2?family=VT323&display=swap');
.font-vt323 {
    font-family: 'VT323', monospace;
    font-size: 24px;
}
/* ───────────────────────────────────────────────────────────
   Win-95-ish 3-D Borders / Buttons
   ─────────────────────────────────────────────────────────── */
.btn-style,
.window-style,
.start-menu-style {
    border-style: solid;
    border-width: 2px;
    border-top-color: #ffffff;
    border-left-color: #ffffff;
    border-right-color: #404040;
    border-bottom-color: #404040;
    box-shadow: 1px 1px 0 1px #000000;
}
.dark .btn-style,
.dark .window-style,
.dark .start-menu-style {
    border-top-color: #4a4a4a;
    border-left-color: #4a4a4a;
    border-right-color: #000000;
    border-bottom-color: #000000;
}
/* sunken-look containers */
.sunken-style {
    border-style: solid;
    border-width: 2px;
    border-top-color: #404040;
    border-left-color: #404040;
    border-right-color: #ffffff;
    border-bottom-color: #ffffff;
}
.dark .sunken-style {
    border-top-color: #000000;
    border-left-color: #000000;
    border-right-color: #4a4a4a;
    border-bottom-color: #4a4a4a;
}
/* inner recessed frame */
.sunken-style-inner {
    border-style: solid;
    border-width: 2px;
    border-top-color: #000000;
    border-left-color: #000000;
    border-right-color: #ffffff;
    border-bottom-color: #ffffff;
    padding: 2px;
    overflow: hidden;
}
.dark .sunken-style-inner {
    border-top-color: #000000;
    border-left-color: #000000;
    border-right-color: #4a4a4a;
    border-bottom-color: #4a4a4a;
}
/* pressed-button effect */
.btn-style:active,
.btn-active {
    border-top-color: #404040;
    border-left-color: #404040;
    border-right-color: #ffffff;
    border-bottom-color: #ffffff;
    transform: translate(1px, 1px);
    box-shadow: none;
}
.dark .btn-style:active,
.dark .btn-active {
    border-top-color: #000000;
    border-left-color: #000000;
    border-right-color: #4a4a4a;
    border-bottom-color: #4a4a4a;
}
/* vertical app name (start menu sidebar) */
.vertical-text {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    text-orientation: mixed;
}
/* Start-menu items */
.menu-item {
    display: flex;
    align-items: center;
    padding: 4px 8px;
    cursor: pointer;
    font-size: 18px;
}
.menu-item:hover,
.menu-item a:hover {
    background-color: #000080;
    color: white;
}
.dark .menu-item:hover,
.dark .menu-item a:hover {
    background-color: #000040;
}
/* ScrollArea custom scrollbar */
[data-radix-scroll-area-viewport] {
    scrollbar-width: thin;
    scrollbar-color: #c0c0c0 #808080;
}
[data-radix-scroll-area-viewport]::-webkit-scrollbar {
    width: 16px;
}
[data-radix-scroll-area-viewport]::-webkit-scrollbar-track {
    background: #808080;
}
[data-radix-scroll-area-viewport]::-webkit-scrollbar-thumb {
    background-color: #c0c0c0;
    border-style: solid;
    border-width: 2px;
    border-top-color: #ffffff;
    border-left-color: #ffffff;
    border-right-color: #404040;
    border-bottom-color: #404040;
}
.dark [data-radix-scroll-area-viewport]::-webkit-scrollbar-thumb {
    background-color: #3b3b3b;
    border-top-color: #4a4a4a;
    border-left-color: #4a4a4a;
    border-right-color: #000000;
    border-bottom-color: #000000;
}
</style>
