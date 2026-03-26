<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import WorkshopCard from '@/Components/WorkshopCard.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    workshops: Array<{
        id: number;
        name: string;
        description: string;
        duration_minutes: number;
        price_per_person: number;
        instructor: {
            name: string;
        };
        sessions: Array<{
            id: number;
            starts_at: string;
            max_capacity: number;
            bookings_count: number;
        }>;
    }>;
}>();
</script>

<template>
    <Head title="Workshop" />

    <AppLayout>
        <div class="text-black">
            <!-- Workshops -->
            <section
                id="workshops"
                class="relative flex min-h-screen w-full flex-col items-center justify-center gap-12 bg-background p-4 md:p-6"
            >
                <div
                    class="mx-auto flex w-full max-w-3xl flex-col items-center justify-center gap-4 text-center"
                >
                    <span
                        class="text-sm font-bold uppercase tracking-widest text-orange-500"
                    >
                        Programjaink
                    </span>
                    <h2 class="text-5xl font-bold text-gray-900 md:text-7xl">
                        Workshopjaink
                    </h2>
                    <p class="text-lg leading-relaxed text-gray-600">
                        Legyen szó kezdő szintről vagy haladó gasztro-kalandról,
                        nálunk mindenki megtalálja a számítását. Nézd meg
                        aktuális eseményeinket és csatlakozz hozzánk!
                    </p>
                </div>

                <div
                    class="grid w-full max-w-7xl grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3"
                >
                    <WorkshopCard
                        v-for="workshop in workshops"
                        :key="workshop.id"
                        :workshop="workshop"
                    >
                        <template #actions>
                            <PrimaryButton as-child>
                                <Link :href="`/workshops/${workshop.id}`">
                                    Részletek
                                </Link>
                            </PrimaryButton>
                        </template>
                    </WorkshopCard>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
