<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const isMobileMenuOpen = ref(false);
const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const user = usePage().props.auth.user;
</script>

<template>
    <div class="relative min-h-screen bg-background">
        <!-- Nav -->
        <nav
            class="fixed left-0 top-0 z-50 h-24 w-full px-6 text-black transition-colors duration-300"
            :class="isMobileMenuOpen ? 'bg-white/90 backdrop-blur-xl' : ''"
        >
            <div
                class="flex h-full items-center justify-between md:grid md:grid-cols-[1fr_auto_1fr]"
            >
                <!-- Logo -->
                <div>
                    <Link
                        href="/"
                        class="flex w-fit justify-start rounded-lg"
                        aria-label="Workshop főoldal"
                    >
                        <span class="text-2xl font-bold tracking-tight"
                            >Workshop</span
                        >
                    </Link>
                </div>

                <!-- Center pill nav -->
                <div
                    class="hidden h-16 w-fit items-center justify-center gap-2 rounded-full bg-white/40 px-2 backdrop-blur-lg md:flex"
                >
                    <Link
                        href="/"
                        class="flex items-center justify-center rounded-full p-3 text-base tracking-wider transition-colors hover:bg-white/30"
                    >
                        Főoldal
                    </Link>
                    <Link
                        href="/#workshops"
                        class="flex items-center justify-center rounded-full p-3 text-base tracking-wider transition-colors hover:bg-white/30"
                    >
                        Workshopok
                    </Link>
                </div>

                <!-- Right side -->
                <div
                    class="hidden flex-row items-center justify-end gap-2 md:flex"
                >
                    <template v-if="!user">
                        <Button as-child variant="default" size="lg">
                            <Link :href="route('login')">Bejelentkezés</Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button s-child variant="ghost" size="lg">
                            <Link :href="route('dashboard')">{{
                                user.name
                            }}</Link>
                        </Button>
                        <Button as-child variant="outline" size="lg">
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                >Kijelentkezés</Link
                            >
                        </Button>
                    </template>
                </div>

                <!-- Mobile hamburger -->
                <button
                    class="flex items-center justify-center rounded-full bg-white p-3 backdrop-blur-lg md:hidden"
                    :aria-expanded="isMobileMenuOpen"
                    @click="toggleMobileMenu"
                >
                    <span class="text-xl">{{
                        isMobileMenuOpen ? '✕' : '☰'
                    }}</span>
                </button>
            </div>
        </nav>

        <!-- Mobile menu -->
        <div
            v-if="isMobileMenuOpen"
            class="fixed inset-0 z-40 flex flex-col gap-6 overflow-y-auto bg-white/95 px-6 pb-6 pt-28 backdrop-blur-xl md:hidden"
        >
            <div class="flex flex-col gap-4 text-2xl font-bold tracking-wider">
                <Link
                    href="/"
                    class="border-b border-gray-200 py-2"
                    @click="toggleMobileMenu"
                    >Főoldal</Link
                >
                <Link
                    href="/#workshops"
                    class="border-b border-gray-200 py-2"
                    @click="toggleMobileMenu"
                    >Workshopok</Link
                >
            </div>
            <div class="mt-auto flex flex-col gap-4 pt-8">
                <template v-if="!user">
                    <Button as-child class="w-full">
                        <Link :href="route('login')" @click="toggleMobileMenu"
                            >Bejelentkezés</Link
                        >
                    </Button>
                </template>
                <template v-else>
                    <Button as-child variant="ghost" class="w-full">
                        <Link
                            :href="route('dashboard')"
                            @click="toggleMobileMenu"
                            >{{ user.name }}</Link
                        >
                    </Button>
                    <Button as-child variant="outline" class="w-full">
                        <Link :href="route('logout')" method="post" as="button"
                            >Kijelentkezés</Link
                        >
                    </Button>
                </template>
            </div>
        </div>

        <!-- Page content -->
        <slot />

        <!-- Footer -->
        <footer
            class="rounded-t-[4rem] bg-gray-100 py-16 text-gray-800"
            role="contentinfo"
        >
            <div class="mx-auto max-w-7xl px-6 md:px-12">
                <div
                    class="mb-12 grid grid-cols-1 gap-12 md:grid-cols-3 lg:grid-cols-4"
                >
                    <div class="lg:col-span-2">
                        <Link href="/" class="text-2xl font-bold tracking-tight"
                            >Workshop</Link
                        >
                        <p class="mt-4 max-w-sm leading-relaxed">
                            Tanuld meg a nagymama titkos receptjét modern
                            köntösben. Közösségi élmény, minőségi alapanyagok és
                            egy felejthetetlen vacsora vár rád.
                        </p>
                    </div>

                    <nav aria-label="Lábléc navigáció">
                        <h2
                            class="mb-4 text-lg font-bold uppercase tracking-wider"
                        >
                            Navigáció
                        </h2>
                        <ul class="space-y-3">
                            <li
                                v-for="link in [
                                    { text: 'Főoldal', to: '/' },
                                    { text: 'Workshopok', to: '/#workshops' },
                                    {
                                        text: 'Bejelentkezés',
                                        to: route('login'),
                                    },
                                ]"
                                :key="link.to"
                            >
                                <Link
                                    :href="link.to"
                                    class="transition-colors hover:text-foreground"
                                >
                                    {{ link.text }}
                                </Link>
                            </li>
                        </ul>
                    </nav>

                    <address class="not-italic">
                        <h2
                            class="mb-4 text-lg font-bold uppercase tracking-wider"
                        >
                            Kapcsolat
                        </h2>
                        <ul class="space-y-3">
                            <li>
                                <a
                                    href="mailto:hello@workshop.hu"
                                    class="transition-colors hover:text-foreground"
                                >
                                    hello@workshop.hu
                                </a>
                            </li>
                            <li>
                                <a
                                    href="tel:+36301234567"
                                    class="transition-colors hover:text-foreground"
                                >
                                    +36 30 123 4567
                                </a>
                            </li>
                            <li class="text-gray-400">
                                1054 Budapest, Túró u. 12.
                            </li>
                        </ul>
                    </address>
                </div>

                <div
                    class="flex flex-col items-center justify-between gap-4 border-t border-gray-200 pt-8 text-sm text-gray-500 md:flex-row"
                >
                    <p>
                        &copy; {{ new Date().getFullYear() }} Workshop. Minden
                        jog fenntartva.
                    </p>
                    <ul class="flex gap-6">
                        <li>
                            <Link
                                href="/terms"
                                class="transition-colors hover:text-gray-800"
                                >ÁSZF</Link
                            >
                        </li>
                        <li>
                            <Link
                                href="/privacy"
                                class="transition-colors hover:text-gray-800"
                                >Adatkezelési Tájékoztató</Link
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</template>
