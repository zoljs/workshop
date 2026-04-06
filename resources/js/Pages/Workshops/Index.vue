<script setup lang="ts">
import LoyaltySection from '@/Components/LoyaltySection.vue';
import { Button } from '@/Components/ui/button';
import WorkshopCard from '@/Components/WorkshopCard.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    containerVariants,
    rotateVariants,
    slideVariants,
} from '@/lib/animations';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowDown } from 'lucide-vue-next';
import { motion } from 'motion-v';

const props = defineProps<{
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
            bookings_sum_headcount: number;
            status: string;
        }>;
    }>;
}>();
</script>

<template>
    <Head title="Főoldal" />

    <AppLayout>
        <div class="text-black">
            <section class="relative h-screen w-screen overflow-hidden">
                <div
                    class="absolute inset-0 p-3 pt-20 sm:p-4 sm:pt-24 md:p-6 md:pt-24"
                >
                    <img
                        class="h-full w-full rounded-2xl object-cover sm:rounded-[2rem] md:rounded-[3rem]"
                        src="https://picsum.photos/seed/alexa/2000/1000"
                        :width="1920"
                        :height="1080"
                        alt="Workshop landing page image"
                    />
                </div>

                <!-- Dark gradient overlay for text legibility on mobile -->
                <div
                    class="absolute inset-0 m-3 rounded-2xl bg-gradient-to-t from-black/60 via-black/20 to-transparent sm:m-4 sm:mt-24 sm:rounded-[2rem] md:m-6 md:mt-24 md:rounded-[3rem]"
                ></div>

                <div
                    class="absolute bottom-16 left-4 right-4 z-10 sm:bottom-20 sm:left-8 sm:right-8 md:bottom-24 md:left-20 md:right-auto md:max-w-4xl"
                    vv
                >
                    <motion.div
                        class="overflow-hidden p-2 font-bold text-white"
                        :variants="containerVariants"
                        initial="hidden"
                        animate="visible"
                    >
                        <div
                            class="mb-3 flex flex-col overflow-hidden leading-tight sm:mb-4 md:mb-6"
                        >
                            <motion.h1
                                :variants="rotateVariants"
                                class="origin-bottom-left text-[42px] sm:text-[60px] md:text-9xl"
                            >
                                Workshop
                            </motion.h1>
                        </div>

                        <div class="mb-4 overflow-hidden sm:mb-6 md:mb-8">
                            <motion.p
                                :variants="slideVariants"
                                class="max-w-xl text-sm font-light leading-relaxed text-gray-200 drop-shadow-md sm:text-base md:text-2xl"
                            >
                                Tanuld meg a nagymama titkos receptjét modern
                                köntösben. Közösségi élmény, minőségi
                                alapanyagok és egy felejthetetlen vacsora vár
                                rád.
                            </motion.p>
                        </div>

                        <motion.div
                            :variants="slideVariants"
                            class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row md:gap-4"
                        >
                            <Button size="lg"> Időpontot foglalok </Button>
                            <Button size="lg" variant="secondary">
                                Részletek
                            </Button>
                        </motion.div>
                    </motion.div>
                </div>

                <div
                    class="absolute bottom-4 left-1/2 flex h-9 w-9 -translate-x-1/2 animate-bounce items-center justify-center rounded-full border border-white/20 bg-white/10 text-white backdrop-blur-lg sm:bottom-6 sm:h-10 sm:w-10 md:bottom-10"
                >
                    <ArrowDown class="h-4 w-4" />
                </div>
            </section>
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

            <LoyaltySection />
        </div>
    </AppLayout>
</template>
