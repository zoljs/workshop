<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader } from '@/Components/ui/card';
import { Separator } from '@/Components/ui/separator';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Bolt, CookingPot, Plus, Sparkles } from 'lucide-vue-next';

const props = defineProps<{
    user: {
        name: string;
        points: number;
    };
    upcomingBookings: Array<{
        id: number;
        headcount: number;
        amount_paid: number;
        session: {
            starts_at: string;
            workshop: {
                id: number;
                name: string;
                duration_minutes: number;
                instructor: {
                    name: string;
                };
            };
        };
    }>;
}>();

function formatDate(d: string) {
    return new Date(d).toLocaleString('hu-HU', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}
</script>

<template>
    <Head title="Vezérlőpult" />

    <AppLayout>
        <div
            class="mx-auto max-w-7xl px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:px-8"
        >
            <!-- Header -->
            <header
                class="mb-12 flex flex-col justify-between gap-4 md:flex-row md:items-end"
            >
                <div>
                    <h1 class="text-5xl font-bold tracking-tight text-primary">
                        Üdvözöljük, {{ user.name }}!
                    </h1>
                    <p class="mt-2 text-lg text-muted-foreground">
                        Mi újság a kreatív utazásoddal?
                    </p>
                </div>
                <Button as-child size="lg" class="h-20">
                    <Link href="/#workshops">
                        <Plus />
                        Új workshop foglalás
                    </Link>
                </Button>
            </header>

            <div class="grid grid-cols-12 gap-8">
                <!-- Points Card -->
                <div class="col-span-12 lg:col-span-4">
                    <!-- Loyalty Points Card -->
                    <Card
                        class="overflow-hidden bg-amber-700 p-2 text-orange-300"
                    >
                        <CardHeader class="pb-2">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xl font-bold uppercase tracking-widest opacity-80"
                                >
                                    Bónuszpontok
                                </span>
                                <Sparkles />
                            </div>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div>
                                <p class="mb-1 text-base opacity-70">
                                    Jelenlegi egyenleg
                                </p>
                                <p
                                    class="text-4xl font-extrabold text-orange-200"
                                >
                                    {{ user.points.toLocaleString() }} pont
                                </p>
                            </div>
                            <Separator class="opacity-20" />
                            <p
                                class="rounded-3xl bg-white/10 p-4 text-base leading-relaxed text-orange-200 opacity-80"
                            >
                                Szerezz pontokat vásárlásaiddal, hogy beválthasd
                                őket exkluzív jutalmakra!
                            </p>
                        </CardContent>
                    </Card>
                </div>

                <!-- Upcoming Bookings -->
                <div class="col-span-12 flex flex-col gap-4 lg:col-span-8">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold">Közelgő foglalások</h2>
                        <Button as-child variant="ghost" size="sm">
                            <Link :href="route('dashboard.bookings')"
                                >Összes megtekintése</Link
                            >
                        </Button>
                    </div>

                    <p
                        v-if="upcomingBookings.length === 0"
                        class="py-8 text-sm text-muted-foreground"
                    >
                        Nincs közelgő foglalásod.
                    </p>

                    <Card
                        v-for="booking in upcomingBookings"
                        :key="booking.id"
                        class="overflow-hidden"
                    >
                        <CardContent class="p-0">
                            <div
                                class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center"
                            >
                                <div
                                    class="h-24 w-24 shrink-0 overflow-hidden rounded-lg"
                                >
                                    <img
                                        :src="`https://picsum.photos/seed/${booking.session.workshop.id}/200/200`"
                                        :alt="booking.session.workshop.name"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-muted-foreground">
                                        {{
                                            formatDate(
                                                booking.session.starts_at,
                                            )
                                        }}
                                    </p>
                                    <h3 class="mt-0.5 text-lg font-bold">
                                        {{ booking.session.workshop.name }}
                                    </h3>
                                    <p class="text-sm text-muted-foreground">
                                        {{
                                            booking.session.workshop.instructor
                                                .name
                                        }}
                                        · {{ booking.headcount }} fő
                                    </p>
                                </div>
                                <Button
                                    as-child
                                    variant="outline"
                                    size="sm"
                                    class="shrink-0"
                                >
                                    <Link
                                        :href="
                                            route('bookings.edit', booking.id)
                                        "
                                        >Módosítás</Link
                                    >
                                </Button>
                            </div>
                            <div class="border-t px-6 py-2">
                                <p
                                    class="text-[10px] italic text-muted-foreground"
                                >
                                    Ingyenes lemondás 48 órával az esemény
                                    előtt.
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <Separator class="my-12" />

            <!-- Bottom CTA Cards -->
            <section class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <Card class="bg-muted">
                    <CardContent class="p-8">
                        <CookingPot />
                        <h3 class="mt-4 text-lg font-bold">
                            Fedezz fel új mesterségeket
                        </h3>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Böngéssz a workshopok között és találj új kreatív
                            élményeket.
                        </p>
                        <Button as-child variant="link" class="mt-4 px-0">
                            <Link href="/#workshops"
                                >Workshopok böngészése</Link
                            >
                        </Button>
                    </CardContent>
                </Card>

                <Card class="bg-muted">
                    <CardContent class="p-8">
                        <Sparkles />
                        <h3 class="mt-4 text-lg font-bold">
                            Pontjaim és jutalmak
                        </h3>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Váltsd be pontjaidat kedvezményekre és kuponokra.
                        </p>
                        <Button as-child variant="link" class="mt-4 px-0">
                            <!--<Link :href="route('dashboard.rewards')"
                                >Jutalmak megtekintése →</Link
                            >-->
                        </Button>
                    </CardContent>
                </Card>

                <Card class="bg-muted">
                    <CardContent class="p-8">
                        <Bolt />
                        <h3 class="mt-4 text-lg font-bold">
                            Profil beállítások
                        </h3>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Frissítsd személyes adataidat és jelszavadat.
                        </p>
                        <Button as-child variant="link" class="mt-4 px-0">
                            <Link :href="route('profile.edit')"
                                >Profil szerkesztése</Link
                            >
                        </Button>
                    </CardContent>
                </Card>
            </section>
        </div>
    </AppLayout>
</template>
