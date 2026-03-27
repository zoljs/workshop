<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader } from '@/Components/ui/card';
import { Separator } from '@/Components/ui/separator';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Info, Pencil, Plus, Sparkles, TriangleAlert } from 'lucide-vue-next';

const props = defineProps<{
    user: {
        name: string;
        points: number;
    };
    bookings: Array<{
        id: number;
        headcount: number;
        amount_paid: number;
        status?: string;
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

const upcomingBookings = computed(() =>
    props.bookings.filter((b) => new Date(b.session.starts_at) > new Date()),
);

const pastBookings = computed(() =>
    props.bookings.filter((b) => new Date(b.session.starts_at) <= new Date()),
);

function formatDate(dateStr: string) {
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
}

function statusVariant(
    status?: string,
): 'default' | 'secondary' | 'outline' | 'destructive' {
    switch (status) {
        case 'confirmed':
            return 'default';
        case 'cancelled_by_user':
            return 'destructive';
        case 'cancelled_by_instructor':
            return 'destructive';
        default:
            return 'default';
    }
}

function statusLocalization(
    status?: string,
): 'Megerősítve' | 'Lemondva' | 'Megszünt' | '-' {
    switch (status) {
        case 'confirmed':
            return 'Megerősítve';
        case 'cancelled_by_user ':
            return 'Lemondva';
        case 'cancelled_by_instructor':
            return 'Megszünt';
        default:
            return '-';
    }
}
</script>

<template>
    <Head title="Foglalásaim" />

    <AppLayout>
        <div class="min-h-screen bg-background">
            <div
                class="mx-auto max-w-7xl px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:px-8"
            >
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_0.4fr]">
                    <!-- Main -->
                    <section class="flex flex-col gap-8">
                        <!-- Header -->
                        <header
                            class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
                        >
                            <div class="max-w-2xl">
                                <h1
                                    class="text-4xl font-bold leading-tight text-primary md:text-6xl"
                                >
                                    Foglalásaim
                                </h1>
                                <p class="mt-1 text-muted-foreground">
                                    Kezeld közelgő élményeid és tekintsd meg
                                    korábbi foglalásaid.
                                </p>
                            </div>
                        </header>

                        <Separator />

                        <Tabs default-value="upcoming">
                            <TabsList>
                                <TabsTrigger value="upcoming">
                                    Közelgő
                                    <Badge variant="destructive" class="ml-2">{{
                                        upcomingBookings.length
                                    }}</Badge>
                                </TabsTrigger>
                                <TabsTrigger value="past">
                                    Korábbi
                                    <Badge variant="outline" class="ml-2">{{
                                        pastBookings.length
                                    }}</Badge>
                                </TabsTrigger>
                            </TabsList>

                            <!-- Upcoming Tab -->
                            <TabsContent value="upcoming" class="mt-4">
                                <Card class="overflow-hidden">
                                    <Table v-if="upcomingBookings.length > 0">
                                        <TableHeader>
                                            <TableRow>
                                                <TableHead>ID</TableHead>
                                                <TableHead>Workshop</TableHead>
                                                <TableHead>Dátum</TableHead>
                                                <TableHead>Oktató</TableHead>
                                                <TableHead>Státusz</TableHead>
                                                <TableHead>
                                                    Résztvevők
                                                </TableHead>
                                                <TableHead />
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            <TableRow
                                                v-for="booking in upcomingBookings"
                                                :key="booking.id"
                                            >
                                                <TableCell
                                                    class="font-mono text-sm text-muted-foreground"
                                                >
                                                    #{{ booking.id }}
                                                </TableCell>
                                                <TableCell>
                                                    <Link
                                                        :href="`/workshops/${booking.session.workshop.id}`"
                                                        class="font-bold hover:underline"
                                                    >
                                                        {{
                                                            booking.session
                                                                .workshop.name
                                                        }}
                                                    </Link>
                                                </TableCell>
                                                <TableCell>{{
                                                    formatDate(
                                                        booking.session
                                                            .starts_at,
                                                    )
                                                }}</TableCell>
                                                <TableCell>{{
                                                    booking.session.workshop
                                                        .instructor?.name ?? '—'
                                                }}</TableCell>
                                                <TableCell>
                                                    <Badge
                                                        :variant="
                                                            statusVariant(
                                                                booking.status,
                                                            )
                                                        "
                                                    >
                                                        {{
                                                            statusLocalization(
                                                                booking.status,
                                                            )
                                                        }}
                                                    </Badge>
                                                </TableCell>
                                                <TableCell>
                                                    {{ booking.headcount }}
                                                    részvevő
                                                </TableCell>
                                                <TableCell
                                                    class="space-x-1 text-right"
                                                >
                                                    <Button
                                                        v-if="
                                                            !booking.status?.startsWith(
                                                                'cancelled',
                                                            )
                                                        "
                                                        as-child
                                                        variant="outline"
                                                        size="sm"
                                                        @click="
                                                            router.visit(
                                                                route(
                                                                    'bookings.edit',
                                                                    booking.id,
                                                                ),
                                                            )
                                                        "
                                                    >
                                                        <Link><Pencil /></Link>
                                                    </Button>
                                                </TableCell>
                                            </TableRow>
                                        </TableBody>
                                    </Table>

                                    <CardContent
                                        v-else
                                        class="py-12 text-center text-muted-foreground"
                                    >
                                        <p class="text-sm">
                                            Nincs közelgő foglalás.
                                        </p>
                                        <Button
                                            as-child
                                            variant="link"
                                            class="mt-2"
                                        >
                                            <Link href="/#workshops"
                                                >Műhelyek böngészése</Link
                                            >
                                        </Button>
                                    </CardContent>
                                </Card>
                            </TabsContent>

                            <!-- Past Tab -->
                        </Tabs>

                        <div class="flex flex-col gap-4 lg:flex-row">
                            <Card class="bg-muted p-2">
                                <CardHeader
                                    class="flex flex-row items-center gap-2 pb-2 text-lg font-semibold text-primary"
                                    ><Info />Módosítási feltételek</CardHeader
                                >
                                <CardContent
                                    >A foglalását (időpontot vagy résztvevők
                                    számát) a workshop kezdete előtt legfeljebb
                                    <strong>48 órával</strong> módosíthatja
                                    külön díj nélkül.</CardContent
                                >
                            </Card>

                            <Card class="bg-muted p-2">
                                <CardHeader
                                    class="flex flex-row items-center gap-2 pb-2 text-lg font-semibold text-primary"
                                    ><TriangleAlert
                                        class="text-red-700"
                                    />Módosítási feltételek</CardHeader
                                >
                                <CardContent
                                    >Teljes visszatérítés a kezdés előtt
                                    <strong>72 órával</strong> történő lemondás
                                    esetén lehetséges. 72 órán belül 50%
                                    jóváírást kap a
                                    jutalomszámlájára.</CardContent
                                >
                            </Card>
                        </div>
                    </section>

                    <!-- Sidebar -->
                    <aside
                        class="flex flex-col gap-4 lg:sticky lg:top-24 lg:self-start"
                    >
                        <Button as-child size="lg" class="h-20">
                            <Link href="/#workshops">
                                <Plus />
                                Új workshop foglalás
                            </Link>
                        </Button>

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
                                    Szerezz pontokat vásárlásaiddal, hogy
                                    beválthasd őket exkluzív jutalmakra!
                                </p>
                            </CardContent>
                        </Card>
                    </aside>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
