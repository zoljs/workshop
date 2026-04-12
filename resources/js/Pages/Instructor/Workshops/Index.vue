<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue';
import { Button } from '@/Components/ui/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import WorkshopSidebar from '@/Pages/Instructor/Workshops/Partials/WorkshopSidebar.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { computed } from 'vue';
import WorkshopSessionsTable from './Partials/WorkshopSessionsTable.vue';

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
            update_url: string;
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
    () => props.workshops.filter((w) => !w.archived).length,
);

const archivedWorkshopsCount = computed(
    () => props.workshops.filter((w) => w.archived).length,
);
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
                        <PageHeader
                            :title="
                                instructor
                                    ? instructor.name + ' workshopjai'
                                    : 'Workshopjaim'
                            "
                            description="Kezeld a saját workshopjaid és az
                                    időpontokat!"
                        />

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

                            <WorkshopSessionsTable
                                :workshop="workshop"
                                :session-store-url="session_store_url"
                            />
                        </div>
                    </section>

                    <WorkshopSidebar
                        :workshop_create="workshop_create"
                        :archived-workshops-count="archivedWorkshopsCount"
                        :active-workshops-count="activeWorkshopsCount"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
