<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
// Define the props that this component expects from Inertia.
// The `nav` object is shared from the Laravel backend.
const props = defineProps({
    nav: {
        type: Object,
        required: true,
    },
});
// Reactive state to control the visibility of the start menu.
const showStartMenu = ref(false);
/**
 * Handles the double-click event on the dashboard icon.
 * Navigates the user based on their authentication status and role.
 */
const openDashboard = () => {
    // Check if the nav object is populated, which indicates a logged-in user.
    if (props.nav && Object.keys(props.nav).length > 0 && props.nav.main) {
        const suggestionsRoute = props.nav.main.find((item) => item.title === 'Suggestions');
        if (suggestionsRoute && suggestionsRoute.href) {
            router.visit(suggestionsRoute.href);
        } else {
            const homeRoute = props.nav.main.find((item) => item.title === 'Dashboard');
            if (homeRoute && homeRoute.href) {
                router.visit(homeRoute.href);
            }
        }
    } else {
        // If the user is a guest, navigate to the login page.
        router.visit(route('login'));
    }
};
/**
 * Toggles the start menu visibility.
 */
const toggleStartMenu = () => {
    showStartMenu.value = !showStartMenu.value;
};
/**
 * Closes the start menu.
 */
const closeStartMenu = () => {
    showStartMenu.value = false;
};
</script>
<template>
    <Head title="Welcome to My Awesome App" />
    <!-- 
      The main container with the classic teal background, acting as the "desktop".
      A click on the desktop will close the start menu.
    -->
    <div @click="closeStartMenu" class="font-vt323 relative min-h-screen bg-[#008080] p-4 md:p-8">
        <!-- Desktop Icon for Dashboard -->
        <div
            @dblclick="openDashboard"
            class="group absolute top-8 left-8 flex w-24 cursor-pointer flex-col items-center text-center"
            title="Double-click to open Dashboard"
        >
            <img src="Dashboard.png" alt="Dashboard Icon" class="h-16 w-16 group-hover:bg-blue-800/50" />
            <span class="mt-1 p-0.5 text-white select-none group-hover:bg-blue-800/50">Dashboard</span>
        </div>
        <!-- Main Application Window -->
        <div class="window-style mx-auto mt-24 max-w-4xl bg-[#C0C0C0]">
            <!-- Title Bar -->
            <div class="flex items-center justify-between bg-[#000080] p-1 text-white select-none">
                <h1 class="text-lg font-bold tracking-wider">My Awesome App</h1>
                <div class="flex items-center space-x-1">
                    <button class="btn-style flex h-5 w-5 items-center justify-center bg-[#C0C0C0] font-bold text-black">_</button>
                    <button class="btn-style flex h-5 w-5 items-center justify-center bg-[#C0C0C0] font-bold text-black">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0H10V1H0V0Z" fill="black" />
                            <path d="M0 1H1V9H0V1Z" fill="black" />
                            <path d="M9 1H10V9H9V1Z" fill="black" />
                            <path d="M0 9H10V10H0V9Z" fill="black" />
                            <path d="M1 1H9V2H1V1Z" fill="black" />
                        </svg>
                    </button>
                    <button class="btn-style flex h-5 w-5 items-center justify-center bg-[#C0C0C0] font-bold text-black">X</button>
                </div>
            </div>
            <!-- Menu Bar -->
            <div class="flex space-x-4 border-b-2 border-b-[#404040] px-2 py-0.5 select-none">
                <p><span class="underline">F</span>ile</p>
                <p><span class="underline">E</span>dit</p>
                <p><span class="underline">V</span>iew</p>
                <p><span class="underline">H</span>elp</p>
            </div>
            <!-- Window Content -->
            <div class="space-y-6 p-4 md:p-6">
                <!-- Content Sections remain the same -->
                <section class="flex flex-col items-center gap-4 sm:flex-row">
                    <img
                        src="https://placehold.co/100x100/008080/FFFFFF?text=Logo"
                        alt="App Logo"
                        class="sunken-style p-0.5"
                        onerror="this.onerror=null;this.src='https://placehold.co/100x100/C0C0C0/FFFFFF?text=Error';"
                    />
                    <div>
                        <h2 class="text-3xl font-bold tracking-wider">Welcome to the Future!</h2>
                        <p class="mt-2 text-lg">
                            This is the landing page for My Awesome App, built with nostalgia and modern technology. It's so fast, it's like a 486DX2!
                        </p>
                    </div>
                </section>
                <section>
                    <h3 class="text-xl font-bold tracking-wider">Features</h3>
                    <div class="mt-2 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="window-style bg-[#C0C0C0] p-3">
                            <div class="mb-2 flex items-center">
                                <img
                                    src="https://www.windows93.net/c/sys/skins/w93/documents.png"
                                    class="mr-2 h-8 w-8"
                                    alt="Documents Icon"
                                    onerror="this.style.display='none'"
                                />
                                <h4 class="text-lg font-bold">Blazing Speed</h4>
                            </div>
                            <p>Our app runs faster than you can say "dial-up". Enjoy seamless performance.</p>
                        </div>
                        <div class="window-style bg-[#C0C0C0] p-3">
                            <div class="mb-2 flex items-center">
                                <img
                                    src="https://www.windows93.net/c/sys/skins/w93/network.png"
                                    class="mr-2 h-8 w-8"
                                    alt="Network Icon"
                                    onerror="this.style.display='none'"
                                />
                                <h4 class="text-lg font-bold">Retro UI</h4>
                            </div>
                            <p>An interface only a 90s kid could truly love. All the charm, none of the crashes.</p>
                        </div>
                        <div class="window-style bg-[#C0C0C0] p-3">
                            <div class="mb-2 flex items-center">
                                <img
                                    src="https://www.windows93.net/c/sys/skins/w93/help.png"
                                    class="mr-2 h-8 w-8"
                                    alt="Help Icon"
                                    onerror="this.style.display='none'"
                                />
                                <h4 class="text-lg font-bold">24/7 Support</h4>
                            </div>
                            <p>Our dedicated support team is always here to help you out.</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- START MENU: Positioned absolutely and shown conditionally -->
        <div v-if="showStartMenu" @click.stop class="start-menu-style absolute bottom-10 left-1 z-20 w-56 bg-[#c0c0c0] p-1">
            <div class="flex">
                <!-- Vertical banner on the side -->
                <div class="flex w-8 items-end justify-center bg-[#000080] p-1">
                    <h2 class="vertical-text text-2xl font-bold text-white">
                        <span class="text-white">My</span><span class="text-[#c0c0c0]">App</span>
                    </h2>
                </div>
                <!-- Menu items -->
                <ul class="flex-1">
                    <li class="menu-item">
                        <a href="https://heinerdevelops.tech" target="_blank" class="flex w-full items-center">
                            <img src="https://www.windows93.net/c/sys/skins/w93/shutdown.png" class="mr-2 h-8 w-8" alt="Portfolio Icon" />
                            <span class="underline">P</span>ortfolio
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="https://github.com/heinergiehl" target="_blank" class="flex w-full items-center">
                            <img src="https://www.windows93.net/c/sys/skins/w93/programs.png" class="mr-2 h-8 w-8" alt="Programs Icon" />G<span
                                class="underline"
                                >i</span
                            >tHubi
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Taskbar -->
        <div class="window-style fixed right-0 bottom-0 left-0 z-10 flex h-10 items-center bg-[#C0C0C0] px-2 select-none">
            <button
                @click.stop="toggleStartMenu"
                class="btn-style flex items-center bg-[#C0C0C0] px-2 py-0.5"
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
                <span>5:00 PM</span>
            </div>
        </div>
    </div>
</template>
<style>
@import url('https://fonts.googleapis.com/css2?family=VT323&display=swap');
.font-vt323 {
    font-family: 'VT323', monospace;
    font-size: 16px;
}
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
.sunken-style {
    border-style: solid;
    border-width: 2px;
    border-top-color: #404040;
    border-left-color: #404040;
    border-right-color: #ffffff;
    border-bottom-color: #ffffff;
}
.btn-style:active,
.btn-active {
    border-top-color: #404040;
    border-left-color: #404040;
    border-right-color: #ffffff;
    border-bottom-color: #ffffff;
    transform: translate(1px, 1px);
    box-shadow: none;
}
.vertical-text {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    text-orientation: mixed;
}
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
</style>
