<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Separator } from '@/Components/ui/separator';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Check, TicketPlus } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps<{
    workshop: {
        id: number;
        name: string;
        description: string;
        price_per_person: number;
        duration_minutes: number;
        instructor: {
            name: string;
        };
        sessions: Array<{
            id: number;
            starts_at: string;
            max_capacity: number;
            bookings_count: number;
        }>;
    };
}>();

const selectedSession = ref<number | null>(
    props.workshop.sessions[0]?.id ?? null,
);
const headcount = ref(1);

function spotsLeft(session: { max_capacity: number; bookings_count: number }) {
    return session.max_capacity - session.bookings_count;
}

function formatTime(d: string) {
    return new Date(d).toLocaleTimeString('hu-HU', {
        hour: '2-digit',
        minute: '2-digit',
    });
}

function formatDay(d: string) {
    return new Date(d).toLocaleDateString('hu-HU', {
        month: 'short',
        day: 'numeric',
    });
}

function formatMonth(d: string) {
    return new Date(d)
        .toLocaleDateString('hu-HU', { month: 'short' })
        .toUpperCase();
}

function formatDayNum(d: string) {
    return new Date(d).getDate();
}

const selectedSessionData = computed(() =>
    props.workshop.sessions.find((s) => s.id === selectedSession.value),
);

const totalPrice = computed(
    () => props.workshop.price_per_person * headcount.value,
);

const imageUrl = `https://picsum.photos/seed/${props.workshop.id}/800/500`;
const imageUrl2 = `https://picsum.photos/seed/${props.workshop.id + 10}/400/300`;
const imageUrl3 = `https://picsum.photos/seed/${props.workshop.id + 20}/400/300`;
</script>

<template>
    <Head :title="workshop.name" />

    <AppLayout>
        <div class="min-h-screen bg-background">
            <div
                class="mx-auto max-w-7xl px-4 pb-40 pt-20 sm:px-6 md:pt-32 lg:px-8"
            >
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-[1fr_0.5fr]">
                    <!-- Left column -->
                    <div>
                        <!-- Header -->
                        <div class="mb-8 max-w-[80%]">
                            <div>
                                <h1
                                    class="text-4xl font-bold leading-tight text-foreground md:text-7xl"
                                >
                                    {{ workshop.name }}
                                </h1>

                                <!-- Meta -->
                                <div
                                    class="mt-4 flex flex-wrap items-center gap-5 text-sm text-muted-foreground"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                        <span
                                            >{{
                                                workshop.duration_minutes
                                            }}
                                            perc</span
                                        >
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                        <span
                                            class="font-semibold text-foreground"
                                            >{{
                                                workshop.price_per_person.toLocaleString(
                                                    'hu-HU',
                                                )
                                            }}
                                            Ft / fő</span
                                        >
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            />
                                        </svg>
                                        <span>{{
                                            workshop.instructor.name
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Separator class="mb-8" />

                        <!-- Description -->
                        <div class="mb-8">
                            <h2 class="mb-4 text-2xl font-bold text-foreground">
                                Leírás
                            </h2>
                            <p class="leading-relaxed text-muted-foreground">
                                {{ workshop.description }}
                            </p>
                        </div>

                        <div
                            class="mb-12 grid auto-rows-fr grid-cols-1 gap-4 md:grid-cols-3"
                        >
                            <div class="md:col-span-2 md:row-span-2">
                                <img
                                    :src="imageUrl"
                                    alt="Workshop main"
                                    class="transition-hover h-full w-full rounded-2xl object-cover shadow-sm duration-300 hover:brightness-90"
                                />
                            </div>

                            <div class="hidden md:block">
                                <img
                                    :src="imageUrl2"
                                    alt="Workshop detail 1"
                                    class="transition-hover aspect-square h-full w-full rounded-2xl object-cover shadow-sm duration-300 hover:brightness-90"
                                />
                            </div>

                            <div class="hidden md:block">
                                <img
                                    :src="imageUrl3"
                                    alt="Workshop detail 2"
                                    class="transition-hover aspect-square h-full w-full rounded-2xl object-cover shadow-sm duration-300 hover:brightness-90"
                                />
                            </div>

                            <div class="flex gap-2 md:hidden">
                                <img
                                    :src="imageUrl2"
                                    class="h-32 w-1/2 rounded-xl object-cover"
                                />
                                <img
                                    :src="imageUrl3"
                                    class="h-32 w-1/2 rounded-xl object-cover"
                                />
                            </div>
                        </div>

                        <Separator class="mb-8" />

                        <!-- Sessions -->
                        <div class="mb-10">
                            <h2 class="mb-6 text-2xl font-bold text-foreground">
                                Közelgő időpontok
                            </h2>

                            <div
                                v-if="workshop.sessions.length > 0"
                                class="space-y-3"
                            >
                                <div
                                    v-for="session in workshop.sessions"
                                    :key="session.id"
                                    class="flex cursor-pointer items-center gap-4 rounded-2xl bg-card p-4 transition-colors"
                                    :class="
                                        selectedSession === session.id
                                            ? 'border-2 border-border'
                                            : ''
                                    "
                                    @click="selectedSession = session.id"
                                >
                                    <!-- Date box -->
                                    <div
                                        class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl bg-background text-center"
                                    >
                                        <span
                                            class="text-xs font-bold uppercase text-muted-foreground"
                                            >{{
                                                formatMonth(session.starts_at)
                                            }}</span
                                        >
                                        <span
                                            class="text-xl font-bold leading-none text-foreground"
                                            >{{
                                                formatDayNum(session.starts_at)
                                            }}</span
                                        >
                                    </div>

                                    <!-- Time + spots -->
                                    <div class="flex-1">
                                        <p
                                            class="font-semibold text-foreground"
                                        >
                                            {{ formatTime(session.starts_at) }}
                                        </p>
                                        <div
                                            class="mt-1 flex items-center gap-1.5 text-sm"
                                            :class="
                                                spotsLeft(session) <= 3
                                                    ? 'text-destructive'
                                                    : 'text-primary'
                                            "
                                        >
                                            <span
                                                class="h-2 w-2 rounded-full"
                                                :class="
                                                    spotsLeft(session) <= 3
                                                        ? 'bg-destructive'
                                                        : 'bg-primary'
                                                "
                                            />
                                            <span
                                                >{{ spotsLeft(session) }} hely
                                                maradt</span
                                            >
                                        </div>
                                    </div>

                                    <!-- Action -->
                                    <div class="flex items-center gap-2">
                                        <Button
                                            v-if="
                                                selectedSession !== session.id
                                            "
                                            variant="secondary"
                                            @click.stop="
                                                selectedSession = session.id
                                            "
                                        >
                                            Foglalás
                                            <TicketPlus />
                                        </Button>
                                        <Button v-else>
                                            Kiválasztva
                                            <Check />
                                        </Button>
                                    </div>
                                </div>
                            </div>

                            <p v-else class="italic text-muted-foreground">
                                Jelenleg nincs elérhető időpont.
                            </p>
                        </div>

                        <Separator class="mb-8" />

                        <!-- Instructor -->
                        <div class="rounded-2xl bg-card p-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="h-16 w-16 shrink-0 overflow-hidden rounded-full bg-muted"
                                >
                                    <img
                                        :src="`https://picsum.photos/seed/${workshop.instructor.name}/100/100`"
                                        :alt="workshop.instructor.name"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div>
                                    <h3
                                        class="text-lg font-bold text-foreground"
                                    >
                                        {{ workshop.instructor.name }}
                                    </h3>
                                    <p
                                        class="mb-2 text-xs font-bold uppercase tracking-widest text-muted-foreground"
                                    >
                                        Lead Instructor
                                    </p>
                                    <p
                                        class="text-sm leading-relaxed text-muted-foreground"
                                    >
                                        Tapasztalt oktató, aki szenvedéllyel
                                        vezeti a workshopokat és segíti a
                                        résztvevőket a legjobb eredmény
                                        elérésében.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right column: booking widget -->
                    <div
                        class="flex flex-col gap-4 lg:sticky lg:top-24 lg:self-start"
                    >
                        <!-- Hero image -->
                        <div class="overflow-hidden rounded-2xl">
                            <img
                                :src="imageUrl"
                                :alt="workshop.name"
                                class="h-48 w-full object-cover md:h-full"
                            />
                        </div>

                        <div class="rounded-2xl bg-card p-6">
                            <h2 class="mb-5 text-xl font-bold text-foreground">
                                Foglalás
                            </h2>

                            <!-- Selected session -->
                            <div class="mb-4 rounded-xl bg-background p-4">
                                <p
                                    class="mb-1 text-xs font-bold uppercase tracking-widest text-muted-foreground"
                                >
                                    Kiválasztott időpont
                                </p>
                                <p
                                    v-if="selectedSessionData"
                                    class="font-semibold text-foreground"
                                >
                                    {{
                                        formatDay(
                                            selectedSessionData.starts_at,
                                        )
                                    }},
                                    {{
                                        formatTime(
                                            selectedSessionData.starts_at,
                                        )
                                    }}
                                </p>
                                <p
                                    v-else
                                    class="text-sm italic text-muted-foreground"
                                >
                                    Válassz időpontot
                                </p>
                            </div>

                            <!-- Headcount -->
                            <div class="mb-4">
                                <p
                                    class="mb-2 text-xs font-bold uppercase tracking-widest text-muted-foreground"
                                >
                                    Résztvevők
                                </p>
                                <div class="flex items-center gap-4">
                                    <Button
                                        variant="outline"
                                        size="icon"
                                        :disabled="headcount <= 1"
                                        @click="headcount--"
                                    >
                                        −
                                    </Button>
                                    <span
                                        class="w-8 text-center text-lg font-bold"
                                        >{{ headcount }}</span
                                    >
                                    <Button
                                        variant="outline"
                                        size="icon"
                                        :disabled="
                                            selectedSessionData
                                                ? headcount >=
                                                  spotsLeft(selectedSessionData)
                                                : true
                                        "
                                        @click="headcount++"
                                    >
                                        +
                                    </Button>
                                </div>
                            </div>

                            <Separator class="my-4" />

                            <!-- Price breakdown -->
                            <div class="mb-4 space-y-2 text-sm">
                                <div
                                    class="flex justify-between text-muted-foreground"
                                >
                                    <span
                                        >{{
                                            workshop.price_per_person.toLocaleString(
                                                'hu-HU',
                                            )
                                        }}
                                        Ft x {{ headcount }} fő</span
                                    >
                                    <span
                                        >{{
                                            (
                                                workshop.price_per_person *
                                                headcount
                                            ).toLocaleString('hu-HU')
                                        }}
                                        Ft</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between text-base font-bold text-foreground"
                                >
                                    <span>Összesen</span>
                                    <span
                                        >{{
                                            totalPrice.toLocaleString('hu-HU')
                                        }}
                                        Ft</span
                                    >
                                </div>
                            </div>

                            <!-- CTA -->
                            <Button
                                as-child
                                class="w-full"
                                size="lg"
                                :disabled="!selectedSession"
                            >
                                <Link
                                    v-if="selectedSession"
                                    :href="`/bookings/create?session=${selectedSession}&headcount=${headcount}`"
                                >
                                    Foglalás megerősítése
                                    <Check />
                                </Link>
                                <span v-else>Válassz időpontot</span>
                            </Button>

                            <p
                                class="mt-3 text-center text-xs text-muted-foreground"
                            >
                                Ingyenes lemondás 48 órával a workshop előtt.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
