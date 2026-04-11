<script setup lang="ts">
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Separator } from '@/Components/ui/separator';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import AppLayout from '@/Layouts/AppLayout.vue';
import { diffForHumans } from '@/lib/utils';
import { Head, Link, router } from '@inertiajs/vue3';
import { Check, Pencil, Plus, Sparkles, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps<{
    workshops: Array<{
        id: number;
        name: string;
        archived: boolean;
        edit_url: string;
        archive_url: string;
        sessions: Array<{
            id: number;
            starts_at: string;
            max_capacity: number;
            status: string;
            bookings_sum_headcount: number | null;
            edit_url: string;
            cancel_url: string;
        }>;
    }>;
    workshop_create: string;
    session_create: string;
    instructor?: {
        id: number;
        name: string;
    };
    session_store_url: string;
}>();

const activeWorkshopsCount = computed(
    () => props.workshops.filter((workshop) => !workshop.archived).length,
);

const archivedWorkshopsCount = computed(
    () => props.workshops.filter((workshop) => workshop.archived).length,
);

function statusVariant(
    status?: string,
): 'default' | 'secondary' | 'destructive' | 'outline' {
    switch (status) {
        case 'active':
            return 'default';
        case 'cancelled':
            return 'destructive';
        default:
            return 'secondary';
    }
}

function statusLocalization(
    status?: string,
): 'Aktív' | 'Lemondva' | 'Megszünt' | '-' {
    switch (status) {
        case 'active':
            return 'Aktív';
        case 'cancelled':
            return 'Megszünt';
        default:
            return '-';
    }
}

// Session UI
const openSessionForm = ref<number | null>(null);
const newSession = ref({ starts_at: '', max_capacity: 10 });

function toggleSessionForm(workshopId: number) {
    if (openSessionForm.value === workshopId) {
        openSessionForm.value = null;
    } else {
        openSessionForm.value = workshopId;
        newSession.value = { starts_at: '', max_capacity: 10 };
    }
}

function submitSession(workshopId: number) {
    router.post(
        props.session_store_url,
        {
            workshop_id: workshopId,
            starts_at: newSession.value.starts_at,
            max_capacity: newSession.value.max_capacity,
        },
        {
            // Intertia does a full page load so we have to preserve scroll, otherwise it looks dumb
            preserveScroll: true,
            onSuccess: () => {
                openSessionForm.value = null;
                newSession.value = { starts_at: '', max_capacity: 10 };
            },
        },
    );
}
</script>

<template>
    <Head title="Workshopjaim" />

    <AppLayout>
        <div class="min-h-screen bg-background">
            <div
                class="mx-auto max-w-7xl px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:px-8"
            >
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_0.4fr]">
                    <section class="flex flex-col gap-8">
                        <!-- Header -->
                        <header
                            class="flex flex-col justify-between gap-4 md:flex-row md:items-end"
                        >
                            <div class="max-w-2xl">
                                <h1
                                    class="text-4xl font-bold leading-tight text-primary md:text-6xl"
                                >
                                    {{
                                        instructor
                                            ? instructor?.name + ' workshopjai'
                                            : 'Workshopjaim'
                                    }}
                                </h1>
                                <p class="mt-1 text-muted-foreground">
                                    Kezeld a saját workshopjaid és az
                                    időpontokat!
                                </p>
                            </div>
                        </header>

                        <Separator />

                        <Button
                            as-child
                            size="lg"
                            class="inline-flex h-20 lg:hidden"
                        >
                            <Link :href="workshop_create">
                                <Plus />
                                Új workshop hozzáadása
                            </Link>
                        </Button>

                        <div
                            v-for="workshop in workshops"
                            :key="workshop.id"
                            class="mb-8"
                        >
                            <div class="mb-4 flex flex-row justify-between">
                                <h2
                                    class="text-2xl font-bold leading-tight text-primary md:text-4xl"
                                >
                                    {{ workshop.name }}
                                    {{
                                        workshop.archived ? '(Archiválva)' : ''
                                    }}
                                </h2>

                                <div
                                    v-if="!workshop.archived"
                                    class="flex flex-row"
                                >
                                    <Button as-child variant="ghost" size="lg">
                                        <Link :href="workshop.edit_url"
                                            >Szerkesztés</Link
                                        >
                                    </Button>
                                </div>
                            </div>

                            <Card class="overflow-hidden">
                                <Table>
                                    <TableHeader>
                                        <TableHead class="w-2/4"
                                            >Dátum</TableHead
                                        >
                                        <TableHead class="w-1/5"
                                            >Helyek</TableHead
                                        >
                                        <TableHead class="w-1/5"
                                            >Státusz</TableHead
                                        >
                                        <TableHead class="w-1/5" />
                                    </TableHeader>
                                    <TableBody
                                        v-if="workshop.sessions.length > 0"
                                    >
                                        <TableRow
                                            v-for="session in workshop.sessions"
                                            :key="session.id"
                                        >
                                            <TableCell>{{
                                                diffForHumans(session.starts_at)
                                            }}</TableCell>
                                            <TableCell
                                                >{{ session.max_capacity }} /
                                                {{
                                                    session.bookings_sum_headcount ??
                                                    0
                                                }}</TableCell
                                            >
                                            <TableCell>
                                                <Badge
                                                    :variant="
                                                        statusVariant(
                                                            session.status,
                                                        )
                                                    "
                                                >
                                                    {{
                                                        statusLocalization(
                                                            session.status,
                                                        )
                                                    }}
                                                </Badge>
                                            </TableCell>
                                            <TableCell
                                                class="flex gap-2 justify-self-end text-right"
                                            >
                                                <Button
                                                    as-child
                                                    variant="ghost"
                                                    size="sm"
                                                    v-if="
                                                        session.status !==
                                                        'cancelled'
                                                    "
                                                >
                                                    <Link
                                                        :href="session.edit_url"
                                                        ><Pencil
                                                    /></Link>
                                                </Button>
                                            </TableCell>
                                        </TableRow>

                                        <!-- New entry -->
                                        <TableRow
                                            v-if="
                                                openSessionForm === workshop.id
                                            "
                                        >
                                            <TableCell :colSpan="5">
                                                <div
                                                    class="grid grid-cols-[2fr_1fr] gap-4 md:grid-cols-[2fr_1fr_2fr]"
                                                >
                                                    <Input
                                                        type="datetime-local"
                                                        v-model="
                                                            newSession.starts_at
                                                        "
                                                    />
                                                    <Input
                                                        type="number"
                                                        v-model="
                                                            newSession.max_capacity
                                                        "
                                                        min="1"
                                                        max="100"
                                                    />
                                                    <div
                                                        class="col-span-2 flex flex-row items-center justify-center gap-2 md:col-span-1 md:justify-end"
                                                    >
                                                        <Button
                                                            class="w-full md:w-min"
                                                            size="sm"
                                                            @click="
                                                                submitSession(
                                                                    workshop.id,
                                                                )
                                                            "
                                                        >
                                                            <Check />
                                                        </Button>
                                                        <Button
                                                            class="w-full md:w-min"
                                                            variant="ghost"
                                                            size="sm"
                                                            @click="
                                                                toggleSessionForm(
                                                                    workshop.id,
                                                                )
                                                            "
                                                        >
                                                            <X />
                                                        </Button>
                                                    </div>
                                                </div>
                                            </TableCell>
                                        </TableRow>

                                        <!-- New entry initiator -->
                                        <TableRow
                                            v-if="
                                                openSessionForm !==
                                                    workshop.id &&
                                                !workshop.archived
                                            "
                                        >
                                            <TableCell :colSpan="5">
                                                <Button
                                                    class="w-full"
                                                    variant="ghost"
                                                    size="sm"
                                                    @click="
                                                        toggleSessionForm(
                                                            workshop.id,
                                                        )
                                                    "
                                                >
                                                    <Plus />
                                                    {{
                                                        openSessionForm ===
                                                        workshop.id
                                                            ? 'Mégsem'
                                                            : 'Új időpont hozzáadása'
                                                    }}
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                    <TableBody
                                        v-if="workshop.sessions.length <= 0"
                                    >
                                        <TableRow>
                                            <TableCell
                                                colspan="5"
                                                class="text-center text-muted-foreground"
                                            >
                                                Nincsenek még időpontok.
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </Card>
                        </div>
                    </section>
                    <aside
                        class="flex flex-col gap-4 lg:sticky lg:top-24 lg:self-start"
                    >
                        <Button
                            as-child
                            size="lg"
                            class="hidden h-20 lg:inline-flex"
                        >
                            <Link :href="workshop_create">
                                <Plus />
                                Új workshop hozzáadása
                            </Link>
                        </Button>

                        <!-- Cards -->
                        <Card
                            class="overflow-hidden bg-amber-700 p-2 text-orange-300"
                        >
                            <CardHeader class="pb-2">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-xl font-bold uppercase tracking-widest opacity-80"
                                    >
                                        Workshopok
                                    </span>
                                    <Sparkles />
                                </div>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div>
                                    <p class="mb-1 text-base opacity-70">
                                        Jelenlegi aktív
                                    </p>
                                    <p
                                        class="text-4xl font-extrabold text-orange-200"
                                    >
                                        {{ activeWorkshopsCount }} db
                                    </p>
                                </div>
                            </CardContent>

                            <CardContent class="space-y-4">
                                <div>
                                    <p class="mb-1 text-base opacity-70">
                                        Archivált
                                    </p>
                                    <p
                                        class="text-4xl font-extrabold text-orange-200"
                                    >
                                        {{ archivedWorkshopsCount }} db
                                    </p>
                                </div>
                            </CardContent>
                        </Card>
                    </aside>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
